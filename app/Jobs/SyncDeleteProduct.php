<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDeleteProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 600;
    public $counter = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = Product::whereNull('description')
            ->whereHas('variations', function ($query) {
                $query
                    ->where('stock', '=', 0);
            })
            ->doesntHave('media')
            ->doesntHave('categories')
            ->with('variations')
            ->get();

        foreach ($products as $product) {
            $pr_id = $product->id;
            $product->delete();
            $this->forceDelete($pr_id);
            $this->counter += $this->counter;
        }

        \Log::debug($this->counter);

    }

    public function forceDelete($id)
    {

        $product = Product::onlyTrashed()->find($id);

        if ($product->categories()->exists()) {

            $product->categories()->detach();

        }

        $product->variations()->update(['product_id' => null]);

        if ($product->attributes()->exists()) {

            $product->attributes()->detach();
        }

        $product->forceDelete();

    }

}
