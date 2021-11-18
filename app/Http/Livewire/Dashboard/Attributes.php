<?php
namespace App\Http\Livewire\Dashboard;

use App\Models\Attribute;
use App\Models\AttributeItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class Attributes extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $name;
    public $range = false;
    public $attribute_id = null;
    public $itemsName = [];
    public $fildName = '';
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
    ];
    protected $listeners = ['saveIt'];

    public function updatedFildName($value)
    {
        redirect()->route('dashboard.products.index', ['filteredByAttribute' => true, 'search' => $this->fildName, 'attrId' => $this->attribute_id]);
    }

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

    public function addNew()
    {
        $this->reset([
            'name',
            'attribute_id',
            'itemsName',
        ]);
    }

    public function openForm($attId)
    {
        $oneAttribute = Attribute::where('id', $attId)->with('items')->first();

        $this->itemsName = $oneAttribute->items()->get(['id', 'name'])->toArray();

        $this->dispatchBrowserEvent('get-items', $this->itemsName);

        $this->attribute_id = $attId;
        $this->name = $oneAttribute->name;
        $this->range = $oneAttribute->range;
    }

    public function saveIt($itemsName)
    {
        $this->itemsName = $itemsName;

        $this->validate([
            'name' => 'required|unique:attributes,name,' . $this->attribute_id,
        ]);

        DB::transaction(function () {
            $attribute = Attribute::updateOrCreate(
                ['id' => $this->attribute_id],
                [
                    'name' => trim($this->name),
                    'range' => $this->range,
                ]
            );

            foreach ($this->itemsName as $item) {
                if ($item['name']) {
                    if (Arr::has($item, 'id') && AttributeItem::where('id', $item['id'])->exists()) {
                        $attribute_item = AttributeItem::find($item['id']);
                        $attribute_item->update([
                            'name' => trim($item['name']),
                        ]);
                    } else {
                        AttributeItem::create([
                            'name' => trim($item['name']),
                            'attribute_id' => $attribute->id,
                        ]);
                    }
                }
            }

            $this->attribute_id = $attribute->id;
            $this->itemsName = AttributeItem::where('attribute_id', $attribute->id)->get(['id', 'name']);
            $this->dispatchBrowserEvent('get-items', $this->itemsName);

            toast()
                ->success('Свойство "' . $attribute->name . '" обновлено.')
                ->push();

            $this->closeForm();
        });
    }

    public function removeItem($item)
    {
        if (Arr::has($item, 'id')) {
            $attributeItem = AttributeItem::where('id', $item['id'])->first();

            $this->dispatchBrowserEvent('close-confirm');

            if ($attributeItem->products()->exists()) {
                return toast()
                    ->success('Вид свойства прикреплен к товару.')
                    ->push();
            }

            $removeItemName = $attributeItem->name;
            $attributeItem->delete();

            $this->openForm($this->attribute_id);

            return toast()
                ->success('Вид свойства ' . $removeItemName . ' удален.')
                ->push();
        }
    }

    public function remove($item_id)
    {
        $attribute = Attribute::find($item_id);

        DB::transaction(function () use ($attribute) {
            if ($attribute->items()->exists()) {
                foreach ($attribute->items as $item) {
                    if ($item->products()->exists()) {
                        return toast()
                            ->warning('У свойства "' . $attribute->name . '" есть товары ({$item->name})')
                            ->push();

                    } else {
                        $this->removeItem($item);
                    }
                }
            } else {
                $attribute_name = $attribute->name;
                $attribute->delete();

                $this->resetFields();

                toast()
                    ->success('Свойство "' . $attribute_name . '" удалено')
                    ->push();
            }
        });
    }

    public function closeForm()
    {
        $this->resetFields();
        $this->dispatchBrowserEvent('get-items', $this->itemsName);
        $this->dispatchBrowserEvent('close');
    }

    public function resetFields()
    {
        $this->name = null;
        $this->itemsName = [];
        $this->attribute_id = null;
    }

    public function render()
    {
        $attributes = Attribute::when($this->search, function ($query) {
            $query->whereLike(['name', 'id', 'items.name'], trim($this->search));
        })
            ->with('items')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        return view('livewire.dashboard.attributes', [
            'attributes' => $attributes,
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
