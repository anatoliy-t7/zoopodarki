<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Toggle extends Component
{
    public $property = false;
    public $lable = '';
    public $unique;

    public function __construct($property, $lable)
    {
        $this->property = $property;
        $this->lable = $lable;
        $this->unique = rand(10, 99);
    }

    public function render()
    {
        return view('components.toggle');
    }
}
