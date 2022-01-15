<?php

namespace App\Http\Livewire\Site\Pages;

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

    public $shelterUrgentlyRequired = false; // Срочно требуется для приюта 2546
    public $shelterMarkdown = false; // Уценка для приюта

    public $promoF = false;
    public $attrsF = []; // Собирает выбранные свойства
    public $brandsF = [];
    public $stockF = 3;

    public $showPromoF = true; // TODO сделать проверку
    public $maxPrice = 10000;
    public $minPrice = 0;
    public $maxRange = 10000;
    public $minRange = 0;
    public $sortSelectedName = 'По популярности';
    public $sortSelectedType = 'popularity';
    public $sortBy = 'desc';
    public $tag = [];
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
    protected $listeners = ['updatedMinMaxPrice', 'updatedMinMaxRange', ];

    public function mount($catalogslug, $categoryslug, $tagslug = null)
    {
        $this->category = Category::where('slug', $categoryslug)
            ->with(['tags' => function ($query) {
                $query->where('show_on_page', true);
            }])
            ->firstOrFail();

        $this->tags = $this->category->tags->toArray();

        $this->catalog = Catalog::where('slug', $catalogslug)
            ->select('id', 'slug', 'name')
            ->firstOrFail()->toArray();

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

        $this->maxRange = $this->maxPrice;
        $this->minRange = $this->minPrice;
    }

    public function setSeo()
    {
        if (!empty($this->tag)) {
            $this->getTagFilters();

            // SEO TITLE
            // *like* Яндекс title + купите в новом интернет зоомагазине товаров для животных в спб с доставкой + (цена от ...) + *доллар* акции и скидки + петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

            $metaTitle = '👍 '
            . $this->tag->meta_title
            . ' купите в новом интернет зоомагазине товаров для животных в спб с доставкой (цена от '
            . discount($this->minPrice, 5) . ' ₽), акции и скидки, петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

            //SEO DESCRIPTION
            // *like* Яндекс title + (в наличии) купите в спб + *самолетик* с бесплатной доставкой в интернет зоомагазине товаров для животных  (цена от ...) + *галочка* фото, составы, описание, применение* + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

            $metaDescription = '👍 '
            . $this->tag->meta_title .
            ' (в наличии) купите в спб 🚚 с бесплатной доставкой в интернет зоомагазине товаров для животных (цена от '
            . discount($this->minPrice, 5) .
            ' рублей) ❗ фото, составы, описание, применение, ₽ акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

            SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
        } else {
            if ($this->catalog['id'] === 14) {
                // SEO TITLE
                // Купите корм для питомника и приюта для собак и кошек в спб + (цена  от ...) + в новом интернет магазине кормов и товаров для животных Зооподарки *доллар* Акции, Скидки, Распродажи, Душевное обслуживание

                $metaTitle = 'Купите корм для питомника и приюта для собак и кошек в спб (цена от '
            . discount($this->minPrice, 5) . ' рублей) в новом интернет магазине кормов и товаров для животных Зооподарки ₽ Акции, Скидки, Распродажи, Душевное обслуживание';

                //SEO description
                // *сердечко* Купите корм для питомника и приюта для собак и кошек в спб + (цена  от ...) + в новом интернет магазине кормов и товаров для животных Зооподарки *доллар* Акции, Скидки, Распродажи

                $metaDescription = '🧡  '
            . $this->category->meta_description .
            ' Купите корм для питомника и приюта для собак и кошек в спб (цена от '
            . discount($this->minPrice, 5) .
            ' рублей) в новом интернет магазине кормов и товаров для животных Зооподарки ₽ Акции, Скидки, Распродажи';
            } else {
                // SEO TITLE
                // *like* для SEO title + купите + в новом интернет зоомагазине спб с доставкой (цена от ) +*доллар* акции и скидки + петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

                $metaTitle = '👍 '
            . $this->category->meta_title
            . ', купите в новом интернет зоомагазине спб с доставкой (цена от '
            . discount($this->minPrice, 5) . ' ₽) акции и скидки + петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

                //SEO description
                // *like* название для SEO description + купите в магазине кормов и товаров для животных в спб *самолетик* с бесплатной доставкой *галочка* фото, составы, описание, применение* + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

                $metaDescription = '👍 '
            . $this->category->meta_description .
            ' купите в магазине кормов и товаров для животных в спб 🚚 с бесплатной доставкой❗ (цена от '
            . discount($this->minPrice, 5) .
            ' рублей) фото, составы, описание, применение, дозировка, ₽ акции и скидки 🧡 душевное обслуживание, гарантии, 🚚 самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';
            }



            SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
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
                    'max' => str_replace(',', '.', $item->max('name')),
                    'min' => str_replace(',', '.', $item->min('name')),
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
    }

    public function updatedMinMaxPrice($minPrice, $maxPrice)
    {
        $this->minPrice = (int)$minPrice;
        $this->maxPrice = (int)$maxPrice;
    }


    public function updatedMinMaxRange($minRange, $maxRange, $key)
    {
        $this->attributesRanges[$key]['min'] = $minRange;
        $this->attributesRanges[$key]['max'] = $maxRange;
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
        $this->dispatchBrowserEvent('reset-range-attr');
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
            ->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id', 'discount_weight'])
            ->has('media')
            ->has('categories')
            ->has('variations')
            ->whereHas('categories', fn ($q) => $q->where('category_id', $this->category->id))

            ->when($this->brandsF, function ($query) {
                $query->whereIn('brand_id', $this->brandsF);
            })
            ->with('attributes')
            // TODO неработает
            ->when($this->attributesRangeOn, function ($query) {
                $query->whereHas('attributes', function ($query) {
                    foreach ($this->attributesRanges as $range) {
                        $query->where(
                            'attribute_item.attribute_id',
                            $range['id']
                        )
                        ->whereBetween('attribute_item.name', [
                            $range['min'],
                            $range['max'],
                        ]);
                    }
                });
            })

             ->when($this->shelterUrgentlyRequired, function ($query) {
                 $query->whereHas('attributes', fn ($q) => $q->where('attribute_item.id', 2546));
             })

            ->whereHas('variations', function ($query) {
                $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->when($this->promoF, function ($query) {
                    $query->where('promotion_type', '>', 0);
                })
                ->when($this->shelterMarkdown, function ($query) {
                    $query->where('promotion_type', 1);
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
              //  ->with(['media' => function ($query) { $query->unique('model_id'); }])
                ->with('media:id,model_id,model_type,collection_name,disk,conversions_disk,generated_conversions,file_name')
                ->with('brand:id,name,slug')
                ->with('unit:id,name')
                ->with('variations:id,product_id,price,promotion_type,unit_value,promotion_percent,stock')
                ->orderBy($this->sortSelectedType, $this->sortBy)
                ->paginate(32);
    }

    public function updated()
    {
        $this->resetPage();
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

        return view('livewire.site.pages.category-page', [
            'products' => $products,
        ])
                    ->extends('layouts.app')
                    ->section('content');
    }
}
