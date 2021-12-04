<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(AttributeItem::class)->orderBy('name', 'ASC');
    }
}
