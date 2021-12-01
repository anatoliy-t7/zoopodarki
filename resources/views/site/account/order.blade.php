@extends('layouts.app')
@section('title', 'Ваш заказ - ЗооМагазин')
@section('content')
  <div class="py-2">
    <div class="space-y-6">

      <div class="flex justify-start px-4 text-xs font-semibold text-gray-300 xl:px-0">
        <div class="flex items-center justify-between">
          <a itemprop="item" class="py-1 pr-1 hover:underline" href="#">
            <span itemprop="name">Акаунт</span>
          </a>
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M15.54,11.29,9.88,5.64a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l4.95,5L8.46,17a1,1,0,0,0,0,1.41,1,1,0,0,0,.71.3,1,1,0,0,0,.71-.3l5.66-5.65A1,1,0,0,0,15.54,11.29Z">
            </path>
          </svg>
        </div>
        <div class="flex items-center justify-between">
          <a class="p-1 hover:underline" href="{{ route('account.orders') }}">
            <span>Заказы</span>
          </a>
        </div>
      </div>

      <div class="p-8 space-y-8 bg-white rounded-2xl">

        <h1 class="text-2xl font-bold">
          Заказ {{ $order->order_number }}
        </h1>

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
              <div class="font-bold">Статус заказа</div>
              <div>{{ __('constants.order_status.' . $order->status) }}</div>
            </div>

            <div class="space-y-1 text-sm text-gray-600">
              <div class="font-bold">Сумма</div>
              <div class="font-bold"> {{ RUB($order->amount) }}</div>
            </div>
          </div>

          <div class="text-sm text-gray-600">
            <div class="font-bold">Заказать снова</div>
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
                    {{ RUB($item->price) }}
                  </td>
                  <td class="px-5 py-6 text-sm bg-white border-b border-gray-100">
                    Заказать товар
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="flex items-center justify-start px-6 py-5 space-x-16 bg-gray-50 rounded-xl">
          <div class="flex items-start h-full text-sm text-gray-600">
            <div class="font-bold">Контакты</div>
          </div>

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

      </div>

    </div>
  </div>
@endsection
