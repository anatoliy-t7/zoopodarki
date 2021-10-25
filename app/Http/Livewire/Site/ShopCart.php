<?php
namespace App\Http\Livewire\Site;

use App\Models\Product1C;
use App\Traits\Discounts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Component;

class ShopCart extends Component
{
    use Discounts;

    public $cartId; // индификатор корзины
    public $shelterCartId;
    public $counter; // количество товара в корзине
    public $items;
    public $shelterItems;
    public $subTotal;
    public $totalWeight;
    public $userHasDiscount = 0;
    public $currentUrl;
    protected $listeners = [
        'addToCart',
        'increment',
        'decrement',
    ];

    public function mount()
    {
        $this->generateId();

        $this->currentUrl = Route::currentRouteName();

        if (auth()->user()) {
            $this->userHasDiscount = auth()->user()->discount;
        }

        $this->getCart();
    }

    public function addToCart($itemId, $quantity)
    {
        DB::transaction(
            function () use ($itemId, $quantity) {
                $product_1c = Product1C::with('product', 'product.categories')->find($itemId);

                if ($product_1c->stock < $quantity) {
                    $quantity = $product_1c->stock;
                }

                if ((int) $product_1c->stock === 0) {
                    $this->getCart();

                    $this->dispatchBrowserEvent(
                        'toaster',
                        ['type' => 'error', 'text' => 'Товара больше нет в наличии']
                    );

                    $this->emit('getProduct');
                } else {
                    $product_1c->decrement('stock', $quantity);

                    $associatedModel = [
                        'unit_value' => $product_1c->unit_value,
                        'image' => $product_1c->product->getFirstMediaUrl('product-images', 'thumb'),
                        'weight' => $product_1c->weight,
                        'category_id' => $product_1c->product->categories[0]->id,
                        'promotion_type' => $product_1c->promotion_type,
                        'promotion_percent' => $product_1c->promotion_percent,
                        'vendorcode' => $product_1c->vendorcode,
                    ];

                    $cartDiscountByHoliday = $this->checkDiscountByHoliday($product_1c);

                    $cartDiscountByCard = false;
                    // Дис. карта действует сразу, но сама себя не учитывает

                    if ($cartDiscountByHoliday === false && $product_1c->vendorcode !== 'DISCOUNT_CARD') {
                        $cartDiscountByCard = $this->getDiscountByCard($this->userHasDiscount);
                    }

                    if ($product_1c->promotion_type === 1) {
                        $cart = app('shelter')->session($this->shelterCartId);
                    } else {
                        $cart = \Cart::session($this->cartId);
                    }

                    if ($product_1c->vendorcode === 'DISCOUNT_CARD') {
                        $cart->add([
                            'id' => $product_1c->id,
                            'name' => $product_1c->product->name,
                            'price' => $product_1c->price,
                            'quantity' => $quantity,
                            'attributes' => [
                                'unit' => $product_1c->product->unit,
                            ],
                            'associatedModel' => $associatedModel,
                        ]);
                    } else {
                        $cart->add([
                            'id' => $product_1c->id,
                            'name' => $product_1c->product->name,
                            'price' => $product_1c->price,
                            'quantity' => $quantity,
                            'attributes' => [
                                'unit' => $product_1c->product->unit,
                            ],
                            'associatedModel' => $associatedModel,
                        ]);

                        if ($product_1c->promotion_type === 1) {
                            $cartDiscountByUcenka = $this->getDiscountByUcenka($product_1c->price - $product_1c->promotion_price);
                            $cart->addItemCondition($product_1c->id, $cartDiscountByUcenka);
                        } else {
                            $cart->addItemCondition($product_1c->id, $cartDiscountByCard);
                            $cart->addItemCondition($product_1c->id, $cartDiscountByHoliday);
                        }
                    }

                    $this->dispatchBrowserEvent('toast', ['text' => 'Товар добавлен в корзину']);

                    $this->emit('getProduct');
                }
            }
        );

        $this->updateCart();
        $this->getCart();
    }

