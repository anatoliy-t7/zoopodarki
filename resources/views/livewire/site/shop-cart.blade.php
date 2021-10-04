<div x-data="cart" @close.window="close(event)" class="relative">
  <button x-on:click="open" class="p-1 focus:outline-none hover:text-orange-500 focus:text-orange-500">
    <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24">
      <path
        d="M14,18a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,14,18Zm-4,0a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,10,18ZM19,6H17.62L15.89,2.55a1,1,0,1,0-1.78.9L15.38,6H8.62L9.89,3.45a1,1,0,0,0-1.78-.9L6.38,6H5a3,3,0,0,0-.92,5.84l.74,7.46a3,3,0,0,0,3,2.7h8.38a3,3,0,0,0,3-2.7l.74-7.46A3,3,0,0,0,19,6ZM17.19,19.1a1,1,0,0,1-1,.9H7.81a1,1,0,0,1-1-.9L6.1,12H17.9ZM19,10H5A1,1,0,0,1,5,8H19a1,1,0,0,1,0,2Z" />
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
    class="fixed top-0 bottom-0 left-0 right-0 z-40 w-screen h-screen overflow-hidden bg-gray-900 bg-opacity-50 pointer-events-auto">
  </div>

  <div x-cloak x-transition :class="openCart ? 'translate-x-0' : 'translate-x-full'"
    class="fixed top-0 right-0 z-50 h-screen max-w-xs text-gray-700 transition-all duration-700 ease-in-out transform bg-white w-cart">

    <div class="flex flex-col justify-between h-screen ">

      <div class="flex items-center justify-between w-full p-4 bg-white border-b border-gray-100 ">
        <div class="flex justify-between">
          <span class="___class_+?14___">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="10" cy="20.5" r="1" />
              <circle cx="18" cy="20.5" r="1" />
              <path d="M2.5 2.5h3l2.7 12.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6l1.6-8.4H7.1" />
            </svg>
          </span>
          <span class="pt-1 pl-2">Корзина</span>
        </div>

        <div>
          <button @click="close" class="link-hover focus:outline-none focus:shadow-outline">
            <x-tabler-x class="w-6 h-6 text-gray-500 stroke-current" />
          </button>
        </div>
      </div>

      <div class="relative h-full px-4 py-4 overflow-y-auto md:px-6">

        <div>
          @if ($items)
            <div class="text-sm divide-y divide-gray-100">
              @foreach ($items as $key => $item)
                <div
                  class="relative flex flex-col items-center justify-between space-y-3 md:flex-row md:space-y-0 md:space-x-3">

                  <div class="w-24 p-2">
                    @if ($item->associatedModel['image'])
                      <img loading="lazy" class="object-fill w-20 h-full" src="{{ $item->associatedModel['image'] }}"
                        alt="">
                    @endif

                  </div>

                  <div class="flex items-center w-full md:space-x-3">

                    <div class="flex flex-col items-start justify-between w-full py-2">

                      <div class="w-full">
                        <div class="text-xs">
                          {{ $item->name }}
                        </div>
                      </div>

                      <div class="flex items-center justify-between w-full space-x-2 ">

                        <div class="flex items-center justify-start py-2 text-xs text-gray-500">

                          @if ($item->attributes->has('unit'))
                            <x-units :unit="$item->attributes['unit']" :value="$item->associatedModel['unit_value']">
                            </x-units>
                          @endif

                        </div>

                        <div class="flex items-center justify-end">
                          <div class="flex justify-center p-2 leading-none">
                            @if ($item->quantity == 1)
                              <button wire:click="delete({{ $item->id }})"
                                class="px-1 text-gray-400 bg-gray-200 border border-gray-200 hover:bg-gray-300">
                                <x-tabler-trash />
                              </button>
                            @else
                              <button wire:click="decrement({{ $item->id }})"
                                class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 border border-gray-200 hover:bg-gray-300 {{ $item->quantity == 1 ? 'text-gray-400 cursor-not-allowed' : ' ' }} "
                                {{ $item->quantity == 1 ? 'disabled' : ' ' }}>-</button>
                            @endif
                            <span class="w-8 h-8 p-2 px-3 border-t border-b border-gray-200">
                              {{ $item->quantity }}
                            </span>
                            <button wire:click="increment({{ $item->id }})"
                              class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 border border-gray-200 hover:bg-gray-300">+</button>
                          </div>

                          <div class="flex justify-end p-2 w-60">

                            <div>
                              @if ($item->associatedModel['promotion_type'] === 0)
                                <div class="flex items-center justify-end p-2 w-60">
                                  <div class="font-bold">
                                    {{ RUB($item->getPriceSum()) }}
                                  </div>
                                </div>
                              @elseif ($item->associatedModel['promotion_type'] === 2)
                                <div class="flex items-center justify-end p-2 space-x-2 w-60">
                                  <div class="text-xs line-through">
                                    {{ RUB($item->getPriceSum()) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB($item->getPriceSumWithConditions()) }}</div>
                                </div>
                              @elseif ($item->associatedModel['promotion_type'] === 3)
                                <div class="flex items-center justify-end p-2 space-x-2 w-60">
                                  <div class="text-xs line-through">
                                    {{ RUB(discountRevert($item->getPriceSum(), $item->associatedModel['promotion_percent'])) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB($item->getPriceSumWithConditions()) }}</div>
                                </div>
                              @elseif ($item->associatedModel['promotion_type'] === 4)
                                <div class="flex items-center justify-end p-2 space-x-2 w-60">
                                  <div class="text-xs line-through">
                                    {{ RUB($item->getPriceSum()) }}
                                  </div>
                                  <div class="font-bold text-orange-500">
                                    {{ RUB($item->getPriceSumWithConditions()) }}</div>
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
              @foreach ($shelterItems as $shelterItem)
                <div
                  class="relative flex flex-col items-center justify-between space-y-3 md:flex-row md:space-y-0 md:space-x-3">

                  <div class="w-24 p-2">
                    @if ($shelterItem->associatedModel['image'])
                      <img loading="lazy" class="object-fill w-20 h-full"
                        src="{{ $shelterItem->associatedModel['image'] }}" alt="">
                    @endif

                  </div>

                  <div class="flex items-center w-full md:space-x-3">

                    <div class="flex flex-col items-start justify-between w-full py-2">

                      <div class="w-full">
                        <div class="text-xs">
                          {{ $shelterItem->name }}
                        </div>
                      </div>

                      <div class="flex items-center justify-between w-full space-x-2 ">

                        <div class="flex items-center justify-start py-2 text-xs text-gray-500">

                          @if ($shelterItem->attributes->has('unit'))
                            <x-units :unit="$shelterItem->attributes['unit']"
                              :value="$shelterItem->associatedModel['unit_value']">
                            </x-units>
                          @endif

                        </div>

                        <div class="flex items-center justify-end">
                          <div class="flex justify-center p-2 leading-none">
                            @if ($shelterItem->quantity == 1)
                              <button wire:click="delete({{ $shelterItem->id }})"
                                class="px-1 text-gray-400 bg-gray-200 border border-gray-200 hover:bg-gray-300">
                                <x-tabler-trash />
                              </button>
                            @else
                              <button wire:click="decrement({{ $shelterItem->id }})"
                                class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 border border-gray-200 hover:bg-gray-300 {{ $shelterItem->quantity == 1 ? 'text-gray-400 cursor-not-allowed' : ' ' }} "
                                {{ $shelterItem->quantity == 1 ? 'disabled' : ' ' }}>-</button>
                            @endif
                            <span class="w-8 h-8 p-2 px-3 border-t border-b border-gray-200">
                              {{ $shelterItem->quantity }}
                            </span>
                            <button wire:click="increment({{ $shelterItem->id }})"
                              class="w-8 h-8 px-2 pb-2 text-xl bg-gray-200 border border-gray-200 hover:bg-gray-300">+</button>
                          </div>

                          <div class="flex items-center justify-end p-2 space-x-2 w-60">
                            <div class="text-xs line-through">
                              {{ RUB($shelterItem->getPriceSum()) }}
                            </div>
                            <div class="font-bold text-orange-500">
                              {{ RUB($shelterItem->getPriceSumWithConditions()) }}</div>
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
        <div class="w-full pb-6 bg-white md:pb-8">

          <div class="flex items-center justify-between px-5 py-3 space-x-2 bg-gray-50">

            <div class="flex flex-col justify-center text-sm md:items-center md:flex-row md:space-x-2">
              <span>Всего:</span>
              <span class="text-base font-bold">{{ RUB($subTotal) }}</span>

              @if (!Cart::session($cartId)->getConditions()->isEmpty())
                <div class="text-xs text-gray-400">(Со скидкой)</div>
              @endif
            </div>

            @if ($totalWeight > 0)
              <div class="flex items-center justify-between text-sm md:space-x-2">
                <div>Вес:</div>
                <div class="text-base font-bold">{{ kg($totalWeight) }}</div>
              </div>
            @endif

            <div class="flex flex-col justify-between text-sm md:items-center md:flex-row md:space-x-2">
              <span>Кол-во:</span>
              <span class="text-base font-bold">{{ $counter }} шт</span>
            </div>

          </div>

          <div class="px-5 pt-8">
            <a href="{{ route('checkout') }}"
              class="px-3 py-2 font-semibold leading-snug text-center text-white bg-orange-500 rounded-lg md:px-4 md:py-3 md:text-left hover:bg-orange-600">
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
