<div class="space-y-2">

  <div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0">
    <div class="flex items-center justify-between">
      <a class="py-1 pr-1 hover:underline" href="#">
        Акаунт
      </a>
      <x-tabler-chevron-right class="w-5 h-5" />
    </div>
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-between">
        <a class="p-1 hover:underline" href="{{ route('account.orders') }}">
          <span>Заказы</span>
        </a>
        <x-tabler-chevron-right class="w-5 h-5" />
      </div>
    </div>
    <div class="flex items-center justify-between">
      <div class="p-1">
        {{ $order->order_number }}
      </div>
    </div>
  </div>

  <div class="p-8 space-y-8 bg-white rounded-2xl">

    <div>

      <h1 class="text-2xl font-bold">
        Заказ {{ $order->order_number }}
      </h1>
    </div>

    <div class="flex items-center justify-between px-6 py-5 bg-gray-50 rounded-xl">
      <div class="flex items-center justify-start space-x-16">
        <div class="space-y-1 text-sm text-gray-600">
          <div class="font-bold">Дата заказа</div>
          <div>{{ simpleDate($order->created_at) }}</div>
        </div>

        <div class="space-y-1 text-sm text-gray-600">
          <div class="font-bold">Вид получения и дата</div>
          <div>
            @if ($order->order_type)
              Самовывоз
            @else
              Доставка
            @endif
            / {{ simpleDate($order->date) }}
          </div>
        </div>

        <div class="space-y-1 text-sm text-gray-600">
          <div class="font-bold">Вид оплаты и статус</div>
          <div>
            @if ($order->payment_method)
              Наличными
            @else
              Онлайн
            @endif
            / {{ __('constants.payment_status.' . $order->payment_status) }}
          </div>
        </div>

        <div class="space-y-1 text-sm text-gray-600">
          <div class="font-bold">Статус заказа</div>
          <div>{{ __('constants.order_status.' . $order->status) }}</div>
        </div>

      </div>

      <div class="text-sm text-gray-600">
        {{-- <div class="font-bold">Заказать снова</div> --}}
      </div>
    </div>

    <div class="pb-4">
      <table class="w-full leading-normal table-auto">
        <thead>
          <tr class="text-sm font-semibold text-left text-gray-500 border-b border-gray-100">
            <th class="py-3 pr-5">
              Товар
            </th>
            <th class="px-5 py-3">
              Количество
            </th>
            <th class="px-5 py-3">
              Ед. измерения
            </th>
            <th class="px-5 py-3">
              Цена
            </th>
            <th class="px-5 py-3">
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->items as $item)
            <tr>
              <td class="px-5 py-6 text-sm bg-white border-b border-gray-100">
                <div class="flex items-center justify-start space-x-6">
                  <img loading="lazy" class="object-contain object-center w-12 h-12"
                    src="{{ $item->product1c->product->getFirstMediaUrl('product-images', 'thumb') }}"
                    alt="{{ $item->name }}">

                  <a class="hover:underline" target="_blank"
                    href="{{ route('site.product', [$item->product1c->product->categories[0]->catalog->slug, $item->product1c->product->categories[0]->slug, $item->product1c->product->slug]) }}">
                    {{ $item->name }}
                  </a>
                </div>
              </td>
              <td class="px-5 py-6 text-sm bg-white border-b border-gray-100">
                {{ $item->quantity }} шт
              </td>
              <td class="px-5 py-6 text-sm bg-white border-b border-gray-100">
                {{ $item->unit }}
              </td>
              <td class="px-5 py-6 text-sm bg-white border-b border-gray-100">
                {{ RUB($item->price) }}
              </td>
              <td class="px-5 py-6 text-sm bg-white border-b border-gray-100">

                <button title="В корзину" wire:click="$emit('addToCart', {{ $item->product_id }}, 1, 0, 1000)"
                  class="z-10 text-blue-500 transition ease-in-out transform cursor-pointer focus:outline-none hover:text-blue-600 active:scale-95 link-hover">
                  <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                      d="M14,18a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,14,18Zm-4,0a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,10,18ZM19,6H17.62L15.89,2.55a1,1,0,1,0-1.78.9L15.38,6H8.62L9.89,3.45a1,1,0,0,0-1.78-.9L6.38,6H5a3,3,0,0,0-.92,5.84l.74,7.46a3,3,0,0,0,3,2.7h8.38a3,3,0,0,0,3-2.7l.74-7.46A3,3,0,0,0,19,6ZM17.19,19.1a1,1,0,0,1-1,.9H7.81a1,1,0,0,1-1-.9L6.1,12H17.9ZM19,10H5A1,1,0,0,1,5,8H19a1,1,0,0,1,0,2Z" />
                  </svg>
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="flex items-center justify-between px-6 py-5 bg-gray-50 rounded-xl">
      <div class="flex items-center justify-start space-x-16">

        <div class="space-y-1 text-sm text-gray-600">
          <div class="font-bold">Имя</div>
          <div>{{ $order->contact['name'] }}</div>
        </div>

        <div class="space-y-1 text-sm text-gray-600">
          <div class="font-bold">Телефон</div>
          <div>{{ $order->contact['phone'] }}</div>
        </div>

        <div class="space-y-1 text-sm text-gray-600">
          <div class="font-bold">Адрес доставки</div>
          <div>{{ $order->address }}</div>
        </div>
      </div>

      <div class="space-y-1 text-sm text-gray-600">
        <div class="font-bold">Сумма <span class="font-normal">(со скидками)</span></div>
        <div class="font-bold"> {{ RUB($order->amount) }}</div>
      </div>
    </div>

  </div>

</div>
