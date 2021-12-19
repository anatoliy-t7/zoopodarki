@section('title')
  Бренды
@endsection
<div>

  @push('header-css')
    <style>
      .trix-button-group--file-tools {
        display: none !important;
      }

      .trix-button--icon-code {
        display: none !important;
      }

      .trix-button--icon-quote {
        display: none !important;
      }

    </style>
  @endpush

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)" @save.window="saveForm(event)"
    @close.window="closeForm(event)" class="space-y-2">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Бренды</h3>

      @can('create')
        <button @click="openForm()" id="add" title="Создать новый бренд"
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
            Бренд</x-dashboard.table.head>
          <x-dashboard.table.head>Серии</x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse($brands as $key => $brand)
            <x-dashboard.table.row>

              <x-dashboard.table.cell>
                {{ $brand->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                <button class="hover:text-blue-500" @click="openForm" wire:click="openForm({{ $brand->id }})"
                  title="Edit">
                  {{ $brand->name }}</button>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="w-8/12">
                <div class="flex flex-wrap items-center justify-start">
                  @forelse($brand->items as $key => $item)
                    <div class="p-1">
                      <div class="px-2 py-1 text-xs text-white bg-blue-500 rounded-full ">
                        {{ $item->id }} | {{ $item->name }}</div>
                    </div>
                  @empty

                  @endforelse
                </div>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">

                <button @click="openForm" wire:click="openForm({{ $brand->id }})" title="Edit"
                  class="p-2 text-gray-400 rounded-lg hover:text-blue-500">
                  <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                    <path
                      d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                    </path>
                  </svg>
                </button>

                @can('delete')
                  <x-dashboard.confirm :confirmId="$brand->id" wire:click="remove({{ $brand->id }})" />
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
            <div class="flex space-x-6">

              <div class="w-6/12 space-y-4">

                <div class="space-y-1">
                  <label class="font-bold">Название</label>
                  <input wire:model="name" type="text">
                  @error('name')
                    <span class="text-sm text-red-500">
                      {{ $message }}
                    </span>
                  @enderror
                </div>

                <div class="space-y-1">
                  <label class="font-bold">Название на русском</label>
                  <input wire:model.defer="nameRus" type="text">
                  @error('nameRus')
                    <span class="text-sm text-red-500">
                      {{ $message }}
                    </span>
                  @enderror
                </div>

              </div>

              <div class="w-6/12 space-y-4">

                <div class="space-y-1">
                  <label class="font-bold">Meta заголовок</label>
                  <input wire:model.defer="meta_title" type="text">
                  @error('meta_title')
                    <span class="text-sm text-red-500">
                      {{ $message }}
                    </span>
                  @enderror
                </div>

                <div class="space-y-1">
                  <label class="font-bold">Meta описание</label>
                  <textarea rows="2" wire:model.defer="meta_description" name="meta_description"></textarea>
                  @error('meta_description')
                  <span class="block text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="flex space-x-6">



              <div class="w-3/12 space-y-2">
                <div class="font-bold text-gray-600 ">Лого бренда</div>
                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                  x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                  x-on:livewire-upload-progress="progress = $event.detail.progress">

                  <!-- File Input -->
                  <div
                    class="relative w-full h-32 text-center bg-pink-100 border-2 border-gray-400 border-dashed rounded-lg hover:border-pink-200">
                    @if ($logo)
                      <img loading="lazy" class="object-contain object-center w-full h-32 pb-1"
                        src="{{ $logo->temporaryUrl() }}">
                    @elseif($logoName)
                      <div x-data="{ confirm: false}" class="">

                        <div class="relative">
                          <img loading="lazy" class="object-contain object-center w-full h-32 pb-1"
                            src="/brands/{{ $logoName }}">

                          <div class="absolute z-40 -top-8 -right-1">
                            <button x-on:click="confirm = true" type="button" title="remove"
                              class="p-1 text-sm text-red-500 rounded-full hover:text-white focus:outline-none focus:ring hover:bg-red-500">
                              <x-tabler-circle-x class="w-6 h-6" />
                            </button>
                          </div>

                          <div x-show="confirm == true" x-transition.opacity
                            class="absolute top-0 z-40 w-40 px-4 py-3 bg-white shadow-xl left-4 rounded-2xl">
                            <h3 class="text-sm">Вы уверены?</h3>
                            <div class="flex justify-around">
                              <button x-on:click="confirm = false"
                                class="px-3 py-2 text-red-500 rounded-lg hover:text-red-600 focus:outline-none focus:ring hover:bg-gray-200"
                                type="button">
                                Нет
                              </button>
                              <button x-on:click="confirm = false" wire:click="removeImage()"
                                class="px-3 py-2 text-green-500 rounded-lg hover:text-green-600 focus:outline-none focus:ring hover:bg-gray-200"
                                type="button">
                                Удалить
                              </button>
                            </div>
                          </div>
                        </div>

                      </div>
                    @else
                      <div class="pt-8 text-sm text-center">Скиньте сюда изображение или кликните для загрузки</div>
                    @endif
                    <input type="file" wire:model="logo" ondragover="drag()" ondrop="drop()" id="uploadFile"
                      class="absolute top-0 left-0 z-30 w-full h-full opacity-0 cursor-pointer">
                  </div>

                  @error('logo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

                  <div x-show="isUploading">
                    <progress max="100" x-bind:value="progress"></progress>
                  </div>
                </div>
              </div>

              <div class="w-9/12">

                <div x-data="{ description: @entangle('description').defer }" x-init="$watch('description', function (value) {
                             $refs.trix.editor.loadHTML(value)
                             var length = $refs.trix.editor.getDocument().toString().length
                             $refs.trix.editor.setSelectedRange(length - 1)
                             }
                         )" wire:ignore class="space-y-2">
                  <label class="font-bold">Описание</label>
                  <input id="description" name="description" x-model="description" type="hidden" />
                  <div wire:ignore x-on:trix-blur="description = $refs.trix.value"
                    class="w-auto p-4 bg-white rounded-xl">
                    <trix-editor x-ref="trix" input="description" class="h-48 overflow-y-scroll">
                    </trix-editor>
                  </div>
                  @error('description')
                  <span class="block text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>

            <div class="pt-4 font-bold">Серии</div>
          </div>

          <div id="bottom" class="h-full px-4 py-2 space-y-2 overflow-y-auto bg-white scrollbar rounded-xl">
            <template x-for="(field, index) in items" :key="index" hidden>
              <div class="flex items-center justify-start w-full max-w-lg space-x-4">
                <div class="flex items-center justify-start space-x-4">
                  <input x-model="field.name" type="text" class="field" placeholder="Название">
                  <input x-model="field.name_rus" type="text" class="field" placeholder="На русском">
                </div>

                <div>
                  @can('delete')

                    <button x-cloak type="button" title="remove" x-show="field.name === ''"
                      x-on:click="removeField(index)" class="relative p-2 ">
                      <x-tabler-trash class="w-6 h-6 text-gray-400 hover:text-red-500" />
                    </button>

                    <div x-cloak x-show="field.name" class="relative">
                      <button x-on:click="askDelete(index)" type="button" title="remove"
                        class="relative p-2 text-gray-500 rounded-lg hover:text-red-500">
                        <x-tabler-trash class="w-6 h-6 text-gray-400 hover:text-red-500" />
                      </button>
                      <div x-show="confirm == index" x-transition
                        class="absolute z-30 w-40 px-4 pt-4 pb-2 bg-white shadow-xl -top-4 -right-6 rounded-2xl"
                        x-on:click.outside="closeConfirm">
                        <h3 class="text-center">Вы уверены?</h3>
                        <div class="flex justify-around">
                          <button x-on:click="closeConfirm"
                            class="px-3 py-2 text-blue-400 rounded-lg hover:text-blue-500 focus:outline-none focus:ring hover:bg-gray-200"
                            type="button">
                            Нет
                          </button>
                          <button x-on:click="@this.call('removeItem', field)" x-on:click="removeField(index)"
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
                <button @click="addNewField()"
                  class="flex px-3 py-2 space-x-2 text-white bg-green-500 cursor-pointer rounded-xl hover:bg-green-600">
                  <x-tabler-file-plus class="w-6 h-6 text-white" />
                  <div>Добавить серию</div>
                </button>
              @endcan
            </div>
            <button @click="saveForm"
              class="p-2 px-3 text-white bg-pink-500 cursor-pointer rounded-xl hover:bg-pink-700">
              Сохранить бренд
            </button>
          </div>

        </div>

      </x-dashboard.modal>
    </div>

    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $brands->links() }}
      </div>

      <div class="flex items-center justify-end w-4/12 space-x-4 ">
        <x-dashboard.items-per-page />
      </div>
    </div>


    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('handler', () => ({
          items: [],
          form: false,
          confirm: null,
          body: document.body,

          addNewField() {
            this.items.push({
              name: '',
              name_rus: '',
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
            window.livewire.emit('save', this.items)
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

    <script>
      function drag() {
        document.getElementById('uploadFile').parentNode.className = 'draging dragBox';
      }

      function drop() {
        document.getElementById('uploadFile').parentNode.className = 'dragBox';
      }
    </script>


  </div>
</div>
