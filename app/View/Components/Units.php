<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Units extends Component
{

    public $unit;
    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($unit, $value)
    {
        $this->unit = $unit;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.units');
    }
}
