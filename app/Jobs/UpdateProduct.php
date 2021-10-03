<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 300;

    public $count = 0;
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
        $products = Product::with('attributes')->get();

        foreach ($products as $product) {
            $this->update($product);
        }

        logger($this->count);
    }

    public function update($product)
    {

        $this->count = $this->count + 1;

        $prodAttrs       = collect();
        $prodAttrsUnique = [];

        foreach ($product->attributes as $attr) {

            $prodAttrs->push($attr->id);

        }

        $prodAttrsUnique = $prodAttrs->unique()->values()->all();

        $product->attributes()->detach();
        $product->attributes()->attach($prodAttrsUnique);

    }

}
