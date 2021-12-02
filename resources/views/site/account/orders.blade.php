@extends ('layouts.app')
@section('title', 'Мои заказы')

@section('content')
  <div class="space-y-2">
    <div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0">
      <div class="flex items-center justify-between">
        <a class="py-1 pr-1 hover:underline" href="{{ route('account.account') }}">
          Акаунт
        </a>
        <x-tabler-chevron-right class="w-5 h-5" />
      </div>
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-between">
          <a class="p-1 hover:underline" href="{{ route('account.orders') }}">
            <span>Заказы</span>
          </a>
        </div>
      </div>
    </div>

    <div class="p-8 space-y-8 bg-white rounded-2xl">
      <div>
        <h1 class="text-2xl font-bold">
          Заказы
        </h1>
      </div>

      <div class="w-full max-w-md overflow-x-auto md:max-w-full ">

        <table class="w-full leading-normal table-auto">
          <thead>
            <tr class="text-gray-500 border-b-2 border-gray-200 bg-gray-50">
              <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left">
                Дата заказа
              </th>
              <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left">
                Номер заказа
              </th>
              <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left">
                Дата и вид получения
              </th>
              <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left">
                Сумма
              </th>
              <th class="px-5 py-3 text-xs font-semibold tracking-wider text-left">
                Статус заказа
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
              <tr>
                <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                  {{ simpleDate($order->created_at) }}
                </td>
                <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                  <a href="{{ route('account.order', ['orderId' => $order->id]) }}"
                    class="text-blue-500 hover:underline">
                    {{ $order->order_number }}
                  </a>
                </td>
                <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                  <p class="text-gray-900 whitespace-nowrap">
                    {{ simpleDate($order->date) }} /
                    @if ($order->order_type)
                      Самовывоз
                    @else
                      Доставка
                    @endif
                  </p>
                </td>
                <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                  <p class="text-gray-900 whitespace-nowrap">
                    {{ RUB($order->amount) }} ({{ $order->quantity }} шт, {{ $order->weight }} кг)
                  </p>
                </td>
                <td class="px-5 py-5 text-sm bg-white border-b border-gray-200 whitespace-nowrap">

                  {{ __('constants.order_status.' . $order->status) }}

                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pt-6">
          {{ $orders->links() }}
        </div>

      </div>
    </div>
  </div>
@endsection
