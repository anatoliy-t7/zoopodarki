<?php
namespace App\Http\Livewire\Site;

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
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;
use YooKassa\Client;

class Checkout extends Component
{
    use Discounts;
    use WireToast;

    use Delivery;

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
    public $discount = 0;
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
    public $userHasDiscountOnReview = false;
    protected $listeners = [
        'setPickupStore',
        'getContactFromComponent',
        'getAddressFromComponent',
        'getCartCheckout'
    ];

    public function mount()
    {

        if (request()->session()->missing('cart_id')) {
             redirect()->route('site.home');
        } else {
            $this->cartId = session('cart_id');
            $this->shelterCartId = session('shelter_cart');

            if (\Cart::session($this->cartId)->isEmpty()) {
                redirect('/');
            } else {
                $this->getCartCheckout();

                $this->generateDatesForDelivery();

                if (auth()->user()) {
                    $this->discount = auth()->user()->discount;

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
        $this->render(); // TODO not working
    }

    public function hydrate()
    {
        $this->getCartCheckout();
    }

    public function calcTotalAmount()
    {
        if (0 == $this->orderType) {
            $this->totalAmount = $this->subTotal + $this->deliveryCost;
        } else {
            $this->totalAmount = $this->subTotal;
        }

        if (count($this->shelterItems) > 0) {
            $this->totalAmount = $this->totalAmount + $this->deliveryCostToShelter;
        }
    }

    public function getCartCheckout()
    {
        $cart = \Cart::session($this->cartId);

        if (auth()->user()) {
            $this->checkIfFirstOrder($cart->getSubTotal(), $this->cartId);

            if ($this->checkIfUserHasDiscountOnReview($this->cartId)) {
                $this->userHasDiscountOnReview = true;
            } else {
                $this->userHasDiscountOnReview = false;
            }
        }

        $shelterCart = app('shelter')->session($this->shelterCartId);
        $shelterCartCounter = $shelterCart->getTotalQuantity();

        $this->counter = \Cart::session($this->cartId)->getTotalQuantity() + $shelterCartCounter;

        $functionItems = $cart->getContent();
        $this->subTotal = $cart->getSubTotal() + $shelterCart->getSubTotal();

        $this->items = $functionItems->all();

        $functionShelterItems = $shelterCart->getContent();
        $this->shelterItems = $functionShelterItems->all();

        $this->getTotalWeight($functionItems, $functionShelterItems);

        if (auth() && round($this->subTotal) >= 2000 && 'first' === auth()->user()->extra_discount) {
            $this->firstOrder = 200;
        } else {
            $this->firstOrder = 0;
        }

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
            return $this->dispatchBrowserEvent('auth');
        }

        if ($this->deliveryCost === false) {
            return toast()->warning('Дорогой покупатель, в настоящее время мы доставляем только в пределах КАД, но совсем скоро начнем доставку и за его пределами!')->push();
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

        $this->getCartCheckout();

        DB::transaction(function () {
            $this->userId = auth()->user()->id;

            $status = 'pending_payment';

            // 1 cash
            if (1 === $this->orderPaymentType) {
                $status = 'processing';
            }

            $orderNumber = getNextOrderNumber();

            if (0 !== $this->firstOrder) {
                $this->orderComment = 'Скидка за первый заказ -'
                . $this->firstOrder . ' рублей | ' . $this->orderComment;
            }

            if (auth()->user()->review === 'on') {
                $this->orderComment = 'Скидка 2% за отзыв | ' . $this->orderComment;
            }

            if (count($this->shelterItems) > 0) {
                $this->deliveryCost = $this->deliveryCost + $this->deliveryCostToShelter;
            }

            $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $this->userId,
            'amount' => $this->totalAmount,
            'quantity' => $this->counter,
            'weight' => $this->totalWeight,
            'status' => $status,
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
                $product1c = Product1C::find($item->id);

                $discountComment = '';

                if (0 == $item->associatedModel['promotion_type']) {
                    $discountComment = '';
                } elseif (2 == $item->associatedModel['promotion_type']) {
                    $discountComment = '1+1';
                } elseif (3 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Акция поставщика, скидка -' . $product1c->promotion_percent;
                } elseif (4 == $item->associatedModel['promotion_type']) {
                    $discountComment = 'Праздничные, скидка -' . $product1c->promotion_percent;
                }

                OrderItem::create([
                'name' => $item->name,
                'uuid' => $product1c->uuid,
                'barcode' => $product1c->barcode,
                'vendorcode' => $product1c->vendorcode,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'amount' => $item->getPriceSumWithConditions(),
                'order_id' => $order->id,
                'product_id' => $item->id,
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
                    'price' => $shelterItem->price,
                    'amount' => $shelterItem->getPriceSumWithConditions(),
                    'order_id' => $order->id,
                    'product_id' => $shelterItem->id,
                    'discount_comment' => $discountComment,
                    'discount' => round($shelterItem->getPriceSum() - $shelterItem->getPriceSumWithConditions()),
                    ]);
                }
            }

            session(['order_id' => $order->id]);

            \Cart::session($this->cartId)->clear();
            app('shelter')->session($this->shelterCartId)->clear();

            if (auth()->user()->review === 'on') {
                $user = auth()->user();
                $user->review = 'off';
                $user->save();
            }

            if (0 === $this->orderPaymentType) {
                $this->payCreate($order);
            } else {
                redirect()->route('site.payment.cash', ['order_id' => $order->id]);
            }
        });

        toast()
        ->warning('Ваш заказ не создан, попробуйте еще раз')
        ->push();
    }

    public function payCreate($order)
    {
        $clientId = config('services.yookassa.client_id');
        $clientSecret = config('services.yookassa.client_secret');

        $client = new Client();
        $client->setAuth($clientId, $clientSecret);

        $payment = $client->createPayment([
        'amount' => [
            'value' => $order->amount,
            'currency' => 'RUB',
        ],
        'description' => 'Заказ ' . $order->order_number,
        'confirmation' => [
            'type' => 'redirect',
            'locale' => 'ru_RU',
            'return_url' => route('site.payment.status'),
        ],
        'capture' => true,
        'metadata' => [
            'order_id' => $order->id,
        ],
        ], uniqid('', true));

        return redirect()->to($payment->getConfirmation()->getConfirmationUrl());
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
        return view('livewire.site.checkout')
        ->extends('layouts.app')
        ->section('content');
    }
}
