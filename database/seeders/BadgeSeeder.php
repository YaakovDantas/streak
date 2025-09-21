<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\BadgeRequirement;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        $badges = [
            [
                'name' => 'Primeiro Clique',
                'slug' => 'first_click',
                'requirement' => ['type' => 'clicks', 'target' => 1],
            ],
            [
                'name' => '7 Dias de Foco',
                'slug' => 'streak_7',
                'requirement' => ['type' => 'streak', 'target' => 7],
            ],
            [
                'name' => '15 Dias de Foco',
                'slug' => 'streak_15',
                'requirement' => ['type' => 'streak', 'target' => 15],
            ],
            [
                'name' => '30 Dias de Foco',
                'slug' => 'streak_30',
                'requirement' => ['type' => 'streak', 'target' => 30],
            ],
            [
                'name' => '50 Dias de Foco',
                'slug' => 'streak_50',
                'requirement' => ['type' => 'streak', 'target' => 50],
            ],
            [
                'name' => '75 Dias de Foco',
                'slug' => 'streak_75',
                'requirement' => ['type' => 'streak', 'target' => 75],
            ],
            [
                'name' => '100 Dias de Foco',
                'slug' => 'streak_100',
                'requirement' => ['type' => 'streak', 'target' => 100],
            ],
        ];

        foreach ($badges as $badgeData) {
            // Cria ou atualiza badge
            $badge = Badge::updateOrCreate(
                ['slug' => $badgeData['slug']],
                ['name' => $badgeData['name']]
            );

            // Cria ou atualiza requirement
            if (isset($badgeData['requirement'])) {
                BadgeRequirement::updateOrCreate(
                    ['badge_id' => $badge->id],
                    [
                        'type' => $badgeData['requirement']['type'],
                        'target' => $badgeData['requirement']['target'],
                    ]
                );
            }
        }
    }
}
