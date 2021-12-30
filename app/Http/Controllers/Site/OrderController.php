<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product1C;
use App\Traits\SendOrderEmail;
use App\Traits\SendOrderSms;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use SendOrderEmail;
    use SendOrderSms;

    public function orders()
    {
        $orders = Order::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'ASC')
        ->paginate(10);

        return view('site.account.orders', compact('orders'));
    }

    public function checkoutCallback(Request $request)
    {
        if ($request->has('order_id')) {
            $orderId = $request->input('order_id');

            $order = Order::where('id', $orderId)->getOrderData()->first();

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

            return view('livewire.site.checkout.checkout-callback', compact('order', 'comment'));
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
            $order = Order::where('id', $request->object['metadata']['order_id'])
            ->getOrderData()
            ->with('user')
            ->first();

            if ($request->event == 'payment.succeeded' && $request->object['status'] == 'succeeded') {
                if ($request->object['paid'] === true) {
                    $order->payment_status = 'succeeded';
                    $order->status = 'processing';

                    $this->sendEmailWithStatus($order);
                    $this->sendSmsWithStatus($order);
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
