<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use T7team\SmscRu\SmscRuChannel;
use T7team\SmscRu\SmscRuMessage;

class SendOTP extends Notification
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via()
    {
        return [SmscRuChannel::class];
    }

    public function toSmscRu()
    {
        return SmscRuMessage::create("OTP {$this->token} для входа на сайт Zoo_Podarki");
    }
}
