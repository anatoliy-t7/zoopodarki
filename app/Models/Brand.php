<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Sluggable;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public $timestamps = false;

    protected $table = 'brands';

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function items()
    {
        return $this->hasMany(BrandSerie::class);
    }

    public function productsAttributes()
    {

        return $this->hasManyDeepFromRelations($this->products(), (new Product())->attributes());
    }

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'catalog_brand', 'brand_id', 'catalog_id');
    }
}
