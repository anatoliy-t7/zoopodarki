@section('title')
  Заказы
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)"
    @close.window="closeForm(event)" class="space-y-2">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Заказы</h3>

    </div>

    <div class="flex items-center justify-between w-full space-x-6">

      <x-dashboard.search />

      <div>
        <div class="flex items-center justify-end py-3">
          <div x-cloak x-data="{ open: false }" class="relative max-w-xs">
            <button x-on:click="open = !open" x-on:click.outside="open = false"
              class="flex items-center justify-between w-40 py-2 pl-3 pr-2 text-xs text-left text-gray-900 bg-white rounded-lg hover:text-gray-900 focus:text-gray-900 focus:outline-none focus:bg-pink-100 hover:bg-pink-100">
              <span>{{ $filteredByName }}</span>
              <div :class="{'rotate-180': open, 'rotate-0': !open}"
                class="inline align-middle transition-transform duration-200 transform ">
                <x-tabler-chevron-down class="w-4 h-4 text-gray-500 stroke-current" />
              </div>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
              x-transition:enter-start="transform opacity-0 scale-95"
              x-transition:enter-end="transform opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-75"
              x-transition:leave-start="transform opacity-100 scale-100"
              x-transition:leave-end="transform opacity-0 scale-95"
              class="absolute right-0 z-30 w-40 mt-2 origin-top-right shadow-xl rounded-2xl">
              <div class="w-auto p-2 bg-white shadow-sm rounded-2xl" x-on:click="open = !open">
                @foreach ($filterType as $filter)
                  <div class="px-3 py-2 text-xs cursor-pointer hover:bg-gray-50 rounded-xl"
                    wire:click="filterIt('{{ $filter['status'] }}', '{{ $filter['name'] }}')">
                    {{ $filter['name'] }}
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head sortable wire:click="sortBy('order_number')"
            :direction="$sortField === 'order_number' ? $sortDirection : null">
            Номер заказа
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('created_at')"
            :direction="$sortField === 'created_at' ? $sortDirection : null">
            Дата заказа
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Имя и тел
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('vendorcode')"
            :direction="$sortField === 'vendorcode' ? $sortDirection : null">
            Сумма
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Вид получения
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('vendorcode')"
            :direction="$sortField === 'vendorcode' ? $sortDirection : null">
            Дата получения
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Вид оплаты
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Статус оплаты
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Статус заказа
          </x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse ($orders as $order)
            <x-dashboard.table.row wire:key="{{ $loop->index }}" x-on:click="openForm"
              wire:click="openForm({{ $order->id }})" class="cursor-pointer" title="Посмотреть заказ">

              <x-dashboard.table.cell>
                {{ $order->order_number }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ simpleDate($order->created_at) }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $order->contact['name'] }} | {{ $order->contact['phone'] }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ RUB($order->amount) }} ({{ $order->quantity }} шт)
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                @if ($order->order_type)
                  Самовывоз
                @else
                  Доставка
                @endif
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ simpleDate($order->date) }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                @if ($order->payment_method)
                  Наличными
                @else
                  Онлайн
                @endif
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ __('constants.payment_status.' . $order->payment_status) }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ __('constants.order_status.' . $order->status) }}
              </x-dashboard.table.cell>

            </x-dashboard.table.row>
          @empty
            <x-dashboard.table.row>
              <x-dashboard.table.cell>
                Пусто
              </x-dashboard.table.cell>
            </x-dashboard.table.row>
          @endforelse
        </x-slot>

      </x-dashboard.table>
    </div>

    <div>
      <x-overflow-bg x-on:click="closeForm" />

      <x-dashboard.modal class="overflow-y-auto">

        <x-loader wire:target="openForm" />

        <div wire:loading.remove class="flex flex-col w-full space-y-4 ">

          @if ($orderSelected)

            <div class="flex items-center justify-start w-full space-x-4 text-gray-600">
              <div class="w-4/12 ">Номер заказа:</div>
              <div class="font-semibold">{{ $orderSelected->order_number }}</div>
              <div>( {{ simpleDate($orderSelected->created_at) }} )</div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12">Статус:</div>
              <div class="w-auto">
                @if ($orderSelected->status == 'pending')
                  <span class="px-3 py-1 bg-yellow-400 rounded-2xl">
                    В ожидании
                  </span>
                @elseif ($order->status == 'pending_payment')
                  <span class="px-3 py-1 bg-yellow-400 rounded-2xl">
                    Ожидает оплаты
                  </span>
                @elseif ($orderSelected->status == 'processing')
                  <span class="px-3 py-1 bg-green-400 rounded-2xl">
                    В обработке
                  </span>
                @elseif ($orderSelected->status == 'pickup')
                  <span class="px-3 py-1 bg-green-400 rounded-2xl">
                    Готов к самовывозу
                  </span>
                @elseif ($orderSelected->status == 'completed')
                  <span class="px-3 py-1 bg-blue-400 rounded-2xl">
                    Завершен
                  </span>
                @elseif ($orderSelected->status == 'cancelled')
                  <span class="px-3 py-1 bg-red-400 rounded-2xl">
                    Отменен
                  </span>
                @elseif ($orderSelected->status == 'return')
                  <span class="px-3 py-1 bg-pink-400 rounded-2xl">
                    Возврат
                  </span>
                @elseif ($orderSelected->status == 'hold')
                  <span class="px-3 py-1 bg-purple-400 rounded-2xl">
                    Приостановлен
                  </span>
                @endif
              </div>
              <div class="flex items-center justify-center text-xs">
                @if ($orderSelected->sent_to_1c)
                  Отправлен в 1с
                @else
                  Еще не отправлен в 1с
                @endif
              </div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12">Статус оплаты:</div>
              <div>
                @if ($orderSelected->payment_status == 'succeeded')
                  Оплачено
                @elseif ($orderSelected->payment_status = 'waiting_for_capture')
                  Оплата ожидает подтверждения
                @elseif ($orderSelected->payment_status == 'canceled')
                  Оплата отменена
                @elseif ($orderSelected->payment_status == 'refund_succeeded')
                  Возрат подтвержден
                @else
                  Не оплачено
                @endif
              </div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Имя заказчика:</div>
              <div class="font-semibold">{{ $orderSelected->contact['name'] }}</div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Телефон:</div>
              <div class="font-semibold">{{ $orderSelected->contact['phone'] }}</div>
            </div>

            @if ($orderSelected->contact['email'])
              <div class="flex items-center justify-start space-x-4 text-gray-600">
                <div class="w-4/12 ">Email:</div>
                <div class="font-semibold">{{ $orderSelected->contact['email'] }}</div>
              </div>
            @endif

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Вид получения:</div>
              <div class="font-semibold">
                @if ($orderSelected->order_type)
                  Самовывоз
                @else
                  Доставка
                @endif
              </div>
              <div>({{ simpleDate($orderSelected->date) }})</div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Вид оплаты:</div>
              <div class="font-semibold">
                @if ($orderSelected->payment_method)
                  Наличными
                @else
                  Онлайн
                @endif
              </div>
              <div>(
                @if ($orderSelected->payment_status === 'succeeded')
                  Оплачено
                @else
                  Не оплачено
                @endif
                )
              </div>
            </div>

            @if ($orderSelected->order_type === 0)
              <div class="flex items-center justify-start space-x-4 text-gray-600">
                <div class="w-4/12 ">Доставка:</div>
                <div>{{ RUB($orderSelected->delivery_cost) }}</div>
              </div>
            @endif
            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Комментарий к заказу:</div>
              <div>{{ $orderSelected->order_comment }}</div>
            </div>

            <div class="pt-4 text-gray-600">
              <table class="table min-w-full text-xs table-fixed">
                <thead>
                  <tr class="text-left">
                    <th>ID товара</th>
                    <th>Наименование</th>
                    <th>Цена</th>
                    <th>Скидка</th>
                    <th>Кол-во</th>
                    <th>Всего (со скидкой)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orderSelected->items as $item)
                    <tr>
                      <td class="w-16 px-1 py-2 odd:bg-white even:bg-gray-50">{{ $item->product_id }}</td>
                      <td class="max-w-xs px-1 py-2 truncate odd:bg-white even:bg-gray-50">{{ $item->name }}
                      </td>
                      <td class="px-1 py-2 odd:bg-white even:bg-gray-50">{{ $item->price }}</td>
                      <td class="px-1 py-2 odd:bg-white even:bg-gray-50">
                        @if ($item->discount)
                          {{ $item->discount }}
                        @endif
                        @if ($item->discount_comment)
                          ({{ $item->discount_comment }})
                        @endif
                      </td>
                      <td class="px-1 py-2 odd:bg-white even:bg-gray-50">{{ $item->quantity }}</td>
                      <td class="px-1 py-2 odd:bg-white even:bg-gray-50">{{ $item->amount }}</td>
                    </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="px-1 py-2 text-base font-semibold">{{ $orderSelected->quantity }}</td>
                    <td class="px-1 py-2 text-base font-semibold">{{ RUB($orderSelected->amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="pt-4 text-xs text-gray-400">
              Есть еще поля для вывода: need_change, weight, pickup_store, address,

            </div>

            <div class="flex items-center justify-between w-full pt-3 pb-20">

              @can('delete')
                <x-dashboard.confirm wire:click="remove({{ $orderSelected->id }})" :confirmId="$orderSelected->id" />
              @endcan

            </div>
          @endif

        </div>

      </x-dashboard.modal>
    </div>


    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $orders->links() }}
      </div>

      <div class="flex items-center justify-end w-4/12 space-x-4">
        <x-dashboard.items-per-page />
      </div>
    </div>

    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('handler', () => ({
          form: false,
          confirm: null,
          description: null,
          body: document.body,
          openForm() {
            this.form = true
            this.body.classList.add("overflow-hidden")
          },

          closeForm() {
            this.form = false
            this.body.classList.remove("overflow-hidden")
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
</div>
