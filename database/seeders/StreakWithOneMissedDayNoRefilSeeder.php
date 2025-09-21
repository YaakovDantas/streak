<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Click;
use App\Models\Streak;
use Carbon\Carbon;

class StreakWithOneMissedDayNoRefilSeeder extends Seeder
{
    public function run($userId = 1)
    {
        $today = Carbon::today();

        Click::where('user_id', $userId)->whereDate('clicked_at', '>=', $today->copy()->subDays(3))->delete();

        // Cliques: hoje e -3 dias (pulou -1 e -2 dias)
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

        // Atualizar streak — deve resetar para 1
        $streak = Streak::firstOrNew(['user_id' => $userId]);
        $streak->current_streak = 2;
        $streak->highest_streak = max($streak->highest_streak ?? 0, 2);
        $streak->last_clicked_at = $datesClicked[0]; // D-3
        $streak->save();

        $this->command->info("Streak com 1 dia pulado e sem refil criado para usuário #{$userId}.");
    }
}
