<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public $category;
    public $catalog;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($category, $catalog = null)
    {
        $this->category = $category;

        if ($catalog) {
            $this->catalog = $catalog;
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.breadcrumbs');
    }
}
