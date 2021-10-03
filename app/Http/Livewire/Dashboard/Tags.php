<?php
namespace App\Http\Livewire\Dashboard;

use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Tags extends Component
{
    use WithPagination;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $filteredByCategory = null;
    public $onlyOnPage = false;
    public $catalogs;
    public $editTag = [
        'id' => null,
        'name' => null,
        'title' => null,
        'meta_title' => null,
        'slug' => null,
        'filter' => [],
        'category_id' => null,
        'show_on_page' => false,
    ];
    public $categoryId; //  ID a selected category
    public $categoryfilters = []; // Array 'attributes' a selected category
    public $selectedTypeFilterId = null; // Id a selected attribute
    public $filterItems = []; // Array 'attribute_items'
    public $selectedFilterId = null; // Id a selected attribute item
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
        'filteredByCategory',
        'onlyOnPage',
    ];

    public function mount()
    {
        $this->catalogs = Catalog::with(['categories' => function ($query) {
            $query->orderBy('name');
        }])
            ->orderBy('sort')
            ->get();
    }

    public function updatingFilteredByCategory()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOnlyOnPage()
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

    public function addNew()
    {
        $this->resetFields();
    }

    public function openForm($id)
    {
        $this->editTag = Tag::where('id', $id)->first()->toArray();

        $this->categoryId = $this->editTag['category_id'];

        $this->updatedCategoryId($this->categoryId);
    }

    public function updatedSelectedFilterId($id)
    {
        $item = AttributeItem::where('id', $id)->with('attribute')->first()->toArray();

        $filter = [
            'id' => $item['id'],
            'name' => $item['name'],
            'attribute_id' => $item['attribute_id'],
            'attribute_name' => $item['attribute']['name'],
        ];

        array_push($this->editTag['filter'], $filter);
    }

    public function updatedSelectedTypeFilterId($id)
    {
        $this->filterItems = $this->categoryfilters->where('id', $id)->pluck('items')->flatten();
    }

    public function updatedCategoryId($id)
    {
        $categoryAttributes = Category::where('id', $id)->first()->only('attributes');

        $ids = explode(',', $categoryAttributes['attributes']);

        $this->categoryfilters = Attribute::whereIn('id', $ids)->with('items')->get();
    }

    public function save()
    {
        $this->validate([
            'editTag.name' => 'required',
            'editTag.title' => 'required',
            'categoryId' => 'required',
        ]);

        DB::transaction(function () {
            $tag = Tag::updateOrCreate(
                ['id' => $this->editTag['id']],
                [
                    'name' => $this->editTag['name'],
                    'title' => $this->editTag['title'],
                    'meta_title' => $this->editTag['meta_title'],
                    'filter' => $this->editTag['filter'],
                    'category_id' => $this->categoryId,
                    'show_on_page' => $this->editTag['show_on_page'],

                ]
            );

            $this->dispatchBrowserEvent('toaster', ['message' => 'Тег "' . $tag->name . '" сохранен.']);

            $this->closeForm();
        });
    }

    public function removeItem($key)
    {
        unset($this->editTag['filter'][$key]);
    }

    public function remove($id)
    {
        $tag = Tag::find($id);
        $tag_name = $tag->name;
        $tag->delete();

        $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Тег "' . $tag_name . '" удален.']);
    }

    public function closeForm()
    {
        $this->resetFields();
        $this->dispatchBrowserEvent('close');
    }

    public function resetFields()
    {
        $this->reset([
            'editTag',
            'categoryId',
            'categoryfilters',
            'selectedTypeFilterId',
            'filterItems',
            'selectedFilterId',
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.tags', [
            'tags' => Tag::when($this->search, function ($query) {
                $query->whereLike(['name', 'id'], $this->search);
            })
                ->when($this->filteredByCategory, function ($query) {
                    $query->where('category_id', $this->filteredByCategory);
                })
                ->when($this->onlyOnPage, function ($query) {
                    $query->where('show_on_page', true);
                })
                ->with('category', 'category.catalog')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->itemsPerPage),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
