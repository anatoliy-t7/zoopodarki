<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $table = 'product_units';

    protected $guarded = [];

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
}
