<?php

namespace App\Jobs;

use App\Models\AttributeItem;
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
    public $tries = 2;

    public $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $attribute = AttributeItem::all();

        // $attribute->products()->detach();
        $attributs = AttributeItem::all();
        foreach ($attributs as $attribute) {
            if (!$attribute->products->where('product_attribute.product_id', $this->product->id)) {
                $attribute->products()->detach($this->product->id);
                $this->count = $this->count + 1;
            }
        }


        if ($this->count > 0) {
            logger('DONE : ' . $this->count);
        }
    }

    public function addAttributeItem($attribute)
    {
        $products = Product::has('attributes')->get();

        foreach ($products as $key => $product) {
            if (!$attribute->where('product_attribute.product_id', $product->id)) {
                $attribute->products()->detach($product->id);
                $this->count = $this->count + 1;
            }
        }

        unset($products, $attribute);

        // if ($product->country !== null) {
        //     if ($attribute->items()
        //         ->where('name', trim($product->country))
        //         ->first()) {
        //         $attributeItem = AttributeItem::where('name', trim($product->country))->first();
        //     } else {
        //         $attributeItem = AttributeItem::create([
        //             'name' => trim($product->country),
        //             'attribute_id' => $attribute->id,
        //         ]);
        //     }

        //     if (!$product->attributes()
        //         ->where('attribute_item.name', trim($product->country))
        //         ->first()) {
        //         $product->attributes()->attach($attributeItem->id);
        //     }

        //     unset($attributeItem);
        // }
    }
}
