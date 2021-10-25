<?php
namespace App\Http\Livewire\Dashboard;

use App\Models\Brand;
use App\Models\BrandSerie;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Brands extends Component
{
    use WithFileUploads;

    use WithPagination;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $brandId;
    public $name;
    public $nameRus;
    public $title;
    public $description;
    public $logo;
    public $logoName;
    public $itemsName = [];
    protected $listeners = ['save'];
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
    ];

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

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function updatedPhoto()
    {
        $this->validate([
            'logo' => 'image|mimes:jpeg,png,jpg|max:1024',
        ]);
    }

    public function openForm($brandId)
    {
        $this->itemsName = BrandSerie::where('brand_id', $brandId)->get(['id', 'name', 'name_rus']);
        $this->dispatchBrowserEvent('get-items', $this->itemsName);

        $brand = Brand::where('id', $brandId)->firstOrFail();
        $this->name = $brand->name;
        $this->nameRus = $brand->name_rus;
        $this->title = $brand->meta_title;
        $this->description = $brand->meta_description;
        $this->brandId = $brand->id;
        $this->logoName = $brand->logo;
    }

    public function save($itemsName)
    {
        $this->itemsName = $itemsName;

        $this->validate([
            'name' => 'required|unique:brands,name,' . $this->brandId,
        ]);

        DB::transaction(function () {
            $brand = Brand::updateOrCreate(
                ['id' => $this->brandId],
                [
                    'name' => trim($this->name),
                    'name_rus' => trim($this->nameRus),
                    'meta_title' => $this->title,
                    'meta_description' => $this->description,
                ]
            );

            if ($this->logo) {
                Storage::delete('brands/' . $brand->logo);

                $this->storeImage($brand->slug);

                $brand->update([
                    'logo' => $this->logoName,
                ]);
            }

            foreach ($this->itemsName as $item) {
                if ($item['name']) {
                    if (Arr::has($item, 'id') && BrandSerie::where('id', $item['id'])->exists()) {
                        $serie = BrandSerie::find($item['id']);
                        $serie->update([
                            'name' => trim($item['name']),
                            'name_rus' => trim($item['name_rus']),
                        ]);
                    } else {
                        BrandSerie::create([
                            'name' => trim($item['name']),
                            'name_rus' => trim($item['name_rus']),
                            'brand_id' => $brand->id,
                        ]);
                    }
                }
            }

            $this->brandId = $brand->id;
            $this->itemsName = BrandSerie::where('brand_id', $brand->id)->get(['id', 'name']);

            $this->dispatchBrowserEvent('get-items', $this->itemsName);

            $this->dispatchBrowserEvent('toast', ['message' => $brand->name . ' сохранен.']);

            $this->closeForm();
        });
    }

    public function storeImage($slug)
    {
        $this->logoName = $slug . '.' . $this->logo->getClientOriginalExtension();

        $path = $this->logo->storeAs('brands', $this->logoName);
        $img = \Image::make(public_path('storage') . '/' . $path);
        $img->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save();
    }

    public function removeItem($item)
    {
        if (Arr::has($item, 'id')) {
            BrandSerie::findOrFail($item['id'])->delete();

            $this->dispatchBrowserEvent('toast', ['message' => 'Серия удалена.']);
        }
    }

    public function remove($itemId)
    {
        $brand = Brand::find($itemId);

        if ($brand->products()->exists()) {
            $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'У этого бренда есть товары']);
        } else {
            $brand_name = $brand->name;
            Storage::delete('brands/' . $brand->logo);
            $brand->delete();

            $this->resetFields();

            $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'Бренд "' . $brand_name . '" удален.']);
        }
    }

    public function closeForm()
    {
        $this->resetFields();
        $this->dispatchBrowserEvent('get-items', $this->itemsName);
        $this->dispatchBrowserEvent('close');
    }

    public function resetFields()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.dashboard.brands', [
            'brands' => Brand::when($this->search, function ($query) {
                $query->whereLike(['name', 'id', 'items.name'], $this->search);
            })
                ->with('items')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->itemsPerPage),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
