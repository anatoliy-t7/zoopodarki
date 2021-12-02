<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Order;
use App\Notifications\SendSms;
use Illuminate\Notifications\AnonymousNotifiable;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class Orders extends Component
{
    use WireToast;
    use WithPagination;

    public $search;
    public $sortField = 'order_number';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $order;
    public $smsText = '';
    public $orderSelected;
    public $status;
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
        'status',
    ];
    public $orderStatuses;

    protected $rules = [
        'orderSelected.status' => 'required',
    ];


    public function mount()
    {
        $this->orderStatuses = config('constants.order_status');
        $this->search = request()->query('search', $this->search);
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
        $this->resetPage();
    }

    public function openForm($orderId)
    {
        $this->orderSelected = Order::where('id', $orderId)
            ->with('items')
            ->first();
    }

    public function save()
    {
        $this->validate();

        $orderSelected = Order::where('id', $this->orderSelected->id)
        ->update([
            'status' => $this->orderSelected->status,
        ]);

        toast()
            ->success('Статус заказа изменен')
            ->push();
    }

    public function setSms($phone)
    {
        $this->validate([
            'smsText' => 'required|string',
        ]);

        try {
            (new AnonymousNotifiable())
                ->route('smscru', '+7' . $phone)
                ->notify(new SendSms($this->smsText));

            toast()
                ->success('Sms отправлено')
                ->push();

            $this->reset('smsText');
        } catch (\Throwable$th) {
            \Log::error($th);

            toast()
                ->warning('Sms не отправлено')
                ->push();
        }
    }

    public function closeForm()
    {
        $this->reset('orderSelected');
        $this->dispatchBrowserEvent('close');
    }

    public function remove($orderSelectedId)
    {
        $funcOrder = Order::where('id', $orderSelectedId)->first();
        $orderNumber = $funcOrder->order_number;
        $funcOrder->delete();

        $this->closeForm();
        toast()
            ->success('Заказ "' . $orderNumber . '" удален.')
            ->push();
    }

    public function render()
    {
        $orders = Order::when($this->status, function ($query) {
            return $query->where('status', $this->status);
        })
            ->when($this->search, function ($query) {
                $query->whereLike(['order_number', 'id', 'name', 'amount'], $this->search);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        return view('livewire.dashboard.orders', [
            'orders' => $orders,
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
