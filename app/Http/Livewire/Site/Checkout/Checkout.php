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
    public $deliveryCostToShelter = 0; // TODO высчитать доставку
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
    protected $listeners = [
        'setPickupStore',
        'getContactFromComponent',
        'getAddressFromComponent',
        'getCartCheckout'
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
                    $this->getContacts();
                    $this->getAddresses();
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

                // TODO $this->deliveryCostToShelter ?
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

    public function getContactFromComponent($contact)
    {
        $this->contact = $contact;
    }

    public function getAddressFromComponent($address)
    {
        $this->address = $address;


        // TODO не обновляет
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

        if (count($this->shelterItems) > 0) {
            $this->totalAmount = $this->totalAmount + $this->deliveryCostToShelter;
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

        $this->getTotalWeight($functionItems, $functionShelterItems);

        // Check and set discounts
        if ($functionItems) {
            $this->productDiscountIdsByWeight = $this->checkDiscountByWeight($this->cartId, $functionItems);

            $this->getDiscountByCard($this->userHasDiscount, $this->cartId, $functionItems);
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
                $itemWeight = $item->associatedModel['weight'] * $item->quantity;
                $this->totalWeight->push($itemWeight);
            }
        }

        if (count($shelterItems) > 0) {
            foreach ($shelterItems as $shelterItem) {
                $itemWeight = $shelterItem->associatedModel['weight'] * $shelterItem->quantity;
                $this->totalWeight->push($itemWeight);
            }
        }

        $this->totalWeight = $this->totalWeight->sum();
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
            ->warning('Дорогой покупатель, в настоящее время мы доставляем только в пределах КАД,
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
        } else {
            $this->validate([
                'pickupStore' => 'required',
            ]);

            $this->address = 'Магазин из которого заберут: ' . $this->pickupStore;
        }

        $noStockItems = [];
        $noStockItems = $this->reserveStock($this->cartId);
        array_merge($noStockItems, $this->reserveStock($this->shelterCartId, true));

        $this->getCartCheckout();

        try {
            DB::beginTransaction();

            $this->userId = auth()->user()->id;

            $orderNumber = getNextOrderNumber();

            if (0 !== $this->firstOrder) {
                $this->orderComment = 'Скидка за первый заказ -'
                . $this->firstOrder . ' рублей | ' . $this->orderComment;
            }

            // if (auth()->user()->review === 'on') {
            //     $this->orderComment = 'Скидка 2% за отзыв | ' . $this->orderComment;
            // }

            if (count($this->shelterItems) > 0) {
                $this->deliveryCost = $this->deliveryCost + $this->deliveryCostToShelter;
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $this->userId,
                'amount' => $this->totalAmount,
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
                'address' => $this->address,
                'order_comment' => $this->orderComment . ' | ',
            ]);

            foreach ($this->items as $item) {
                $product1c = Product1C::where('id', $item->id)
                    ->with('product', 'product.unit')
                    ->first();

                $discountComment = '';

                if (0 == $item->associatedModel['promotion_type']) {
                    $discountComment = '';
                } elseif (1 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Акция "Уценка"';
                } elseif (2 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Акция "1+1"';
                } elseif (3 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Акция поставщика, -' . $product1c->promotion_percent;
                } elseif (4 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Праздничные скидки, -' . $product1c->promotion_percent;
                }

                $unit = '';
                if ($product1c->product->unit()->exists()) {
                    $unit = $product1c->unit_value . ' ' . $product1c->product->unit->name;
                }

                OrderItem::create([
                    'name' => $item->name,
                    'uuid' => $product1c->uuid,
                    'barcode' => $product1c->barcode,
                    'vendorcode' => $product1c->vendorcode,
                    'quantity' => $item->quantity,
                    'unit' => $unit,
                    'price' => $item->price,
                    'amount' => $item->getPriceSumWithConditions(),
                    'order_id' => $order->id,
                    'product_id' => $product1c->product->id,
                    'discount_comment' => $discountComment,
                    'discount' => round($item->getPriceSum() - $item->getPriceSumWithConditions()),
                ]);
            }

            if (count($this->shelterItems) > 0) {
                foreach ($this->shelterItems as $shelterItem) {
                    $product1c = Product1C::find($shelterItem->id);

                    $discountComment = 'Уценка "Помоги приюту"';

                    OrderItem::create([
                    'name' => $shelterItem->name,
                    'uuid' => $product1c->uuid,
                    'barcode' => $product1c->barcode,
                    'vendorcode' => $product1c->vendorcode,
                    'quantity' => $shelterItem->quantity,
                    'unit' => $unit,
                    'price' => $shelterItem->price,
                    'amount' => $shelterItem->getPriceSumWithConditions(),
                    'order_id' => $order->id,
                    'product_id' => $product1c->product->id,
                    'discount_comment' => $discountComment,
                    'discount' => round($shelterItem->getPriceSum() - $shelterItem->getPriceSumWithConditions()),
                    ]);
                }
            }

            $order->load('items');

            // if (auth()->user()->review === 'on') {
            //     $user = auth()->user();
            //     $user->review = 'off';
            //     $user->save();
            // }

            session(['order_id' => $order->id]);
            session(['no_stock_items' => $noStockItems]);

            // Happy ending :)
            DB::commit();

            return redirect()->route('checkout.confirm');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return toast()
            ->warning('Ваш заказ не создан, попробуйте еще раз')
            ->push();
            ;
        }
    }

    public function reserveStock($cartId, $shelterCart = false)
    {
        if ($shelterCart) {
            $cart = app('shelter')->session($cartId);
        } else {
            $cart = \Cart::session($cartId);
        }
        $functionItems = $cart->getContent();
        $items = $functionItems->all();

        $noStockItems = [];

        DB::transaction(
            function () use ($items, $shelterCart, $cartId, $noStockItems) {
                foreach ($items as $item) {
                    $product_1c = Product1C::find($item['id']);
                    if ($product_1c->stock === 0) {
                        array_push($noStockItems, [
                          'id' =>  $item['id'],
                          'name' =>  $item['name'],
                          'stock' =>  0,
                        ]);

                        if ($shelterCart) {
                            app('shelter')->session($cartId)
                                ->remove($item['id']);
                        } else {
                            \Cart::session($cartId)
                                ->remove($item['id']);
                        }

                        session()->flash(
                            'message',
                            'К сожалению, к настоящему моменту кто-то уже купил товар, выделенный ниже серым. Вы можете продолжить покупку без этого товара, заказать его или вернуться обратно в магазин. Надеемся на ваше понимание:) Спасибо!'
                        );
                    } elseif ($product_1c->stock < $item['quantity']) {
                        array_push($noStockItems, [
                          'id' =>  $item['id'],
                          'name' =>  $item['name'],
                          'stock' =>  $product_1c->stock,
                        ]);

                        if ($shelterCart) {
                            app('shelter')
                                ->session($cartId)
                                ->update($item['id'], array(
                                'quantity' => $product_1c->stock,
                                ));
                        } else {
                            \Cart::session($cartId)
                                ->update($item['id'], array(
                                    'quantity' => $product_1c->stock,
                                ));
                        }

                        $product_1c->stock = 0;
                        $product_1c->save();

                        session()->flash(
                            'message',
                            'К сожалению, к настоящему моменту кто-то уже купил товар, выделенный ниже красным. Мы уменьшили количество до имеющегося у нас на складе. Надеемся на ваше понимание:) Спасибо!'
                        );
                    } else {
                        $product_1c->stock = $product_1c->stock - $item['quantity'];
                        $product_1c->save();
                    }
                }
            }
        );

        return $noStockItems;
    }

    public function getContacts()
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->load('addresses');

            if ($user->pref_contact !== 0) {
                $this->contact = $user->contacts->where('id', $user->pref_contact)->first()->toArray();
            }
        }
    }

    public function getAddresses()
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->load('addresses');

            if ($user->pref_address !== 0) {
                $this->address = $user->addresses->where('id', $user->pref_address)->first()->toArray();
            }
        }
    }

    public function render()
    {
        return view('livewire.site.checkout.checkout')
        ->extends('layouts.clean')
        ->section('content');
    }
}
