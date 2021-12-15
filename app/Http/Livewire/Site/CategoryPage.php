<?php

namespace App\Http\Livewire\Site;

use App\Models\AttributeItem;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryPage extends Component
{
    use WithPagination;

    public $category;
    public $catalog;
    public $brands;
    public $tags;
    public $allAttributes;
    public $attributesRangeOn = false;
    public $attributesRange;
    public $attributesRanges = [];
    private $attrsGroupFilters = []; // Групирует выбранные свойства

    public $promoF = false;
    public $attrsF = []; // Собирает выбранные свойства
    public $brandsF = [];
    public $stockF = 3;


    public $showPromoF = true; // TODO сделать проверку
    public $maxPrice = 10000;
    public $minPrice = 0;
    public $sortSelectedName = 'По популярности';
    public $sortSelectedType = 'popularity';
    public $sortBy = 'desc';
    public $tag = [];
    public $metaTitle = 'ZooPodarki';
    public $metaDescription = 'ZooPodarki';
    public $name = 'ZooPodarki';
    public $charity = false;
    public $sortType = [
        '0' => [
            'name' => 'По популярности',
            'type' => 'popularity',
            'sort' => 'desc',
        ],
        '1' => [
            'name' => 'Название: от А до Я',
            'type' => 'name',
            'sort' => 'asc',
        ],
        '2' => [
            'name' => 'Название: от Я до А',
            'type' => 'name',
            'sort' => 'desc',
        ],
        '3' => [
            'name' => 'Цена по возрастанию',
            'type' => 'price_avg',
            'sort' => 'asc',
        ],
        '4' => [
            'name' => 'Цена по убыванию',
            'type' => 'price_avg',
            'sort' => 'desc',
        ],
    ];
    protected $queryString = [
        'attrsF' => ['except' => ''],
        'brandsF' => ['except' => ''],
        'page' => ['except' => 1],
        'promoF' => ['except' => ''],
        'stockF' => ['except' => ''],
    ];
    protected $listeners = ['updateMinPrice', 'updateMaxPrice', 'updateMinRange', 'updateMaxRange'];

    public function mount($catalogslug, $categoryslug, $tagslug = null)
    {
        $this->category = Category::where('slug', $categoryslug)
            ->with(['tags' => function ($query) {
                $query->where('show_on_page', true);
            }])
            ->firstOrFail();

        $this->tags = $this->category->tags;

        $this->catalog = Catalog::where('slug', $catalogslug)
            ->select('id', 'slug', 'name')
            ->firstOrFail();

        if ($tagslug !== null) {
            $this->tag = Tag::where('slug', $tagslug)->firstOrFail();
        }

        $this->setMaxAndMinPrices();

        $this->getBrands($this->category->products);

        $this->name = $this->category->name;

        $this->setSeo();
    }

    public function setMaxAndMinPrices()
    {
        $variationsId = $this->category->products()
            ->isStatusActive()
            ->has('media')
            ->with('variations', fn ($q) => $q->hasStock())
            ->whereHas('variations', fn ($q) => $q->hasStock())
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

    public function setSeo()
    {
        SEOMeta::setTitle($this->category->meta_title ? $this->category->meta_title : $this->category->name)
                ->setDescription($this->category->meta_description ? $this->category->meta_description : $this->category->name);

        if (!empty($this->tag)) {
            $this->getTagFilters();

            $metaTitle = '👍 '
            . $this->tag->meta_title
            . ', купите в новом интернет зоомагазине спб с доставкой (цена от '
            . discount($this->minPrice, 5) . ' рублей), акции и скидки, петшопы в Невском районе и пр. Просвещения';
            $this->name = $this->tag->name;

            $metaDescription = '👍 '
            . $this->tag->meta_title .
            ' (в наличии) купите в спб 🚚 с бесплатной доставкой в интернет зоомагазине (цена от '
            . discount($this->minPrice, 5) .
            ' рублей) ❗ фото, составы, описание, применение, дозировка, акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района и с пр. Просвещения';

            SEOMeta::setTitle($metaTitle)
                ->setDescription($metaDescription);
        }
    }

    public function getBrands($products)
    {
        $brands_ids = $products
            ->pluck('brand_id')
            ->flatten()
            ->unique()
            ->flatten()
            ->all();

        $this->brands = DB::table('brands')
            ->whereIn('id', $brands_ids)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
    }

    public function getAttributes($productAttributes = [])
    {
        $allAttributes = $this->category->filters($productAttributes);

        $this->attributesRange = $allAttributes
            ->where('range', 1)->flatten()->toArray();

        foreach ($allAttributes->where('range', 1)->pluck('items') as $key => $item) {
            array_push(
                $this->attributesRanges,
                [
                    'id' => $this->attributesRange[$key]['id'],
                    'name' => $this->attributesRange[$key]['name'],
                    'max' => $item->max('name'),
                    'min' => $item->min('name'),
                ],
            );
        }

        $this->allAttributes = $allAttributes->where('range', 0)->toArray();
    }

    public function setAttFilter($attrsF = [])
    {
        $this->attrsF = array_unique($attrsF);

        $attFilters = AttributeItem::whereIn('id', $attrsF)
            ->get();

        $this->attrsGroupFilters = $attFilters->mapToGroups(function ($item) {
            return [$item['attribute_id'] => $item['id']];
        });

        $this->attrsGroupFilters = $this->attrsGroupFilters->toArray();
        //dd($this->attrsGroupFilters);
    }

    public function updatedBrandsF()
    {
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function updateMinRange($minRange, $key)
    {
        $this->attributesRanges[$key]['min'] = $minRange;
        $this->resetPage();
        $this->attributesRangeOn = true;
    }

    public function updateMaxRange($maxRange, $key)
    {
        $this->attributesRanges[$key]['max'] = $maxRange;
        $this->resetPage();
        $this->attributesRangeOn = true;
    }

    public function sortIt($type, $sort, $name)
    {
        $this->sortSelectedType = $type;
        $this->sortSelectedName = $name;
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'attrsF',
            'brandsF',
            'promoF',
            'stockF',
            'attributesRanges',
            'allAttributes',
        ]);
        $this->setMaxAndMinPrices();
        $this->resetPage();

        $this->dispatchBrowserEvent('reset-range');
    }

    public function getTagFilters()
    {
        foreach ($this->tag->filter as $filter) {
            array_push($this->attrsF, $filter['id']);
        }
        $this->resetPage();
    }

    public function getProducts()
    {
        return Product::isStatusActive()
            ->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])

            ->has('media')

            ->whereHas('categories', fn ($q) => $q->where('category_id', $this->category->id))

            ->when($this->brandsF, function ($query) {
                $query->whereIn('brand_id', $this->brandsF);
            })

            ->when($this->attributesRangeOn, function ($query) {
                return $query->whereHas('attributes', function ($query1) {
                    $query1->where(function ($subQuery) {
                        if ($this->attributesRanges > 0) {
                            foreach ($this->attributesRanges as $key => $range) {
                                return $subQuery->where(
                                    'attribute_item.attribute_id',
                                    $this->attributesRanges[$key]['id']
                                )
                                        ->whereBetween('name', [
                                            $this->attributesRanges[$key]['min'],
                                            $this->attributesRanges[$key]['max'],
                                        ]);
                            }
                        }
                    });
                });
            })

            ->whereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->when($this->promoF, function ($query) {
                    $query->where('promotion_type', '>', 0);
                });
            })

            ->when($this->attrsF, function ($query) {
                foreach ($this->attrsGroupFilters as $ids) {
                    $query->whereHas('attributes', fn ($q) => $q->whereIn('attribute_item.id', $ids));
                }
            })

            ->when($this->stockF, function ($query) {
                $query->checkStock((int) $this->stockF);
            })

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
        $this->setAttFilter($this->attrsF);

        $products = $this->getProducts();


        $this->getAttributes($products->pluck('attributes')->flatten()->pluck('id')->unique()->values()->toArray());

        if ($products->total() < 5) {
            SEOMeta::setRobots('noindex, nofollow');
        }

        $this->emit('lozad', '');

        return view('livewire.site.category-page', [
            'products' => $products,
        ])
                    ->extends('layouts.app')
                    ->section('content');
    }
}
