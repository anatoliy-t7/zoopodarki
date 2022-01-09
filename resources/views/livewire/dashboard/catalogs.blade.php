@section('title')
  Каталоги и категории
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)" @save.window="saveForm(event)"
    @close.window="closeForm(event)" @close-form-category.window="closeFormCategory(event)" class="space-y-2">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Каталоги и категории</h3>

      @can('create')
        <button @click="openForm()" wire:click="resetFields()" id="add" title="Создать новый каталог"
          class="space-x-2 text-white bg-green-500 btn hover:bg-green-600">
          <x-tabler-file-plus class="w-6 h-6 text-white" />
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
          <x-dashboard.table.head sortable wire:click="sortBy('sort')"
            :direction="$sortField === 'sort' ? $sortDirection : null">sort
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('name')"
            :direction="$sortField === 'name' ? $sortDirection : null">
            Каталог</x-dashboard.table.head>
          <x-dashboard.table.head>Категории</x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse($catalogs as $key => $catalog)
            <x-dashboard.table.row>

              <x-dashboard.table.cell>
                {{ $catalog->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $catalog->sort }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                <button x-on:click="openForm()" wire:click="openForm({{ $catalog->id }})" title="Edit"
                  class="flex items-center justify-start text-blue-500 hover:underline">
                  @if ($catalog->menu == 1)
                    <div title="Показывать в меню">
                      <x-tabler-layout-2 class="w-4 h-4 -ml-6 text-gray-400 stroke-current" />
                    </div>
                  @endif
                  <div>{{ $catalog->name }}</div>
                </button>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="w-8/12">
                <div class="flex flex-wrap items-center justify-start text-gray-500">
                  @forelse($catalog->categories->sortBy('sort') as $key => $category)

                    <div class="pr-1">{{ $category->name }} |</div>

                  @empty

                  @endforelse
                </div>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">

                <button @click="openForm" wire:click="openForm({{ $catalog->id }})" title="Edit"
                  class="p-2 text-gray-400 rounded-lg hover:text-blue-500">
                  <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                    <path
                      d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                    </path>
                  </svg>
                </button>
                @can('delete')
                  <x-dashboard.confirm :confirmId="$catalog->id" wire:key="remove{{ $catalog->id }}" />
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

    <div class="relative">
      <x-overflow-bg x-on:click="closeForm" />

      <x-dashboard.modal>

        <x-loader wire:target="openForm, save" />

        <div class="flex flex-col justify-between h-screen space-y-2">

          <div class="">
            <div class="flex items-start justify-between space-x-6">
              <div class="w-6/12 space-y-4">

                <div class="space-y-1">
                  <div class="font-bold">Каталог</div>
                  <input wire:model="editCatalog.name" type="text">
                  @error('editCatalog.name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="pb-4 space-y-1">
                  <div class="font-bold">URL (slug)</div>
                  <input wire:model.defer="editCatalog.slug" type="text">
                  @error('editCatalog.slug') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between space-x-6">
                  <x-toggle wire:model="editCatalog.menu" :property="$editCatalog['menu']"
                    :lable="'Показывать в меню'" />

                  <div class="flex items-center gap-2">
                    <div class="font-bold">Сортировка</div>
                    <div class="w-16">
                      <input wire:model.defer="editCatalog.sort" type="number"
                        class="w-16 px-3 py-2 bg-white border-2 border-pink-50 hover:border-pink-200 rounded-xl focus:outline-none focus:ring focus:border-transparent">
                    </div>
                  </div>

                </div>

                <div class="flex items-center justify-start w-full">

                  <div class="w-full space-y-1">
                    <label class="font-bold">ID брендов</label>

                    <div x-data="{tags: @entangle('brandsForCatalog').defer, newTag: '' }">
                      <template x-for="(tag, index) in tags" :key="index">
                        <input type="hidden" x-bind:value="tag">
                      </template>
                      <div class="w-full">
                        <div class="flex flex-wrap gap-2 p-2 bg-white rounded-xl ">
                          <template x-for="(tag, index) in tags" :key="index">
                            <span
                              class="relative flex items-center gap-1 px-2 py-1 text-sm leading-normal text-blue-100 bg-blue-500 select-none rounded-xl group">
                              <span x-text="tag"></span>
                              <button type="button"
                                class="absolute inset-0 z-30 flex items-center justify-center invisible w-full h-full bg-red-500 rounded-xl group-hover:visible"
                                @click="tags = tags.filter(i => i !== tag)">
                                <x-tabler-circle-x class="w-6 h-6" />
                              </button>
                            </span>
                          </template>

                          <input class="inline-flex flex-1 w-24 py-1 outline-none"
                            placeholder="ввести id и нажать enter"
                            @keydown.enter.prevent="if (newTag.trim() !== '') tags.push(newTag.trim()); newTag = ''"
                            x-model="newTag" pattern="[0-9]" type="number">
                        </div>
                      </div>
                    </div>

                    @error('brandsForCatalog') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

                  </div>
                </div>

              </div>
              <div class="w-6/12 space-y-4">

                <div class="space-y-1 ">
                  <div class="font-bold ">Meta загаловок</div>
                  <input wire:model.defer="editCatalog.meta_title" type="text">
                  @error('editCatalog.meta_title') <span class="text-sm text-red-500">{{ $message }}</span>
                  @enderror
                </div>

                <div class="space-y-1">
                  <div class="font-bold">Meta описание</div>
                  <textarea rows="2" wire:model.defer="editCatalog.meta_description"></textarea>
                  @error('editCatalog.meta_description') <span class="text-sm text-red-500">{{ $message }}</span>
                  @enderror
                </div>

                <div class="space-y-1">
                  <div class="font-bold">Доп. загаловок</div>
                  <input wire:model.defer="editCatalog.extra_title" type="text" placeholder="для кошки">
                  @error('editCatalog.extra_title') <span class="text-sm text-red-500">{{ $message }}</span>
                  @enderror
                </div>

              </div>
            </div>
            <div class="flex items-center justify-start pt-6 space-x-4">
              <div class="pb-2 font-bold text-gray-600">Категории</div>
              <div>
                <x-tooltip :width="'280px'">
                  <x-slot name="title">
                    <x-tabler-alert-circle class="w-6 h-6 text-gray-400 stroke-current" />
                  </x-slot>
                  <div class="flex items-start justify-start space-x-2">
                    <x-tabler-drag-drop-2 class="w-8 h-8 text-white stroke-current" />
                    <span>Потяните иконку чтобы поставить категорию в нужной сортировке</span>
                  </div>
                </x-tooltip>
              </div>
            </div>

          </div>
          <div wire:ignore class="h-full py-2 overflow-y-auto bg-white scrollbar rounded-xl">
            <div x-data="{a: @entangle('categories').defer, dragging: null, dropping: null, timer: null}"
              @drop.prevent="if(dragging !== null &amp;&amp; dropping !== null){if(dragging &lt; dropping) a = [...a.slice(0, dragging), ...a.slice(dragging + 1, dropping + 1), a[dragging], ...a.slice(dropping + 1)]; else a = [...a.slice(0, dropping), a[dragging], ...a.slice(dropping, dragging), ...a.slice(dragging + 1)]}; dropping = null;"
              @dragover.prevent="$event.dataTransfer.dropEffect = &quot;move&quot;" class="py-2">
              <div class="flex flex-col gap-1 px-4">
                <template x-for="(category, index) in a" :key="category.id">
                  <div class="relative flex items-center justify-between p-2 bg-white border rounded" draggable="true"
                    :class="{'border-blue-600': dragging === index}" @dragstart="dragging = index"
                    @dragend="dragging = null">
                    <div class="flex gap-2">
                      <div class="p-1 text-xs text-gray-400" x-text="category.id"></div>
                      <div class="text-gray-300 cursor-move hover:text-gray-500">
                        <x-tabler-drag-drop-2 class="w-6 h-6 stroke-current " />
                      </div>
                      <button x-on:click="openFormCategory(); $wire.call('openCategory', category.id)"
                        class="cursor-pointer hover:underline" x-text="category.name"></button>
                    </div>

                    <div class="absolute inset-0 opacity-50" x-show.transition="dragging !== null"
                      :class="{'bg-blue-200': dropping === index}"
                      @dragenter.prevent="if(index !== dragging) {dropping = index}"
                      @dragleave="if(dropping === index) dropping = null"></div>

                    <div x-show="category.menu == 1" title="Отображается в меню">
                      <x-tabler-menu-2 class="w-4 h-4 text-gray-400 stroke-current" />
                    </div>

                  </div>
                </template>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between w-full pt-3 pb-20 md:space-x-4">

            <div>
              @can('create')
                <button wire:click="openCategory(null)" x-on:click="openFormCategory()"
                  class="flex space-x-2 text-white bg-green-500 btn hover:bg-green-600">
                  <x-tabler-file-plus class="w-6 h-6 text-white" />
                  <div>Добавить категорию</div>
                </button>
              @endcan
            </div>

            <button @click="saveForm" class="text-white bg-blue-500 btn hover:bg-blue-600"
              {{ $editCatalog['name'] === null ? 'disabled' : '' }}>
              Сохранить каталог
            </button>

          </div>

        </div>

      </x-dashboard.modal>
    </div>

    <div>
      <div x-cloak :class="formCategory ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="fixed top-0 left-0 z-50 w-full h-full max-w-md min-w-full min-h-screen px-8 py-6 overflow-y-auto transition duration-300 transform border-r bg-pink-50 md:min-w-half">

        <x-loader wire:target="openCategory" />

        <div class="space-y-6">

          <div class="flex items-center justify-between w-full">

            <div class="font-bold">
              @if ($editCategory['id'])
                ID: {{ $editCategory['id'] }}
              @endif
            </div>

            <button @click="closeFormCategory" class="text-gray-600 link-hover">
              <x-tabler-circle-x class="w-6 h-6 stroke-current" />
            </button>
          </div>

          <div class="flex items-start justify-between space-x-6">
            <div class="w-6/12 space-y-4">
              <div class="space-y-1">
                <div class="font-bold">Название категории</div>
                <input wire:model="editCategory.name" type="text">
              </div>

              <div class="space-y-1">
                <label class="font-bold">Название в меню</label>
                <input wire:model.defer="editCategory.menu_name" type="text">
              </div>

              <div class="flex items-center justify-start w-full">

                <div class="w-full space-y-1">
                  <label class="font-bold">ID cвойств <span class="text-xs text-gray-500">(показывать как фильтры в
                      этой
                      категории)</span>
                  </label>

                  <div x-data="{tags: @entangle('editCategory.attributes').defer, newTag: '' }">
                    <template x-for="(tag, index) in tags" :key="index">
                      <input type="hidden" x-bind:value="tag">
                    </template>
                    <div class="w-full">
                      <div class="flex flex-wrap gap-2 p-2 bg-white rounded-xl ">
                        <template x-for="(tag, index) in tags" :key="index">
                          <span
                            class="relative flex items-center gap-1 px-2 py-1 text-sm leading-normal text-blue-100 bg-blue-500 select-none rounded-xl group">
                            <span x-text="tag"></span>
                            <button type="button"
                              class="absolute inset-0 z-30 flex items-center justify-center invisible w-full h-full bg-red-500 rounded-xl group-hover:visible"
                              @click="tags = tags.filter(i => i !== tag)">
                              <x-tabler-circle-x class="w-6 h-6" />
                            </button>
                          </span>
                        </template>

                        <input class="inline-flex flex-1 w-24 py-1 outline-none" placeholder="ввести id и нажать enter"
                          @keydown.enter.prevent="if (newTag.trim() !== '') tags.push(newTag.trim()); newTag = ''"
                          x-model="newTag" pattern="[0-9]" type="number">
                      </div>
                    </div>
                  </div>

                  @error('editCategory.attributes') <span class="text-sm text-red-500">{{ $message }}</span>
                  @enderror

                </div>
              </div>

            </div>
            <div class="w-6/12 space-y-4">
              <div class="space-y-1">
                <label class="font-bold">Meta загаловок</label>
                <input wire:model.defer="editCategory.meta_title" type="text">
                @error('editCategory.meta_title') <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
              </div>

              <div class="space-y-1">
                <label class="font-bold">Meta описание</label>
                <textarea wire:model.defer="editCategory.meta_description" rows="2"></textarea>
                @error('editCategory.meta_description') <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
              </div>

              <div class="pt-2">
                <x-toggle wire:model="editCategory.menu" :property="$editCategory['menu']"
                  :lable="'Показывать в меню'" />
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div>
              @can('delete')
                @if ($editCategory['id'] !== null)
                  <x-dashboard.confirm :confirmId="$editCategory['id']"
                    wire:key="removeCat{{ $editCategory['id'] }}" />
                @endif
              @endcan
            </div>

            <div class="flex items-center">

              <button wire:click="saveCategory()"
                class="p-2 px-3 text-white bg-blue-500 cursor-pointer rounded-xl hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-50"
                {{ $editCategory['name'] === null ? 'disabled' : '' }}>
                Сохранить категорию
              </button>
            </div>

          </div>


        </div>

      </div>

      <div x-cloak wire:click="closeFormCategory()" x-show="formCategory" x-transition.opacity
        class="fixed top-0 bottom-0 left-0 right-0 z-40 w-screen h-screen overflow-hidden bg-gray-700 bg-opacity-50 cursor-pointer pointer-events-auto backdrop-filter backdrop-blur-sm">
      </div>
    </div>

    <div class="flex flex-col items-center justify-center w-full gap-6 lg:px-4 lg:flex-row">
      <div class="w-full lg:w-8/12">
        {{ $catalogs->links() }}
      </div>

      <div class="flex items-center justify-end w-full lg:w-4/12">
        <x-dashboard.items-per-page />
      </div>
    </div>

    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('handler', () => ({
          categories: @entangle('categories'),
          dragging: null,
          dropping: null,
          form: false,
          formCategory: false,
          body: document.body,

          openForm() {
            this.form = true
            this.body.classList.add("overflow-hidden")
          },

          closeForm() {
            this.form = false
            this.formCategory = false
            this.body.classList.remove("overflow-hidden")
          },

          openFormCategory() {
            this.formCategory = true
          },

          closeFormCategory() {
            this.formCategory = false
          },

          saveForm() {
            if (this.formCategory === false) {
              window.livewire.emit('save', this.items)
            }
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
