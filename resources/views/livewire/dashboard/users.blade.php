@section('title')
  Пользователи
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)" @save.window="saveForm(event)"
    @close.window="closeForm(event)" class="space-y-2">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Пользователи</h3>

      @can('create')
        <button @click="openForm()" id="add" title="Создать пользователя"
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
            :direction="$sortField === 'id' ? $sortDirection : null">
            Id
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('name')"
            :direction="$sortField === 'name' ? $sortDirection : null">
            Имя
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('phone')"
            :direction="$sortField === 'phone' ? $sortDirection : null">
            Телефон
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('email')"
            :direction="$sortField === 'email' ? $sortDirection : null">
            Email
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Права
          </x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse($users as $key => $user)
            <x-dashboard.table.row wire:key="{{ $user->id }}">

              <x-dashboard.table.cell>
                {{ $user->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $user->name }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $user->phone }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $user->email }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                @foreach ($user->roles as $role)
                  <span class="px-2 py-1 bg-gray-100 rounded-xl">{{ $role->name }}</span>
                @endforeach
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">

                <button @click="openForm" wire:click="openForm({{ $user->id }})" title="Edit"
                  class="p-2 text-gray-400 rounded-lg hover:text-indigo-500">
                  <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                    <path
                      d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                    </path>
                  </svg>
                </button>

                <x-dashboard.confirm :confirmId="$user->id" wire:click="remove({{ $user->id }})" />

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

      <x-dashboard.modal class="overflow-y-auto">

        <x-loader wire:target="openForm, remove" />

        <div class="space-y-4">
          <div class="flex items-start justify-between space-x-6">

            <div class="w-6/12 space-y-4">

              <div class="space-y-1">
                <div class="font-bold">Имя пользователя</div>
                <input wire:model.defer="name" type="text">
                @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
              </div>

              <div class="space-y-1">
                <div class="font-bold">Email</div>
                <input wire:model.defer="email" type="email">
                @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
              </div>

            </div>

            <div class="w-6/12 space-y-4">

              <div class="space-y-1">
                <div class="font-bold">Телефон</div>
                <input wire:model.defer="phone" type="tel">
                @error('phone') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
              </div>

              <div class="space-y-1">
                <div class="font-bold">Пароль</div>
                <input wire:model.defer="password" type="text">
                @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
              </div>
            </div>

          </div>


          <div class="flex items-start justify-between space-x-6">

            <div class="py-2">
              <x-toggle wire:model="company" :property="$company" :lable="'Компания'" />
            </div>

            <div class="flex items-center justify-between space-x-4">
              <div class="font-bold">Скидка по карте % (число)</div>
              <div class="w-16">
                <input wire:model.defer="discount" type="number" class="field">
              </div>
              @error('discount')
                <span class="text-sm text-red-500">{{ $message }}</span>
              @enderror
            </div>

          </div>

          <div class="py-2 ">

            <div class="pb-4">
              <div class="font-bold">Права пользователя</div>
              <div class="text-xs">(клиенту не надо указывать
                права)</div>
            </div>

            <div class="h-full p-4 space-y-3 overflow-y-auto bg-white rounded-lg" style="max-height: 270px;">
              @foreach ($roles as $role)
                <div wire:key="{{ $loop->index }}" class="container-checkbox">
                  <span class="text-xs">{{ $role->name }}</span>
                  <input wire:model.lazy="userRoles" value="{{ $role->name }}" type="checkbox">
                  <span class="checkmark"></span>
                </div>
              @endforeach
            </div>
          </div>

          <div class="flex items-center justify-end">
            <button wire:click="save" class="text-white bg-pink-500 btn hover:bg-pink-600">
              Сохранить
            </button>
          </div>
        </div>


      </x-dashboard.modal>
    </div>

    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $users->links() }}
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

          askDelete($id) {
            this.confirm = $id
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
          window.livewire.emit('save')
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
