<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use T7team\SmscRu\SmscRuChannel;
use T7team\SmscRu\SmscRuMessage;

class SendSms extends Notification
{
    private $smsText;

    public function __construct($smsText)
    {
        $this->smsText = $smsText;
    }

    public function via()
    {
        return [SmscRuChannel::class];
    }

    public function toSmscRu()
    {
        return SmscRuMessage::create($this->smsText . "\n" . 'Zoo_Podarki');
    }
}
