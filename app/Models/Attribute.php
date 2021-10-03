<?php

namespace App\Models;

use App\Models\AttributeItem;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Attribute extends Model
{
    use PowerJoins;

    public $timestamps = false;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(AttributeItem::class)->orderBy('name', 'ASC');
    }

}
