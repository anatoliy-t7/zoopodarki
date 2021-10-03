<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id', 'favoritable_id', 'favoritable_type',
    ];
    public function favoritable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault(['name' => 'Anonymous']);
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'favoritable_id');
    }
}
