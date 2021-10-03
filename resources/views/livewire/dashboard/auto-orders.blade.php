@section('title')
Автозаказы (в разработке)
@endsection
<div>

  <div class="flex items-center justify-start pb-6 space-x-4">
    <div>
      <a title="Вернуться" href="javascript:%20history.go(-1)" class="text-gray-300 hover:text-gray-500">
        <x-tabler-arrow-left class="w-6 h-6" />
      </a>
    </div>
    <h3 class="text-xl font-bold text-gray-500">Автозаказы</h3>
  </div>


  <div x-data="handler" @set-user.window="setUserFromServer(event)" @set-users.window="setUsersFromServer(event)"
    @save.window="saveIt(event)" @close.window="closeForm(event)" @set-products.window="setProducts(event)"
    @set-user.window="setUserServer(event)" @set-calendar.window="setCalendar(event)" @save.window="saveIt(event)">

    <div class="flex items-center justify-start w-full pb-4 space-x-8">

      <button @click="openForm()" id="add" title="Создать новый товар"
        class="flex px-3 py-2 space-x-2 text-white bg-green-500 shadow cursor-pointer rounded-xl hover:bg-green-700">
        <x-tabler-file-plus class="w-6 h-6 text-white" />
        <div>Создать</div>
      </button>

      <x-dashboard.search placeholder="Поиск по Имени, Email и телефону пользователя" />

    </div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head sortable wire:click="sortBy('id')"
            :direction="$sortField === 'id' ? $sortDirection : null">Id
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('next_order')"
            :direction="$sortField === 'next_order' ? $sortDirection : null">
            Следующий заказ</x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('periodicity')"
            :direction="$sortField === 'periodicity' ? $sortDirection : null">Периодичность</x-dashboard.table.head>
          <x-dashboard.table.head>Пользователь</x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse($autoOrders as $key => $autoOrder)
          <x-dashboard.table.row>

            <x-dashboard.table.cell>
              {{ $autoOrder->id }}
            </x-dashboard.table.cell>

            <x-dashboard.table.cell>
              {{ $autoOrder->next_order }}
            </x-dashboard.table.cell>

            <x-dashboard.table.cell>
              {{ $autoOrder->periodicity }}
            </x-dashboard.table.cell>

            <x-dashboard.table.cell>
              {{ $autoOrder->user->name }}
            </x-dashboard.table.cell>

            <x-dashboard.table.cell class="flex justify-end ">

              <button @click="openForm" wire:click="openForm({{ $autoOrder->id }})" title="Edit"
                class="p-2 text-gray-500 rounded-lg hover:text-blue-500">
                <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                  <path
                    d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                  </path>
                </svg>
              </button>

              <x-dashboard.confirm :confirmId="$autoOrder->id" wire:click="remove({{ $autoOrder->id }})" />

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

    <x-overflow-bg />

    <x-dashboard.modal>

      <div class="space-y-6">
        <div class="flex items-start justify-start w-full space-x-6 text-gray-700">

          <div class="w-64 space-y-1">
            <label for="users">Пользователь</label>
            <div wire:ignore>
              <input id="users" name='users' class="field">
            </div>
            @error ('user')
            <span class="text-xs text-red-500">
              Выберите пользователя
            </span>
            @enderror
          </div>

          <div class="space-y-1">
            <label for="periodicity">Периодичность</label>
            <select wire:model="periodicity" name="periodicity" class="mt-1 field">
              <option value="1">Раз в неделю</option>
              <option value="2">Раз в 2 недели</option>
              <option value="3">Раз в 3 недели</option>
              <option value="4">Раз в 4 недели</option>
              <option value="5">Раз в месяц</option>
            </select>
            @error ('status') <span class="text-xs text-red-500">Поле обязательно для
              заполнения</span> @enderror
          </div>

          <div class="w-40 space-y-1">

            <label for="calendar">
              Дата доставки
            </label>
            <div wire:ignore class="relative">
              <input wire:model.defer="nextOrder" id="calendar" type="text" readonly class="field" name="date">
              <div class="absolute top-2 right-3">
                <svg class="w-6 h-6 text-gray-400 fill-current" viewBox="0 0 24 24">
                  <path
                    d="M19,4H17V3a1,1,0,0,0-2,0V4H9V3A1,1,0,0,0,7,3V4H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V7A3,3,0,0,0,19,4Zm1,15a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H20Zm0-9H4V7A1,1,0,0,1,5,6H7V7A1,1,0,0,0,9,7V6h6V7a1,1,0,0,0,2,0V6h2a1,1,0,0,1,1,1Z" />
                </svg>
              </div>
            </div>
            @error ('nextOrder')
            <span class="text-xs text-red-600">
              Выберите дату доставки
            </span>
            @enderror
          </div>

        </div>


        <div>

          <div class="flex space-x-2">
            <div>
              Товары
            </div>
            <div>
              @if (empty($variations))
              <span class="text-xs text-red-500">
                (Вы должны выбрать как минимум один товар)
              </span>
              @endif
            </div>
          </div>

          @if ($autoOrderProducts1c)
          <div class="divide-y divide-gray-100">
            @foreach ($autoOrderProducts1c as $autoOrderProduct)
            <div class="flex items-center justify-between p-1 space-x-2">
              <div class="text-sm">
                {{ $autoOrderProduct->product->name }}
              </div>
              <div class="font-semibold">
                {{ kg($autoOrderProduct->unit_value)}} / {{ RUB($autoOrderProduct->price) }}
              </div>
            </div>
            @endforeach
          </div>
          @endif
        </div>


        <div class="flex items-center justify-between md:space-x-4">
          <div>
            <button @click="formItem = true"
              class="flex px-3 py-2 space-x-2 text-white bg-green-500 cursor-pointer rounded-xl hover:bg-green-600">
              <x-tabler-file-plus class="w-6 h-6 text-white" />
              <div>Добавить товары</div>
            </button>
          </div>
          <button x-on:click="saveIt()"
            class="p-2 px-3 text-white bg-pink-500 cursor-pointer rounded-2xl hover:bg-pink-700">
            Сохранить
          </button>
        </div>

      </div>

    </x-dashboard.modal>

    <div x-cloak :class="formItem ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
      class="fixed top-0 left-0 z-50 w-full h-full max-w-md min-w-full min-h-screen px-8 py-6 overflow-y-auto transition duration-300 transform bg-white border-r md:min-w-half">
      <div class="space-y-2">
        <div class="flex justify-end w-full">
          <button @click="formItem = false" class="text-gray-600 link-hover">
            <x-tabler-circle-x class="w-6 h-6 stroke-current" />
          </button>
        </div>

        <div class="relative w-full max-w-xl">
          <input
            class="w-full py-2 pl-10 pr-4 font-medium text-gray-600 placeholder-gray-300 bg-gray-100 rounded-lg shadow focus:outline-none focus:ring"
            wire:model.debounce.600ms="searchProducts" placeholder="Поиск по названию, 1с коду, штрих-коду и артиклу">
          <div class="absolute top-0 left-0 inline-flex items-center p-2">
            <x-tabler-search class="w-6 h-6 text-gray-400" />
          </div>
        </div>

        <div>
          @if ($products)
          <div class="px-2 py-4 divide-y divide-gray-100">
            @foreach ($products as $item)

            <div wire:key="{{ $item->id }}" x-on:click="productId = {{ $item->id }}"
              class="p-1 cursor-pointer hover:bg-gray-50">
              <div class="flex items-center justify-between space-x-2 text-sm">
                <div>{{ $item->name }}</div>
                <div></div>
              </div>
              <div class="flex items-center justify-start p-2 space-x-2" x-show="productId === {{ $item->id }}">
                @foreach ($item->variations as $variation)

                <div wire:key="{{ $variation->id }}" wire:click.="setProductToOrder({{ $variation->id }})"
                  class="px-2 py-1 border border-blue-500 hover:bg-blue-500 rounded-xl hover:text-white">
                  <div>
                    {{ kg($variation->unit_value)}} / {{ RUB($variation->price) }}
                  </div>
                </div>

                @endforeach
              </div>
            </div>

            @endforeach
          </div>

          <div class="w-full">
            {{ $products->links() }}
          </div>
          @endif
        </div>
      </div>
    </div>



    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $autoOrders->links() }}
      </div>

      <div class="flex items-center justify-end w-4/12 space-x-4">
        <x-dashboard.items-per-page />
      </div>
    </div>


    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('handler', () => ({
              users: [],
              tagifyUsers: null,
              user: null,
              form: false,
              formItem: false,
              confirm: null,
              body: document.body,

              productId: null,
              variations: [],

              getProductToOrder(variationId) {
                this.variations.push({
                  'id': variationId,
                });
              },

              openForm() {
                  this.form = true
                  this.body.classList.add("overflow-hidden")
                  window.livewire.emit('sendDataToFrontend')
                  
              },
              closeForm() {
                  this.form = false
                  this.formItem = false
                  this.body.classList.remove("overflow-hidden")
                  this.tagifyUsers.removeAllTags();
              },

              saveIt() {
                user = this.mapItBack();
                window.livewire.emit('save', user)
              },

              getItems(items) {
                  this.items = items.detail;
              },
              askDelete($id) {
                  this.confirm = $id
              },
              closeConfirm() {
                  this.confirm = null
              },

              setUsersFromServer(users) {
                if (this.tagifyUsers === null) {
                  this.users = null;
                  this.users = users.detail;

                  this.users = this.users.map(({
                      id: id,
                      name: value
                  }) => ({
                      id,
                      value
                  }));
                  this.initUsers()
                }
              },

              setUserFromServer(user) {
                  this.user = null;
                  this.user = [user.detail];
                  this.user = this.user.map(({
                      id: id,
                      name: value
                  }) => ({
                      id,
                      value
                  }));
                
                 
                    this.tagifyUsers.addTags(this.user);
                  
              },

              initUsers() {
                  var inputElm = document.querySelector('input[name=users]');
                  this.tagifyUsers = new Tagify(inputElm, {
                      whitelist: this.users,
                      dropdown: {
                          classname: "w-full",
                          enabled: 0,
                          maxItems: 100,
                          position: "all",
                          closeOnSelect: true,
                          highlightFirst: true,
                          searchKeys: ["value"],
                          fuzzySearch: false,
                      },
                      addTagOnBlur: false,
                      editTags: false,
                      maxTags: 1,
                      skipInvalid: true,
                      enforceWhitelist: true,
                      delimiters: "`",
                  });
                  this.tagifyUsers.addTags(this.user)

                  this.tagifyUsers.on('change', e => {

                      if (e.detail.value) {
                          this.user = JSON.parse(e.detail.value)
                      } else {
                          this.user = []
                      }

                  })
              },

              mapItBack() {
              if (this.user !== null){
                user = this.user.map(({
                    id: id,
                    value: name
                }) => ({
                    id,
                    name
                }));
              } else {
                user = [];
              }
              return user;
            },

            setCalendar(date) {
              const fp = flatpickr("#calendar", {
                altInput: true,
                altFormat: "j M Y",
                dateFormat: "Y-m-d",
              });      
              fp.formatDate(date.detail, "Y-m-d");
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

  @push('header-css')
  <link href="{{ mix('css/tagify.css') }}" rel="stylesheet">
  <link href="{{ mix('css/calendar.css') }}" rel="stylesheet">
  @endpush
  @push('footer')
  <script src="{{ mix('js/tagify.min.js') }}"></script>
  <script src="{{ mix('js/calendar.js') }}"></script>
  @endpush

</div>