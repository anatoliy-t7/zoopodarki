<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnusedProductFields extends Model
{

    protected $table = 'unused_product_fields';
    protected $guarded = [];

    public $timestamps = false;

    public function product1c()
    {
        return $this->belongsTo('App\Models\Product1C', 'products_1c_id');
    }
}
