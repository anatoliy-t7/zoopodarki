@section('title')
  Акции и товары 1C
@endsection
<div>
  <div class="flex items-center justify-start pb-6 space-x-4">
    <h3 class="text-2xl">Акции и товары 1C</h3>
  </div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)"
    @close.window="closeForm(event)" @open-form.window="openForm(event)">

    <div class="flex items-center justify-start w-full space-x-6">
      <x-dashboard.search />

      <x-toggle wire:model="onlyPromotions" :property="$onlyPromotions" :lable="'Только акции'" />
    </div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head sortable wire:click="sortBy('id')"
            :direction="$sortField === 'id' ? $sortDirection : null">Id
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('name')"
            :direction="$sortField === 'name' ? $sortDirection : null">
            Название</x-dashboard.table.head>
          @if ($onlyPromotions)
            <x-dashboard.table.head sortable wire:click="sortBy('promotion_type')"
              :direction="$sortField === 'promotion_type' ? $sortDirection : null">Вид акции</x-dashboard.table.head>
          @endif

          <x-dashboard.table.head sortable wire:click="sortBy('price')"
            :direction="$sortField === 'price' ? $sortDirection : null">Цена</x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('weight')"
            :direction="$sortField === 'weight' ? $sortDirection : null">Вес</x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('stock')"
            :direction="$sortField === 'stock' ? $sortDirection : null">Запас</x-dashboard.table.head>
          <x-dashboard.table.head>Единица измерения</x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('cod1c')"
            :direction="$sortField === 'cod1c' ? $sortDirection : null">Код1С</x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('barcode')"
            :direction="$sortField === 'barcode' ? $sortDirection : null">Штрихкод</x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse ($products1c as $product1c)
            <x-dashboard.table.row wire:key="{{ $loop->index }}">

              <x-dashboard.table.cell>
                {{ $product1c->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>

                <div class="flex items-center justify-start max-w-sm space-x-2">

                  @if ($product1c->product)
                    <a class="font-semibold text-orange-500 hover:text-orange-600" target="_blank"
                      href="/dashboard/products/edit?id={{ $product1c->product->id }}">
                      <x-tabler-external-link class="w-5 h-5" />
                    </a>
                  @endif

                  <button wire:click="openForm({{ $product1c->id }})" title="{{ $product1c->name }}"
                    class="max-w-md truncate hover:text-blue-500">
                    {{ $product1c->name }}
                  </button>


              </x-dashboard.table.cell>

              @if ($onlyPromotions)
                <x-dashboard.table.cell>
                  @if ($product1c->promotion_type === 1)
                    {{ $promotions['1'] }}
                  @elseif ($product1c->promotion_type === 2)
                    {{ $promotions['2'] }}
                  @elseif ($product1c->promotion_type === 3)
                    {{ $promotions['3'] }}
                  @elseif ($product1c->promotion_type === 4)
                    {{ $promotions['4'] }}
                  @endif
                </x-dashboard.table.cell>
              @endif

              <x-dashboard.table.cell>
                @if ($product1c->promotion_type === 0 or $product1c->promotion_type === 2)
                  {{ RUB($product1c->price) }}
                @elseif($product1c->promotion_type === 1 or $product1c->promotion_type === 3)
                  <span class="pr-1 line-through">
                    {{ RUB($product1c->promotion_price) }}
                  </span>
                  <b>{{ RUB($product1c->price) }}</b>
                @elseif($product1c->promotion_type === 4)
                  <span class="pr-1 line-through">{{ RUB($product1c->price) }}</span>
                  <b>{{ RUB(discount($product1c->price, $product1c->promotion_percent)) }}</b>
                @endif
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $product1c->weight }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $product1c->stock }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                @if ($product1c->product)
                  <x-units :unit="$product1c->product->unit" :value="$product1c->unit_value"
                    :wire:key="$product1c->product->id" />
                @endif
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $product1c->cod1c }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $product1c->barcode }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">
                <div class="flex justify-end space-x-3">

                  <button wire:click="openForm({{ $product1c->id }})" title="Edit">
                    <svg class="fill-current w-7 h-7 hover:text-blue-500" xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 32 32" title="edit">
                      <path
                        d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                      </path>
                    </svg>
                  </button>

                </div>
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


    <x-overflow-bg @click="closeForm" />

    <x-dashboard.modal class="overflow-y-auto">

      <x-loader wire:target="openForm" />

      @if ($product1c)
        <div class="block space-y-4">

          <div class="space-y-1">
            <div class="font-bold">Название товара</div>
            <div>{{ $product1c['name'] }}</div>
          </div>

          <div class="block space-y-6">

            @if ($product1c['promotion_type'] === 0)

              <div class="block space-y-1 w-60 ">
                <label for="promotion" class="block font-bold">Вид акции</label>
                <select wire:model="promotion.type" name="promotion" id="promotion" class="w-full">
                  <option selected value="">Выберите акцию</option>
                  @foreach ($promotions as $key => $promo)
                    <option value="{{ $key }}">{{ $promo }}</option>
                  @endforeach
                </select>
              </div>

              <div class="flex space-x-8">

                @if ($promotion['type'] === '2')
                  <div class="w-6/12 space-y-1">
                    <label for="stock" class="font-bold">Количество товара <span class="font-normal ">(максимум:
                        {{ $product1c['stock'] }})</span></label>
                    <div class="w-20">
                      <input wire:model.defer="promotion.stock" type="number" min="1" max="{{ $product1c['stock'] }}"
                        id="stock">
                    </div>
                    @error('promotion.stock') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                  </div>
                @endif

                @if ($promotion['type'] === '2' or $promotion['type'] === '4')
                  <div class="w-6/12 space-y-1">
                    <label for="date" class="font-bold">Дата завершения</label>
                    <input type="date" id="date" wire:model.defer="promotion.date">
                    @error('promotion.date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                  </div>
                @endif

                @if ($promotion['type'] === '3' or $promotion['type'] === '4')
                  <div class="w-6/12 space-y-1">
                    <label for="percent" class="font-bold">Процент скидки
                      <span class="font-normal ">(указать без знака %)</span>
                    </label>
                    <div class="w-20">
                      <input type="number" id="percent" wire:model.defer="promotion.percent">
                    </div>
                    @error('promotion.percent') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                  </div>
                @endif

              </div>

            @else

              <div class="block w-6/12 space-y-1">
                <div>Цена товара: <b>{{ $product1c['price'] }}</b></div>
                @if ($product1c['promotion_price'] !== null)
                  <div>Цена акции: <b>{{ $product1c['promotion_price'] }}</b></div>
                @endif
              </div>

              <div class="w-6/12 space-y-1">
                <div>Количество товара: <b>{{ $product1c['stock'] }}</b></div>
                @if ($product1c['promotion_percent'] !== null)
                  <div>Процент акции: <b>{{ $product1c['promotion_percent'] }}</b></div>
                @endif
                @if ($product1c['promotion_date'] !== null)
                  <div>Окончание акции: <b>{{ $product1c['promotion_date'] }}</b></div>
                @endif
              </div>

            @endif


          </div>
          <div class="py-2 font-bold text-gray-600">
            <div class="pt-6">
              @if ($product1c['promotion_type'] === 0)
                <button wire:click="save" class="text-white bg-green-500 btn hover:bg-green-600">
                  Сохранить
                </button>
              @else
                <button wire:click="stop" class="text-white bg-red-500 btn hover:bg-red-600">
                  Прекратить акцию
                </button>
              @endif
            </div>
          </div>

        </div>
      @endif

    </x-dashboard.modal>

    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $products1c->links() }}
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
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
          e.preventDefault();
          window.livewire.emit('save')
        }
        if (e.keyCode == 27) {
          e.preventDefault();
          var event = new CustomEvent('close');
          window.dispatchEvent(event);
        }
      }, false);
    </script>


  </div>
</div>
