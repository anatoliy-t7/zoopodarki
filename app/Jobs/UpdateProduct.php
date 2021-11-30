<?php

namespace App\Jobs;

use App\Models\Attribute;
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
            $this->addAttributeItem($product);
            // $this->updateProduct($product);
        }
    }

    public function updateProduct($product)
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

    public function addAttributeItem($product)
    {
        $attribute = Attribute::where('id', 64)->with('items')->first();

        if ($product->country !== null) {
            if ($attribute->items()
                ->where('name', trim($product->country))
                ->first()) {
                 $attributeItem = AttributeItem::where('name', trim($product->country))->first();
            } else {
                $attributeItem = AttributeItem::create([
                'name' => trim($product->country),
                'attribute_id' => $attribute->id,
                ]);
            }

            if (!$product->attributes()
                ->where('attribute_item.name', trim($product->country))
                ->first()) {
                $product->attributes()->attach($attributeItem->id);
            }

            unset($attributeItem);
        }

        unset($attribute);
    }
}
