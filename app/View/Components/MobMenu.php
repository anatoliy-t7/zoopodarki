<?php

namespace App\View\Components;

use App\Models\Catalog;
use Illuminate\View\Component;

class MobMenu extends Component
{

    public $catalogs;

    public function __construct()
    {

        $this->catalogs = cache()->remember('categories-menu', 60 * 60 * 24, function () {
            return Catalog::where('menu', true)->with('categories')
                ->orderBy('sort', 'asc')
                ->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.mob-menu');
    }
}
