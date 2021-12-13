<?php

namespace App\Http\Livewire\Site;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class DiscountPage extends Component
{
    use WithPagination;

    public $petF = [];
    public $catF = [];
    public $brandF = [];
    public $sortType;
    public $sortSelectedName = 'По популярности';
    public $sortSelectedType = 'popularity';
    public $sortBy = 'desc';
    public $maxPrice = 10000;
    public $minPrice = 0;
    public $categories;
    public $brands;

    protected $queryString = [
        'petF' => ['except' => ''],
        'catF' => ['except' => ''],
        'brandF' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['updateMinPrice', 'updateMaxPrice'];

    public function mount()
    {
        $this->sortType = config('constants.sort_type');

        $this->getCategoriesForFilter();
        $this->getBrandsForFilter();
        $this->setMaxAndMinPrices();
    }

    public function setMaxAndMinPrices()
    {
        $variationsId = Product::isStatusActive()
            ->withWhereHas('variations', function ($query) {
                $query->where('promotion_type', '>', 0);
            })
            ->get()
            ->pluck('variations')
            ->flatten()
            ->unique('id')
            ->pluck('id');

        $this->maxPrice = \DB::table('products_1c')
            ->whereIn('id', $variationsId)
            ->max('price');

        $this->minPrice = \DB::table('products_1c')
            ->whereIn('id', $variationsId)
            ->where('price', '>', 0)
            ->min('price');
    }


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

    public function getCategoriesForFilter(): void
    {
        $this->categories = Product::isStatusActive()
            ->whereHas('variations', function ($query) {
                $query->where('promotion_type', '>', 0);
            })
                ->with('categories')
                ->get()
                ->pluck('categories')
                ->flatten()
                ->unique('id')
                ->toArray();
    }

    public function getBrandsForFilter(): void
    {
        $this->brands = Product::isStatusActive()
            ->whereHas('variations', function ($query) {
                $query->where('promotion_type', '>', 0);
            })
                ->with('brand')
                ->get()
                ->pluck('brand')
                ->flatten()
                ->unique('id')
                ->toArray();
    }

    public function getProducts()
    {
        return Product::isStatusActive()
            //->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])
            ->whereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->where('promotion_type', '>', 0);
            })
            ->has('media')
            ->when($this->petF, function ($query) {
                $query->withWhereHas('categories', function ($query) {
                    $query->whereIn('catalog_id', $this->petF);
                });
            })
            ->when($this->catF, function ($query) {
                $query->withWhereHas('categories', function ($query) {
                    $query->whereIn('category_id', $this->catF);
                });
            })
             ->when($this->brandF, function ($query) {
                 $query->withWhereHas('brand', function ($query) {
                     $query->whereIn('brand_id', $this->brandF);
                 });
             })
                ->with('media')
                ->with('brand')
                ->with('unit')
                ->with('attributes')
                ->with('variations')
                ->with('categories')
                ->with('categories.catalog')
                // ->orderBy($this->sortSelectedType, $this->sortBy)
                ->paginate(32);
    }

    public function resetFilters()
    {
        $this->reset([
            'petF',
            'brandF',
            'catF',
        ]);
        $this->setMaxAndMinPrices();
        $this->resetPage();
        $this->dispatchBrowserEvent('reset-range');
    }

    public function render()
    {
        $products = $this->getProducts();

        // dd($this->categories);
        $this->emit('lozad', '');

        return view('livewire.site.discount-page', [
            'products' => $products,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
