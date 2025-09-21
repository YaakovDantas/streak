<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ListBadges extends Component
{
    public $badges;
    public function __construct($badges)
    {
        $this->badges = $badges;
    }

    public function render()
    {
        return view('components.dashboard.list-badges', [
            'badges' => $this->badges
        ]);
    }
}
