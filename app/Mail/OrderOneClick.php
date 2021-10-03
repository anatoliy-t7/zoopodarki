<?php

namespace App\Mail;

use App\Models\Product1C;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderOneClick extends Mailable
{
    use Queueable, SerializesModels;

    public $product1c;
    public $count;
    public $total;
    public $orderOneClick;
    public $productUrl;

    public function __construct(Product1C $product1c, $count, $orderOneClick, $productUrl)
    {
        $this->product1c = $product1c;
        $this->count = $count;
        $this->orderOneClick = $orderOneClick;
        $this->productUrl = $productUrl;
        $this->total = $product1c->price * $count;
    }

    public function build()
    {
        return $this->view('emails.templates.dist.order-one-click')
            ->subject('Заказ в один клик с ZooPodarki');
    }
}
