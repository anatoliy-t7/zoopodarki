<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Media extends Model
{

    use PowerJoins;

    protected $table = 'media';

    protected $guarded = [];

}
