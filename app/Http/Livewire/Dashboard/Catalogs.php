<?php
namespace App\Http\Livewire\Dashboard;

use App\Models\Catalog;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Catalogs extends Component
{
    use WithPagination;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $itemsPerPage = 30;
    public $editCatalog = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'meta_title' => null,
        'meta_description' => null,
        'menu' => 1,
        'sort' => 1,
    ];
    public $categories = [];
    public $editCategory = [
        'id' => null,
        'name' => null,
        'menu_name' => null,
        'slug' => null,
        'meta_title' => null,
        'meta_description' => null,
        'menu' => 1,
        'sort' => 0,
        'show_in_catalog' => 0,
        'attributes' => '',
        'catalog_id' => null,
    ];
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
    ];
    protected $listeners = ['save', 'closeFormCategory'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortDirection = 'desc';
        }
        $this->sortField = $field;
    }

    public function openForm($catalog_id)
    {
        $this->categories = Category::where('catalog_id', $catalog_id)->orderBy('sort')->get()->toArray();

        $this->editCatalog = Catalog::where('id', $catalog_id)->first()->toArray();
    }

    public function openCategory($id)
    {
        if ($id !== null) {
            $this->editCategory = Category::find($id)->toArray();
        }
    }

    public function saveCategory()
    {
        $this->validate([
            'editCategory.name' => 'required|min:2',
        ]);

        if (Arr::has($this->editCategory, 'name')) {
            DB::transaction(function () {
                if (Arr::has($this->editCategory, 'id') && Category::where('id', $this->editCategory['id'])->exists()) {
                    $category = Category::find($this->editCategory['id']);

                    $category->update([
                        'name' => trim($this->editCategory['name']),
                        'menu_name' => trim($this->editCategory['menu_name']),
                        'meta_title' => $this->editCategory['meta_title'],
                        'meta_description' => $this->editCategory['meta_description'],
                        'attributes' => $this->editCategory['attributes'],
                        'menu' => $this->editCategory['menu'],
                        'show_in_catalog' => $this->editCategory['show_in_catalog'],
                        'catalog_id' => $this->editCatalog['id'],
                    ]);

                    $category->save();
                } else {
                    Category::create([
                        'name' => trim($this->editCategory['name']),
                        'menu_name' => trim($this->editCategory['menu_name']),
                        'meta_title' => $this->editCategory['meta_title'],
                        'meta_description' => $this->editCategory['meta_description'],
                        'attributes' => $this->editCategory['attributes'],
                        'menu' => $this->editCategory['menu'],
                        'show_in_catalog' => $this->editCategory['show_in_catalog'],
                        'catalog_id' => $this->editCatalog['id'],
                    ]);
                }
            });

            toast()
                ->success('Категория "' . $this->editCategory['name'] . '" сохранена')
                ->push();

        }

        $this->editCategory = [];
        $this->categories = Category::where('catalog_id', $this->editCatalog['id'])->get();

        $this->resetValidation();
        $this->closeFormCategory();
    }

    public function save()
    {
        $this->validate([
            'editCatalog.name' => 'required|unique:catalogs,name,' . $this->editCatalog['id'],
            'editCatalog.slug' => 'required|unique:catalogs,slug,' . $this->editCatalog['id'],
        ]);

        DB::transaction(function () {
            $this->editCatalog = Catalog::updateOrCreate(
                ['id' => $this->editCatalog['id']],
                [
                    'name' => trim($this->editCatalog['name']),
                    'slug' => trim(Str::of($this->editCatalog['slug'])->slug('-')),
                    'meta_title' => $this->editCatalog['meta_title'],
                    'menu' => $this->editCatalog['menu'],
                    'sort' => $this->editCatalog['sort'],
                    'meta_description' => $this->editCatalog['meta_description'],
                ]
            );

            foreach ($this->categories as $key => $item) {
                if (Arr::has($item, 'id') && Category::where('id', $item['id'])->exists()) {
                    $category = Category::find($item['id']);
                    $category->update([
                        'sort' => $key,
                    ]);

                    $category->save();
                }
            }

            $this->categories = Category::where('catalog_id', $this->editCatalog->id)->get();

            // Clean the cache after saving
            Cache::forget('categories-menu');

            toast()
                ->success('Каталог "' . $this->editCatalog->name . '" сохранен.')
                ->push();

            $this->resetValidation();
            $this->closeForm();
        });
    }

    public function removeItem($id)
    {
        if ($category = Category::find($id)) {
            if ($category->products()->exists()) {

                toast()
                    ->warning('У категории "' . $category->name . '" есть товары')
                    ->push();

            } else {
                $category->delete();

                $this->categories = Category::where('catalog_id', $this->editCatalog['id'])->get();

                $this->closeFormCategory();

                toast()
                    ->success('Категория удалена.')
                    ->push();

            }
        } else {
            toast()
                ->warning('Категория не удалена.')
                ->push();

        }
    }

    public function remove($item_id)
    {
        $catalog = Catalog::where('id', $item_id)->with('categories')->first();

        DB::transaction(function () use ($catalog) {
            if ($catalog->categories()->exists()) {
                foreach ($catalog->categories as $item) {
                    if ($item->products()->exists()) {
                        return toast()
                            ->warning('У категории "' . $item->name . '" есть товары')
                            ->push();

                    } else {
                        $this->removeItem($item);
                    }

                    $catalog_name = $catalog->name;
                    $catalog->delete();

                    $this->resetFields();

                    toast()
                        ->success('Каталог "' . $catalog_name . '" удален.')
                        ->push();

                }
            } else {
                $catalog_name = $catalog->name;
                $catalog->delete();

                $this->resetFields();

                toast()
                    ->success('Каталог "' . $catalog_name . '" удален.')
                    ->push();

            }
        });
    }

    public function closeForm()
    {
        $this->resetFields();
        $this->dispatchBrowserEvent('close');
    }

    public function resetFields()
    {
        $this->reset(['editCatalog', 'categories']);
    }

    public function closeFormCategory()
    {
        $this->reset('editCategory');

        $this->dispatchBrowserEvent('close-form-category');
    }

    public function render()
    {
        return view('livewire.dashboard.catalogs', [
            'catalogs' => Catalog::when($this->search, function ($query) {
                $query->whereLike(['name', 'id', 'categories.name'], $this->search);
            })
                ->with('categories')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->itemsPerPage),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
