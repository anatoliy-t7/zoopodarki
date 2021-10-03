<?php

namespace App\View\Components;

use App\Models\Catalog;
use Illuminate\View\Component;

class Mainmenu extends Component
{
    public $menuCatalogs;

    public function __construct()
    {
        $this->menuCatalogs = cache()->remember('categories-menu', 60 * 60 * 24, function () {
            return Catalog::where('menu', true)
                ->with('categories', function ($query) {
                    $query->where('menu', true);
                })
                ->orderBy('sort', 'asc')
                ->get();
        });
    }

    public function render()
    {
        return view('components.mainmenu');
    }
}
