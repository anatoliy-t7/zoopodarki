@section('title')
  Свойства
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)" @save.window="saveForm(event)"
    @close.window="closeForm(event)" @close-confirm.window="closeConfirm(event)" class="space-y-2">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Свойства товаров</h3>

      @can('create')
        <button @click="openForm()" wire:click="addNew()" id="add" title="Создать новое свойство"
          class="space-x-2 text-white bg-green-500 btn hover:bg-green-600">
          <x-tabler-plus class="w-6 h-6 text-white" />
          <div>Создать</div>
        </button>
      @endcan

    </div>

    <div class="flex items-center justify-start w-full space-x-6">

      <x-dashboard.search />

    </div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head sortable wire:click="sortBy('id')"
            :direction="$sortField === 'id' ? $sortDirection : null">Id
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('name')"
            :direction="$sortField === 'name' ? $sortDirection : null">
            Имя свойства</x-dashboard.table.head>
          <x-dashboard.table.head>Виды свойства</x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse($attributes as $key => $attribute)
            <x-dashboard.table.row>

              <x-dashboard.table.cell>
                {{ $attribute->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                <button x-on:click="openForm()" wire:click="openForm({{ $attribute->id }})" title="Edit"
                  class="text-blue-500 hover:underline">
                  {{ $attribute->name }}
                </button>

              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="w-8/12">
                <div class="flex flex-wrap items-center justify-start">
                  @forelse($attribute->items as $key => $item)
                    <div class="p-1">
                      <a title="Найти товары прикрепленные к свойству"
                        href="{{ route('dashboard.products.index', [
                            'filteredByAttribute' => true,
                            'search' => $item->name,
                            'attrId' => $attribute->id,
                        ]) }}"
                        target="_blank"
                        class="px-2 py-1 text-xs text-gray-500 rounded-full bg-gray-50 hover:bg-gray-200 ">
                        {{ $item->name }}
                      </a>
                    </div>
                  @empty
                  @endforelse
                </div>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">

                <button x-on:click="openForm()" wire:click="openForm({{ $attribute->id }})" title="Edit"
                  class="p-2 text-gray-400 rounded-lg hover:text-blue-500">
                  <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                    <path
                      d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                    </path>
                  </svg>
                </button>

                @can('delete')
                  <x-dashboard.confirm :confirmId="$attribute->id" wire:click="remove({{ $attribute->id }})" />
                @endcan

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
      <x-overflow-bg />

      <x-dashboard.modal>

        <x-loader wire:target="openForm, saveIt" />

        <div class="flex flex-col justify-between h-screen space-y-2">

          <div>
            <div class="flex space-x-8">

              <div class="w-6/12 space-y-1">
                <div class="font-bold">Имя свойства</div>
                <input wire:model.defer="name" type="text">
                @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
              </div>

              <div class="w-6/12 pt-8">

                <x-toggle wire:model="range" :property="$range" :lable="'Быть в фильтрах как ползунок диапазона'" />
                <span class="pl-16 text-xs text-gray-400">(будет работать если все значения цифры)</span>
              </div>
            </div>

            <div class="pt-8">
              <div class="font-bold">Виды свойства</div>
              <div class="pt-1 text-xs text-gray-400">(при
                удалении
                отцепляются все продукты)</div>
            </div>
          </div>

          <div id="bottom" class="h-full px-4 py-2 overflow-y-auto bg-white scrollbar rounded-xl">

            <template x-for="(field, index) in items" :key="index" hidden>
              <div class="flex items-center justify-start max-w-lg space-x-4 ">
                <span x-text="field.id" class="w-12 text-xs text-right text-gray-400"></span>
                <div class="w-full py-2">
                  <input x-ref="fieldName" x-model="field.name" type="text" class="field "
                    :class="confirm === index && 'text-red-500'">
                </div>

                <div class="w-12 pb-6 pl-1">
                  <div class="relative block cursor-pointer select-none " title="Показывать">
                    <input :id="'show' + index" type="checkbox" x-model="field.show" :checked="field.show == 1"
                      class="absolute inset-0 z-30 w-full h-full opacity-0 cursor-pointer">
                    <label :for="'show' + index" class="absolute inset-0 z-10 block cursor-pointer">
                      <x-tabler-eye class="w-6 h-6 text-gray-400" />
                    </label>
                  </div>
                </div>

                <a title="Товары прикрепленные к свойству" x-on:click="$wire.set('fildName', field.name)">
                  <x-tabler-route class="w-6 h-6 text-gray-400 hover:text-orange-400" />
                </a>

                <div x-cloak>
                  @can('delete')
                    <button type="button" title="remove" x-show="field.id === ''" x-on:click="removeField(index)"
                      class="relative p-2 ">
                      <x-tabler-trash class="w-6 h-6 text-gray-400 hover:text-red-500" />
                    </button>

                    <div x-show="field.id" class="relative">

                      <button x-on:click="askDelete(field.id)" type="button" title="remove"
                        class="relative p-2 hover:text-red-500">
                        <x-tabler-trash class="w-6 h-6 text-gray-400 hover:text-red-500" />
                      </button>

                      <div x-show="confirm == field.id" x-transition
                        class="absolute z-30 w-40 px-4 pt-4 pb-2 bg-white shadow-xl -top-4 -right-6 rounded-2xl"
                        x-on:click.outside="closeConfirm">
                        <h3 class="text-center">Вы уверены?</h3>
                        <div class="flex justify-around">
                          <button x-on:click="closeConfirm"
                            class="px-3 py-2 text-blue-400 rounded-lg hover:text-blue-500 focus:outline-none focus:ring hover:bg-gray-200"
                            type="button">
                            Нет
                          </button>
                          <button x-on:click="@this.call('removeItem', field.id)"
                            class="px-3 py-2 text-red-400 rounded-lg hover:text-red-600 focus:outline-none focus:ring hover:bg-gray-200">
                            Да
                          </button>
                        </div>
                      </div>

                    </div>
                  @endcan
                </div>

              </div>
            </template>

          </div>

          <div class="flex items-center justify-between w-full pt-3 pb-20 md:space-x-4">
            <div>
              @can('create')
                <button x-on:click="addNewField()" class="flex space-x-2 text-white bg-green-500 btn hover:bg-green-600">
                  <x-tabler-file-plus class="w-6 h-6 text-white" />
                  <div>Добавить вид</div>
                </button>
              @endcan
            </div>
            <button x-on:click="@this.call('saveIt', items)" class="text-white bg-pink-500 btn hover:bg-pink-700"
              wire:loading.attr="disabled">
              Сохранить свойство
            </button>
          </div>

        </div>

      </x-dashboard.modal>
    </div>

    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $attributes->links() }}
      </div>

      <div class="flex items-center justify-end w-4/12 space-x-4">
        <x-dashboard.items-per-page />
      </div>
    </div>

    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('handler', () => ({
          items: [{
            name: '',
            id: '',
            show: 1,
          }],
          attrName: '',
          form: false,
          confirm: null,
          body: document.body,
          addNewField() {
            this.items.push({
              name: '',
              id: '',
              show: 1,
            });
            setTimeout(function() {
              var div = document.getElementById('bottom');
              div.scrollTop = div.scrollHeight - div.clientHeight;
            }, 300);
          },
          removeField(index) {
            this.items.splice(index, 1);
            this.confirm = null;
          },
          openForm() {
            this.form = true
            this.body.classList.add("overflow-hidden")
          },
          closeForm() {
            this.form = false
            this.body.classList.remove("overflow-hidden")
          },

          saveForm() {
            window.livewire.emit('saveIt', this.items)
          },

          getItems(items) {
            this.items = items.detail;
          },
          askDelete(id) {
            this.confirm = id
          },
          closeConfirm() {
            this.confirm = null
          },
        }))
      })
    </script>

    <script>
      document.addEventListener("keydown", function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
          e.preventDefault();
          var event = new CustomEvent('save');
          window.dispatchEvent(event);
        }

        if (e.keyCode == 112) {
          e.preventDefault();
          var event = new CustomEvent('new');
          window.dispatchEvent(event);
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
