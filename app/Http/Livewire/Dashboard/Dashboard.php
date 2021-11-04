<?php
namespace App\Http\Livewire\Dashboard;

use App\Models\Product;
use App\Models\Review;
use App\Models\Waitlist;
use Livewire\Component;

class Dashboard extends Component
{
    public $pendingReviews;
    public $pendingWaitlist;
    public $productsGotDescription = 7741;
    public $productsHaveDescription = 0;
    public $productsDoneDescription = 0;
    public $productsDoesNotHaveDescription = 0;
    public $productsGotImage = 8307;
    public $productsHaveImage = 0;
    public $productsDoneImage = 0;
    public $productsDoesNotHaveImage = 0;

    public function mount()
    {
        $this->pendingReviews = Review::where('status', 'pending')->get();
        $this->pendingWaitlist = Waitlist::where('status', 'pending')->get();


        // TODO удалить в production
        $this->productsHaveDescription = Product::whereNotNull('description')->count();
        $this->productsDoesNotHaveDescription = Product::count() - $this->productsHaveDescription;
        $this->productsDoneDescription = $this->productsHaveDescription - $this->productsGotDescription;

        $this->productsHaveImage = Product::has('media')->count();
        $this->productsDoesNotHaveImage = Product::count() - $this->productsHaveImage;
        $this->productsDoneImage = $this->productsHaveImage - $this->productsGotImage;
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')
            ->extends('dashboard.app')
            ->section('content');
    }
}
