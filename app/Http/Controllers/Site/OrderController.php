<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product1C;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders()
    {
        $orders = Order::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'ASC')
        ->paginate(10);

        return view('site.account.orders', compact('orders'));
    }

    public function order(Request $request)
    {

        if ($request->has('orderId')) {
            $orderId = $request->input('orderId');

            // TODO get data only needs
            $order = Order::where('id', $orderId)->getOrderData();

            if ($order === null) {
                toast()
                    ->info('Заказ не найден')
                    ->pushOnNextPage();

                return redirect()->route('account.orders');
            }

            return view('site.account.order', compact('order'));
        }

        toast()
            ->info('Заказ не найден')
            ->pushOnNextPage();

        return redirect()->route('account.orders');
    }

    public function checkoutCallback()
    {
        if (request()->session()->has('order_id')) {
             $orderId = session('order_id');

            // TODO get data only needs
            $order = Order::where('id', $orderId)
                ->where('user_id', auth()->user()->id)
                ->with('items')
                ->first();

            $comment = '';

            if ($order->payment_status == 'succeeded') {
                $comment = 'Оплата прошла успешно и ваш заказ отправлен на обработку';
            } elseif ($order->payment_status = 'waiting_for_capture') {
                $comment = 'Оплата ожидает подтверждения';
            } elseif ($order->payment_status == 'canceled') {
                $comment = 'Оплата отменена';
            } elseif ($order->payment_status == 'refund_succeeded') {
                $comment = 'Возрат подтвержден';
            } elseif ($order->payment_status == 'cash') {
                $comment = 'Ваш заказ отправлен на обработку, оплата наличными при получении';
            }

            $this->incrementPopularity($order);

            return view('site.checkout.checkout-callback', compact('order', 'comment'));
        } else {

            toast()
                ->info('Авторизуйтесь, что бы посмотреть ваш статус заказа')
                ->pushOnNextPage();

            return redirect()->route('site.home');
        }

    }

    public function yooKassaCallback(Request $request)
    {
        if ($request->has('event')) {
            $order = Order::where('id', $request->object['metadata']['order_id'])->first();
            if ($request->event == 'payment.succeeded' && $request->object['status'] == 'succeeded') {
                if ($request->object['paid'] === true) {
                    $order->payment_status = 'succeeded';
                    $order->status = 'processing';
                }
            } elseif ($request->event == 'payment.waiting_for_capture') {
                $order->payment_status = 'waiting_for_capture';
            } elseif ($request->event == 'payment.canceled') {
                $order->payment_status = 'canceled';
            } elseif ($request->event == 'refund.succeeded') {
                $order->payment_status = 'refund_succeeded';
            }

            $order->save();

            $this->incrementPopularity($order);
        }
    }

    public function incrementPopularity($order)
    {
        foreach ($order->items as $key => $item) {
            $product1c = Product1C::with('product:id,popularity')->where('uuid', $item->uuid)->first();

            $product1c->product->increment('popularity', 1);
        }
    }
}
