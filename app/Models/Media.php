<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use PowerJoins;

    protected $table = 'media';

    protected $guarded = [];
}
