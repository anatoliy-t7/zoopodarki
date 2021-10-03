<?php

namespace App\Models;

use App\Traits\Revieweable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Review extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Revieweable;
    use PowerJoins;

    protected $table = 'reviews';
    protected $guarded = [];
    protected $casts = [
        'published' => 'boolean',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        ini_set('memory_limit', '512M');
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->optimize()
            ->crop(Manipulations::CROP_CENTER, 64, 64)
            ->nonQueued()
            ->performOnCollections('product-customers-photos');
    }

    public function revieweable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault(['name' => 'Anonymous']);
    }

}
