<?php
namespace App\Http\Livewire\Dashboard;

use App\Mail\ReviewChangedToUser;
use App\Models\Waitlist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Waitlists extends Component
{
    use WithPagination;

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


            $this->dispatchBrowserEvent('toast', ['message' => 'Заказ сохранен с статусом "' . __('constants.review_status.' . $waitlist->status) . '"']);

            $this->closeForm();

            $this->dispatchBrowserEvent('close');
        });
    }

    public function sandEmail()
    {
        // if ($this->waitlistEdit->user->email) {
        //     $productName = $this->waitlistEdit->revieweable->name;

        //     $productLink = env('APP_URL') . '/pet/' . $this->waitlistEdit->revieweable->categories[0]->catalog->slug . '/' . $this->waitlistEdit->revieweable->categories[0]->slug . '/' . $this->waitlistEdit->revieweable->slug;

        //     Mail::to($this->waitlistEdit->user->email)->send(new ReviewChangedToUser($this->waitlistEdit, $productName, $productLink));

        //     $this->dispatchBrowserEvent('toast', ['message' => 'Пользователь оповещен.']);
        // } else {
        //     $this->dispatchBrowserEvent('toast', ['message' => 'У пользователя нет почты']);
        // }
    }

    public function remove($itemId)
    {
        Waitlist::find($itemId)->delete();

        $this->closeForm();

        $this->dispatchBrowserEvent('close');

        $this->dispatchBrowserEvent('toast', ['message' => 'Заказ удален.']);
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
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        return view('livewire.dashboard.waitlists', [
            'waitlists' => $waitlists,
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
