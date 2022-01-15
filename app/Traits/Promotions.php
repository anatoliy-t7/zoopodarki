<?php

namespace App\Traits;

use App\Models\Product1C;

trait Promotions
{
    public function initPromotion($product1cArray, $promotion)
    {
        if ($promotion['type'] === '1') {
            return $this->promotionUcenka($product1cArray, $promotion);
        } elseif ($promotion['type'] === '2') {
            return $this->promotionOnePlusOne($product1cArray);
        } elseif ($promotion['type'] === '3') {
            return $this->promotionByProvider($product1cArray, $promotion);
        } elseif ($promotion['type'] === '4') {
            return $this->promotionHoliday($product1cArray, $promotion);
        }

        return false;
    }

    public function promotionUcenka($product1cArray, $promotion) // 1 "Уценка"
    {
        $product1c = $this->getModel($product1cArray['id']);

        $originalPrice = $this->calcDiscountForUcenka($product1c->price, $promotion['percent']);

        $product1c->update([
            'promotion_type' => intval($promotion['type']),
            'promotion_price' => $originalPrice,
        ]);

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

    public function promotionByProvider($product1cArray, $promotion) // 3 "Акции от поставщика"
    {
        try {
            $product1c = $this->getModel($product1cArray['id']);

            $originalPrice = $this->discountRevert($product1c->price, $promotion['percent']);

            $product1c->update([
                'promotion_type' => intval($promotion['type']),
                'promotion_percent' => $promotion['percent'],
                'promotion_price' => $originalPrice,
            ]);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function promotionHoliday($product1cArray, $promotion) // 4 "Праздники"
    {
        $product1c = $this->getModel($product1cArray['id']);

        $product1c->update([
            'promotion_type' => intval($promotion['type']),
            'promotion_percent' => $promotion['percent'],
            'promotion_date' => $promotion['date'],
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

        $product1c->update([
            'promotion_type' => 0,
            'promotion_price' => null,
            'promotion_percent' => null,
            'promotion_date' => null,
        ]);
    }

    public function discountRevert($discount, $procent): int
    {
        $price = $discount + floor($discount * $procent / 100);

        return floor($price);
    }

    public function calcDiscountForUcenka($price, $percent): int
    {
        // Стоимость товара 1С*100/75
        $percent = 100 - $percent;

        return floor(($price * 100) / $percent);
    }
}
