<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\AutoOrder;
use App\Models\Product1C;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AutoOrders extends Component
{
    use WithPagination;

    public $search;
    public $searchProducts;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;

    public $users;

    public $autoOrderId;
    public $periodicity;
    public $nextOrder;
    public $user;
    public $autoOrderProducts1c;
    public $variations = array();

    public $itemsName = [];

    protected $listeners = ['save', 'setProductToOrder', 'sendDataToFrontend'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
    ];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);

        $this->users = User::orderBy('name', 'ASC')->get(['id', 'name']);

    }

    public function setProductToOrder($variationId)
    {
        array_push($this->variations, $variationId);
        $this->getProductToOrder();
    }

    public function getProductToOrder()
    {
        $this->autoOrderProducts1c = Product1C::whereIn('id', $this->variations)->with('product')
            ->get();
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

    public function sendDataToFrontend()
    {
        $this->dispatchBrowserEvent('set-users', $this->users);
        $this->dispatchBrowserEvent('set-user', $this->user);
    }

    public function openForm($autoOrderId)
    {

        $autoOrder = AutoOrder::where('id', $autoOrderId)->with('user')->firstOrFail();
        $this->autoOrderId = $autoOrder->id;
        $this->periodicity = $autoOrder->periodicity;
        $this->nextOrder = $autoOrder->next_order;
        $this->variations = json_decode($autoOrder->variations);
        // dd($this->variations);
        $this->user = $autoOrder->user->only('id', 'name');
        //dd($this->user);

        $this->dispatchBrowserEvent('set-calendar', $this->nextOrder);
        $this->getProductToOrder();

    }

    public function save($user)
    {

        if ($user) {
            $this->user = $user[0];
        }

        $this->validate([
            'user' => 'required',
            'periodicity' => 'required',
            'nextOrder' => 'required',
            'variations' => 'required',
        ]);

        DB::transaction(function () {

            $autoOrder = AutoOrder::updateOrCreate(
                ['id' => $this->autoOrderId],
                [
                    'periodicity' => $this->periodicity,
                    'next_order' => $this->nextOrder,
                    'variations' => json_encode($this->variations),
                    'user_id' => $this->user['id'],
                ]);

            $this->autoOrder = $autoOrder->id;

            $this->dispatchBrowserEvent('toaster', ['message' => 'Автозаказ сохранен']);
            $this->closeForm();
        });
    }

    public function removeItem($item)
    {
        if (Arr::has($item, 'id')) {
            BrandSerie::findOrFail($item['id'])->delete();
            $this->dispatchBrowserEvent('toaster', ['message' => 'Серия удалена.']);
        }

    }

    public function remove($itemId)
    {
        $brand = AutoOrder::find($itemId)->delete();

        $this->resetFields();
        $this->dispatchBrowserEvent('toaster', ['message' => 'Автозаказ удален.']);

    }

    public function closeForm()
    {
        $this->resetFields();
        $this->dispatchBrowserEvent('close');
    }

    public function resetFields()
    {
        $this->reset();
    }

    public function getProducts()
    {

        $products = Product::isStatusActive()
            ->with('variations')
            ->where('name', 'like', '%' . $this->searchProducts . '%')
            ->when($this->searchProducts, function ($query) {
                $query->whereHas('variations', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('cod1c', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('vendorcode', 'like', '%' . $this->search . '%');

                });
            })
            ->whereHas('variations', function ($query) {
                $query->where('stock', '>', 0)
                    ->where('price', '>', 0);
            })
            ->paginate(15);

        return $products;
    }

    public function render()
    {
        $products = null;
        if ($this->searchProducts) {
            $products = $this->getProducts();
        }

        return view('livewire.dashboard.auto-orders', [
            'autoOrders' => AutoOrder::when($this->search, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
                ->with('user')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->itemsPerPage),
            'products' => $products,
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
