<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Click;
use App\Models\Streak;
use Carbon\Carbon;

class ClickSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $userId ID do usuário
     * @param int $days Número de dias clicados consecutivos (terminando em ontem)
     * @return void
     */
    public function run($userId = 1, $days = 29)
    {
        if ($days < 1) {
            $this->command->info('Número de dias deve ser ao menos 1.');
            return;
        }

        // Data inicial: hoje menos $days (exclusivo hoje)
        $startDate = Carbon::today()->subDays($days);

        // Remove cliques existentes nesse intervalo
        Click::where('user_id', $userId)
            ->whereDate('clicked_at', '>=', $startDate->toDateString())
            ->whereDate('clicked_at', '<', Carbon::today()->toDateString())
            ->delete();

        // Criar cliques para os dias consecutivos antes de hoje (de startDate até ontem)
        for ($i = 0; $i < $days; $i++) {
            $clickDate = Carbon::today()->subDays($days - $i)->setTime(12, 0);

            Click::create([
                'user_id' => $userId,
                'clicked_at' => $clickDate,
            ]);
        }

        // Atualizar streak
        $streak = Streak::firstOrNew(['user_id' => $userId]);

        $currentStreak = 0;
        $highestStreak = $streak->highest_streak ?? 0;
        $lastClickedAt = null;

        $today = Carbon::today();
        // Percorrer desde ontem para trás, pois hoje não tem clique criado
        for ($offset = 1; $offset <= $days; $offset++) {
            $dateToCheck = $today->copy()->subDays($offset)->toDateString();

            $clicked = Click::where('user_id', $userId)
                ->whereDate('clicked_at', $dateToCheck)
                ->exists();

            if ($clicked) {
                $currentStreak++;
                if ($lastClickedAt === null) {
                    $lastClickedAt = Carbon::parse($dateToCheck)->setTime(12, 0);
                }
            } else {
                break; // streak interrompido
            }
        }

        // Atualizar maior streak se necessário
        if ($currentStreak > $highestStreak) {
            $highestStreak = $currentStreak;
        }

        $streak->current_streak = $currentStreak;
        $streak->highest_streak = $highestStreak;
        $streak->last_clicked_at = $lastClickedAt;
        $streak->save();

        $this->command->info("{$days} cliques criados (até ontem) e streak atualizado para o usuário #{$userId}.");
    }
}
