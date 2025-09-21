<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Click;
use App\Models\Refill;
use App\Models\Streak;
use App\Models\UserRefillBalance;
use Carbon\Carbon;

class StreakWithOneMissedDayRefilSeeder extends Seeder
{
    public function run($userId = 1)
    {
        $today = Carbon::today();

        // Limpar cliques e refis recentes
        Click::where('user_id', $userId)->whereDate('clicked_at', '>=', $today->copy()->subDays(4))->delete();
        Refill::where('user_id', $userId)->whereNull('used_at')->delete();

        // Cliques: -3 e -2 dias (pulou -1 dia, hoje ainda não clicado)
        $datesClicked = [
            $today->copy()->subDays(3)->setTime(12,0),
            $today->copy()->subDays(2)->setTime(12,0),
        ];

        foreach ($datesClicked as $date) {
            Click::create([
                'user_id' => $userId,
                'clicked_at' => $date,
                'type' => 'click',
            ]);
        }

        // Criar refil para o dia pulado (ontem)
        Refill::create([
            'user_id' => $userId,
            'type' => 'free',
            'refilled_for' => $today->copy()->subDay()->toDateString(),
            'used_at' => null,
        ]);

        UserRefillBalance::create([
            'user_id' => $userId,
            'free' => 1,
            'ad' => 0,
            'paid' => 0,
        ]);

        // Atualizar streak
        $streak = Streak::firstOrNew(['user_id' => $userId]);
        $streak->current_streak = 2; // considerando os 2 dias clicados
        $streak->highest_streak = max($streak->highest_streak ?? 0, 2);
        $streak->last_clicked_at = $today->copy()->subDays(2)->setTime(12, 0);
        $streak->save();

        $this->command->info("Streak com 1 dia pulado e refil disponível criado para usuário #{$userId}.");
    }
}
