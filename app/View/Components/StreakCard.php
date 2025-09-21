<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StreakCard extends Component
{
    public $streak;
    public $clickedToday;

    public function __construct($streak, $clickedToday)
    {
        $this->streak = $streak;
        $this->clickedToday = $clickedToday;
    }

    public function render()
    {
        return view('components.dashboard.streak-card');
    }
}
