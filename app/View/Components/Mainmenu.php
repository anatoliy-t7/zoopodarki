<?php

namespace App\View\Components;

use App\Models\Catalog;
use Illuminate\View\Component;

class Mainmenu extends Component
{
    public $menuCatalogs;

    public function __construct()
    {

        if (config('app.env') === 'local') {
            $this->menuCatalogs = Catalog::where('menu', true)
                ->withWhereHas('categories', fn ($query) => $query->where('menu', true))
                ->with('categories.tags', fn ($query) => $query->where('show_in_menu', true))
                ->with('brands')
                ->orderBy('sort', 'asc')
                ->get();
        } else {
            $this->menuCatalogs = cache()->remember('categories-menu', 60 * 60 * 24, function () {
                return Catalog::where('menu', true)
                        ->withWhereHas('categories', fn ($query) => $query->where('menu', true))
                        ->with('categories.tags', fn ($query) => $query->where('show_in_menu', true))
                        ->with('brands')
                        ->orderBy('sort', 'asc')
                        ->get();
            });
        }
    }

    public function render()
    {
        return view('components.mainmenu');
    }
}
