<?php

namespace App\Traits;

use App\Notifications\SendSms;
use Illuminate\Notifications\AnonymousNotifiable;

trait SendOrderSms
{
    public function sendSmsWithStatus($order)
    {
        if ($order->status === 'processing') {
            $this->sendSmsWithStatusProcessing($order);
        }

        if ($order->status === 'shipped') {
            $this->sendSmsWithStatusShipped($order);
        }

        if ($order->status === 'delivered') {
            $this->sendSmsWithStatusDelivered($order);
        }
    }

    public function sendSmsWithStatusProcessing($order)
    {
        $text = 'Ваш заказ #' . $order->order_number . ' в магазине "Зооподарки"' . "\n" . 'https://zoopodarki.spb.ru';

        $this->trySendSms($order->contact['phone'], $text);
    }

    public function sendSmsWithStatusShipped($order)
    {
        $text = 'Ваш заказ #' . $order->order_number . ' отправлен. Доставка:' . simpleDate($order->date) . ' ' . $order->delivery_time . "\n" . 'https://zoopodarki.spb.ru';

        $this->trySendSms($order->contact['phone'], $text);
    }

    public function sendSmsWithStatusDelivered($order)
    {
        $text = 'Ваш заказ #' . $order->order_number . ' доставлен. Заходите снова! ' . "\n" . 'https://zoopodarki.spb.ru';

        $this->trySendSms($order->contact['phone'], $text);
    }

    public function trySendSms($phone, $text)
    {
        try {
            (new AnonymousNotifiable())
                ->route('smscru', '+7' . $phone)
                ->notify(new SendSms($text));

            toast()
                ->success('Sms отправлено')
                ->push();
        } catch (\Throwable$th) {
            \Log::error($th);

            toast()
                ->warning('Sms не отправлено')
                ->push();
        }
    }
}
