<?php

namespace App\Http\Livewire\Dashboard\Products;

use App\Models\Catalog;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class Index extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $onlyTrashed = false;
    public $countTrash;
    public $variationMoreOne = false;
    public $productsWithoutDescription = false;
    public $filteredByCategory = null;
    public $filteredByAttribute = false;
    public $attrId;
    public $productsWithoutImage = false;
    public $available = false;
    public $noCategories = false;
    public $catalogs;
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 0],
        'sortField',
        'sortDirection',
        'itemsPerPage',
        'onlyTrashed',
        'variationMoreOne',
        'filteredByCategory',
        'filteredByAttribute',
        'attrId',
        'productsWithoutDescription',
        'productsWithoutImage',
        'available',
    ];

    public function mount()
    {
        $this->countTrash = Product::onlyTrashed()->count();
        $this->catalogs = Catalog::with(['categories' => function ($query) {
            $query->orderBy('name');
        }])
            ->orderBy('sort')
            ->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilteredByCategory()
    {
        $this->resetPage();
    }

    public function updatingAvailable()
    {
        $this->resetPage();
    }

    public function updatingProductsWithoutDescription()
    {
        $this->resetPage();
    }

    public function updatingProductsWithoutImage()
    {
        $this->resetPage();
    }

    public function showTrashed()
    {
        $this->resetPage();
        $this->onlyTrashed = ! $this->onlyTrashed;
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->where('id', $id)->with(['categories', 'attributes'])->first();

        if ($product->categories && $product->categories->isNotEmpty()) {
            $product->categories()->detach();
        }

        if ($product->attributes && $product->attributes->isNotEmpty()) {
            $product->attributes()->detach();
        }

        $product->variations()->update(['product_id' => null]);

        $product->forceDelete();

        $this->countTrash = Product::onlyTrashed()->count();

        toast()
            ->warning('Товар удален полностью')
            ->push();
    }

    public function forceDeleteAll()
    {
        $products = Product::onlyTrashed()->get();

        foreach ($products as $product) {
            if ($product->categories->isNotEmpty()) {
                $product->categories()->detach();
            }

            if ($product->attributes->isNotEmpty()) {
                $product->attributes()->detach();
            }

            $product->variations()->update(['product_id' => null]);

            $product->forceDelete();
        }

        $this->countTrash = Product::onlyTrashed()->count();

        toast()
            ->info('Корзина отчищена')
            ->push();
    }

    public function restoreTrashed($id)
    {
        if ($product = Product::onlyTrashed()->find($id)) {
            $product->restore();

            $this->countTrash = Product::onlyTrashed()->count();

            toast()
                ->success('Товар востановлен')
                ->push();
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortDirection = 'desc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function getOnlyTrashed()
    {
        return Product::onlyTrashed()
            ->with('categories', 'variations', 'attributes', 'attributes.attribute')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('id', 'like', '%'.$this->search.'%')
                    ->orWhereHas('attributes', function ($query) {
                        $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('categories', function ($query) {
                        $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('variations', function ($query) {
                        $query->where('name', 'like', '%'.$this->search.'%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);
    }

    public function getProducts()
    {
        return Product::when($this->search, function ($query) {
            if ($this->filteredByAttribute && $this->attrId) {
                $query->whereHas('attributes', function ($query) {
                    $query->where([
                        ['attribute_item.name', 'LIKE', '%'.$this->search.'%'],
                        ['attribute_item.attribute_id', $this->attrId],
                    ]);
                });
                $query->with('attributes', function ($query) {
                    $query->where([
                        ['attribute_item.name', 'LIKE', '%'.$this->search.'%'],
                        ['attribute_item.attribute_id', $this->attrId],
                    ]);
                });
            } else {
                $query->whereLike(['name', 'id', 'categories.name', 'variations.name'], $this->search);
            }
        })
            ->when($this->filteredByCategory, function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->where('category_id', $this->filteredByCategory);
                });
            })
            ->when($this->noCategories, function ($query) {
                $query->doesntHave('categories')
                ->whereHas('variations', function ($query) {
                    $query->hasStock();
                });
            })
            ->when($this->variationMoreOne, function ($query) {
                $query->has('variations', '>=', 2);
            })
            ->when($this->available, function ($query) {
                $query->whereHas('variations', function ($query) {
                     $query->hasStock();
                });
            })
            ->when($this->productsWithoutDescription, function ($query) {
                $query->where('description', null);
            })
            ->when($this->productsWithoutImage, function ($query) {
                $query->doesntHave('media');
            })
            ->with('categories', 'variations', 'attributes')
            ->withCount('media')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);
    }

    public function render()
    {
        if ($this->onlyTrashed) {
            $products = $this->getOnlyTrashed();
        } else {
            $products = $this->getProducts();
        }

        return view('livewire.dashboard.products.index', [
            'products' => $products,
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
