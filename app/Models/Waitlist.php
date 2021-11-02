<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $table = 'waitlists';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault(['name' => 'Anonymous']);
    }

    public function product1c()
    {
        return $this->belongsTo('App\Models\Product1C')->withDefault(['name' => 'Удален']);
    }
}
