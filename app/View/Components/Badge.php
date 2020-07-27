<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    public $type;
    public $slot;
    public $show;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $slot, $show)
    {
        $this->type=$type;
        $this->slot=$slot;
        $this->show=$show;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.badge');
    }
}
