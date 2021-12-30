<?php

namespace App\Traits;

use App\Mail\OrderDelivered;
use App\Mail\OrderProcessing;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;

trait SendOrderEmail
{
    public function sendEmailWithStatus($order)
    {
        if ($order->status === 'processing') {
            $this->sendEmailWithStatusProcessing($order);
        }

        if ($order->status === 'shipped') {
            $this->sendEmailWithStatusShipped($order);
        }

        if ($order->status === 'delivered') {
            $this->sendEmailWithStatusDelivered($order);
        }
    }

    public function sendEmailWithStatusProcessing($order)
    {
        if ($order->user->email !== null) {
            Mail::to($order->user->email)->send(new OrderProcessing($order));
        }

        if ($order->user->email !== null && $order->user->email !== $order->contact['email']) {
            Mail::to($order->contact['email'])->send(new OrderProcessing($order));
        }
    }

    public function sendEmailWithStatusShipped($order)
    {
        if ($order->user->email !== null) {
            Mail::to($order->user->email)->send(new OrderShipped($order));
        }

        if ($order->user->email !== null && $order->user->email !== $order->contact['email']) {
            Mail::to($order->contact['email'])->send(new OrderShipped($order));
        }
    }

    public function sendEmailWithStatusDelivered($order)
    {
        if ($order->user->email !== null) {
            Mail::to($order->user->email)->send(new OrderDelivered($order));
        }

        if ($order->user->email !== null && $order->user->email !== $order->contact['email']) {
            Mail::to($order->contact['email'])->send(new OrderDelivered($order));
        }
    }
}
