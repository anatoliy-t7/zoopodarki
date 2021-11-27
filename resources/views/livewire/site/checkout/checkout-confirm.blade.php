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
        @if ($order->items()->exists())
          <div class="flex flex-col justify-between w-full py-4 pl-4 space-y-12">
            @foreach ($order->items as $item)
              <div class="flex items-center justify-between space-x-6">
                <div>
                  @if ($item->product()->exists())
                    <a class="w-full" target="_blank"
                      href="{{ route('site.product', [$item->product->categories[0]->catalog->slug, $item->product->categories[0]->slug, $item->product->slug]) }}">
                      <img loading="lazy" class="object-fill w-16 h-full"
                        src="{{ $item->product->getFirstMediaUrl('product-images', 'thumb') }}"
                        alt="{{ $item->name }}">
                    </a>
                  @endif
                </div>

                <div class="w-full space-y-4 text-gray-600">
                  <a class="block w-full hover:underline" target="_blank"
                    href="{{ route('site.product', [$item->product->categories[0]->catalog->slug, $item->product->categories[0]->slug, $item->product->slug]) }}">
                    {{ $item->name }}
                  </a>

                  <div class="flex justify-between">
                    <div class="flex justify-start space-x-4 text-xs text-gray-500">
                      @if ($item->unit)
                        {{ $item->unit }}
                      @endif
                    </div>
                    <div class="flex space-x-4 items-centerjustify-end">
                      <div> {{ $item->quantity }} шт x</div>
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
      </div>

      <div>
        @if (session()->has('message'))
          <div class="text-sm text-red-500">
            {{ session('message') }}
          </div>
        @endif
      </div>

      @if (empty($noStockItems))
        <div>
          @foreach ($noStockItems as $item)
            <div>{{ $item['name'] }}</div>
          @endforeach
        </div>
      @endif

    </div>

    <div class="block w-full space-y-2 md:w-4/12">
      <div class="sticky p-6 top-5 ">
        <div class="block px-6 md:px-0">

          <button wire:click="confirmOrder"
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
              Подтвердить заказ
            </span>
          </button>

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
