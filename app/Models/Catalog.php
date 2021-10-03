<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Catalog extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use PowerJoins;

    protected $table = 'catalogs';
    protected $guarded = [];

    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasManyDeep(Product::class, [Category::class, 'product_category']);
    }

}
