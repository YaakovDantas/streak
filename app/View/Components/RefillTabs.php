<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RefillTabs extends Component
{
    public $refillBalance;

    public function __construct($refillBalance)
    {
        $this->refillBalance = $refillBalance;
    }

    public function render()
    {
        return view('components.dashboard.refill-tabs');
    }
}
