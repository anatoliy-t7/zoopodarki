<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product1C extends Model
{
    protected $table = 'products_1c';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'unit_id');
    }

    public function scopeHasStock($query)
    {
        return $query->where('stock', '>=', 1)->where('price', '>=', 1);
    }

    public function scopeGetTypeOfDiscount($query, $typeF)
    {
        if ($typeF == 0) {
            return $query->where('promotion_type', '>', 0);
        }
        if ($typeF == 1) {
            return $query->where('promotion_type', 1);
        }
        if ($typeF == 2) {
            return $query->where('promotion_type', '>=', 2);
        }
    }
}
