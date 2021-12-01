<?php

namespace App\Http\Livewire\Site\Account;

use App\Models\Order;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class OrderPage extends Component
{
    use WireToast;

    public $orderId;

    protected $queryString = [
        'orderId' => ['except' => ''],
    ];

    public function getOrder()
    {

        $order = Order::where('id', $this->orderId)->getOrderData();

        if ($order === null) {
            toast()
                ->info('Заказ не найден')
                ->pushOnNextPage();

            return redirect()->route('account.orders');
        }

        return $order;

    }

    public function render()
    {
        $order = $this->getOrder();

        return view('livewire.site.account.order', [
            'order' => $order,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
