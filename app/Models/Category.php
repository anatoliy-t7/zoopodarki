<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kirschbaum\PowerJoins\PowerJoins;

class Category extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use Sluggable;
    use PowerJoins;

    protected $table = 'categories';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'menu' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::class, 'catalog_id');
    }

    // Берет свойста из категории и виды свойств из товаров
    public function filters($filtredItems = [])
    {
        if (Str::of($this->attributes['attributes'])->trim()->isEmpty()) {
            return collect(); // return empty collection
        }

        $attributesId = Str::replace('.', ',', $this->attributes['attributes']);
        $ids = explode(',', $attributesId);

        if (!empty($filtredItems)) {
            $items = $this->productsAttributes()
                ->get()
                ->unique()
                ->pluck('id')->toArray();
            $items = array_intersect($items, $filtredItems);
        } else {
            $items = $this->productsAttributes()
                ->get()
                ->unique()
                ->pluck('id');
        }

        return Attribute::whereIn('id', $ids)
            ->withWhereHas('items', fn ($query) => $query->whereIn('id', $items)->where('show', true))
            ->whereHas('items.products', fn ($q) => $q->isStatusActive())
            ->whereHas('items.products.variations', fn ($q) => $q->hasStock())
            ->orderBy('name', 'asc')
            ->get();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id');
    }

    public function productsAttributes()
    {
        return $this->hasManyDeepFromRelations($this->products(), (new Product())->attributes());
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
