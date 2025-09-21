<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClickButtonController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $now = Carbon::now();

        if ($this->hasClickedToday($user, $now)) {
            return back()->with('message', 'Você já clicou hoje.');
        }

        DB::beginTransaction();

        try {

            $streak = $user->streak()->firstOrCreate([], [
                'current_streak' => 0,
                'highest_streak' => 0,
                'last_clicked_at' => null,
            ]);

            $this->processClickStreak($user, $streak, $now);
            $this->handleRefillReward($user, $streak, $now);

            $user->clicks()->create(['clicked_at' => now()]);
            $this->checkBadges($user);
            DB::commit();
            return back()->with('message', 'Clique registrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('message', 'Erro ao registrar clique. Tente novamente.');
        }
    }

    private function hasClickedToday($user, $now): bool
    {
        return $user->clicks()->whereDate('clicked_at', $now->toDateString())->exists();
    }

    private function processClickStreak($user, $streak, Carbon $now): void
    {
        $lastClicked = $streak->last_clicked_at ? Carbon::parse($streak->last_clicked_at) : null;

        if (! $lastClicked) {
            $streak->current_streak = 1;

            $refillUsed = $this->consumeRefillIfAvailable($user, $now);

            if ($refillUsed) {
                // Cria o clique do tipo refil para o dia perdido
                $missingDate = $now->copy()->subDay()->startOfDay();

                $user->clicks()->create([
                    'clicked_at' => $missingDate,
                    'type' => 'refil',
                ]);
            }
        } else {
            $diffDays = (int)$lastClicked->startOfDay()->diffInDays($now->startOfDay());
            if ($diffDays === 1) {
                $streak->current_streak += 1;
                $this->applyMissingDayRefil($user, $now);

            } elseif ($diffDays === 2) {
                // Data do dia faltante (dia 2)
                // Tenta consumir um refil (free, ad ou paid)
                $refillUsed = $this->consumeRefillIfAvailable($user, $now);
                if ($refillUsed) {
                    $this->applyMissingDayRefil($user, $now);
                    // Se usou o refil, considera o dia faltante "recuperado"
                    // Incrementa o streak em 2 (1 para o dia faltante + 1 para o clique de hoje)
                    $streak->current_streak += 1;
                } else {
                    // Sem refil, streak reseta porque o dia faltante não foi coberto
                    $streak->current_streak = 1;
                }
            } else {
                $streak->current_streak = 1;
            }
        }

        if ($streak->current_streak > $streak->highest_streak) {
            $streak->highest_streak = $streak->current_streak;
        }

        $streak->last_clicked_at = now();
        $streak->save();
    }

    private function applyMissingDayRefil($user, Carbon $now): void
    {
        $refil = $user->refils()
            ->where('used_at', $now)
            ->orderBy('created_at')
            ->first();
        if ($refil) {
            $refil->update(['used_at' => $now]);

            $missingDate = $now->copy()->subDay()->startOfDay();

            $user->clicks()->create([
                'clicked_at' => $missingDate,
                'type' => 'refil',
            ]);
        }
    }

    private function handleRefillReward($user, $streak, Carbon $now): void
    {
        if ($streak->current_streak % 30 !== 0) {
            return;
        }

        $isVip = $user->is_vip;
        $shouldCreateRefil = $isVip || !$user->refils()->where('type', 'free')->whereNull('used_at')->exists();

        if ($shouldCreateRefil) {
            $user->refils()->create([
                'type' => 'free',
                'refilled_for' => $now->toDateString(),
            ]);

            $balance = $user->refillBalance()->first();

            if ($balance) {
                $balance->increment('free');
            } else {
                $user->refillBalance()->create([
                    'free' => 1,
                    'ad' => 0,
                    'paid' => 0,
                ]);
            }
        }
    }

    private function consumeRefillIfAvailable($user, Carbon $now): bool
    {
        $balance = $user->refillBalance()->first();

        if (! $balance) {
            return false; // Não tem saldo nenhum
        }
        // Ordem de prioridade dos tipos de refil
        $refilTypes = ['free', 'ad', 'paid'];

        foreach ($refilTypes as $type) {
            if ($balance->$type > 0) {
                // Buscar primeiro refil não usado desse tipo
                $refil = $user->refils()
                    ->where('type', $type)
                    ->whereNull('used_at')
                    ->orderBy('created_at')
                    ->first();
                if ($refil) {
                    // Marcar refil como usado
                    $refil->update(['used_at' => $now]);

                    // Decrementar saldo do tipo na tabela user_refill_balances
                    $balance->decrement($type);

                    return true; // Consumiu refil com sucesso
                }
            }
        }

        return false; // Não tem refil disponível
    }

    private function checkBadges($user): void
    {
        $now = now();

        // Badge: Primeiro clique
        if (! $user->badges()->where('slug', 'first_click')->exists()) {
            $user->badges()->attach(Badge::where('slug', 'first_click')->first(), ['earned_at' => $now]);
        }

        $streak = $user->streak;

        // Badge: 7 dias
        if ($streak->current_streak >= 7 && !$user->badges()->where('slug', 'streak_7')->exists()) {
            $user->badges()->attach(Badge::where('slug', 'streak_7')->first(), ['earned_at' => $now]);
        }

        // Badge: 15 dias
        if ($streak->current_streak >= 15 && !$user->badges()->where('slug', 'streak_15')->exists()) {
            $user->badges()->attach(Badge::where('slug', 'streak_15')->first(), ['earned_at' => $now]);
        }

        // Badge: 30 dias
        if ($streak->current_streak >= 30 && !$user->badges()->where('slug', 'streak_30')->exists()) {
            $user->badges()->attach(Badge::where('slug', 'streak_30')->first(), ['earned_at' => $now]);
        }
    }

}
