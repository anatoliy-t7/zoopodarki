<?php
namespace App\Traits;

use T7team\Shopcart\CartCondition;

trait Discounts
{
    public function checkAndSetPromotionDiscount($cart, $product_1c)
    {
        $cartDiscountByHoliday = $this->checkDiscountByHoliday($product_1c);

        if ($product_1c->promotion_type === 1) {
            $cartDiscountByUcenka = $this->getDiscountByUcenka(
                $product_1c->price - $product_1c->promotion_price
            );

            $cart->addItemCondition($product_1c->id, $cartDiscountByUcenka);
        } else {
            $cart->addItemCondition($product_1c->id, $cartDiscountByHoliday);
        }
    }

    public function checkDiscountByCard($userHasDiscount)
    {
        if ($userHasDiscount !== 0) {
            return $cartDiscountByCard = new CartCondition([
                'name' => 'Скидочная карта',
                'type' => 'discount_card',
                'target' => 'subtotal',
                'value' => '-' . $userHasDiscount . '%',
            ]);
        }
        return false;
    }

    public function getDiscountByCard($userHasDiscount, $cartId, $items)
    {

        $cartDiscountByCard = $this->checkDiscountByCard($userHasDiscount);

        if ($cartDiscountByCard === false) {
            // Дис. карта действует сразу, но сама себя не учитывает
            foreach ($items as $item) {
                if ($item->associatedModel['vendorcode'] === 'DISCOUNT_CARD') {
                    $cartDiscountByCard = $this->getDiscountByCard($item->associatedModel['unit_value'], $this->cartId);
                }
                break;
            }
        }

        if ($cartDiscountByCard) {
            foreach ($items as $item) {
                if ($item->getConditionByType('discount_card')) {
                        \Cart::session($cartId)->removeItemCondition($item['id'], 'discount_card');
                }

                if ($item->associatedModel['vendorcode'] !== 'DISCOUNT_CARD'
                    && $item->associatedModel['promotion_type'] !== 4
                    && !$item->getConditionByType('weight')) {
                    \Cart::session($cartId)->addItemCondition($item['id'], $cartDiscountByCard);
                }
            }
        }
    }

    public function checkIfFirstOrder($subTotal, $cartId)
    {
        if (ceil($subTotal) >= 2000 && auth()->user()->extra_discount === 'first') {
            $condition = new CartCondition([
                'name' => 'Первый заказ',
                'target' => 'total',
                'type' => 'discount',
                'value' => '-200',
                'order' => 1
            ]);

            \Cart::session($cartId)->condition($condition);

            return true;
        } elseif (ceil($subTotal) < 2000 && auth()->user()->extra_discount !== 'first') {
            \Cart::session($cartId)->removeCartCondition('Первый заказ');

             return false;
        } else {
            \Cart::session($cartId)->removeCartCondition('Первый заказ');
             return false;
        }
    }

    public function checkIfUserHasDiscountOnReview($cartId)
    {
        if (auth()->user()->review === 'on') {
            $condition = new CartCondition([
                'name' => 'Скидка 2% за отзыв',
                'target' => 'total',
                'type' => 'discount',
                'value' => '-2%',
                'order' => 2
            ]);

            \Cart::session($cartId)->condition($condition);

            return true;
        } else {
            \Cart::session($cartId)->removeCartCondition('Скидка 2% за отзыв');

            return false;
        }
    }

    public function getDiscountByWeight()
    {
        return new CartCondition([
            'name' => 'Скидочная 10% на сухие корма более 5кг',
            'target' => 'subtotal',
            'type' => 'weight',
            'value' => '-10%',
        ]);
    }

    public function checkDiscountByWeight($cartId, $items)
    {

        // TODO при добавлении еще раз перезаписывать первый
        //у всех “сухих кормов для любого животного” считать вес, если более 5кг, то скидка 10% (дис. карта НЕ работает)
        //id сухих кормов: 18, 34
        $productDiscountIds = [];
        $totalWeight = collect();

        foreach ($items as $item) {
            // if ($item->associatedModel['category_id'] === 18 || $item->associatedModel['category_id'] === 34) {

            if ($item->associatedModel['promotion_type'] === 0) {
                array_push($productDiscountIds, $item['id']);
                $itemWeight = $item->associatedModel['weight'] * $item->quantity;

                $totalWeight->push($itemWeight);
            }
        }

        $totalWeight = $totalWeight->sum();


        if ($totalWeight < 5000) {
            return false;
        }

        foreach ($items as $item) {
            if ($item->getConditionByType('weight')) {
                   \Cart::session($cartId)->removeItemCondition($item['id'], 'weight');
            }
            if ($item->getConditionByType('discount_card')) {
                \Cart::session($cartId)->removeItemCondition($item['id'], 'discount_card');
            }
            if (in_array($item['id'], array_unique($productDiscountIds))) {
                \Cart::session($cartId)->addItemCondition($item['id'], $this->getDiscountByWeight());
            } else {
                if ($item->getConditionByType('weight')) {
                    \Cart::session($cartId)->removeItemCondition($item['id'], 'weight');
                }
            }
        }

        return true;
    }

    public function getDiscountByUcenka($discount)
    {
        return new CartCondition([
        'name' => 'Уценка',
        'type' => 'price_down',
        'value' => '-' . $discount,
        ]);
    }

    public function checkDiscountByOnePlusOne($cartItems, $cartId)
    {
        $discountItems = [];

        foreach ($cartItems as $item) {
            if ($item->associatedModel['promotion_type'] === 2) {
                array_push($discountItems, [
                    'id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                \Cart::session($cartId)->removeItemCondition($item['id'], 'discountPlus');
            }
        }

        if ($discountItems && count($discountItems) >= 2) {
            $numberOfFree = floor(count($discountItems) / 2);

            $price = array_column($discountItems, 'price');

            array_multisort($price, SORT_ASC, $discountItems);
            $cheapestItems = array_slice($discountItems, 0, $numberOfFree);

            array_multisort($price, SORT_DESC, $discountItems);
            $expenciveItems = array_slice($discountItems, 0, $numberOfFree);

            $count = array_sum(array_column($expenciveItems, 'quantity'));

            foreach ($cheapestItems as $cheapestItem) {
                $itemDiscount = new CartCondition([
                    'name' => '1 + 1',
                    'type' => 'discountPlus',
                    'value' => '-' . $cheapestItem['price'] * $count . ' ',
                ]);

                // TODO все время показывает 0
                \Cart::session($cartId)->clearItemConditions($cheapestItem['id']);

                \Cart::session($cartId)->addItemCondition($cheapestItem['id'], $itemDiscount);
            }
        }
    }

    public function checkDiscountByHoliday($product_1c)
    {
        if ($product_1c->promotion_type === 4) {
            $discount = intval($product_1c->promotion_percent);

            return new CartCondition([
                'name' => 'Праздничные',
                'type' => 'discount',
                'value' => '-' . $discount . '%',
            ]);
        }

        return false;
    }

    public function removeCheckoutDiscounts($items, $cartId)
    {
        foreach ($items as $item) {
            if ($item->getConditionByType('weight')) {
                    \Cart::session($cartId)->removeItemCondition($item['id'], 'weight');
            }

            if ($item->getConditionByType('discount_card')) {
                \Cart::session($cartId)->removeItemCondition($item['id'], 'discount_card');
            }
        }
    }
}
