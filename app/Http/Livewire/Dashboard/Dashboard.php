<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Order;
use App\Models\Product1C;
use App\Models\Product;
use App\Models\Review;
use App\Models\Waitlist;
use Livewire\Component;

class Dashboard extends Component
{
    public $newProducts1C;
    public $orders;
    public $pendingReviews;
    public $pendingWaitlist;

    public $productsDoesNotHaveDescription = 0;
    public $productsDoesNotHaveImage = 0;

    public function mount()
    {
        $this->orders = Order::whereIn('status', ['pending_confirm', 'pending_payment', 'processing', 'hold'])->get(['id', 'status']);

        $this->newProducts1C = Product1C::whereNull('product_id')
        ->where('stock', '>', 0)
        ->where('price', '>', 0)
        ->get();

        $this->pendingReviews = Review::where('status', 'pending')->get();
        $this->pendingWaitlist = Waitlist::where('status', 'pending')->get();

        $this->productsDoesNotHaveDescription = Product::whereNull('description')->count();
        $this->productsDoesNotHaveImage = Product::doesntHave('media')->count();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')
            ->extends('dashboard.app')
            ->section('content');
    }
}
