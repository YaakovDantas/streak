<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LeaderBoard extends Component
{
    public $data;
    public $type;

    public function __construct($data, $type)
    {
        $this->type = $type; // 'week', 'month', or 'all'
        $this->data = $data;
    }

    public function render()
    {
        return view('components.dashboard.leader-board', ['data' => $this->data, 'type' => $this->type]);
    }
}