    public function increment($itemId)
    {
        DB::transaction(
            function () use ($itemId) {
                $product_1c = Product1C::find($itemId);

                if ((int) $product_1c->stock === 0) {
                    $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'Извините, товара больше нет в наличии']);

                    $this->getCart();
                    $this->emitTo('product-card', 'render');
                } else {
                    if ($product_1c->promotion_type === 1) {
                        $cart = app('shelter')->session($this->shelterCartId);
                    } else {
                        $cart = \Cart::session($this->cartId);
                    }

                    $product_1c->decrement('stock');
                    $cart->update(
                        $itemId,
                        [
                            'quantity' => 1,
                        ]
                    );
                    $this->updateCart();
                    $this->getCart();
                    $this->emitTo('product-card', 'render');
                }

                $this->reloadCartCheckout();
            }
        );
    }

    public function decrement($itemId)
    {
        $product_1c = Product1C::find($itemId);

        if ($product_1c->promotion_type === 1) {
            $cart = app('shelter')->session($this->shelterCartId);
        } else {
            $cart = \Cart::session($this->cartId);
        }

        $cart->update(
            $itemId,
            ['quantity' => -1]
        );

        $product_1c->increment('stock', 1);

        $this->updateCart();
        $this->getCart();
        $this->emitTo('product-card', 'render');

        $this->reloadCartCheckout();
    }

    public function delete($itemId)
    {
        if ($itemId) {
            $product_1c = Product1C::find($itemId);

            if ($product_1c->promotion_type === 1) {
                $cart = app('shelter')->session($this->shelterCartId);
            } else {
                $cart = \Cart::session($this->cartId);
            }

            $cart->remove($itemId);
            Product1C::find($itemId)->increment('stock', 1);
            $this->getCart();
            $this->emitTo('product-card', 'render');

            $this->reloadCartCheckout();
        }
    }

    public function generateId()
    {
        if (request()->session()->missing('cart_id')) {
            $this->cartId = 'cart_id' . Str::random(10);
            session(['cart_id' => $this->cartId]);
        } else {
            $this->cartId = session('cart_id');
        }


        if (request()->session()->missing('shelter_cart')) {
            $this->shelterCartId = 'shelter_cart' . Str::random(10);
            session(['shelter_cart' => $this->shelterCartId]);
        } else {
            $this->shelterCartId = session('shelter_cart');
        }
    }

    public function updateCart()
    {
        $cart = \Cart::session($this->cartId);

        $items = $cart->getContent();

        if ($items) {
            $this->checkDiscountByOnePlusOne($items, $this->cartId);

            $productDiscountIdsByWeight = $this->checkDiscountByWeight($items);

            if ($productDiscountIdsByWeight !== false) {
                foreach ($items as $item) {
                    if (in_array($item['id'], $productDiscountIdsByWeight)) {
                        \Cart::session($this->cartId)->addItemCondition($item['id'], $this->getDiscountByWeight());
                    }
                }
            }
        }
    }

    public function getCart()
    {
        $cart = \Cart::session($this->cartId);

        $shelterCart = app('shelter')->session($this->shelterCartId);
        $shelterCartCounter = $shelterCart->getTotalQuantity();

        $this->counter = \Cart::session($this->cartId)->getTotalQuantity() + $shelterCartCounter;

        $items = $cart->getContent();
        $this->subTotal = $cart->getSubTotal() + $shelterCart->getSubTotal();

        $this->items = $items->all();

        $shelterItems = $shelterCart->getContent();
        $this->shelterItems = $shelterItems->all();

        $this->getTotalWeight($items, $shelterItems);
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

    public function reloadCartCheckout()
    {
        if ($this->currentUrl === 'checkout') {
            $this->emit('getCartCheckout');
        }
    }

    public function render()
    {
        return view('livewire.site.shop-cart');
    }
}
