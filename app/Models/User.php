<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function routeNotificationForSmscru()
    {
        return $this->phone;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite', 'user_id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Contact');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }
}
