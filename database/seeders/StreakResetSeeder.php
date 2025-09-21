<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Click;
use App\Models\Streak;
use Carbon\Carbon;

class StreakResetSeeder extends Seeder
{
    public function run($userId = 1)
    {
        $today = Carbon::today();

        Click::where('user_id', $userId)->whereDate('clicked_at', '>=', $today->copy()->subDays(10))->delete();

        // Cliques: hoje e -5 dias (pulou 4 dias)
        $datesClicked = [
            $today->copy()->subDays(5)->setTime(12,0),
            $today->setTime(12,0),
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
        $streak->current_streak = 1;
        $streak->highest_streak = max($streak->highest_streak ?? 0, 1);
        $streak->last_clicked_at = $today->setTime(12, 0);
        $streak->save();

        $this->command->info("Streak resetado por pulos maiores que 1 dia para usuário #{$userId}.");
    }
}
