<?php

namespace App\Http\Livewire\Site\Pages;

use App\Models\Brand;
use App\Models\Product;
use Artesaos\SEOTools\Facades\SEOMeta;
use Livewire\Component;
use Livewire\WithPagination;

class BrandPage extends Component
{
    use WithPagination;

    public $petF = [];
    public $catF = [];
    public $countries;
    public $brand;
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
    protected $queryString = [
        'petF' => ['except' => ''],
        'catF' => ['except' => ''],
        'page' => ['except' => 1],
    ];
    protected $listeners = ['updatedMinMaxPrice'];

    public function mount($brandslug)
    {
        $this->sortType = config('constants.sort_type');

        $this->brand = Brand::where('slug', $brandslug)->firstOrFail();

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

        $this->setSeo();
        $this->setMaxAndMinPrices();
        $this->getCatalogsForFilter();
    }

    public function setSeo()
    {

            // $minPrice = \DB::table('products_1c')
        // ->where('product_id', $this->product->id)
        // ->where('price', '>', 0)
        // ->min('price');


        //SEO TITLE
        // *like* бренд англ + для собак, кошек и других животных + в новом интернет зоомагазине кормов и товаров для животных в спб (цена от ) с доставкой по городу + *доллар* акции и скидки + петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект + бренд рус

        $metaTitle = '👍 ' . $this->brand->name . ' для собак, кошек и других животных. В новом интернет зоомагазине кормов и товаров для животных в спб (цена от 100 ₽) с доставкой по городу. Акции и скидки. Петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект. ' . $this->brand->name_rus;

        // SEO description
        // *like* бренд англ +для собак, кошек и других животных + в спб *самолетик* с бесплатной доставкой по городу в интернет зоомагазине кормов и товаров для животных *галочка* фото, описание, применение* + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект* + бренд рус

        $metaDescription = '👍 ' . $this->brand->name . ' для собак, кошек и других животных в спб 🚚 с бесплатной доставкой по городу в интернет зоомагазине кормов и товаров для животных ❗ фото, описание, применение. ₽ Акции и скидки 🧡 душевное обслуживание, гарантии. Самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект ' . $this->brand->name_rus;

        SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
    }

    public function sortIt($type, $sort, $name)
    {
        $this->sortSelectedType = $type;
        $this->sortSelectedName = $name;
        $this->sortBy = $sort;
    }

    public function setMaxAndMinPrices()
    {
        $variationsId = Product::isStatusActive()
            ->whereHas('brand', function ($query) {
                $query->where('brand_id', $this->brand->id);
            })
            ->with('variations')
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

    public function updatedMinMaxPrice($minPrice, $maxPrice)
    {
        $this->minPrice = (int)$minPrice;
        $this->maxPrice = (int)$maxPrice;
    }

    public function getCatalogsForFilter(): void
    {
        $this->catalogs = Product::isStatusActive()
            ->has('media')
            ->has('categories')
            ->has('variations')
            ->whereHas('brand', function ($query) {
                $query->where('brand_id', $this->brand->id);
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
            ->has('media')
            ->has('categories')
            ->has('variations')
            ->whereHas('brand', function ($query) {
                $query->where('brand_id', $this->brand->id);
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

    public function getProducts()
    {
        return Product::isStatusActive()
            //->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])
            ->has('media')
            ->has('categories')
            ->has('variations')
            ->whereHas('brand', function ($query) {
                $query->where('brand_id', $this->brand->id);
            })
            ->withWhereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
            })
            ->when($this->petF, function ($query) {
                $query->withWhereHas('categories', function ($query) {
                    $query->whereIn('catalog_id', $this->petF);
                });
            }, function ($query) {
                return $query->with('categories.catalog');
            })
            ->when($this->catF, function ($query) {
                $query->withWhereHas('categories', function ($query) {
                    $query->whereIn('category_id', $this->catF);
                });
            }, function ($query) {
                return $query->with('categories');
            })
                ->with('media')
                ->with('brand')
                ->with('unit')
                ->with('attributes')
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
            'petF',
            'catF',
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

        return view('livewire.site.pages.brand-page', [
            'products' => $products,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
