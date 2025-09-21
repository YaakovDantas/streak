<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TabStreakBadge extends Component
{
    public $streak;
    public $clickedToday;
    public $badges;

    public function __construct($streak, $clickedToday, $badges)
    {
        $this->streak = $streak;
        $this->clickedToday = $clickedToday;
        $this->badges = $badges;
    }

    public function render()
    {
        return view('components.dashboard.tab-streak-badge', [
            'streak' => $this->streak,
            'clickedToday' => $this->clickedToday,
            'badges' => $this->badges
        ]);
    }
}
