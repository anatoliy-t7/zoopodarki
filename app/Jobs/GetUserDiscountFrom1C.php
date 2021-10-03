<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetUserDiscountFrom1C implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use \App\Traits\DataFrom1с;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        if ($this->getUserData($this->user->phone)) {
            $userData1C = $this->getUserData($this->user->phone);

            $this->user->update([
                'discount'     => $userData1C['discount'],
                'discountGUID' => $userData1C['discountGUID'],
            ]);
        }

        if ($this->user->orders()->exists()) {
            $this->user->extra_discount = '';
            $this->user->save();
        }
    }
}
