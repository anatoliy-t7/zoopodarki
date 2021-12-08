<?php

namespace App\Http\Livewire\Site;

use App\Models\Product;
use Livewire\Component;

class DiscountPage extends Component
{
    public $petF = [];
    public $sortType;
    public $sortSelectedName = 'По популярности';
    public $sortSelectedType = 'popularity';
    public $sortBy = 'desc';
    public $maxPrice = 10000;
    public $minPrice = 0;

    protected $queryString = [
        // 'attrsF' => ['except' => ''],
        // 'brandsF' => ['except' => ''],
        // 'page' => ['except' => 1],
    ];

    protected $listeners = ['updateMinPrice', 'updateMaxPrice'];

    public function mount()
    {
        $this->sortType = config('constants.sort_type');
    }

    // public function setMaxAndMinPrices()
    // {
    //     $variationsId = $products
    //         ->isStatusActive()
    //         ->has('media')
    //         ->with('variations', fn ($q) => $q->hasStock())
    //         ->whereHas('variations', fn ($q) => $q->hasStock())
    //         ->get()
    //         ->pluck('variations')
    //         ->flatten()
    //         ->unique('id')
    //         ->pluck('id');

    //     $this->maxPrice = \DB::table('products_1c')
    //         ->whereIn('id', $variationsId)
    //         ->max('price');

    //     $this->minPrice = \DB::table('products_1c')
    //         ->whereIn('id', $variationsId)
    //         ->where('price', '>', 0)
    //         ->min('price');
    // }


    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }
    public function sortIt($type, $sort, $name)
    {
        $this->sortSelectedType = $type;
        $this->sortSelectedName = $name;
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function getProducts()
    {
        return Product::isStatusActive()
            ->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])

            ->has('media')

            ->when($this->petF, function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->where('catalog_id', $this->petF);
                });
            })

            ->whereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->where('promotion_type', '>', 0);
            })

            // ->when($this->stockF, function ($query) {
            //     $query->checkStock((int) $this->stockF);
            // })

                ->with('media')
                ->with('brand')
                ->with('unit')
                ->with('attributes')
                ->with('variations')
                ->orderBy($this->sortSelectedType, $this->sortBy)
                ->paginate(32);
    }


    public function render()
    {
        $products = $this->getProducts();


        $this->emit('lozad', '');

        return view('livewire.site.discount-page', [
            'products' => $products,
        ])
                    ->extends('layouts.app')
                    ->section('content');
    }
}
