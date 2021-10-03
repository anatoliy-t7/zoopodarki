<?php

namespace App\Models;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class BrandSerie extends Model
{
    use PowerJoins;

    public $timestamps = false;
    protected $table   = 'brand_series';

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
