@section('title')
  Права пользователей
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)" @save.window="saveForm(event)"
    @close.window="closeForm(event)">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Права пользователей</h3>

      @can('create')
        <button @click="openForm()" id="add" title="Создать новую роль"
          class="space-x-2 text-white bg-green-500 btn hover:bg-green-600">
          <x-tabler-plus class="w-6 h-6 text-white" />
          <div>Создать</div>
        </button>
      @endcan

    </div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head>Id</x-dashboard.table.head>
          <x-dashboard.table.head>Права</x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse($permissions as $key => $permission)
            <x-dashboard.table.row wire:key="{{ $loop->index }}">

              <x-dashboard.table.cell>
                {{ $permission->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $permission->name }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">

                <button @click="openForm" wire:click="openForm({{ $permission->id }})" title="Edit"
                  class="p-2 text-gray-400 rounded-lg hover:text-indigo-500">
                  <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                    <path
                      d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                    </path>
                  </svg>
                </button>

                <x-dashboard.confirm :confirmId="$permission->id" wire:click="remove({{ $permission->id }})" />

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

        <div class="block space-y-4">


          <div class="space-y-1">
            <div class="font-bold">Имя</div>
            <input wire:model.defer="name" type="text" class="max-w-sm">
            @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
          </div>

          <div class="flex items-center justify-end">
            <button wire:click="save" class="text-white bg-pink-500 btn hover:bg-pink-600">
              Сохранить
            </button>
          </div>

        </div>

      </x-dashboard.modal>

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
