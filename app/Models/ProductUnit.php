<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class ProductUnit extends Model
{
    use PowerJoins;

    protected $table = 'product_units';

    protected $guarded = [];

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
}
