<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Waitlist;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class Waitlists extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $waitlistId;
    public $statuses;
    public $status;
    public $waitlistEdit;
    public $filteredBy;
    protected $listeners = ['save'];
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
        'filteredBy',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->statuses = config('constants.waitlist_status');
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

    public function openForm($waitlistId)
    {
        $this->waitlistEdit = Waitlist::where('id', $waitlistId)
            ->with('user')
            ->with('product1c')
            ->first();

        $this->waitlistId = $this->waitlistEdit->id;
        $this->status = $this->waitlistEdit->status;
    }

    public function save()
    {
        DB::transaction(function () {
            $waitlist = Waitlist::updateOrCreate(
                ['id' => $this->waitlistId],
                [
                    'status' => $this->status,
                ]
            );

            toast()
                ->success('Заказ сохранен с статусом "'.__('constants.review_status.'.$waitlist->status).'"')
                ->push();

            $this->closeForm();

            $this->dispatchBrowserEvent('close');
        });
    }

    public function remove($itemId)
    {
        Waitlist::find($itemId)->delete();

        $this->closeForm();

        $this->dispatchBrowserEvent('close');

        toast()
            ->success('Заказ удален')
            ->push();
    }

    public function closeForm()
    {
        $this->reset(['waitlistId', 'status', 'waitlistEdit']);
    }

    public function render()
    {
        $waitlists = Waitlist::when($this->filteredBy, function ($query) {
            $query->where('status', $this->filteredBy);
        })
            ->when($this->search, function ($query) {
                $query->whereLike(['phome', 'email'], $this->search);
            })
            ->with('product1c')
            ->with('product1c.product')
            ->with('product1c.product.unit')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        return view('livewire.dashboard.waitlists', [
            'waitlists' => $waitlists,
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
