<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use Sluggable;

    protected $table = 'tags';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'filter' => 'array',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
