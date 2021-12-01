<?php

namespace App\Http\Livewire\Site;

use App\Models\AttributeItem;
use App\Models\Product;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Category extends Component
{
    use WithPagination;

    public $category;
    public $catalog;
    public $brands;
    public $attrs;
    public $attributesRangeOn = false;
    public $attributesRange;
    public $attributesRanges = [];
    public $attFilter = []; // Собирает выбранные свойства
    private $attsFilters = []; // Групирует выбранные свойства
    public $brandFilter = [];
    public $maxPrice = 10000;
    public $minPrice = 0;
    public $productsCount = 0;
    public $filterStock = 1; // 0 without stock, 1 with stock, 2 all
    public $sortSelectedName = 'Название: от А до Я';
    public $sortSelectedType = 'name';
    public $sortBy = 'asc';
    public $page = 1;
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
        'attFilter' => ['except' => ''],
        'brandFilter' => ['except' => ''],
    ];
    protected $listeners = ['updateMinPrice', 'updateMaxPrice', 'updateMinRange', 'updateMaxRange'];

    public function mount()
    {
        $variationsId = $this->category->products()
            ->isStatusActive()
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

        if (! empty($this->tag)) {
            $this->getTagFilters();
            $this->metaTitle = '👍 '
            .$this->tag->meta_title
            .', купите в новом интернет зоомагазине спб с доставкой (цена от '
            .$this->minPrice.' рублей), акции и скидки, петшопы в Невском районе и пр. Просвещения';
            // TODO вычесть 5% из $this->minPrice
            $this->name = $this->tag->name;

            $this->metaDescription = '👍 '
            .$this->tag->meta_title.
            ' (в наличии) купите в спб 🚚 с бесплатной доставкой в интернет зоомагазине (цена от '
            .$this->minPrice.
            ' рублей) ❗ фото, составы, описание, применение, дозировка, акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района и с пр. Просвещения';
        } else {
            $this->metaTitle = $this->category->meta_title;
             // TODO добавить в title . ' | страница ' . $this->products->links()->paginator->currentPage() . ' из ' . $this->products->links()->paginator->lastPage();
            $this->name = $this->category->name;
            $this->metaDescription = $this->category->meta_description;
        }

        $brands_ids = $this->category
            ->products()
            ->isStatusActive()
            ->whereHas('variations', fn ($q) => $q->whereBetween('price', [$this->minPrice, $this->maxPrice])->hasStock())
            ->has('brand')
            ->get()
            ->pluck('brand_id')
            ->flatten()
            ->unique()
            ->all();

        $this->brands = DB::table('brands')
            ->whereIn('id', $brands_ids)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        $this->updatedAttFilter($this->attFilter);

        $this->seo();
    }

    public function seo()
    {
        SEOMeta::setTitle($this->metaTitle);
        SEOMeta::setDescription($this->metaDescription);
        // SEOMeta::addKeyword(['key1', 'key2', 'key3']);
        OpenGraph::setTitle($this->metaTitle);
        OpenGraph::setDescription($this->metaDescription);
        OpenGraph::addProperty('type', 'website');
        // OpenGraph::addImage($post->cover->url);
    }

    public function getAttributes($items = [])
    {
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

    public function updatedAttFilter($ids = [])
    {

        $attFilters = AttributeItem::whereIn('id', $ids)
            ->get();

        $this->attsFilters = $attFilters->mapToGroups(function ($item) {
            return [$item['attribute_id'] => $item['id']];
        });
    }

    public function updateMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
        $this->page = 1;
    }

    public function updateMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
        $this->page = 1;
    }

    public function updateMinRange($minRange, $key)
    {
        $this->attributesRanges[$key]['min'] = $minRange;
        $this->page = 1;
        $this->attributesRangeOn = true;
    }

    public function updateMaxRange($maxRange, $key)
    {
        $this->attributesRanges[$key]['max'] = $maxRange;
        $this->page = 1;
        $this->attributesRangeOn = true;
    }

    public function sortIt($type, $sort, $name)
    {
        $this->sortSelectedType = $type;
        $this->sortSelectedName = $name;
        $this->sortBy = $sort;
    }

    public function resetFilters()
    {
        $this->reset([
            'attFilter',
            // 'attsFilters',
            'brandFilter',
            'attributesRanges',
            'maxPrice',
            'minPrice',
        ]);

        $this->dispatchBrowserEvent('reset-range');
    }

    public function getTagFilters()
    {
        foreach ($this->tag->filter as $filter) {
            array_push($this->attFilter, $filter['id']);
        }
    }

    public function render()
    {
        $products = Product::isStatusActive()
            ->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])
            ->has('media')
            ->whereHas('categories', fn ($q) => $q->where('category_id', $this->category->id))
            ->whereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->when($this->filterStock == 0, function ($query) {
                    $query->where('stock', 0);
                })
                ->when($this->filterStock == 1, function ($query) {
                    $query->where('stock', '>=', 1);
                })
                ->when($this->filterStock == 0, function ($query) {
                    $query->where('stock', 0);
                })
                ->where('price', '>=', 1);
            })
            ->when($this->attFilter, function ($query) {
                if (count($this->attsFilters) >= 2) {
                    foreach ($this->attsFilters as $ids) {
                        $query->whereHas('attributes', fn ($q) => $q->whereIn('attribute_item.id', $ids));
                    }
                } else {
                    $query->whereHas('attributes', fn ($q) => $q->whereIn('attribute_item.id', $this->attFilter));
                }
            })
            ->when($this->brandFilter, function ($query) {
                    $query->whereIn('brand_id', $this->brandFilter);
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

                $this->resetPage();

                $this->emit('lozad', '');

                $this->productsCount = $products->count();

        // TODO пропадают фильтры
        if ($this->attFilter) {
            $this->getAttributes($products->pluck('attributes')->flatten()->pluck('id')->unique()->values()->toArray());
        } else {
            $this->getAttributes();
        }

                return view('livewire.site.category', [
                    'products' => $products,
                ]);
    }
}
