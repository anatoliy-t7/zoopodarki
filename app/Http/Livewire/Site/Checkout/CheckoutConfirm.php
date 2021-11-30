<?php
namespace App\Http\Livewire\Site\Checkout;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;
use YooKassa\Client;

class CheckoutConfirm extends Component
{
    use WireToast;

    public $order;
    public $noStockItems = [];

    public function mount()
    {

        if (!auth()->user()) {
            return redirect()->route('site.home');
        }

        if (request()->session()->has('order_id')) {
            $orderId = session('order_id');

            $this->order = Order::where('id', $orderId)
                ->with([
                    'items',
                    'items.product1c:id,product_id',
                    'items.product1c.product:id,slug',
                    'items.product1c.product.media',
                    'items.product1c.product.categories:id,slug,catalog_id',
                    'items.product1c.product.categories.catalog:id,slug',
                    ])
                ->first();

            unset($orderId);

            if ($this->order === null) {
                       return redirect()->route('site.home');
            }
        } else {
            return redirect()->route('site.home');
        }

        if (request()->session()->has('no_stock_items')) {
            $this->noStockItems = session('no_stock_items');
        }
    }

    public function confirmOrder()
    {

        $order = Order::where('id', $this->order->id)
            ->first();

         // 1 cash
        if ($order->payment_method == 0) {
            $order->status = 'processing';
        } else {
            $order->status = 'pending_payment';
        }
        $order->save();

        $this->stopDiscountFirstOrder();

        \Cart::session(session('cart_id'))->clear();
        app('shelter')->session(session('shelter_cart'))->clear();

        if ($order->payment_method == 0) {
                $this->payCreate($order);
        } else {
            redirect()->route('site.order.confirmed', [
                'order_id' => $order->id
            ]);
        }
    }


    public function payCreate($order)
    {
        $clientId = config('services.yookassa.client_id');
        $clientSecret = config('services.yookassa.client_secret');

        $client = new Client();
        $client->setAuth($clientId, $clientSecret);

        $payment = $client->createPayment([
            'amount' => [
            'value' => $order->amount,
            'currency' => 'RUB',
            ],
            'description' => 'Заказ ' . $order->order_number,
            'confirmation' => [
            'type' => 'redirect',
            'locale' => 'ru_RU',
            'return_url' => route('site.order.status'),
            ],
            'capture' => true,
            'metadata' => [
            'order_id' => $order->id,
            ],
        ], uniqid('', true));

        return redirect()->to($payment->getConfirmation()->getConfirmationUrl());
    }

    public function stopDiscountFirstOrder()
    {
            $user = auth()->user();
            $user->extra_discount = 'no';
            $user->save();
    }

    public function render()
    {
        return view('livewire.site.checkout.checkout-confirm')
        ->extends('layouts.clean')
        ->section('content');
    }
}
