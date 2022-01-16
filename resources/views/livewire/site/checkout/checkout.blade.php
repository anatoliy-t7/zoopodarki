@section('title')
  Оформление заказа
@endsection

<div class="py-6 space-y-4">

  <div class="flex flex-col items-center justify-start max-w-3xl gap-12 px-4 md:items-start md:flex-row">

    <x-logo />

    <div class="flex items-center justify-between w-full pt-3">

      <div class="relative block w-full max-w-2xl">
        <div class="absolute -top-8" style="left: calc({{ $greenLine }}% - 1.7rem)">
          <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path class="text-green-500 stroke-current" stroke-linecap="round" stroke-linejoin="round"
              stroke-miterlimit="10" stroke-width="1.5"
              d="M2 2h1.74c1.08 0 1.93.93 1.84 2l-.83 9.96a2.796 2.796 0 0 0 2.79 3.03h10.65c1.44 0 2.7-1.18 2.81-2.61l.54-7.5c.12-1.66-1.14-3.01-2.81-3.01H5.82M16.25 22a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5zm-8 0a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5zM9 8h12" />
          </svg>
        </div>
        <div class="w-full bg-white rounded-full">
          <div class="h-2 text-xs leading-none text-center text-white transition-all bg-green-500 rounded-full"
            style="width: {{ $greenLine }}%">
          </div>
        </div>
        <div class="flex items-center justify-between py-1 text-xs">
          <div class="{{ $step === 1 ? 'font-bold' : '' }}">Авторизация</div>
          <div class="{{ $step === 2 ? 'font-bold' : '' }}">Оформление</div>
          <div>Подтверждение</div>
        </div>
      </div>
    </div>
  </div>


  <h3 class="block px-4 text-2xl font-semibold">Оформление заказа</h3>

  <div class="justify-between block md:flex md:space-x-6">
    <div class="block w-full px-6 pt-4 pb-6 space-y-8 bg-white md:w-8/12 rounded-2xl">

      @guest
        <div x-cloak x-data class="py-4 text-sm text-center text-gray-600">Пожалуйста,
          авторизуйтесь или зарегистрируйтесь для продолжения оформления заказа.
        </div>
        <div class="relative flex items-center justify-center w-full">
          <div class="max-w-xs mx-auto">
            <livewire:auth.auth-com>
          </div>
        </div>
      @else
        <div>
          <h4 class="text-lg font-bold">Контакты</h4>
          <div class="items-center justify-start block pt-2 space-y-6 md:space-y-0 md:space-x-6 md:flex">
            @if ($contactSelected)
              <div
                class="items-start justify-start block w-full px-4 py-3 space-y-4 md:w-9/12 md:space-y-0 md:space-x-4 md:flex md:h-14">

                @if ($contactSelected['name'])
                  <div>
                    <div class="text-xs text-gray-400">Имя: </div>
                    <div>{{ $contactSelected['name'] }}</div>
                  </div>
                @endif

                @if ($contactSelected['phone'])
                  <div class="w-32">
                    <div class="text-xs text-gray-400">Тел: </div>
                    <div>{{ $contactSelected['phone'] }}</div>
                  </div>
                @endif

                @if ($contactSelected['email'])
                  <div>
                    <div class="text-xs text-gray-400">Электронная почта: </div>
                    <div>{{ $contactSelected['email'] }}</div>
                  </div>
                @endif
              </div>
            @else
              <div class="w-full md:w-9/12">
                <div>Добавьте контакт который должен получить заказ</div>
                @error('contactSelected')
                  <div class="text-sm text-red-500">
                    Вам необходимо добавить контакты
                  </div>
                @enderror
              </div>
            @endif

            <div class="relative w-full md:w-3/12">
              <livewire:site.user-contacts />
            </div>

          </div>


        </div>

        <div x-data="{ toggle: {{ $orderType }} }">
          <h4 class="pb-2 text-lg font-bold">Способ доставки</h4>
          <div class="space-y-4">
            <div class="items-center justify-start block space-y-4 md:space-y-0 md:space-x-4 md:flex">
              <div
                class="inline-flex w-full leading-none text-gray-400 bg-gray-200 border-2 border-gray-200 rounded-2xl md:w-6/12 h-14">

                <button wire:click="getOrderType(0)" x-on:click="toggle = 0"
                  :class="{ 'bg-white text-green-600': toggle === 0 }"
                  class="inline-flex items-center w-6/12 px-4 py-2 transition-colors duration-300 ease-in rounded-2xl focus:outline-none hover:text-green-600">
                  <svg :class="{ 'text-green-600': toggle === 0 }" class="w-8 h-6 pr-2 fill-current"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                      d="M1,12.5v5a1,1,0,0,0,1,1H3a3,3,0,0,0,6,0h6a3,3,0,0,0,6,0h1a1,1,0,0,0,1-1V5.5a3,3,0,0,0-3-3H11a3,3,0,0,0-3,3v2H6A3,3,0,0,0,3.6,8.7L1.2,11.9a.61.61,0,0,0-.07.14l-.06.11A1,1,0,0,0,1,12.5Zm16,6a1,1,0,1,1,1,1A1,1,0,0,1,17,18.5Zm-7-13a1,1,0,0,1,1-1h9a1,1,0,0,1,1,1v11h-.78a3,3,0,0,0-4.44,0H10Zm-2,6H4L5.2,9.9A1,1,0,0,1,6,9.5H8Zm-3,7a1,1,0,1,1,1,1A1,1,0,0,1,5,18.5Zm-2-5H8v2.78a3,3,0,0,0-4.22.22H3Z" />
                  </svg>
                  <span class="font-semibold">Доставка</span>
                </button>
                <button wire:click="getOrderType(1)" x-on:click="toggle = 1"
                  :class="{ 'bg-white text-green-600': toggle === 1 }"
                  class="inline-flex items-center w-6/12 px-4 py-2 transition-colors duration-300 ease-in rounded-2xl focus:outline-none hover:text-green-600">
                  <svg :class="{ 'text-green-600': toggle === 1 }" class="w-8 h-8 pr-2 fill-current"
                    xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24">
                    <path
                      d="M22,16H19.82A3,3,0,0,0,20,15V10a3,3,0,0,0-3-3H11a3,3,0,0,0-3,3v5a3,3,0,0,0,.18,1H7a1,1,0,0,1-1-1V5A3,3,0,0,0,3,2H2A1,1,0,0,0,2,4H3A1,1,0,0,1,4,5V15a3,3,0,0,0,2.22,2.88,3,3,0,1,0,5.6.12h3.36a3,3,0,1,0,5.64,0H22a1,1,0,0,0,0-2ZM9,20a1,1,0,1,1,1-1A1,1,0,0,1,9,20Zm2-4a1,1,0,0,1-1-1V10a1,1,0,0,1,1-1h6a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1Zm7,4a1,1,0,1,1,1-1A1,1,0,0,1,18,20Z" />
                  </svg>
                  <span class="font-semibold">Самовывоз</span>
                </button>
              </div>

              <div class="flex items-center w-full md:w-6/12">
                <svg class="hidden w-8 h-8 pr-2 text-gray-300 fill-current md:block" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24">
                  <path
                    d="M14.83,11.29,10.59,7.05a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41L12.71,12,9.17,15.54a1,1,0,0,0,0,1.41,1,1,0,0,0,.71.29,1,1,0,0,0,.71-.29l4.24-4.24A1,1,0,0,0,14.83,11.29Z" />
                </svg>
                <div class="text-sm text-gray-400">Санкт-Петербург</div>
              </div>
            </div>

            <div class="w-full">

              <div :class="{ 'flex': toggle == 0, 'hidden': toggle == 1}"
                class="flex flex-col items-center justify-start gap-6 md:flex-row">
                @if (is_array($addressSelected) && !empty($addressSelected))
                  <div class="flex-col items-start justify-start w-full px-4 py-3 space-y-1 md:w-9/12 h-14">
                    <div class="leading-snug">
                      {{ $addressSelected['address'] }}

                      @if (Arr::has($addressSelected, 'apartment'))
                        , кв. {{ $addressSelected['apartment'] }}
                      @endif
                    </div>
                    @if (array_key_exists('extra', $addressSelected))
                      <div class="text-xs text-gray-400">
                        {{ $addressSelected['extra'] }}
                      </div>
                    @endif
                    @if ($this->deliveryCost === 0)
                      <span class="text-xs text-red-500">Пожалуйста укажите адрес в пределах КАД</span>
                    @endif
                  </div>
                @else
                  <div class="w-full md:w-9/12 md:pl-4">
                    Добавьте адрес доставки
                    @error('addressSelected')
                      <div class="pt-1 text-sm text-red-500">
                        Вам необходимо добавить адрес
                      </div>
                    @enderror
                  </div>
                @endif

                <div class="relative w-full md:w-3/12">
                  <x-loader-small wire:target="address" :bg="true" />
                  <livewire:site.user-addresses />
                </div>

              </div>

              <div x-cloak :class="{ 'block': toggle == 1, 'hidden': toggle == 0}" class="w-full fadeIn">

                <div x-cloak>

                  <div class="items-center justify-start block gap-6 md:flex">

                    <div class="items-center justify-start block w-full pb-2 md:px-4 md:flex md:w-9/12">
                      <div class="leading-snug">
                        @if ($pickupStore)
                          {{ $pickupStore }}
                        @else
                          <div class="py-2 cursor-pointer">
                            Выберите пункт выдачи справа
                          </div>
                        @endif
                        @error('pickupStore')
                          <div class="text-sm text-red-500">
                            Вам необходимо добавить адрес
                          </div>
                        @enderror
                      </div>
                    </div>

                    <div class="w-full md:w-3/12">
                      <x-modal :width="'6xl'">
                        <x-slot name="button">
                          <div x-on:click="$dispatch('init-map')"
                            class="flex items-center justify-center w-full gap-1 px-4 bg-gray-100 border border-gray-300 cursor-pointer hover:border-gray-400 h-14 rounded-xl">
                            <div>Пункты выдачи</div>
                            <x-tabler-chevron-right class="w-6 h-6 text-gray-400 stroke-current" />
                          </div>
                        </x-slot>
                        <x-slot name="content">
                          <livewire:site.map :checkout="true">
                        </x-slot>
                      </x-modal>
                    </div>
                  </div>

                </div>
              </div>

            </div>

            <div x-cloak :class="{ 'block': toggle == 0, 'hidden': toggle == 1}"
              class="relative w-full pt-6 space-y-6 fadeIn ">
              <div class="block w-full space-y-6 md:space-y-0 md:space-x-6 md:flex md:justify-between">

                <div x-data="{date: @entangle('date')}" class="w-full md:w-9/12">

                  <div class="block pb-2 text-lg font-bold text-gray-700">Дата доставки</div>

                  <div wire:ignore id="date" class="flex items-center justify-center w-full overflow-hidden splide">
                    <div class="flex items-center splide__arrows">
                      <button class="splide__arrow splide__arrow--prev">
                        <div class="p-1 text-2xl hover:bg-gray-100">
                          <x-tabler-chevron-right />
                        </div>
                      </button>
                      <button class="splide__arrow splide__arrow--next">
                        <div class="p-1 text-2xl hover:bg-gray-100">
                          <x-tabler-chevron-right class="text-gray-500 bg-transparent stroke-current" />
                        </div>
                      </button>
                    </div>
                    <div class="w-full max-w-xs md:max-w-md splide__track">
                      <ul class="splide__list">
                        @foreach ($dates as $item)
                          <li class="relative cursor-pointer splide__slide hover:bg-white"
                            :class=" date == '{{ $item['date'] }}' ? 'text-green-600 bg-white' : 'text-gray-500 bg-gray-50'">
                            <div x-on:click="$wire.set('date', '{{ $item['date'] }}')"
                              class="block px-2 py-3 text-center "
                              :class=" date == '{{ $item['date'] }}' ? 'border-2 border-green-500' : 'border-r-2 border-y-2 border-white'">
                              <div class="pb-1 font-semibold">
                                {{ $item['number'] }}
                              </div>
                              <div class="text-xs capitalize truncate"
                                :class=" date == '{{ $item['date'] }}' ? 'text-green-600 ' : 'text-gray-500 '">
                                {{ $item['name'] }}
                              </div>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                  @error('date')
                    <span class="block pt-2 pl-4 text-sm text-red-500">
                      Выберите дату доставки
                    </span>
                  @enderror

                  @push('footer')
                    <script src="{{ mix('js/splide.min.js') }}"></script>
                    <script>
                      document.addEventListener('DOMContentLoaded', function() {
                        new Splide('#date', {
                          fixedWidth: '5rem',
                          perMove: 2,
                          pagination: false,
                          classes: {
                            arrows: 'splide__arrows your-class-arrows',
                            arrow: 'splide__arrow your-class-arrow',
                            prev: 'splide__arrow--prev your-class-prev',
                            next: 'splide__arrow--next your-class-next',
                          },
                        }).mount();
                      });
                    </script>
                  @endpush

                </div>

                <div class="w-full md:w-3/12">
                  <label class="block pb-2 text-lg font-bold text-gray-700">Время доставки</label>
                  <select wire:model="deliveryTime" name="deliveryTime" class="h-14 field">
                    <option value="10:00 - 17:00">10:00 - 17:00</option>
                    <option value="17:00 - 23:00">17:00 - 23:00</option>
                    <option value="10:00 - 23:00">10:00 - 23:00</option>
                  </select>
                </div>

              </div>
            </div>

          </div>
        </div>

        <div x-data="{payment: 'online'}" class="pt-2">
          <div class="pb-2 text-lg font-bold leading-normal text-gray-700">Вид оплаты</div>
          <div class="content-center justify-start block space-y-4 md:flex md:space-y-0 md:space-x-4">

            <div
              class="inline-flex w-full leading-none text-gray-400 bg-gray-200 border-2 border-gray-200 md:h-14 rounded-2xl md:w-6/12">
              <button wire:click="paymentType(0)" x-on:click="payment = 'online'"
                :class="{ 'bg-white text-green-600': payment == 'online' }"
                class="inline-flex items-center w-6/12 px-4 py-2 transition-colors duration-300 ease-in rounded-2xl focus:outline-none hover:text-green-600">

                <x-tabler-credit-card :class="{ 'text-green-600': payment == 'online' }"
                  class="inline mr-2 stroke-current w-7 h-7" />

                <span>Оплата онлайн</span>
              </button>
              <button wire:click="paymentType(1)" x-on:click="payment = 'cash'"
                :class="{ 'bg-white text-green-600': payment == 'cash' }"
                class="flex items-center justify-center w-6/12 px-4 py-2 space-x-4 transition-colors duration-300 ease-in rounded-2xl focus:outline-none hover:text-green-600">
                <svg :class="{ 'text-green-600': payment == 'cash' }" class="w-8 h-8 fill-current"
                  xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" viewBox="0 0 24 24">
                  <g fill="none">
                    <path
                      d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2m2 4h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm7-5a2 2 0 1 1-4 0a2 2 0 0 1 4 0z"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </g>
                </svg>
                <span>Наличными <br><span class="text-xs">(при получении)</span></span>
              </button>
            </div>

            <div class="flex items-center w-full md:w-6/12">
              <svg class="hidden w-8 h-8 pr-2 text-gray-300 fill-current md:block" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24">
                <path
                  d="M14.83,11.29,10.59,7.05a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41L12.71,12,9.17,15.54a1,1,0,0,0,0,1.41,1,1,0,0,0,.71.29,1,1,0,0,0,.71-.29l4.24-4.24A1,1,0,0,0,14.83,11.29Z" />
              </svg>
              <div class="text-gray-400">
                <div :class="{ 'flex': payment == 'online', 'hidden': payment == 'cash' }" class="w-full text-sm">
                  Банковской картой онлайн
                </div>
                <div x-cloak :class="{ 'flex': payment == 'cash', 'hidden': payment == 'online'}"
                  class="items-center justify-end space-x-3">
                  <span class="block text-sm text-right text-gray-700 ">Нужна сдача с</span>
                  <div class="w-32">
                    <input wire:model="needChange" type="number" class="placeholder-gray-300 field" placeholder="500"
                      inputmode="numeric">
                  </div>
                  <span class="flex text-sm text-gray-700">рублей</span>
                </div>
              </div>
            </div>

          </div>
        </div>

        @if ($orderType === 0 && $orderPaymentType === 0)
          <x-toggle wire:model="contactlessDelivery" :property="$contactlessDelivery" :lable="'Бесконтактная доставка'" />
        @endif

        <div class="flex justify-between">
          <div class="w-full">
            <div class="block pb-2 text-lg font-bold text-gray-700">Комментарий к заказу</div>
            <textarea wire:model="orderComment" name="comment" rows="2" class="resize-none field"></textarea>
          </div>

        </div>
      @endguest

    </div>
    <div class="block w-full space-y-2 md:w-4/12">

      <div class="p-6 space-y-4 text-sm ">
        <div class="text-lg font-bold">Ваш заказ</div>
        @if ($items)
          <div class="flex flex-col justify-between w-full divide-y">
            @foreach ($items as $item)
              <div class="py-2">
                <div class="flex items-center justify-between space-x-2 ">
                  <div class="p-2 bg-white ">
                    @if ($item->associatedModel['image'])
                      <a class="w-full" target="_blank"
                        href="{{ route('site.product', ['catalogslug' => $item->associatedModel['catalog_slug'], 'categoryslug' => $item->associatedModel['category_slug'], 'productslug' => $item->associatedModel['product_slug']]) }}">
                        <img loading="lazy" class="object-fill w-12 h-full"
                          src="{{ $item->associatedModel['image'] }}" alt="{{ $item->name }}">
                      </a>
                    @endif
                  </div>

                  <div class="w-full">

                    <a class="block w-full hover:underline" target="_blank"
                      href="{{ route('site.product', ['catalogslug' => $item->associatedModel['catalog_slug'], 'categoryslug' => $item->associatedModel['category_slug'], 'productslug' => $item->associatedModel['product_slug']]) }}">
                      {{ $item->name }}
                    </a>

                    <div class="flex justify-between pt-2">
                      <div class="flex justify-start space-x-4 text-xs text-gray-500">
                        @if ($item->attributes->has('unit'))
                          <x-units :unit="$item->attributes['unit']" :value="$item->attributes->weight">
                          </x-units>
                        @endif
                      </div>
                      <div class="flex space-x-4 items-centerjustify-end">
                        @if ($item->attributes->unit_value != 'на развес')
                          <div>{{ $item->quantity }} шт x</div>
                        @else
                          <div>на развес</div>
                        @endif

                        <div class="flex justify-end ">
                          @if ($item->associatedModel['promotion_type'] === 0)
                            <div class="font-bold ">
                              {{ RUB($item->price) }}
                            </div>
                          @elseif ($item->associatedModel['promotion_type'] === 3 || $item->associatedModel['promotion_type'] === 1)
                            <div class="flex items-center justify-end space-x-2 ">
                              <div class="text-xs line-through">
                                {{ RUB($item->associatedModel['promotion_price']) }}
                              </div>
                              <div class="font-bold text-orange-500">
                                {{ RUB($item->price) }}
                              </div>
                            </div>
                          @elseif ($item->associatedModel['promotion_type'] === 2 || $item->associatedModel['promotion_type'] === 4)
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
          <div class="px-6 py-2 -mx-6 bg-gray-100 rounded-xl">
            <div class="py-2 text-lg font-semibold">"Помоги приюту"</div>

            <div class="flex flex-col justify-between w-full divide-y">
              @foreach ($shelterItems as $shelterItem)
                <div class="py-2">
                  <div class="flex items-center justify-between space-x-2 ">
                    <div class="p-2 ">
                      @if ($shelterItem->associatedModel['image'])
                        <a class="w-full" target="_blank"
                          href="{{ route('site.product', ['catalogslug' => $shelterItem->associatedModel['catalog_slug'], 'categoryslug' => $shelterItem->associatedModel['category_slug'], 'productslug' => $shelterItem->associatedModel['product_slug']]) }}">
                          <img loading="lazy" class="object-fill w-12 h-full"
                            src="{{ $shelterItem->associatedModel['image'] }}" alt="{{ $shelterItem->name }}">
                        </a>
                      @endif
                    </div>

                    <div class="w-full">

                      <a class="block w-full hover:underline" target="_blank"
                        href="{{ route('site.product', ['catalogslug' => $shelterItem->associatedModel['catalog_slug'], 'categoryslug' => $shelterItem->associatedModel['category_slug'], 'productslug' => $shelterItem->associatedModel['product_slug']]) }}">
                        {{ $shelterItem->name }}
                      </a>

                      <div class="flex justify-between pt-2">
                        <div class="flex justify-start space-x-4 text-xs text-gray-500">
                          @if ($shelterItem->attributes->has('unit'))
                            <x-units :unit="$shelterItem->attributes['unit']" :value="$shelterItem->attributes->weight">
                            </x-units>
                          @endif
                        </div>
                        <div class="flex space-x-4 items-centerjustify-end">
                          @if ($shelterItem->attributes->unit_value != 'на развес')
                            <div>{{ $shelterItem->quantity }} шт x</div>
                          @else
                            <div>на развес</div>
                          @endif

                          <div class="flex justify-end ">
                            @if ($shelterItem->associatedModel['promotion_type'] === 0)
                              <div class="font-bold ">
                                {{ RUB($shelterItem->price) }}
                              </div>
                            @elseif ($shelterItem->associatedModel['promotion_type'] === 3 || $shelterItem->associatedModel['promotion_type'] === 1)
                              <div class="flex items-center justify-end space-x-2 ">
                                <div class="text-xs line-through">
                                  {{ RUB($shelterItem->associatedModel['promotion_price']) }}
                                </div>
                                <div class="font-bold text-orange-500">
                                  {{ RUB($shelterItem->price) }}
                                </div>
                              </div>
                            @elseif ($shelterItem->associatedModel['promotion_type'] === 2 || $shelterItem->associatedModel['promotion_type'] === 4)
                              <div class="flex items-center justify-end space-x-2 ">
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
            <span class="font-bold">
              @if ($deliveryCost == 0.1)
                бесплатно
              @else
                {{ RUB($deliveryCost) }}
              @endif
            </span>
          </div>

        @else
          <div class="flex justify-between">
            <span>Самовывоз</span>
            <span class="font-bold">бесплатно</span>
          </div>
        @endif

        @if ($userHasDiscount !== 0)
          <x-tooltip :width="'280px'">
            <x-slot name="title">
              <span></span>Есть дисконтная карта?
              <x-tabler-alert-circle class="w-6 h-6 text-orange-400 stroke-current" />
            </x-slot>
            <div class="flex items-start justify-start space-x-2">
              <span>Если вы приобрели дисконтную карту в одном из наших магазинов, но не видите здесь скидку по карте
                5%, отправьте, пожалуйста, фото карты на email (zoopodarki@mail.ru) или воцап (8 (931)239 98 83) и мы
                обязательно исправим
                это временное
                недоразумение!</span>
            </div>
          </x-tooltip>
        @endif

        <div class="flex justify-between pt-2 text-lg font-bold border-t">
          <span>Итого</span>
          <span class="font-bold">{{ RUB($totalAmount) }}</span>
        </div>
      </div>

      <div class="px-4 py-2">
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
              <span class="w-full md:w-4/6">Доставка на</span> <span
                class="flex justify-end w-full font-bold md:w-8/6">
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
            <span wire:loading wire:target="createOrder" class="absolute top-0 flex items-center h-full left-6">
              <svg class="w-6 h-6 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
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
