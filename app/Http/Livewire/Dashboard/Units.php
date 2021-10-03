<?php
namespace App\Http\Livewire\Dashboard;

use App\Models\ProductUnit;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Units extends Component
{
    use WithPagination;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $editUnit = [
        'id' => null,
        'name' => null,
    ];
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

    public function openForm($unitId)
    {
        $this->editUnit = ProductUnit::where('id', $unitId)->first()->toArray();
    }

    public function save()
    {
        $this->validate([
            'editUnit.name' => 'required',
        ]);

        DB::transaction(function () {
            $this->editUnit = ProductUnit::updateOrCreate(
                ['id' => $this->editUnit['id']],
                [
                    'name' => trim($this->editUnit['name']),
                ]
            );

            $this->dispatchBrowserEvent('toaster', ['message' => 'Единица измерения ' . $this->editUnit['name'] . ' сохранена.']);

            $this->closeForm();
            $this->dispatchBrowserEvent('close');
        });
    }

    public function remove($unitId)
    {
        $unit = ProductUnit::find($unitId);

        if ($unit->products()->exists()) {
            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'С этой единицей измерения связан товар']);
        } else {
            $unit_name = $unit->name;
            $unit->delete();

            $this->reset(['editUnit']);

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Единица измерения "' . $unit_name . '" удалена.']);
        }
    }

    public function closeForm()
    {
        $this->reset(['editUnit']);
    }

    public function render()
    {
        return view('livewire.dashboard.units', [
            'units' => ProductUnit::when($this->search, function ($query) {
                $query->whereLike(['name', 'id'], $this->search);
            })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->itemsPerPage),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
