<?php

namespace App\Http\Livewire\Site;

use App\Models\Attribute;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Brand extends Component
{
    use WithPagination;

    public $countries;
    public $brand;
    public $attrs;
    public $attributesRangeOn = false;
    public $attributesRange;
    public $attributesRanges = [];
    public $attFilter = []; // Собирает выбранные свойства
    private $attsFilters = [];
    public $maxPrice = 10000;
    public $minPrice = 0;
    public $sortSelectedName = 'Название: от А до Я';
    public $sortSelectedType = 'name';
    public $sortBy = 'asc';

    protected $queryString = ['attFilter'];

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

    protected $listeners = ['updateMinPrice', 'updateMaxPrice', 'updateAttributeFilter'];

    public function mount()
    {
        $ids = $this->brand->productsAttributes()->get()->pluck('id');
        // Берет свойства которые есть у товаров только этой категории
        $this->attrs = Attribute::withWhereHas('items', function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        })
            ->orderBy('name', 'asc')
            ->get();

        $variationsId = $this->brand->products()
            ->isStatusActive()
            ->with('variations', function ($query) {
                $query
                    ->where('stock', '>', 0)
                    ->where('price', '>', 0);
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

        $this->countries = cache()->remember('categories-menu', 60 * 60 * 24, function () {
            return Product::where('brand_id', $this->brand->id)
                ->withWhereHas('attributes', fn ($q) => $q->where('attribute_item.attribute_id', 64))
                ->get()
                ->pluck('attributes')
                ->flatten()
                ->pluck('name')
                ->unique()
                ->all();
        });
    }

    public function updateAttributeFilter($attFilter)
    {
        $this->attFilter = $attFilter;
    }

    public function updateMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
    }

    public function updateMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
    }

    public function sortIt($type, $sort, $name)
    {
        $this->sortSelectedType = $type;
        $this->sortSelectedName = $name;
        $this->sortBy = $sort;
    }

    public function resetFilters()
    {
        $this->attFilter = [];
        $this->maxPrice = 10000;
        $this->minPrice = 0;
        $this->dispatchBrowserEvent('reset-range');
    }

    public function render()
    {

        $products = Product::isStatusActive()
        ->where('brand_id', $this->brand->id)
        ->whereHas('variations', function ($query) {
            $query
                ->whereBetween('price', [$this->minPrice, $this->maxPrice])
                ->where('stock', '>', 0)
                ->whereNotNull('price');
        })
        ->when($this->attFilter, function ($query) {
            return $query->whereHas('attributes', function ($query) {
                $query->whereIn('attribute_item.id', $this->attFilter);
            });
        })
        ->has('media')
        ->with('brand')
        ->with('unit')
        ->with('attributes')
        ->with('variations')
        ->with('categories')
        ->with('categories.catalog')
        ->with('media')
        ->orderBy($this->sortSelectedType, $this->sortBy)
        ->paginate(32);

        $this->emit('lozad', '');

        return view('livewire.site.brand', [
            'products' => $products,
        ]);
    }
}
