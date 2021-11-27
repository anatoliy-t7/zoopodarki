<?php
namespace App\Http\Livewire\Site\Checkout;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;
use YooKassa\Client;

class CheckoutConfirm extends Component
{
    use WireToast;

    public $order;
    public $noStockItems;

    public function mount()
    {

        if (!auth()->user()) {
            return redirect()->route('site.home');
        }

        if (request()->session()->has('order_id')) {
            $order_id = request()->session()->input('order_id');

            $this->order = Order::where('id', $order_id)
            ->with('items', 'items.product')
            ->first();
        } else {
            return redirect()->route('site.home');
        }

        if (request()->has('no_stock_items')) {
            $this->noStockItems = request()->input('no_stock_items');
        }
    }

    public function confirmOrder()
    {

        $order = Order::where('id', $this->order->id)
            ->first();

         // 1 cash
        if (1 === $this->orderPaymentType) {
            $order->status = 'processing';
        } else {
            $order->status = 'pending_payment';
        }
        $order->save();

        \Cart::session($this->cartId)->clear();
        app('shelter')->session($this->shelterCartId)->clear();

        if (0 === $this->orderPaymentType) {
                $this->payCreate($order);
        } else {
            redirect()->route('site.payment.cash', [
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
            'return_url' => route('site.payment.status'),
            ],
            'capture' => true,
            'metadata' => [
            'order_id' => $order->id,
            ],
        ], uniqid('', true));

        return redirect()->to($payment->getConfirmation()->getConfirmationUrl());
    }

    public function render()
    {
        return view('livewire.site.checkout.checkout-confirm')
        ->extends('layouts.clean')
        ->section('content');
    }
}
