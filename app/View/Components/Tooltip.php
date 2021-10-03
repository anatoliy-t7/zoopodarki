<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Tooltip extends Component
{

    public $width = 'w-48';
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($width)
    {

        if ($width) {
            $this->width = $width;
        } else {
            $this->width = '170px';
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.tooltip');
    }
}
