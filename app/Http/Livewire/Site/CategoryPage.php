<?php

namespace App\Http\Livewire\Site;

use App\Models\AttributeItem;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryPage extends Component
{
    use WithPagination;

    public $category;
    public $catalog;
    public $brands;
    public $attrs;
    public $attributesRangeOn = false;
    public $attributesRange;
    public $attributesRanges = [];
    public $attrsF = []; // Собирает выбранные свойства
    private $attsFilters = []; // Групирует выбранные свойства
    public $brandsF = [];
    public $showPromoF = true; // TODO сделать проверку
    public $promoF = false;
    public $maxPrice = 10000;
    public $minPrice = 0;
    public $productsCount = 0;
    public $stockF = 1; // 0 without stock, 1 with stock, 2 all
    public $sortSelectedName = 'Название: от А до Я';
    public $sortSelectedType = 'name';
    public $sortBy = 'asc';
    public $tag = [];
    public $metaTitle = 'ZooPodarki';
    public $metaDescription = 'ZooPodarki';
    public $name = 'ZooPodarki';
    public $charity = false;
    public $sortType = [
        '0' => [
            'name' => 'По популярности',
            'type' => 'popularity',
            'sort' => 'asc',
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
        'stockF',
    ];
    protected $listeners = ['updateMinPrice', 'updateMaxPrice', 'updateMinRange', 'updateMaxRange'];

    public function mount($catalogslug, $categoryslug, $tagslug = null)
    {
        $this->category = Category::where('slug', $categoryslug)
            ->with(['tags' => function ($query) {
                $query->where('show_on_page', true);
            }])
            ->first();

        $this->catalog = Catalog::where('slug', $catalogslug)
            ->first();

        if ($tagslug !== null) {
            $this->tag = Tag::where('slug', $tagslug)->first();
        }

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


        meta()
            ->set('title', $this->category->meta_title ? $this->category->meta_title : $this->category->name)
            ->set('description', $this->category->meta_description ? $this->category->meta_description : $this->category->name);

        $this->name = $this->category->name;

        if (!empty($this->tag)) {
            $this->getTagFilters();

            $metaTitle = '👍 '
            . $this->tag->meta_title
            . ', купите в новом интернет зоомагазине спб с доставкой (цена от '
            . $this->minPrice . ' рублей), акции и скидки, петшопы в Невском районе и пр. Просвещения';
            // TODO вычесть 5% из $this->minPrice
            $this->name = $this->tag->name;

            $metaDescription = '👍 '
            . $this->tag->meta_title .
            ' (в наличии) купите в спб 🚚 с бесплатной доставкой в интернет зоомагазине (цена от '
            . $this->minPrice .
            ' рублей) ❗ фото, составы, описание, применение, дозировка, акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района и с пр. Просвещения';

            meta()
                ->set('title', $metaTitle)
                ->set('description', $metaDescription);
        }

        $brands_ids = $this->category
            ->products()
            ->has('brand')
            ->has('media')
            ->isStatusActive()
            ->whereHas('variations', fn ($q) => $q->whereBetween('price', [$this->minPrice, $this->maxPrice]))
            ->hasStock()
            ->get()
            ->pluck('brand_id')
            ->flatten()
            ->unique()
            ->flatten()
            ->all();

        $this->brands = DB::table('brands')
            ->whereIn('id', $brands_ids)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);


        $this->updatedAttFilter($this->attrsF);
    }


    public function getAttributes($items = [])
    {

        // $this->reset('attrsF');
        $attrs = $this->category->filters($items);

        $this->attributesRange = $attrs
            ->where('range', 1)->flatten()->toArray();

        foreach ($attrs->where('range', 1)->pluck('items') as $key => $item) {
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

        $this->attrs = $attrs->where('range', 0)->all();
    }

    public function updatedAttFilter($attrsF = [])
    {
        $this->attrsF = array_unique($attrsF);

        // dd($this->attrsF);
        $attFilters = AttributeItem::whereIn('id', $attrsF)
            ->get();

        $this->attsFilters = $attFilters->mapToGroups(function ($item) {
            return [$item['attribute_id'] => $item['id']];
        });
    }

    public function updateMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;

        $this->resetPage();
    }

    public function updateMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
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
            // 'attsFilters',
            'brandsF',
            'attributesRanges',
            'maxPrice',
            'minPrice',
        ]);
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

    public function render()
    {
        $products = Product::isStatusActive()
            ->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])
            ->has('media')
            ->whereHas('categories', fn ($q) => $q->where('category_id', $this->category->id))
            ->whereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->when($this->stockF == 0, function ($query) {
                    $query->where('stock', 0);
                })
                ->when($this->stockF == 1, function ($query) {
                    $query->where('stock', '>=', 1);
                })
                ->when($this->stockF == 0, function ($query) {
                    $query->where('stock', 0);
                })
                ->when($this->promoF, function ($query) {
                    $query->where('promotion_type', '>', 0);
                })
                ->where('price', '>=', 1);
            })
            ->when($this->attrsF, function ($query) {
                if (count($this->attsFilters) >= 2) {
                    foreach ($this->attsFilters as $ids) {
                        $query->whereHas('attributes', fn ($q) => $q->whereIn('attribute_item.id', $ids));
                    }
                } else {
                    $query->whereHas('attributes', fn ($q) => $q->whereIn('attribute_item.id', $this->attrsF));
                }
            })
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
                ->with('media')
                ->with('brand')
                ->with('unit')
                ->with('attributes')
                ->with('variations')
                ->orderBy($this->sortSelectedType, $this->sortBy)
                ->paginate(32);

        $this->emit('lozad', '');

        $this->productsCount = $products->count();
        if ($this->productsCount < 5) {
            meta()->noIndex();
        }


        // TODO пропадают фильтры

        if (count($this->attrsF) > 0) {
            $this->getAttributes($products->pluck('attributes')->flatten()->pluck('id')->unique()->values()->toArray());
        } else {
            $this->getAttributes();
        }

        return view('livewire.site.category-page', [
            'products' => $products,
        ])
                    ->extends('layouts.app')
                    ->section('content');
    }
}
