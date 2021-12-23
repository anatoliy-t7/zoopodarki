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
    public $tries = 1;

    public $collect = [];

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
        $products = Product::withWhereHas('unit', function ($query) {
            $query->where('product_units.name', 'гр');
        })
            ->withWhereHas('variations', function ($query) {
                $query->where('products_1c.unit_value', '!=', null);
            })
            ->get();

        // logger($products);

        foreach ($products as $product) {
            foreach ($product->variations as $product1c) {
                if ($product1c->unit_value && $product1c->unit_value !== 'на развес') {
                    $product1c->weight = $product1c->unit_value;
                    $product1c->save();
                    $this->count = $this->count + 1;
                }
            }
        }

        // logger($this->collect);
        logger('done: ' . $this->count);
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
