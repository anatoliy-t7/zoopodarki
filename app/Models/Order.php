<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'contact'  => 'array',
        'address'  => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeGetOrderData($query)
    {
        return $query->where('user_id', auth()->user()->id)
                ->with([
                    'items',
                    'items.product1c:id,product_id',
                    'items.product1c.product:id,slug',
                    'items.product1c.product.media',
                    'items.product1c.product.categories:id,slug,catalog_id',
                    'items.product1c.product.categories.catalog:id,slug',
                ]);
    }
}
