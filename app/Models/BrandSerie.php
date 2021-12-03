<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandSerie extends Model
{
    public $timestamps = false;
    protected $table = 'brand_series';

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
