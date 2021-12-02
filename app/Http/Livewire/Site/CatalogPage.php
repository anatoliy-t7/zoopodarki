<?php

namespace App\Http\Livewire\Site;

use App\Models\Catalog;
use App\Models\Category;
use Livewire\Component;

class CatalogPage extends Component
{
    public $catalog;
    public $selectedCategories;

    public function mount($catalogslug)
    {
        $this->catalog = Catalog::where('slug', $catalogslug)->with('categories')->first();

        $categoriesId = $this->catalog->categories()
            ->where('show_in_catalog', true)
            ->get('id'); // Categories ID that marked with show in catalog

        $this->selectedCategories = Category::whereIn('id', $categoriesId)
            ->with(
                ['products' => function ($query) {
                    return $query->with(['media', 'variations', 'unit', 'brand'])
                        ->orderBy('products.popularity');
                }]
            )
            ->get();
    }

    public function render()
    {
        return view('livewire.site.catalog-page')
            ->extends('layouts.app')
            ->section('content');
    }
}
