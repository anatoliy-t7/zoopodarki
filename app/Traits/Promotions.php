<?php
namespace App\Traits;

use App\Models\Product;
use App\Models\Product1C;
use Illuminate\Support\Facades\DB;

trait Promotions
{
    public function initPromotion($product1cArray)
    {
        if ($this->promotion['type'] === '1') {
            if ($this->promotionUcenka($promotion, $product1cArray)) {
                return true;
            }
        } elseif ($this->promotion['type'] === '2') {
            if ($this->promotionOnePlusOne($product1cArray)) {
                return true;
            }
        } elseif ($this->promotion['type'] === '3') {
            if ($this->promotionByProvider($product1cArray)) {
                return true;
            }
        } elseif ($this->promotion['type'] === '4') {
            if ($this->promotionHoliday($product1cArray)) {
                return true;
            }
        }

        return false;
    }

    public function promotionUcenka($promotion, $product1cArray) // 1 "Помоги приюту"
    {
        $product1c = $this->getModel($product1cArray['id']);

        $promotionPrice = ($product1cArray['price'] / 1.22) * 1.08;

        $product1c->loadMissing(['product', 'product.unit']);

        DB::transaction(function () use ($product1c, $promotion, $promotionPrice) {

            // клонируем товар сайта
            $productClon = Product::find($product1c->product->id)->duplicate();

            $productClon->categories()->attach(82); // ID категории "Помоги приюту"

            // клонируем товар 1С
            Product1C::create([
                'uuid' => null,
                'cod1c' => $product1c->cod1c,
                'name' => $product1c->name,
                'barcode' => $product1c->barcode,
                'vendorcode' => $product1c->vendorcode,
                'commission' => $product1c->commission,
                'price' => $product1c->price,
                'stock' => $promotion['stock'],
                'promotion_type' => intval($this->promotion['type']),
                'promotion_price' => $promotionPrice,
                'promotion_percent' => null,
                'promotion_date' => null,
                'weight' => $product1c->weight,
                'size' => $product1c->size,
                'unit_value' => $product1c->unit_value,
                'product_id' => $productClon->id,
            ]);

            $product1c->stock = max($product1c->stock - $promotion['stock'], 0);
            $product1c->save();
        });

        return true;
    }

    public function promotionOnePlusOne($product1cArray) // 2
    {
        $product1c = $this->getModel($product1cArray['id']);

        $product1c->update([
            'promotion_type' => intval($this->promotion['type']),
            'promotion_date' => $this->promotion['date'],
            'stock' => $this->promotion['stock'],
        ]);

        return true;
    }

    public function promotionByProvider($product1cArray) // 3
    {
        $product1c = $this->getModel($product1cArray['id']);

        $originalPrice = discountRevert($product1c->price, $this->promotion['percent']);

        $product1c->update([
            'promotion_type' => intval($this->promotion['type']),
            'promotion_percent' => $this->promotion['percent'],
            'promotion_price' => $originalPrice,
        ]);

        return true;
    }

    public function promotionHoliday($product1cArray) // 4
    {
        $product1c = $this->getModel($product1cArray['id']);

        $product1c->update([
            'promotion_type' => intval($this->promotion['type']),
            'promotion_percent' => $this->promotion['percent'],
            'promotion_date' => $this->promotion['date'],
        ]);

        return true;
    }

    public function getModel($product1cId)
    {
        return Product1C::find($product1cId);
    }

    public function stopPromotion($product1cId)
    {
        $product1c = $this->getModel($product1cId);

        if ($product1c->promotion_type === 1) {
            $product1c->loadMissing('product');
            $product1c->product->delete();
            $product1c->delete();
        } else {
            $product1c->update([
                'promotion_type' => 0,
                'promotion_price' => null,
                'promotion_percent' => null,
                'promotion_date' => null,
            ]);
        }
    }
}
