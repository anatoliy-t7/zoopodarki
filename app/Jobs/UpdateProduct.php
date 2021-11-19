<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AttributeItem;

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

        $attributes = AttributeItem::where('name', '')->get();

        foreach ($attributes as $key => $attr) {
            $attr->delete();
            $this->count = $this->count + 1;
        }

        logger($this->count);
    }

    public function update($product)
    {

        $prodAttrs = [];
        // $prodAttrsUnique = [];

        foreach ($product->attributes as $attr) {
            if ($attr->name === "") {
                 array_push($prodAttrs, $attr->id);

            }
        }

        // $prodAttrsUnique = $prodAttrs->unique()->values()->all();

        $product->attributes()->detach($prodAttrs);


    }
}
