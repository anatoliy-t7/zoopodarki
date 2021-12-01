@extends ('site.account.layout')
@section('title', 'Заказы')

@section('block')
  <div class="w-full max-w-md pl-2 overflow-x-auto md:max-w-full ">

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
              <a href="{{ route('account.order', ['orderId' => $order->id]) }}" class="text-blue-500 hover:underline">
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
@endsection
