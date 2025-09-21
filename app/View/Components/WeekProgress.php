<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WeekProgress extends Component
{
    public $weekStreak;

    public function __construct($weekStreak)
    {
        $this->weekStreak = $weekStreak;
    }

    public function render()
    {
        return view('components.dashboard.week-progress');
    }
}
