<?php

namespace App\Http\Livewire\Site\Pages;

use App\Models\AttributeItem;
use App\Models\Product;
use Artesaos\SEOTools\Facades\SEOMeta;
use Livewire\Component;
use Livewire\WithPagination;

class DiscountPage extends Component
{
    use WithPagination;

    public $typeF = [];
    public $petF = [];
    public $catF = [];
    public $brandF = [];
    public $attrsF = [];
    public $sortType;
    public $sortSelectedName = 'По популярности';
    public $sortSelectedType = 'popularity';
    public $sortBy = 'desc';
    public $maxPrice = 10000;
    public $minPrice = 0;
    public $maxRange = 10000;
    public $minRange = 0;
    public $catalogs;
    public $categories;
    public $brands;
    public $attributes;

    protected $queryString = [
        'typeF' => ['except' => ''],
        'petF' => ['except' => ''],
        'catF' => ['except' => ''],
        'brandF' => ['except' => ''],
        'attrsF' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['updatedMinMaxPrice'];

    public function mount()
    {
        $this->sortType = config('constants.sort_type');

        $this->getBrandsForFilter();
        $this->setMaxAndMinPrices();
        $this->getCatalogsForFilter();
        $this->getAttributesForFilter();

        $this->setSeo();
    }

    public function updatedMinMaxPrice($minPrice, $maxPrice)
    {
        $this->minPrice = (int)$minPrice;
        $this->maxPrice = (int)$maxPrice;
        $this->resetPage();
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
        $this->maxRange = $this->maxPrice;
        $this->minRange = $this->minPrice;
    }

    public function setSeo()
    {

        //SEO TITLE
        // Акции на сухие и влажные корма, распродажи и скидки на одежду для собак + в новом интернет зоомагазине товаров для животных в спб с бесплатной доставкой по городу + (цена  от ...) + *петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaTitle = 'Акции на сухие и влажные корма, распродажи и скидки на одежду для собак в новом интернет зоомагазине товаров для животных в спб с бесплатной доставкой по городу (цена  от ' . $this->minPrice . ' ₽). Петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

        // SEO description
        // *доллар* Акции на сухие и влажные корма, распродажи и скидки на одежду для собак + в спб *самолетик* с бесплатной доставкой по городу в новом интернет зоомагазине товаров для животных (цена  от ...) *like* душевное обслуживание, гарантии, большой выбор, фото, составы *петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaDescription = '₽ Акции на сухие и влажные корма, распродажи и скидки на одежду для собак в спб 🚚  с бесплатной доставкой по городу в новом интернет зоомагазине товаров для животных (цена  от ' . $this->minPrice . ' ₽) 👍 душевное обслуживание, гарантии, большой выбор, фото, составы. Петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

        SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
    }

    public function sortIt($type, $sort, $name)
    {
        $this->sortSelectedType = $type;
        $this->sortSelectedName = $name;
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function getCatalogsForFilter(): void
    {
        $this->catalogs = Product::isStatusActive()
            ->has('media')
            ->has('categories')
            ->has('variations')
            ->whereHas('variations', function ($query) {
                $query->where('promotion_type', '>', 0);
            })
            ->with('categories')
            ->with('categories.catalog')
                ->get()
                ->pluck('categories')
                ->flatten()
                ->unique('id')
                ->pluck('catalog')
                ->unique('id')
                ->toArray();
    }

    public function getCategoriesForFilter(): void
    {
        $this->categories = Product::isStatusActive()
            ->whereHas('variations', function ($query) {
                $query->where('promotion_type', '>', 0);
            })
            ->withWhereHas('categories', function ($query) {
                $query->whereIn('catalog_id', $this->petF);
            })
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


    public function getAttributesForFilter(): void
    {
        $this->attributes = AttributeItem::whereIn('id', config('constants.attributes_discount'))
            ->get()
            ->toArray();
    }

    public function getProducts()
    {
        return Product::isStatusActive()
            //->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])

            ->whereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->when(!empty($this->typeF), function ($query) {
                    $query->getTypeOfDiscount($this->typeF);
                }, function ($query) {
                    $query->getTypeOfDiscount();
                });
            })

            ->when(!empty($this->attrsF), function ($query) {
                $query->orWhereHas('attributes', function ($query) {
                    $query->whereIn('product_attribute.attribute_id', $this->attrsF);
                });
            }, function ($query) {
                if (empty($this->typeF)) {
                    $query->orWhereHas('attributes', function ($query) {
                        $query->whereIn('product_attribute.attribute_id', config('constants.attributes_discount'));
                    });
                }
            })

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
            ->has('media')
            ->has('categories')
            ->has('variations')
                ->with('media')
                ->with('brand')
                ->with('unit')
                ->with('attributes')
                ->with('variations')
                ->with('categories')
                ->with('categories.catalog')
                    ->orderBy($this->sortSelectedType, $this->sortBy)
                    ->paginate(32);
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'typeF',
            'petF',
            'brandF',
            'catF',
            'attrsF',
        ]);
        $this->setMaxAndMinPrices();
        $this->resetPage();
        $this->dispatchBrowserEvent('reset-range');
    }

    public function render()
    {
        if ($this->petF) {
            $this->getCategoriesForFilter();
        }
        $products = $this->getProducts();

        $this->emit('lozad', '');

        return view('livewire.site.pages.discount-page', [
            'products' => $products,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
