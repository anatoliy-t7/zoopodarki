@extends('layouts.app')
@section('title', 'Ваш заказ - Zoo Подарки')
@section('content')
  <div class="py-2">
    <div class="space-y-6">

      <div class="p-4 space-y-8 bg-white md:p-8 rounded-2xl">

        <h1 class="text-2xl font-bold">
          Заказ {{ $order->order_number }}
        </h1>

        <div class="prose">
          <p>Мы приняли ваш заказ. Благодарим Вас за покупку!</p>
        </div>

        <div class="flex items-center justify-between px-6 py-5 bg-gray-50 rounded-xl">
          <div class="flex flex-col justify-start gap-4 md:items-center md:gap-16 md:flex-row">
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

        </div>

        <div class="pb-4">
          <table class="table w-full leading-normal table-mobile">
            <thead class="hidden md:visible">
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
              </tr>
            </thead>
            <tbody class="block md:table-row-group">
              @foreach ($order->items as $item)
                <tr class="block md:table-row">
                  <td data-label="Товар" class="block px-5 py-6 text-sm bg-white border-b border-gray-100 md:table-cell">
                    <div class="flex items-center justify-start space-x-6">
                      <img loading="lazy" class="object-contain object-center w-12 h-12"
                        src="{{ $item->product1c->product->getFirstMediaUrl('product-images', 'thumb') }}"
                        alt="{{ $item->name }}">

                      <a class="hover:underline" target="_blank"
                        href="{{ route('site.product', ['catalogslug' => $item->product1c->product->categories[0]->catalog->slug, 'categoryslug' => $item->product1c->product->categories[0]->slug, 'productslug' => $item->product1c->product->slug]) }}">
                        {{ $item->name }}
                      </a>
                    </div>
                  </td>
                  <td data-label="Количество"
                    class="block px-5 py-6 text-sm bg-white border-b border-gray-100 md:table-cell">
                    <span class="inline-block w-6/12 font-bold md:hidden">Количество</span> {{ $item->quantity }} шт
                  </td>
                  <td data-label="Ед. измерения"
                    class="block px-5 py-6 text-sm bg-white border-b border-gray-100 md:table-cell">
                    <span class="inline-block w-6/12 font-bold md:hidden">Ед. измерения</span> {{ $item->unit }}
                  </td>
                  <td data-label="Цена" class="block px-5 py-6 text-sm bg-white border-b border-gray-100 md:table-cell">
                    <span class="inline-block w-6/12 font-bold md:hidden">Цена</span> {{ RUB($item->price) }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div
          class="flex flex-col justify-between gap-4 px-6 py-5 md:items-center bg-gray-50 rounded-xl md:flex-row md:gap-0">
          <div class="flex flex-col justify-start gap-4 md:items-center md:gap-16 md:flex-row">

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
  </div>

@endsection
