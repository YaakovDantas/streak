<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OverallBoard extends Component
{
    public $weekLeaderboard;
    public $monthLeaderboard;
    public $allTimeLeaderboard;

    public function __construct($weekLeaderboard, $monthLeaderboard, $allTimeLeaderboard)
    {
        $this->weekLeaderboard = $weekLeaderboard;
        $this->monthLeaderboard = $monthLeaderboard;
        $this->allTimeLeaderboard = $allTimeLeaderboard;
    }

    public function render()
    {
        return view('components.dashboard.overall-board');
    }
}
