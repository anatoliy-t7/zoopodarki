@section('title')
  Заказы
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)"
    @close.window="closeForm(event)" class="space-y-4">

    <div class="flex items-center justify-between w-full space-x-6">

      <h3 class="text-2xl">Заказы</h3>

    </div>

    <div class="flex items-center justify-between w-full space-x-6">

      <x-dashboard.search />

      <div>
        <div class="flex items-center justify-end">

          <label class="flex items-center justify-end space-x-4">
            <span class="text-gray-700">Фильтр по статусу</span>
            <div class="relative">
              <select wire:model="status" name="status" class="mt-1 field">
                <option default value="">Все</option>
                @foreach ($orderStatuses as $item)
                  <option value="{{ $item }}">{{ __('constants.order_status.' . $item) }}</option>
                @endforeach
              </select>
            </div>
            @error('status') <span class="text-xs text-red-500">Поле обязательно для
              заполнения</span> @enderror
          </label>
        </div>
      </div>

    </div>

    <div>
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
            <x-dashboard.table.row wire:key="{{ $order->id }}" x-on:click="openForm"
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

    <div class="relative">
      <x-overflow-bg x-on:click="closeForm" />

      <x-dashboard.modal class="overflow-y-auto">

        <x-loader wire:target="openForm, save, setSms" />

        <div class="flex flex-col w-full space-y-4 ">

          @if ($orderSelected)

            <div class="absolute top-4 right-4">
              @can('admin')
                <x-dashboard.confirm wire:click="remove({{ $orderSelected->id }})" :confirmId="$orderSelected->id" />
              @endcan
            </div>

            <div class="flex items-center justify-start w-full space-x-4 text-gray-600">
              <div class="w-4/12 ">Номер заказа:</div>
              <div class="font-bold">{{ $orderSelected->order_number }}</div>
              <div>( {{ simpleDate($orderSelected->created_at) }} )</div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12">Статус:</div>
              <div class="w-auto font-bold">
                {{ __('constants.order_status.' . $order->status) }}
              </div>
              <div class="flex items-center justify-center text-xs">
                (@if ($orderSelected->sent_to_1c)
                  Отправлен в 1с
                @else
                  Еще не отправлен в 1с
                @endif
                )
              </div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Статус оплаты:</div>
              <div class="font-bold">
                {{ __('constants.payment_status.' . $orderSelected->payment_status) }}
              </div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Имя заказчика:</div>
              <div class="font-bold">{{ $orderSelected->contact['name'] }}</div>
            </div>

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Телефон:</div>
              <div class="font-bold">{{ $orderSelected->contact['phone'] }}</div>
            </div>

            @if ($orderSelected->contact['email'])
              <div class="flex items-center justify-start space-x-4 text-gray-600">
                <div class="w-4/12 ">Email:</div>
                <div class="font-bold">{{ $orderSelected->contact['email'] }}</div>
              </div>
            @endif

            <div class="flex items-center justify-start space-x-4 text-gray-600">
              <div class="w-4/12 ">Вид получения:</div>
              <div class="font-bold">
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
              <div class="font-bold">
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

            <div class="p-2 pt-4 text-gray-600 bg-white rounded-lg">
              <table class="table min-w-full text-xs table-fixed">
                <thead>
                  <tr class="text-left border-b border-gray-100">
                    <th class="px-1 py-2 ">Id товара</th>
                    <th class="px-1 py-2 ">Наименование</th>
                    <th class="px-1 py-2 ">Цена</th>
                    <th class="px-1 py-2 ">Скидка</th>
                    <th class="px-1 py-2 ">Кол-во</th>
                    <th class="px-1 py-2 ">Всего (со скидкой)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orderSelected->items as $item)
                    <tr class="text-left border-b border-gray-100">
                      <td class="px-1 py-2 w-18 ">{{ $item->product_id }}</td>
                      <td class="max-w-xs px-1 py-2 truncate ">{{ $item->name }}
                      </td>
                      <td class="px-1 py-2 ">{{ $item->price }}</td>
                      <td class="px-1 py-2 ">
                        @if ($item->discount)
                          {{ $item->discount }}
                        @endif
                        @if ($item->discount_comment)
                          ({{ $item->discount_comment }})
                        @endif
                      </td>
                      <td class="px-1 py-2 ">{{ $item->quantity }}</td>
                      <td class="px-1 py-2 ">{{ $item->amount }}</td>
                    </tr>
                  @endforeach
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="px-1 pt-2 text-base font-bold">{{ $orderSelected->quantity }}</td>
                    <td class="px-1 pt-2 text-base font-bold">{{ RUB($orderSelected->amount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="flex items-start justify-between w-full pt-2 space-x-8">

              <div class="max-w-xs space-y-1">
                <label for="smsText">SMS текст</label>
                @error('smsText')<span class="pl-4 text-sm text-red-500">{{ $message }}</span> @enderror
                <textarea wire:model.defer="smsText" name="smsText" id="smsText" rows="3"></textarea>
                <x-button wire:click="setSms({{ $orderSelected->contact['phone'] }})" :color="'blue'"
                  class="mb-1">
                  Отправить SMS
                </x-button>
              </div>

              <div class="flex items-end justify-end space-x-6">
                <div class="max-w-xs space-y-1">
                  <label for="ordersSatus">Статус заказа</label>
                  <select wire:model.defer="orderSelected.status" name="ordersSatus" id="ordersSatus"
                    class="w-full">
                    @foreach ($this->orderStatuses as $orderStatus)
                      <option {{ $orderSelected->status === $orderStatus ? 'selected' : '' }}
                        value="{{ $orderStatus }}">{{ __('constants.order_status.' . $orderStatus) }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <x-button wire:click="save" :color="'pink'" class="mb-1">
                  Сохранить
                </x-button>
              </div>

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
