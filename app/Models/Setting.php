<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'options' => Json::class,
    ];
}
