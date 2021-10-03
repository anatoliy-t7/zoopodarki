<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewChangedToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $productLink;
    public $productName;

    public function __construct(Review $review, $productName, $productLink)
    {
        $this->review = $review;
        $this->productName = $productName;
        $this->productLink = $productLink;

    }

    public function build()
    {
        return $this->view('emails.templates.dist.review-changed-to-user')->subject('Изменения вашего отзыва');
    }
}
