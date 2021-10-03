<?php

namespace App\Models;

use App\Models\BrandSerie;
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

}
