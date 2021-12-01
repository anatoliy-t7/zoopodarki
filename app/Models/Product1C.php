<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Product1C extends Model
{
    use PowerJoins;

    protected $table = 'products_1c';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function scopeHasStock($query)
    {
        return $query->where('stock', '>=', 1)->where('price', '>=', 1);
    }
}
