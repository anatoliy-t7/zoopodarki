<div x-data="cart" @close.window="close(event)" class="relative">
  <button x-on:click="open" class="p-1 group focus:outline-none hover:text-orange-500 focus:text-orange-500"
    @add-to-cart.window="close(event)" aria-label="Открыть корзину">

    <svg class="text-gray-600 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <path class="text-gray-600 stroke-current group-hover:text-orange-500 focus:text-orange-500" stroke-linecap="round"
        stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"
        d="M8.5 14.25c0 1.92 1.58 3.5 3.5 3.5s3.5-1.58 3.5-3.5M8.81 2 5.19 5.63m10-3.63 3.62 3.63" />
      <path class="text-gray-600 stroke-current group-hover:text-orange-500 focus:text-orange-500" stroke-width="1.5"
        d="M2 7.85c0-1.85.99-2 2.22-2h15.56c1.23 0 2.22.15 2.22 2 0 2.15-.99 2-2.22 2H4.22C2.99 9.85 2 10 2 7.85Z" />
      <path class="text-gray-600 stroke-current group-hover:text-orange-500 focus:text-orange-500" stroke-linecap="round"
        stroke-width="1.5" d="m3.5 10 1.41 8.64C5.23 20.58 6 22 8.86 22h6.03c3.11 0 3.57-1.36 3.93-3.24L20.5 10" />
    </svg>

    @if ($counter)
      <span wire:loading.remove class="absolute top-0 left-0 right-0 flex items-center justify-center">
        <span
          class="w-8 h-8 pt-2 pl-px font-bold text-white bg-orange-500 bg-opacity-75 rounded-full">{{ $counter }}</span>
      </span>
    @endif
    <div wire:loading class="absolute top-0.5 left-0.5 flex items-center justify-center">
      <svg class="mx-auto text-orange-500 w-7 h-7 animate-spin " xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
        </path>
      </svg>
    </div>
  </button>

  <div x-cloak @click="close" x-show="openCart" x-transition.opacity
    class="fixed top-0 bottom-0 left-0 right-0 z-40 w-screen h-screen overflow-hidden bg-gray-900 bg-opacity-50 cursor-pointer pointer-events-auto">
  </div>

  <div id="cart" @swiperight="close" x-cloak x-transition :class="openCart ? 'translate-x-0' : 'translate-x-full'"
    class="fixed top-0 right-0 z-50 h-screen text-gray-700 transition-all duration-700 ease-in-out transform bg-white w-cart">

    <div class="flex flex-col justify-between h-screen ">
      <div
        class="flex items-center justify-between w-full px-6 py-3 bg-white border-b border-gray-100 md:border-transparent md:py-5 ">
        <div class="flex justify-between space-x-2">
          <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path class="text-gray-500 stroke-current " stroke-linecap="round" stroke-linejoin="round"
              stroke-miterlimit="10" stroke-width="1.5"
              d="M8.5 14.25c0 1.92 1.58 3.5 3.5 3.5s3.5-1.58 3.5-3.5M8.81 2 5.19 5.63m10-3.63 3.62 3.63" />
            <path class="text-gray-500 stroke-current " stroke-width="1.5"
              d="M2 7.85c0-1.85.99-2 2.22-2h15.56c1.23 0 2.22.15 2.22 2 0 2.15-.99 2-2.22 2H4.22C2.99 9.85 2 10 2 7.85Z" />
            <path class="text-gray-500 stroke-current " stroke-linecap="round" stroke-width="1.5"
              d="m3.5 10 1.41 8.64C5.23 20.58 6 22 8.86 22h6.03c3.11 0 3.57-1.36 3.93-3.24L20.5 10" />
          </svg>
          <span class="pt-1 pl-2">Корзина</span>
        </div>

        <div>
          <button @click="close" class="link-hover focus:outline-none focus:shadow-outline"
            aria-label="Закрыть корзину">
            <x-tabler-x class="text-gray-500 stroke-current w-7 h-7" />
          </button>
        </div>
      </div>

      <div class="relative z-50 h-full px-4 py-4 overflow-y-auto bg-white md:px-6 scrollbar">

        <div>
          @if ($items)
            <div class="text-sm divide-y divide-gray-100">
              @foreach ($items as $key => $item)
                <div class="flex items-center justify-between space-x-2 md:space-x-3">
                  <div class="w-2/12 p-2">
                    @if ($item->associatedModel['image'])
                      <a class="w-full"
                        href="{{ route('site.product', ['catalogslug' => $item->associatedModel['catalog_slug'], 'categoryslug' => $item->associatedModel['category_slug'], 'productslug' => $item->associatedModel['product_slug']]) }}">
                        <img loading="lazy" class="object-fill w-20 h-full"
                          src="{{ $item->associatedModel['image'] }}" alt="{{ $item->name }}">
                      </a>
                    @endif
                  </div>

                  <div class="flex items-center w-10/12 space-x-3">

                    <div class="flex flex-col items-start justify-between w-full py-2">
                      <a class="block w-full"
                        href="{{ route('site.product', ['catalogslug' => $item->associatedModel['catalog_slug'], 'categoryslug' => $item->associatedModel['category_slug'], 'productslug' => $item->associatedModel['product_slug']]) }}"
                        class="text-xs">
                        {{ $item->name }}
                      </a>

                      <div class="flex items-center justify-between w-full space-x-2">

                        <div class="flex items-center justify-start w-2/12 py-2 text-xs text-gray-500">
                          @if ($item->attributes->has('unit'))
                            <x-units :unit="$item->attributes['unit']" :value="$item->attributes->weight">
                            </x-units>
                          @endif
                        </div>

                        <div class="flex items-center justify-between w-10/12">

                          <div class="flex items-center justify-center p-2 leading-none">
                            @if ($item->attributes->unit_value != 'на развес')
                              @if ($item->quantity == 1)
                                <button wire:click="delete({{ $item->id }})"
                                  class="flex items-center justify-center w-8 h-8 text-gray-400 bg-gray-200 border border-gray-200 rounded-l-lg hover:bg-gray-300"
                                  aria-label="Удалить товар">
                                  <x-tabler-trash class="w-5 h-5" />
                                </button>
                              @else
                                <button wire:click="decrement({{ $item->id }})" aria-label="Уменьшить"
                                  class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 rounded-l-lg border border-gray-200 hover:bg-gray-300 {{ $item->quantity == 1 ? 'text-gray-400 cursor-not-allowed' : ' ' }} "
                                  {{ $item->quantity == 1 ? 'disabled' : ' ' }}>-</button>
                              @endif
                              <div class="flex items-center justify-center w-8 h-8 border-t border-b">
                                <div class="border-gray-200" aria-label="Количество">
                                  {{ $item->quantity }}
                                </div>
                              </div>
                              <button wire:click="increment({{ $item->id }})" aria-label="Увеличить"
                                class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 border border-gray-200 rounded-r-lg hover:bg-gray-300">+</button>

                            @else
                              <button wire:click="delete({{ $item->id }})"
                                class="p-1 text-gray-400 bg-gray-200 border border-gray-200 rounded-lg hover:bg-gray-300">
                                <x-tabler-trash class="w-5 h-5" />
                              </button>
                              <div class="pl-2 text-sm text-gray-400">на развес</div>
                            @endif
                          </div>

                          <div class="flex justify-end p-2">

                            <div>
                              @if ($item->associatedModel['promotion_type'] === 0 && (int) $item->associatedModel['discount_weight'] === 1)
                                <div class="flex items-center justify-end p-2 space-x-2">
                                  <div class="text-xs line-through">
                                    {{ RUB($item['price']) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB(discount($item['price'], 10)) }}
                                  </div>
                                </div>
                              @elseif ($item->associatedModel['promotion_type'] === 0)
                                <div class="flex items-center justify-end p-2 ">
                                  <div class="font-bold">
                                    {{ RUB($item->price) }}
                                  </div>
                                </div>
                              @elseif ($item->associatedModel['promotion_type'] === 3 || $item->associatedModel['promotion_type'] === 1)
                                <div class="flex items-center justify-end p-2 space-x-2">
                                  <div class="text-xs line-through">
                                    {{ RUB($item->associatedModel['promotion_price']) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB($item->price) }}
                                  </div>
                                </div>
                              @elseif ($item->associatedModel['promotion_type'] === 2 || $item->associatedModel['promotion_type'] === 4)
                                <div class="flex items-center justify-end p-2 space-x-2">
                                  <div class="text-xs line-through">
                                    {{ RUB($item->price) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB($item->getPriceWithConditions()) }}
                                  </div>
                                </div>
                              @endif
                            </div>

                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach

            </div>
          @endif
        </div>
        @if ($shelterItems)
          <div class="px-6 py-2 -mx-6 bg-gray-50">
            <div class="py-2">"Помоги приюту"</div>
            <div class="text-sm divide-y divide-gray-100">
              @foreach ($shelterItems as $key => $shelterItem)
                <div class="flex items-center justify-between space-x-2 md:space-x-3">
                  <div class="w-2/12 p-2">
                    @if ($shelterItem->associatedModel['image'])
                      <a class="w-full"
                        href="{{ route('site.product', ['catalogslug' => $shelterItem->associatedModel['catalog_slug'], 'categoryslug' => $shelterItem->associatedModel['category_slug'], 'productslug' => $shelterItem->associatedModel['product_slug']]) }}">
                        <img loading="lazy" class="object-fill w-20 h-full"
                          src="{{ $shelterItem->associatedModel['image'] }}" alt="{{ $shelterItem->name }}">
                      </a>
                    @endif
                  </div>

                  <div class="flex items-center w-10/12 space-x-3">

                    <div class="flex flex-col items-start justify-between w-full py-2">
                      <a class="block w-full"
                        href="{{ route('site.product', ['catalogslug' => $shelterItem->associatedModel['catalog_slug'], 'categoryslug' => $shelterItem->associatedModel['category_slug'], 'productslug' => $shelterItem->associatedModel['product_slug']]) }}"
                        class="text-xs">
                        {{ $shelterItem->name }}
                      </a>

                      <div class="flex items-center justify-between w-full space-x-2">

                        <div class="flex items-center justify-start w-2/12 py-2 text-xs text-gray-500">
                          @if ($shelterItem->attributes->has('unit'))
                            <x-units :unit="$item->attributes['unit']" :value="$item->attributes->weight">
                            </x-units>
                          @endif
                        </div>

                        <div class="flex items-center justify-between w-10/12">

                          <div class="flex items-center justify-center p-2 leading-none">
                            @if ($shelterItem->attributes->unit_value != 'на развес')
                              @if ($shelterItem->quantity == 1)
                                <button wire:click="delete({{ $shelterItem->id }}, {{ $shelterCatalogId }})"
                                  class="flex items-center justify-center w-8 h-8 text-gray-400 bg-gray-200 border border-gray-200 rounded-l-lg hover:bg-gray-300"
                                  aria-label="Удалить товар">
                                  <x-tabler-trash class="w-5 h-5" />
                                </button>
                              @else
                                <button wire:click="decrement({{ $shelterItem->id }}, {{ $shelterCatalogId }})"
                                  aria-label="Уменьшить"
                                  class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 rounded-l-lg border border-gray-200 hover:bg-gray-300 {{ $shelterItem->quantity == 1 ? 'text-gray-400 cursor-not-allowed' : ' ' }} "
                                  {{ $shelterItem->quantity == 1 ? 'disabled' : ' ' }}>-</button>
                              @endif
                              <div class="flex items-center justify-center w-8 h-8 border-t border-b">
                                <div class="border-gray-200" aria-label="Количество">
                                  {{ $shelterItem->quantity }}
                                </div>
                              </div>
                              <button wire:click="increment({{ $shelterItem->id }}, 1, {{ $shelterCatalogId }})"
                                aria-label="Увеличить"
                                class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 border border-gray-200 rounded-r-lg hover:bg-gray-300">+</button>

                            @else
                              <button wire:click="delete({{ $shelterItem->id }}, {{ $shelterCatalogId }})"
                                class="p-1 text-gray-400 bg-gray-200 border border-gray-200 rounded-lg hover:bg-gray-300">
                                <x-tabler-trash class="w-5 h-5" />
                              </button>
                              <div class="pl-2 text-sm text-gray-400">на развес</div>
                            @endif
                          </div>

                          <div class="flex justify-end p-2">

                            <div>
                              @if ($shelterItem->associatedModel['promotion_type'] === 0)
                                <div class="flex items-center justify-end p-2 ">
                                  <div class="font-bold">
                                    {{ RUB($shelterItem->price) }}
                                  </div>
                                </div>
                              @elseif ($shelterItem->associatedModel['promotion_type'] === 1 || $shelterItem->associatedModel['promotion_type'] === 3)
                                <div class="flex items-center justify-end p-2 space-x-2">
                                  <div class="text-xs line-through">
                                    {{ RUB($shelterItem->associatedModel['promotion_price']) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB($shelterItem->price) }}
                                  </div>
                                </div>
                              @elseif ($shelterItem->associatedModel['promotion_type'] === 2 || $shelterItem->associatedModel['promotion_type'] === 4)
                                <div class="flex items-center justify-end p-2 space-x-2">
                                  <div class="text-xs line-through">
                                    {{ RUB($shelterItem->price) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB($shelterItem->getPriceWithConditions()) }}
                                  </div>
                                </div>
                              @endif
                            </div>

                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach

            </div>
          </div>
        @endif

        @if (count($items) === 0 and count($shelterItems) === 0)
          <div class="flex justify-center">
            <div class="pt-10 text-center text-gray-600">
              <img loading="lazy" class="Корзина пуста" src="/assets/img/hangry-cat.svg" alt="">
              <div class="pt-10">Ваша корзина пуста</div>
            </div>
          </div>
        @endif

      </div>

      @if ($items)
        <div class="w-full bg-white border-t border-gray-100 h-96 md:h-auto md:border-transparent">

          <div class="px-6 py-3 space-y-2 bg-gray-50">

            <div class="flex items-center justify-between space-x-2 ">

              <div class="flex items-center justify-center space-x-2 text-sm ">
                <span>Всего:</span>
                <span class="text-base font-bold">{{ RUB($subTotal) }}</span>
              </div>

              @if ($totalWeight > 0)
                <div class="flex items-center justify-center space-x-2 text-sm">
                  <div>Вес:</div>
                  <div class="text-base font-bold">{{ kg($totalWeight) }}</div>
                </div>
              @endif

              <div class="flex items-center justify-center space-x-2 text-sm">
                <span>Кол-во:</span>
                <span class="text-base font-bold">{{ $counter }} шт</span>
              </div>

            </div>

            <div class="text-xs leading-tight text-gray-500">Доп. скидки рассчитываются при оформлении заказа.</div>

          </div>

          <div class="px-6 py-2 md:py-4">
            <a href="{{ route('checkout') }}"
              class="w-full px-4 py-3 font-bold leading-snug text-center text-white uppercase bg-orange-400 rounded-lg btn hover:bg-orange-500">
              Оформить заказ
            </a>
          </div>

        </div>
      @endif

    </div>

  </div>

  <script>
    document.addEventListener('alpine:initializing', () => {
      Alpine.data('cart', () => ({
        cartWindow: new TouchSweep(cart),
        body: document.body,
        openCart: false,
        open() {
          this.openCart = true
          this.body.classList.add('overflow-hidden', 'pr-4');

        },
        close() {
          this.openCart = false
          this.body.classList.remove('overflow-hidden', 'pr-4');
        },
      }))
    })
  </script>
  <script>
    document.addEventListener("keydown", function(e) {
      if (e.keyCode == 27) {
        e.preventDefault();
        var event = new CustomEvent('close');
        window.dispatchEvent(event);
      }
    }, false);
  </script>

</div>
