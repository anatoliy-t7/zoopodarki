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
    <div class="block w-full px-6 pt-4 pb-6 space-y-6 bg-white md:w-8/12 rounded-2xl">



      <div>
        @if (session()->has('message'))
          <div class="text-sm text-red-500">
            {{ session('message') }}
          </div>
        @endif
      </div>

      <div>
        {{-- @foreach ($noStockItems as $item)
            <div>$item['']</div>

          @endforeach --}}
      </div>

      <script type="text/javascript">
        //TODO create route
        window.addEventListener('beforeunload', function(e) {
          navigator.sendBeacon('/closedTab', {{ $order->id }})
        });
      </script>

    </div>
    <div class="block w-full space-y-2 md:w-4/12">

      <div class="p-6 space-y-4 text-sm ">
        <div class="text-lg font-bold">Ваш заказ</div>
        @if ($items)
          <div class="flex flex-col justify-between w-full ">
            @foreach ($items as $item)
              <div class="py-2 border-b border-gray-200 ">
                <div class="flex items-center justify-between space-x-2 ">
                  <div class="p-2 bg-white ">
                    @if ($item->associatedModel['image'])
                      <a class="w-full" target="_blank"
                        href="{{ route('site.product', ['catalog' => $item->associatedModel['catalog_slug'], 'category' => $item->associatedModel['category_slug'], 'slug' => $item->associatedModel['product_slug']]) }}">
                        <img loading="lazy" class="object-fill w-12 h-full"
                          src="{{ $item->associatedModel['image'] }}" alt="{{ $item->name }}">
                      </a>
                    @endif
                  </div>

                  <div class="w-full">

                    <a class="block w-full hover:underline" target="_blank"
                      href="{{ route('site.product', ['catalog' => $item->associatedModel['catalog_slug'], 'category' => $item->associatedModel['category_slug'], 'slug' => $item->associatedModel['product_slug']]) }}">
                      {{ $item->name }}
                    </a>

                    <div class="flex justify-between pt-2">
                      <div class="flex justify-start space-x-4 text-xs text-gray-500">
                        @if ($item->attributes->has('unit'))
                          <x-units :unit="$item->attributes['unit']" :value="$item->associatedModel['weight']">
                          </x-units>
                        @endif
                      </div>
                      <div class="flex space-x-4 items-centerjustify-end">
                        <div> {{ $item->quantity }} шт x</div>
                        <div class="flex justify-end ">
                          @if ($item->associatedModel['promotion_type'] === 0)
                            <div class="font-bold ">
                              {{ RUB($item->price) }}
                            </div>
                          @elseif ($item->associatedModel['promotion_type'] === 1 ||
                            $item->associatedModel['promotion_type'] === 3)
                            <div class="flex items-center justify-end space-x-2 ">
                              <div class="text-xs line-through">
                                {{ RUB($item->associatedModel['promotion_price']) }}
                              </div>
                              <div class="font-bold text-orange-500">
                                {{ RUB($item->price) }}
                              </div>
                            </div>
                          @elseif ($item->associatedModel['promotion_type'] === 2 ||
                            $item->associatedModel['promotion_type'] === 4)
                            <div class="flex items-center justify-end space-x-2 ">
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
            @endforeach
          </div>
        @endif

        @if ($shelterItems)
          <div class="px-6 -mx-6 bg-gray-100">
            <div class="py-2 font-semibold">"Помоги приюту"</div>

            <div class="flex flex-col justify-between w-full ">
              @foreach ($shelterItems as $shelterItem)
                <div class="flex items-center justify-between py-2 space-x-2 border-b border-gray-200">

                  <div class="p-2">
                    <img loading="lazy" class="object-cover object-center w-12"
                      src="{{ $shelterItem->associatedModel['image'] }}" alt="">
                  </div>

                  <div class="w-full">
                    <div>
                      {{ $shelterItem->name }}
                    </div>

                    <div class="flex justify-between py-2 ">
                      <div class="flex justify-start text-xs text-gray-500">
                        <span>
                          {{ $shelterItem->quantity }} шт
                        </span>
                      </div>

                      <div class="flex items-center justify-end space-x-2 font-bold">
                        <div class="text-xs line-through">
                          {{ RUB($shelterItem->getPriceSum()) }}
                        </div>
                        <div class="font-bold text-orange-500">
                          {{ RUB($shelterItem->getPriceSumWithConditions()) }}</div>
                      </div>

                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif


        @if ($productDiscountIdsByWeight)
          <div class="flex justify-between space-x-2">
            <span>Скидка за вес (больше 5 кг)</span>
            <span class="font-bold">-10%</span>
          </div>
        @endif

        @if ($userHasDiscount !== 0)
          <div class="flex justify-between space-x-2">
            <span>Скидка дисконтной карты</span>
            <span class="font-bold">-{{ $userHasDiscount }}%</span>
          </div>
        @endif


        <div class="flex justify-between font-bold text-gray-700">
          <span>Всего</span>
          <span class="">{{ RUB($subTotal) }}</span>
        </div>



        @if ($userHasDiscountOnReview)
          <div class="flex justify-between space-x-2">
            <span>Скидка за отзыв</span>
            <span class="font-bold">-2%</span>
          </div>
        @endif

        @if ($firstOrder !== 0)
          <div class="flex justify-between">
            <span>Скидка за первый заказ</span>
            <span class="font-bold"> -{{ RUB($firstOrder) }}</span>
          </div>
        @endif



        @if ($orderType == 0)
          <div class="flex justify-between">
            <span>Доставка</span>
            <span class="font-bold">{{ RUB($deliveryCost) }}</span>
          </div>

        @else
          <div class="flex justify-between">
            <span>Самовывоз</span>
            <span class="font-bold">бесплатно</span>
          </div>
        @endif

        @if (count($shelterItems) > 0)
          <div class="flex justify-between">
            <span>Доставка в приют</span>
            <span class="font-bold">{{ RUB($deliveryCostToShelter) }}</span>
          </div>
        @endif



        <div class="flex justify-between pt-2 text-lg font-bold border-t">
          <span wire:ignore>Итого</span>
          <span class="font-bold">{{ RUB($totalAmount) }}</span>
        </div>
      </div>

      <div class="p-6">
        <div class="py-4 space-y-4 text-gray-700 border-t border-b border-gray-200">

          @if ($orderType == 1 and $date)
            <div class="flex space-x-2 text-sm">
              <span class="w-full md:w-4/6">Самовывоз на</span>
              <span class="flex justify-end w-full font-bold md:w-8/6">{{ simpleDate($date) }}</span>
            </div>
          @endif
          @if ($orderType == 1 and $pickupStore)
            <div class="space-y-2 text-sm leading-tight">
              <span>Самовывоз из магазина: </span>
              <span class="font-bold">{{ $pickupStore }}</span>
            </div>
          @endif
          @if ($orderType == 0 and $date)
            <div class="flex space-x-2 text-sm">
              <span class="w-full md:w-4/6">Доставка на</span> <span class="flex justify-end w-full font-bold md:w-8/6">
                {{ simpleDate($date) }}</span>
            </div>
          @endif
          @if ($orderType == 0 and $deliveryTime and $date)
            <div class="flex space-x-2 text-sm">
              <span class="w-full md:w-4/6">Время доставки</span>
              <span class="flex justify-end w-full font-bold md:w-8/6">{{ $deliveryTime }}</span>
            </div>
          @endif

          @if ($orderPaymentType == 1)
            <div class="flex space-x-2 text-sm">
              <span class="w-full md:w-3/6">Оплата</span>
              <span class="flex justify-end w-full font-bold md:w-9/6">наличными при получении</span>
            </div>
            @if ($needChange)
              <div class="flex space-x-2 text-sm">
                <span class="w-full md:w-4/6">Сдача с</span>
                <span class="flex justify-end w-full font-bold md:w-8/6">{{ $needChange }}<span
                    class="pl-1">₽</span></span>
              </div>
            @endif
          @else
            <div class="flex space-x-2 text-sm">
              <span class="w-full md:w-3/6">Оплата</span>
              <span class="flex justify-end w-full font-bold md:w-9/6">онлайн</span>
            </div>
          @endif

          <div class="space-y-2 text-sm">
            <div class="flex justify-between ">
              <span>Количество</span>
              <span class="font-bold">{{ $counter }} шт</span>
            </div>
            @if ($totalWeight)
              <div class="flex justify-between">
                <span>Вес заказа</span>
                <span class="font-bold">{{ kg($totalWeight) }}</span>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="sticky p-6 top-5 ">
        <div class="block px-6 md:px-0">

          <button wire:click="createOrder"
            class="relative w-full px-3 py-4 text-lg font-bold text-white uppercase bg-green-500 hover:bg-green-600 rounded-2xl disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled">
            <span wire:loading wire:target="createOrder" class="absolute top-4 left-4">
              <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
            </span>
            <span>
              Оформить заказ
            </span>
          </button>

          <div class="pt-4 text-xs text-gray-500">
            Нажимая кнопку "Оформить заказ", Вы соглашаетесь c <a class="leading-tight text-green-500"
              href="/page/privacy-policy" target="_blank">условиями
              политики конфиденциальности</a>.
          </div>

        </div>
      </div>
    </div>

  </div>
