@section('title')
  Оформление заказа
@endsection

<div class="py-6 space-y-4">

  <div class="flex items-start justify-start max-w-3xl px-4 space-x-12">
    <x-logo />
    <div class="flex items-center justify-between w-full pt-3">
      <div class="relative block w-full max-w-2xl">
        <div class="absolute -top-8" style="left: calc(100% - 1.7rem)">
          <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path class="text-green-500 stroke-current" stroke-linecap="round" stroke-linejoin="round"
              stroke-miterlimit="10" stroke-width="1.5"
              d="M2 2h1.74c1.08 0 1.93.93 1.84 2l-.83 9.96a2.796 2.796 0 0 0 2.79 3.03h10.65c1.44 0 2.7-1.18 2.81-2.61l.54-7.5c.12-1.66-1.14-3.01-2.81-3.01H5.82M16.25 22a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5zm-8 0a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5zM9 8h12" />
          </svg>
        </div>
        <div class="w-full bg-white rounded-full">
          <div class="h-2 text-xs leading-none text-center text-white transition-all bg-green-500 rounded-full"
            style="width: 100%">
          </div>
        </div>
        <div class="flex items-center justify-between py-1 text-xs">
          <div>Авторизация</div>
          <div>Оформление</div>
          <div class="font-bold">Подтверждение</div>
        </div>
      </div>
    </div>
  </div>

  <h3 class="block px-4 text-2xl font-semibold">Подтверждение заказа</h3>

  <div class="justify-between block md:flex md:space-x-6">
    <div class="block w-full px-8 py-6 space-y-6 bg-white md:w-8/12 rounded-2xl">

      <div class="space-y-4">
        <div class="text-lg font-bold">Ваш заказ</div>

        @if (!empty($noStockItems) && !empty($noStockItems['less_stock']))
          <div class="text-sm text-red-500">
            К сожалению, к настоящему моменту кто-то уже купил товар, <b>выделенный ниже красным</b>. Мы уменьшили
            количество
            до
            имеющегося у нас на складе. Надеемся на ваше понимание:) Спасибо!
          </div>
        @endif
        @if ($order->items()->exists())
          <div class="flex flex-col justify-between w-full pb-8 space-y-4">
            @foreach ($order->items as $key => $item)
              <div
                class="flex items-center justify-between p-2 rounded-lg space-x-6
              {{ !empty($noStockItems) && in_array($item['product_id'], $noStockItems['less_stock']) ? 'border-red-200 border' : '' }}
              ">
                <div>
                  <a class="w-full" target="_blank"
                    href="{{ route('site.product', [$item->product1c->product->categories[0]->catalog->slug, $item->product1c->product->categories[0]->slug, $item->product1c->product->slug]) }}">
                    <img loading="lazy" class="object-fill w-16 h-full"
                      src="{{ $item->product1c->product->getFirstMediaUrl('product-images', 'thumb') }}"
                      alt="{{ $item->name }}">
                  </a>
                </div>

                <div class="w-full space-y-4 text-gray-600">
                  <a class="block w-full hover:underline" target="_blank"
                    href="{{ route('site.product', [$item->product1c->product->categories[0]->catalog->slug, $item->product1c->product->categories[0]->slug, $item->product1c->product->slug]) }}">
                    {{ $item->name }}
                  </a>

                  <div class="flex justify-between text-xs text-gray-500">
                    <div class="flex justify-start space-x-4">
                      @if ($item->unit)
                        {{ kg($item->unit) }}
                      @endif
                    </div>
                    <div class="flex space-x-4 items-centerjustify-end">
                      <div
                        class="{{ !empty($noStockItems) && in_array($item['product_id'], $noStockItems['less_stock']) ? 'text-red-500 font-bold' : '' }}">
                        {{ $item->quantity }} шт x</div>
                      <div class="flex justify-end ">
                        <div class="font-bold ">
                          {{ RUB($item->price) }}
                        </div>
                      </div>
                    </div>

                  </div>

                </div>
              </div>

            @endforeach
          </div>
        @endif
        @if (!empty($noStockItems) && !empty($noStockItems['no_stock']))
          <div class="text-sm text-red-500">
            К сожалению, к настоящему моменту кто-то уже купил этот товар. Вы можете продолжить
            покупку
            без этого товара, заказать его или вернуться обратно в магазин. Надеемся на ваше понимание:) Спасибо!
          </div>

          <div class="flex flex-col justify-between w-full space-y-4">
            @foreach ($noStockItems['no_stock'] as $itemNoStock)
              <div class="flex items-center justify-start p-2 space-x-6 rounded-lg opacity-50 ">
                <div>
                  <img loading="lazy" class="object-fill w-16 h-full" src="{{ $itemNoStock['image'] }}"
                    alt="{{ $itemNoStock['name'] }}">
                </div>
                <div class="flex flex-col w-full space-y-4">
                  <div class="w-full space-y-4 text-gray-600">
                    {{ $itemNoStock['name'] }}
                  </div>
                  <div class="flex justify-between text-xs text-gray-500">
                    <div>
                      {{ $itemNoStock['unit'] }}
                    </div>
                    <div class="flex space-x-4 items-centerjustify-end">
                      <div>Нет в наличии</div>
                      <div class="font-bold ">
                        {{ RUB($itemNoStock['price']) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

        @endif
      </div>


    </div>

    <div class="block w-full p-6 space-y-8 md:w-4/12">
      <div class="space-y-4">

        <div class="flex space-x-2 text-sm">
          <span class="w-full md:w-4/6">Количество</span>
          <span class="flex justify-end w-full font-bold md:w-8/6">{{ $order->quantity }} шт</span>
        </div>

        @if ($order->weight)
          <div class="flex space-x-2 text-sm">
            <span class="w-full md:w-4/6">Вес</span>
            <span class="flex justify-end w-full font-bold md:w-8/6">{{ kg($order->weight) }}</span>
          </div>
        @endif

        @if ($order->order_type == 1 && $order->date)
          <div class="flex space-x-2 text-sm">
            <span class="w-full md:w-4/6">Самовывоз на</span>
            <span class="flex justify-end w-full font-bold md:w-8/6">{{ simpleDate($order->date) }}</span>
          </div>
        @endif
        @if ($order->order_type == 1 && $order->pickup_store)
          <div class="space-y-2 text-sm leading-tight">
            <span>Самовывоз из магазина: </span>
            <span class="font-bold">{{ $order->pickup_store }}</span>
          </div>
        @endif
        @if ($order->order_type == 0 and $order->date)
          <div class="flex space-x-2 text-sm">
            <span class="w-full md:w-4/6">Доставка на</span> <span class="flex justify-end w-full font-bold md:w-8/6">
              {{ simpleDate($order->date) }}</span>
          </div>
        @endif
        @if ($order->order_type == 0 and $order->delivery_time and $order->date)
          <div class="flex space-x-2 text-sm">
            <span class="w-full md:w-4/6">Время доставки</span>
            <span class="flex justify-end w-full font-bold md:w-8/6">{{ $order->delivery_time }}</span>
          </div>
        @endif

        @if ($order->payment_method == 1)
          <div class="flex space-x-2 text-sm">
            <span class="w-full md:w-3/6">Оплата</span>
            <span class="flex justify-end w-full font-bold md:w-9/6">наличными при получении</span>
          </div>
          @if ($order->need_change)
            <div class="flex space-x-2 text-sm">
              <span class="w-full md:w-4/6">Сдача с</span>
              <span class="flex justify-end w-full font-bold md:w-8/6">{{ $order->need_change }}<span
                  class="pl-1">₽</span></span>
            </div>
          @endif
        @else
          <div class="flex space-x-2 text-sm">
            <span class="w-full md:w-3/6">Оплата</span>
            <span class="flex justify-end w-full font-bold md:w-9/6">онлайн</span>
          </div>
        @endif

        @if ($order->order_type == 0)
          <div class="flex space-x-2 text-sm">
            <span class="w-full md:w-3/6">Доставка</span>
            <span class="flex justify-end w-full font-bold md:w-9/6">
              @if ($order->delivery_cost == 0)
                бесплатно
              @else
                {{ RUB($order->delivery_cost) }}
              @endif
            </span>
            {{-- <span class="font-bold">{{ RUB($deliveryCostToShelter) }}</span> --}}
          </div>
        @else
          <div class="flex justify-between">
            <span>Самовывоз</span>
            <span class="font-bold">бесплатно</span>
          </div>
        @endif

        <div class="flex justify-between pt-2 text-lg font-bold">
          <span wire:ignore>Итого</span>
          <span class="font-bold">{{ RUB($order->amount) }}</span>
        </div>

      </div>

      <div class="sticky">
        <div class="block">
          <button wire:click="confirmOrder"
            class="relative w-full px-3 py-4 text-lg font-bold leading-tight text-white uppercase bg-orange-400 hover:bg-orange-500 rounded-2xl disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled">
            <span wire:loading wire:target="confirmOrder" class="absolute left-4 top-6">
              <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
            </span>
            <span>
              Подтвердить заказ<br><span class="text-xs lowercase">и перейти к оплате</span>
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<script type="text/javascript">
  //TODO create route
  window.addEventListener('beforeunload', function(e) {
    navigator.sendBeacon('/closedTab', {{ $order->id }})
  });
</script>

</div>
