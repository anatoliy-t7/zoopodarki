<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product1C;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class PaymentController extends Controller
{
    public function payСash(Request $request)
    {
        if ($request->has('order_id')) {
            $order_id = $request->input('order_id');

            // TODO get data only needs
            $order = Order::where('id', $order_id)
                ->with('items')
                ->first();

            $comment = 'Ваш заказ отправлен на обработку, оплата наличными при получении';

            $this->incrementPopularity($order);
            $this->stopDiscountFirstOrder();

            return view('site.payment-status', compact('order', 'comment'));
        } else {
            return redirect()->route('site.home');
        }
    }

    public function payCallback(Request $request)
    {
        if ($request->has('event')) {
            info(json_encode($request->object));

            $order = Order::where('id', $request->object['metadata']['order_id'])->first();

            if ($request->event == 'payment.succeeded' && $request->object['status'] == 'succeeded') {
                if ($request->object['paid'] === true) {
                    $order->amount = $request->object['amount']['value'];
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

    public function userCameBack()
    {
        if (!session()->has('order_id')) {
            return redirect()->route('site.home');
        }

        $orderId = session('order_id');

        $order = Order::where('id', $orderId)
              ->with('items')
              ->first();

        if ($order->payment_status == 'succeeded') {
            $comment = 'Оплата прошла успешно и ваш заказ отправлен на обработку';
        } elseif ($order->payment_status = 'waiting_for_capture') {
            $comment = 'Оплата ожидает подтверждения';
        } elseif ($order->payment_status == 'canceled') {
            $comment = 'Оплата отменена';
        } elseif ($order->payment_status == 'refund_succeeded') {
            $comment = 'Возрат подтвержден';
        }

        $this->stopDiscountFirstOrder();

        return view('site.payment-status', compact('order', 'comment'));
    }

    public function incrementPopularity($order)
    {
        foreach ($order->items as $key => $item) {
            $product1c = Product1C::with('product:id,popularity')->find($item->product->id);

            $product1c->product->increment('popularity', 1);
        }
    }

    public function stopDiscountFirstOrder()
    {
        if (auth()->user()->extra_discount === 'first') {
            $user = auth()->user();
            $user->extra_discount = 'no';
            $user->save;
        }
    }
}
