<?php
namespace App\Http\Livewire\Dashboard;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $search;
    public $sortField = 'order_number';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $order;
    public $orderSelected;
    public $filteredBy = null;
    public $filteredByName = 'Фильтр по статусу';
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
        'filteredBy',
    ];
    public $filterType = [
        '0' => [
            'name' => 'Все статусы',
            'status' => '',
        ],
        '1' => [
            'name' => 'В ожидании',
            'status' => 'pending',
        ],
        '2' => [
            'name' => ' В обработке',
            'status' => 'processing',
        ],
        '3' => [
            'name' => 'Готов к самовывозу',
            'status' => 'pickup',
        ],
        '4' => [
            'name' => 'Завершен',
            'status' => 'completed',
        ],
        '5' => [
            'name' => 'Отменен',
            'status' => 'cancelled',
        ],
        '6' => [
            'name' => 'Возврат',
            'status' => 'return',
        ],
        '7' => [
            'name' => 'Приостановлен',
            'status' => 'hold',
        ],
    ];
    protected $listeners = ['save'];

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

    public function filterIt($status, $name)
    {
        $this->filteredBy = $status;
        $this->filteredByName = $name;
    }

    public function openForm($orderId)
    {
        $this->orderSelected = Order::where('id', $orderId)
            ->with('items')
            ->firstOrFail();
    }

    public function closeForm()
    {
        $this->reset();
        $this->dispatchBrowserEvent('close');
    }

    public function remove($orderSelectedId)
    {
        $funcOrder = Order::where('id', $orderSelectedId)->first();
        $orderNumber = $funcOrder->order_number;
        $funcOrder->delete();

        $this->closeForm();
        $this->dispatchBrowserEvent('toast', ['message' => 'Заказ "' . $orderNumber . '" удален.']);
    }

    public function render()
    {
        $orders = Order::when($this->filteredBy, function ($query) {
            return $query->where('status', $this->filteredBy);
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
