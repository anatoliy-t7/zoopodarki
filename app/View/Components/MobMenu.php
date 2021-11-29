<?php

namespace App\View\Components;

use App\Models\Catalog;
use Illuminate\View\Component;

class MobMenu extends Component
{

    public $catalogs;

    public function __construct()
    {

        if (config('app.env') === 'local') {
            $this->menuCatalogs = Catalog::where('menu', true)
                ->withWhereHas('categories', fn ($query) => $query->where('menu', true))
                ->with('categories.tags', fn ($query) => $query->limit(6))
                ->with('brands')
                ->orderBy('sort', 'asc')
                ->get();
        } else {
            $this->menuCatalogs = cache()->remember('categories-menu', 60 * 60 * 24, function () {
                        return Catalog::where('menu', true)
                        ->withWhereHas('categories', fn ($query) => $query->where('menu', true))
                        ->with('categories.tags', fn ($query) => $query->limit(6))
                        ->with('brands', fn ($query) => $query->limit(6))
                        ->orderBy('sort', 'asc')
                        ->get();
            });
        }
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
