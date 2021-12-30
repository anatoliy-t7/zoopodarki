<?php

namespace App\Models;

use App\Traits\Revieweable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Revieweable;
    use Sluggable;
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    use Searchable;

    protected $table = 'products';
    protected $guarded = [];

    public function registerMediaConversions(Media $media = null): void
    {
        ini_set('memory_limit', '512M');

        $this->addMediaConversion('thumb')
            ->format(Manipulations::FORMAT_WEBP)
            ->width(350)
            ->height(350)
            ->optimize()
            ->nonQueued()
            ->performOnCollections('product-images');

        $this->addMediaConversion('medium')
            ->format(Manipulations::FORMAT_WEBP)
            ->width(800)
            ->height(800)
            ->optimize()
            ->nonQueued()
            ->performOnCollections('product-images');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('product-images')
            ->useFallbackUrl('/assets/img/no-photo.webp')
            ->useFallbackPath(public_path('/assets/img/no-photo.webp'));
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function searchableAs()
    {
        return 'products';
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('categories:id,slug')
            ->with('categories.catalog:id,slug')
            ->with('media');
    }

    public function toSearchableArray()
    {
        $array = $this->only('id', 'name', 'meta_title', 'description', 'slug');

        if ($this->categories()->exists()) {
            $array['category'] = $this->categories[0]['slug'];
            $array['catalog'] = $this->categories[0]->catalog['slug'];
        }

        if ($this->media()->count() > 0) {
            $array['image'] = $this->getFirstMediaUrl('product-images', 'thumb');
        }

        return $array;
    }

    public function shouldBeSearchable()
    {
        return $this->isPublished();
    }

    public function isPublished()
    {
        return $this->status === 'active';
    }

    public function scopeIsStatusActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHasStock($query)
    {
        return $query->whereHas('variations', function ($query) {
            $query->where('stock', '>=', 1)->where('price', '>=', 1);
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    public function variations()
    {
        return $this->hasMany('App\Models\Product1C');
    }

    public function attributes()
    {
        return $this->belongsToMany(AttributeItem::class, 'product_attribute', 'product_id', 'attribute_id');
    }

    public function parentAttribute()
    {
        return $this->hasManyDeepFromRelations($this->attributes(), (new AttributeItem())->attribute());
    }

    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'revieweable');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function serie()
    {
        return $this->belongsTo('App\Models\BrandSerie', 'brand_serie_id');
    }

    public function favorites()
    {
        return $this->morphMany('App\Models\Favorite', 'favoritable');
    }

    public function scopeCheckStock($query, $stockF)
    {
        if ($stockF == 1) {
            return $query->whereHas('variations', function ($query) {
                $query->where('stock', '=', 0);
            });
        }
        if ($stockF == 2) {
            return $query->whereHas('variations', function ($query) {
                $query->where('stock', '>=', 1);
            });
        }
        if ($stockF == 3) {
            return $query->whereHas('variations', function ($query) {
                $query->where('stock', '>=', 0);
            });
        }
    }
}
