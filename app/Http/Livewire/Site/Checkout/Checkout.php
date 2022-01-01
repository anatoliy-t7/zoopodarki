<?php

namespace App\Http\Livewire\Site\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product1C;
use App\Traits\Delivery;
use App\Traits\Discounts;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Checkout extends Component
{
    use Discounts;
    use WireToast;
    use Delivery;

    public $greenLine = 4; // 4, 50 проценты для полосы
    public $step = 1; // 1, 2 шаги заказа
    public $order = null;
    public $cartId; // индификатор корзины
    public $shelterCartId;
    public $counter; // количество товара в корзине
    public $items;
    public $shelterItems;
    public $subTotal;
    public $deliveryCost = 0;
    public $totalAmount;
    public $totalWeight = 200;
    public $userId;
    public $contact;
    public $address = [];
    public $date;
    public $dates;
    public $orderComment = '';
    public $needChange = null;
    public $deliveryTime = '10:00 - 17:00';
    public $orderType = 0; // 0 delivery, 1 pickup
    public $orderPaymentType = 0; // 0 online, 1 cash
    public $pickupStore = null;
    public $storeId = 999; // 999 = не назначен
    public $storeGuid;
    public $firstOrder = 0;
    public $userHasDiscount = 0;
    public $userHasDiscountOnReview = false;
    public $productDiscountIdsByWeight = false;
    public $contactlessDelivery = false;
    protected $listeners = [
        'setPickupStore',
        'getAddressesforCheckout',
        'getContactsforCheckout',
        'getCartCheckout',
    ];

    public function booted()
    {
        if (!request()->session()->has('cart_id')) {
            return redirect()->route('site.home');
        }

        $this->getCartCheckout();
    }

    public function mount()
    {
        if (!request()->session()->has('cart_id')) {
            return redirect()->route('site.home');
        } else {
            $this->cartId = session('cart_id');
            $this->shelterCartId = session('shelter_cart');

            if (\Cart::session($this->cartId)->isEmpty()) {
                redirect('/');
            } else {
                $this->generateDatesForDelivery();
                if (auth()->user()) {
                    $this->step = 2;
                    $this->greenLine = 50;
                    $this->userHasDiscount = auth()->user()->discount;
                    $this->getContactsforCheckout();
                    $this->getAddressesforCheckout();
                    $this->updatedOrderType();
                }
            }
        }
    }

    public function updatedOrderType()
    {
        if ($this->orderType === 0 && !empty($this->address)) {
            //$this->getDeliveryCostsByBoxberry($this->totalWeight, $this->address['zip']);
            if (Arr::has($this->address, 'lat')) {
                $this->deliveryCost = $this->getDeliveryCostsByStore(
                    $this->subTotal,
                    $this->address['lat'],
                    $this->address['lng']
                );
            }
        }
    }

    public function generateDatesForDelivery()
    {
        $from = Carbon::tomorrow()->locale('ru_RU');

        $to = Carbon::tomorrow()->addDays(14)->locale('ru_RU');

        $period = CarbonPeriod::create($from, $to)->toArray();

        foreach ($period as $key => $date) {
            $this->dates[$key]['number'] = $date->day;
            $this->dates[$key]['name'] = $date->translatedFormat('l');
            $this->dates[$key]['date'] = $date->format('Y-m-d');
        }
    }

    public function calcTotalAmount()
    {
        if (0 == $this->orderType) {
            $this->totalAmount = \Cart::session($this->cartId)->getTotal()
                + app('shelter')->session($this->shelterCartId)->getTotal()
                + $this->deliveryCost;
        } else {
            $this->totalAmount = \Cart::session($this->cartId)->getTotal()
                + app('shelter')->session($this->shelterCartId)->getTotal();
        }
    }

    public function getCartCheckout()
    {
        $cart = \Cart::session($this->cartId);
        $shelterCart = app('shelter')->session($this->shelterCartId);

        $functionItems = $cart->getContent();
        $this->items = $functionItems->all();
        $functionShelterItems = $shelterCart->getContent();
        $this->shelterItems = $functionShelterItems->all();

        if (count($this->items) === 0 && count($this->shelterItems) === 0) {
            return redirect()->route('site.home');
        }

        $shelterCartCounter = $shelterCart->getTotalQuantity();
        $this->counter = $cart->getTotalQuantity() + $shelterCartCounter;

        $this->getTotalWeight($this->items, $functionShelterItems);

        // Check and set discounts
        if ($functionItems) {
            $this->productDiscountIdsByWeight = $this->checkDiscountByWeight($this->cartId, $this->items);

            $this->getDiscountByCard($this->items, $this->cartId, $this->userHasDiscount);

            if ($this->userHasDiscount == 0 && $this->checkIfCartHasDiscountCard($this->items) !== false) {
                $this->userHasDiscount = $this->checkIfCartHasDiscountCard($this->items);
            }
        }

        if (auth()->user() && $this->checkIfFirstOrder($cart->getSubTotal(), $this->cartId)) {
            $this->firstOrder = 200;
        }

        $this->subTotal = $cart->getSubTotal() + $shelterCart->getSubTotal();

        if (auth()->user()) {
            $this->updatedOrderType();
        }

        $this->calcTotalAmount();
    }

    public function setPickupStore($store, $storeId, $storeGuid)
    {
        $this->pickupStore = $store;
        $this->storeId = $storeId;
        $this->storeGuid = $storeGuid;

        $this->dispatchBrowserEvent('close-modal');
    }

    public function getOrderType($type)
    {
        $this->orderType = $type;
        $this->calcTotalAmount();
    }

    public function paymentType($type)
    {
        $this->orderPaymentType = $type;
    }

    public function getTotalWeight($items, $shelterItems)
    {
        $this->totalWeight = collect();

        if (count($items) > 0) {
            foreach ($items as $item) {
                $itemWeight = $item->attributes->weight * $item->quantity;
                $this->totalWeight->push($itemWeight);
            }
        }

        if (count($shelterItems) > 0) {
            foreach ($shelterItems as $shelterItem) {
                $itemWeight = $shelterItem->attributes->weight * $shelterItem->quantity;
                $this->totalWeight->push($itemWeight);
            }
        }

        $this->totalWeight = $this->totalWeight->sum();
    }

    public function checkIfUserHasOrderWithStatusPendingConfirm()
    {
        if (request()->session()->has('order_id')) {
            if (Order::where('id', session('order_id'))->where('status', 'pending_confirm')->first()) {
                $order = Order::where('id', session('order_id'))
                ->where('status', 'pending_confirm')
                ->with('items', 'items.product1c')
                ->first();

                foreach ($order->items as $item) {
                    DB::transaction(function () use ($item) {
                        $product1c = Product1C::find($item->product_id);
                        $product1c->stock = $product1c->stock + $item->quantity;
                        $product1c->save();
                        unset($product1c);
                    });
                }
                $order->delete();
            }
        }
    }

    public function createOrder()
    {
        if (!auth()->user()) {
            $this->greenLine = 4;
            $this->step = 1;

            return $this->dispatchBrowserEvent('auth');
        }

        if ($this->deliveryCost === false) {
            return toast()
            ->warning('Дорогой покупатель, в настоящее время мы доставляем только в пределах СПБ КАД,
            но совсем скоро начнем доставку и за его пределами!')
            ->push();
        }

        $this->validate([
            'contact' => 'required',
        ]);

        if (0 == $this->orderType) {
            $this->validate([
                'date' => 'required',
                'deliveryTime' => 'required',
                'address' => 'required',
            ]);

            $address = $this->address['address'] . ' ' . $this->address['building'];

            if (Arr::has($this->address, 'apartment')) {
                $address = $address . ', кв ' . $this->address['apartment'];
            }

            if (Arr::has($this->address, 'extra')) {
                $address = $address . ', ' . $this->address['extra'];
            }
        } else {
            $this->validate([
                'pickupStore' => 'required',
            ]);

            $address = 'Самовывоз из магазина: ' . $this->pickupStore;
        }

        $this->checkIfUserHasOrderWithStatusPendingConfirm();

        $noStockItems = ['no_stock' => [], 'less_stock' => []];
        $noStockItems = $this->reserveStock($this->cartId, false, $noStockItems);
        array_merge($noStockItems, $this->reserveStock($this->shelterCartId, true, $noStockItems));

        $this->getCartCheckout();

        $orderComment = $this->orderComment;

        if ($contactlessDelivery === true) {
            $orderComment = 'Бесконтактная доставка' . "\n" . $orderComment;
        }

        if (0 !== $this->firstOrder) {
            $orderComment = 'Скидка за первый заказ -'
              . $this->firstOrder . ' рублей (Применить)' . "\n" . $orderComment;
        }

        $this->userId = auth()->user()->id;

        try {
            DB::beginTransaction();

            $orderNumber = getNextOrderNumber();

            // if (auth()->user()->review === 'on') {
            //     $this->orderComment = 'Скидка 2% за отзыв | ' . $this->orderComment;
            // }

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $this->userId,
                'amount' => ceil($this->totalAmount),
                'quantity' => $this->counter,
                'weight' => $this->totalWeight,
                'status' => 'pending_confirm',
                'payment_status' => 'pending',
                'payment_method' => $this->orderPaymentType,
                'need_change' => $this->needChange,
                'order_type' => $this->orderType,
                'pickup_store' => $this->storeGuid,
                'date' => Carbon::parse($this->date),
                'delivery_time' => $this->deliveryTime,
                'delivery_cost' => $this->deliveryCost,
                'contact' => $this->contact,
                'address' => $address,
                'order_comment' => $orderComment,
            ]);

            foreach ($this->items as $item) {
                $product1c = Product1C::where('id', $item->id)
                    ->with('product')
                    ->with('product.unit')
                    ->first();

                $unit = '';
                if ($product1c->unit_value == 'на развес') {
                    $unit = $item->attributes->weight;
                }
                if ($product1c->product->unit()->exists()) {
                    $unit = $product1c->unit_value . ' ' . $unit . ' ' . $product1c->product->unit->name;
                }

                $amount = $item->getPriceSumWithConditions();
                $discount = 0;
                $discountComment = '';

                if (0 == $item->associatedModel['promotion_type']) {
                    $discount = $item->getPriceSum() - $item->getPriceSumWithConditions();
                    $discountComment = '';
                }
                if (1 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Акция "Уценка"';
                }
                if (2 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Акция "1+1"';
                }
                if (3 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Акция поставщика, -' . $product1c->promotion_percent;
                }
                if (4 == $item->associatedModel['promotion_type']) {
                    $discount = $item->getPriceSum() - $item->getPriceSumWithConditions();
                    $discountComment = 'Праздничные скидки ' . $product1c->promotion_percent . '%';
                }

                if ($item->attributes->unit_value == 'на развес') {
                    $discountComment = 'на развес: ' . $item->attributes->weight . 'гр, ' . $discountComment;
                }

                if ($item->getConditionByType('discount_card')) {
                    $discountComment = $discountComment . 'Прим. диск. карта ' . $this->userHasDiscount . '%';
                }

                $discountProcent = (($item->getPriceSum() - $item->getPriceSumWithConditions()) * 100)
                 / $item->getPriceSum();

                OrderItem::create([
                    'name' => $item->name,
                    'uuid' => $product1c->uuid,
                    'barcode' => $product1c->barcode,
                    'vendorcode' => $product1c->vendorcode,
                    'quantity' => $item->quantity,
                    'unit' => $unit,
                    'price' => $item->price,
                    'amount' => ceil($amount),
                    'order_id' => $order->id,
                    'product_id' => $product1c->id,
                    'discount_comment' => $discountComment,
                    'discount' => ceil($discount),
                    'discount_procent' => $discountProcent,
                ]);

                unset($product1c);
            }

            if (count($this->shelterItems) > 0) {
                foreach ($this->shelterItems as $shelterItem) {
                    $product1c = Product1C::find($shelterItem->id);

                    $unit = '';
                    if ($product1c->product->unit()->exists()) {
                        $unit = $product1c->unit_value . ' ' . $product1c->product->unit->name;
                    }

                    $discountComment = 'Уценка "Помоги приюту"';

                    if ($shelterItem->getConditionByType('discount_card')) {
                        $discountComment = $discountComment . 'Прим. диск. карта ' . $this->userHasDiscount . '%';
                    }

                    $discountProcent = (($item->getPriceSum() - $shelterItem->getPriceSumWithConditions()) * 100)
                    / $shelterItem->getPriceSum();

                    OrderItem::create([
                        'name' => $shelterItem->name,
                        'uuid' => $product1c->uuid,
                        'barcode' => $product1c->barcode,
                        'vendorcode' => $product1c->vendorcode,
                        'quantity' => $shelterItem->quantity,
                        'unit' => $unit,
                        'price' => $shelterItem->price,
                        'amount' => ceil($shelterItem->getPriceSumWithConditions()),
                        'order_id' => $order->id,
                        'product_id' => $product1c->id,
                        'discount_comment' => $discountComment,
                        'discount' => ceil($shelterItem->getPriceSum() - $shelterItem->getPriceSumWithConditions()),
                        'discount_procent' => $discountProcent,
                    ]);
                }
            }

            $order->load('items');

            // if (auth()->user()->review === 'on') {
            //     $user = auth()->user();
            //     $user->review = 'off';
            //     $user->save();
            // }

            if (request()->session()->has('order_id')) {
                request()->session()->forget(['order_id', 'no_stock_items']);
            }
            session(['order_id' => $order->id]);
            session(['no_stock_items' => $noStockItems]);

            // Happy ending :)
            DB::commit();

            return redirect()->route('site.checkout.confirm');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);

            return toast()
            ->warning('Ваш заказ не создан, попробуйте еще раз')
            ->push();
        }
    }

    public function reserveStock($cartId, $shelterCart = false, $noStockItems = [
        'no_stock' => [],
        'less_stock' => [],
    ])
    {
        if ($shelterCart) {
            $cart = app('shelter')->session($cartId);
        } else {
            $cart = \Cart::session($cartId);
        }
        $functionItems = $cart->getContent();
        $items = $functionItems->all();

        try {
            DB::beginTransaction();
            foreach ($items as $item) {
                $product_1c = Product1C::where('id', $item['id'])
                ->with('product:id,name,slug', 'product.media')
                ->first();

                if ($product_1c->stock === 0) {
                    array_push($noStockItems['no_stock'], [
                        'name' => $product_1c->product->name,
                        'image' => $product_1c->product->getFirstMediaUrl('product-images', 'thumb'),
                        'unit' => $product_1c->unit_value . ' ' . $product_1c->product->unit,
                        'price' => $product_1c->price,
                    ]);

                    if ($shelterCart) {
                        app('shelter')->session($cartId)
                            ->remove($item['id']);
                    } else {
                        \Cart::session($cartId)
                        ->remove($item['id']);
                    }
                } elseif ($product_1c->stock < $item['quantity']) {
                    array_push($noStockItems['less_stock'], $item['id']);
                    if ($shelterCart) {
                        app('shelter')
                        ->session($cartId)
                        ->update($item['id'], [
                            'quantity' => [
                                'relative' => false,
                                'value' => $product_1c->stock,
                            ],
                        ]);
                    } else {
                        \Cart::session($cartId)
                        ->update($item['id'], [
                            'quantity' => [
                                'relative' => false,
                                'value' => $product_1c->stock,
                            ],
                        ]);
                    }

                    $product_1c->stock = 0;
                    $product_1c->save();
                } else {
                    $product_1c->stock = $product_1c->stock - $item['quantity'];
                    $product_1c->save();
                }
            }

            DB::commit();

            return $noStockItems;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);

            return [];
        }
    }

    public function getContactsforCheckout()
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->load('addresses');

            if ($user->pref_contact !== 0) {
                $this->contact = $user->contacts->where('id', $user->pref_contact)->first()->toArray();
            }
        }
    }

    public function getAddressesforCheckout()
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->load('addresses');

            if ($user->pref_address !== 0) {
                $this->address = $user->addresses->where('id', $user->pref_address)->first()->toArray();
            }
        }

        $this->updatedOrderType();
    }

    public function render()
    {
        return view('livewire.site.checkout.checkout')
        ->extends('layouts.clean')
        ->section('content');
    }
}
