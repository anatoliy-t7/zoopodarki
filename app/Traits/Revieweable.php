<?php

namespace App\Traits;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;

//https://github.com/willvincent/laravel-rateable

trait Revieweable
{

    public function rate($value, $body)
    {
        $review          = new Review();
        $review->rating  = $value;
        $review->body    = $body;
        $review->user_id = Auth::id();
    }

    public function rateOnce($value, $body)
    {
        $review = Review::query()
            ->where('revieweable_type', '=', get_class($this))
            ->where('revieweable_id', '=', $this->id)
            ->where('user_id', '=', Auth::id())
            ->first()
        ;

        if ($review) {
            $review->rating = $value;
            $review->body   = $body;
            $review->save();
        } else {
            $this->rate($value, $body);
        }
    }

    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'revieweable');
    }

    public function averageRating()
    {
        return ceil($this->reviews()->avg('rating'));
    }

    public function sumRating()
    {
        return $this->reviews()->sum('rating');
    }

    public function timesRated()
    {
        return $this->reviews()->where('published', 1)->count();
    }

    public function usersRated()
    {
        return $this->reviews()->groupBy('user_id')->pluck('user_id')->count();
    }

    public function userAverageRating()
    {
        return $this->reviews()->where('user_id', Auth::id())->avg('rating');
    }

    public function userSumRating()
    {
        return $this->reviews()->where('user_id', Auth::id())->sum('rating');
    }

    public function ratingPercent($max = 5)
    {
        $quantity = $this->reviews()->count();
        $total    = $this->sumRating();

        return $quantity * $max > 0 ? $total / ($quantity * $max / 100) : 0;
    }

    // Getters

    public function getAverageRatingAttribute()
    {
        return $this->averageRating();
    }

    public function getSumRatingAttribute()
    {
        return $this->sumRating();
    }

    public function getUserAverageRatingAttribute()
    {
        return $this->userAverageRating();
    }

    public function getUserSumRatingAttribute()
    {
        return $this->userSumRating();
    }
}
