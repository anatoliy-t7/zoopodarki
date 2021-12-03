<?php

namespace App\Http\Livewire\Site;

use App\Models\Catalog;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Brand extends Component
{
    use WithPagination;

    public $countries;
    public $brand;
    public $catalogs;

    public function mount()
    {
        $this->countries = cache()->remember('brand-country', 60 * 60 * 24, function () {
            return Product::where('brand_id', $this->brand->id)
                ->withWhereHas('attributes', fn ($q) => $q->where('attribute_item.attribute_id', 64))
                ->get()
                ->pluck('attributes')
                ->flatten()
                ->pluck('name')
                ->unique()
                ->all();
        });

        // TODO cache it
        $this->catalogs = Catalog::whereHas('products', fn ($q) => $q->where('brand_id', $this->brand->id))
         ->withWhereHas('categories', fn ($q) => $q->whereHas('products', fn ($q) => $q->where('brand_id', $this->brand->id)))
        ->get();
    }

    public function render()
    {
        return view('livewire.site.brand');
    }
}
