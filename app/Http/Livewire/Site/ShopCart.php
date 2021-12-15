<?php

namespace App\Http\Livewire\Site;

use App\Models\Product1C;
use App\Traits\Discounts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class ShopCart extends Component
{
    use Discounts;
    use WireToast;

    public $cartId; // индификатор корзины
    public $shelterCartId; // индификатор корзины shelter
    public $counter; // количество товара в корзине
    public $items;
    public $shelterItems;
    public $subTotal;
    public $totalWeight;

    public $currentUrl;
    protected $listeners = [
        'addToCart',
        'increment',
        'decrement',

    ];

    public function mount()
    {
        if (request()->session()->has('cart_id')) {
            $cart_id = session('cart_id');
        }

        $this->generateId();

        $this->getCart();
    }

    public function addToCart(int $itemId, int $quantity, $catalogId = 0, int $byWeight = 0)
    {
        $cart = $this->checkShelterCategory((int) $catalogId);

        if ($cart->isEmpty()) {
            $this->add($itemId, $quantity, (int) $catalogId, $byWeight);
        } else {
            if ($cart->get($itemId) !== null) {
                if ($cart->get($itemId)->attributes->unit_value == 'на развес') {
                    $this->add($itemId, $quantity, (int) $catalogId, $byWeight);
                } else {
                    $this->increment($itemId, $quantity, (int) $catalogId, $byWeight);
                }
            } else {
                $this->add($itemId, $quantity, (int) $catalogId, $byWeight);
            }
        }

        $this->getCart();
    }

    public function add(int $itemId, int $quantity, int $catalogId = 0, int $byWeight = 0)
    {
        DB::transaction(
            function () use ($itemId, $quantity, $catalogId, $byWeight) {
                $product_1c = Product1C::with('product', 'product.categories', 'product.categories.catalog')
                  ->find($itemId);

                if ($product_1c->stock < $quantity) {
                    $quantity = $product_1c->stock;
                }

                if ((int) $product_1c->stock === 0) {
                    $this->getCart();

                    return toast()
                    ->info('Извините, товара больше нет в наличии')
                    ->push();
                } else {
                    $cart = $this->checkShelterCategory($catalogId);

                    $associatedModel = [
                        'stock' => $product_1c->stock,
                        'unit_value' => $product_1c->unit_value,
                        'image' => $product_1c->product->getFirstMediaUrl('product-images', 'thumb'),
                        'promotion_type' => $product_1c->promotion_type,
                        'promotion_price' => $product_1c->promotion_price,
                        'discount_weight' => $product_1c->product->discount_weight,
                        'vendorcode' => $product_1c->vendorcode,
                        'catalog_slug' => $product_1c->product->categories[0]->catalog->slug,
                        'category_slug' => $product_1c->product->categories[0]->slug,
                        'product_slug' => $product_1c->product->slug,
                    ];

                    $weight = $product_1c->weight;
                    if ($product_1c->unit_value == 'на развес') {
                        $weight = $byWeight;
                    }

                    $cart->add([
                        'id' => $product_1c->id,
                        'name' => $product_1c->product->name,
                        'price' => $product_1c->price,
                        'quantity' => $quantity,
                        'attributes' => [
                            'unit' => $product_1c->product->unit,
                            'weight' => $weight,
                            'unit_value' => $product_1c->unit_value,
                        ],
                        'associatedModel' => $associatedModel,
                    ]);

                    if ($product_1c->unit_value == 'на развес') {
                        $price = ($byWeight / 1000) * $product_1c->price;

                        $cart->update($product_1c->id, [
                            'quantity' => [
                                'relative' => false,
                                'value' => 1,
                            ],
                            'price' => $price,
                        ]);
                    }

                    if ($product_1c->vendorcode !== 'DISCOUNT_CARD') {
                        $this->checkAndSetPromotionDiscount($cart, $product_1c);
                    }

                    toast()
                      ->success('Товар добавлен в корзину')
                      ->push();
                }
            }
        );
    }

    public function increment(int $itemId, int $quantity = 1, int $catalogId = 0): void
    {
        $cart = $this->checkShelterCategory($catalogId);
        $item = $cart->get($itemId);

        if ($item->associatedModel['vendorcode'] === 'DISCOUNT_CARD') {
            $cart->update($item->id, [
                'quantity' => [
                    'relative' => false,
                    'value' => 1,
                ],
            ]);
        } else {
            if ($item->associatedModel['stock'] < $item->quantity + 1) {
                toast()
            ->info('Извините, товара больше нет в наличии')
            ->push();

                $this->getCart();
                $this->emitTo('product-card', 'render');
            } else {
                $quantity = $this->checkQuantity($item->quantity, $quantity);

                $cart->update($item->id, [
                    'quantity' => [
                        'relative' => false,
                        'value' => $quantity,
                    ],
                ]);

                $this->getCart();
                $this->emitTo('product-card', 'render');

                toast()
                ->success('Товар добавлен в корзину')
                ->push();
            }
        }
    }

    public function decrement(int $itemId, int $catalogId = 0): void
    {
        $cart = $this->checkShelterCategory($catalogId);

        $cart->update(
            $itemId,
            ['quantity' => -1]
        );

        $this->getCart();
        $this->emitTo('product-card', 'render');
    }

    public function delete($itemId, $catalogId = 0): void
    {
        if ($itemId) {
            $cart = $this->checkShelterCategory($catalogId);
            $cart->remove($itemId);
            $this->getCart();
            $this->emitTo('product-card', 'render');
        }
    }

    public function generateId(): void
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

    public function getCart(): void
    {
        $cart = \Cart::session($this->cartId);

        $shelterCart = app('shelter')->session($this->shelterCartId);
        $shelterCartCounter = $shelterCart->getTotalQuantity();

        $this->counter = \Cart::session($this->cartId)->getTotalQuantity() + $shelterCartCounter;

        $functionItems = $cart->getContent();
        $this->subTotal = $cart->getSubTotal() + $shelterCart->getSubTotal();

        $this->items = $functionItems->all();

        $this->removeCheckoutDiscounts($this->items, $this->cartId);

        $functionShelterItems = $shelterCart->getContent();
        $this->shelterItems = $functionShelterItems->all();

        $this->getTotalWeight($functionItems, $functionShelterItems);
    }

    public function getTotalWeight($items, $shelterItems): void
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

    public function checkShelterCategory($catalogId)
    {
        if ($catalogId === 14) { // Помоги приюту
            return $cart = app('shelter')->session($this->shelterCartId);
        } else {
            return $cart = \Cart::session($this->cartId);
        }
    }

    public function checkQuantity($itemQuantity, $quantity)
    {
        if ($itemQuantity + $quantity > 64) {
            toast()
                ->info('Если вы хотите купить оптом, свяжитесь с нами по телефону ' . config('constants.phone'))
                ->push();

            return 64;
        }

        return $itemQuantity + $quantity;
    }

    public function render()
    {
        return view('livewire.site.shop-cart');
    }
}
