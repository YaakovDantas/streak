<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Click;
use App\Models\Streak;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserWithStreakSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 10) as $i) {
            $user = User::create([
                'name' => "Usuário {$i}",
                'email' => "user{$i}@teste.com",
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $currentStreak = rand(0, 10);
            $startDate = Carbon::now()->copy()->subDays($currentStreak - 1);
            $usedDates = [];

            // Cria cliques consecutivos para streak
            for ($d = 0; $d < $currentStreak; $d++) {
                $date = $startDate->copy()->addDays($d)->toDateString();
                $usedDates[] = $date;

                Click::create([
                    'user_id' => $user->id,
                    'clicked_at' => Carbon::parse($date)->setTime(rand(8, 22), rand(0, 59)),
                    'type' => 'click',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Gera cliques aleatórios em dias que ainda não foram usados
            $extraClicks = rand(5, 15);
            while ($extraClicks > 0) {
                $randomDay = Carbon::now()->copy()->subDays(rand(0, 29))->toDateString();

                if (!in_array($randomDay, $usedDates)) {
                    $usedDates[] = $randomDay;

                    Click::create([
                        'user_id' => $user->id,
                        'clicked_at' => Carbon::parse($randomDay)->setTime(rand(8, 22), rand(0, 59)),
                        'type' => 'click',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $extraClicks--;
                }
            }

            // Último clique registrado
            $lastClick = Click::where('user_id', $user->id)->orderByDesc('clicked_at')->first();

            Streak::create([
                'user_id' => $user->id,
                'current_streak' => $currentStreak,
                'highest_streak' => max($currentStreak, rand($currentStreak, 20)),
                'last_clicked_at' => $lastClick?->clicked_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
