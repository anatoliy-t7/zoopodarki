@section('title')
  Теги
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)" @save.window="saveForm(event)"
    @close.window="closeForm(event)" class="space-y-2">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Теги</h3>

      @can('create')
        <button @click="openForm()" wire:click="addNew()" id="add" title="Создать новый тег"
          class="space-x-2 text-white bg-green-500 btn hover:bg-green-600">
          <x-tabler-plus class="w-6 h-6 text-white" />
          <div>Создать</div>
        </button>
      @endcan

    </div>

    <div class="flex items-center justify-start w-full space-x-6">

      <x-dashboard.search />

      <div wire:ignore>
        <label for="filteredByCategory"></label>
        <select wire:model="filteredByCategory" name="filteredByCategory" id="filteredByCategory" class="w-80">
          <option selected value="">Все категории</option>
          @foreach ($catalogs as $catalog)
            <optgroup label="{{ $catalog->name }}">
              @foreach ($catalog->categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </optgroup>
          @endforeach
        </select>
      </div>



      <div class="flex space-x-8">
        <div class="flex items-center space-x-2">
          <x-toggle wire:model="onlyOnPage" :property="$onlyOnPage" :lable="'В категории'" />
          <x-tabler-eye class="w-6 h-6 stroke-current {{ $onlyOnPage ? 'text-green-500' : 'text-gray-400' }}" />
        </div>

        <div class="flex items-center space-x-2">
          <x-toggle wire:model="onlyInMenu" :property="$onlyInMenu" :lable="'В меню'" />
          <x-tabler-eye class="w-6 h-6 stroke-current {{ $onlyInMenu ? 'text-green-500' : 'text-gray-400' }}" />
        </div>
      </div>



    </div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head sortable wire:click="sortBy('id')"
            :direction="$sortField === 'id' ? $sortDirection : null">Id
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('name')"
            :direction="$sortField === 'name' ? $sortDirection : null">
            Тег</x-dashboard.table.head>
          <x-dashboard.table.head>Каталог | Категория</x-dashboard.table.head>
          <x-dashboard.table.head>Свойство | Вид свойства</x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('show_on_page')"
            :direction="$sortField === 'show_on_page' ? $sortDirection : null">В категории
          </x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('show_in_menu')"
            :direction="$sortField === 'show_in_menu' ? $sortDirection : null">В меню
          </x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse($tags as $key => $tag)
            <x-dashboard.table.row>

              <x-dashboard.table.cell>
                {{ $tag->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                <button x-on:click="openForm()" wire:click="openForm({{ $tag->id }})" title="Edit"
                  class="cursor-pointer hover:text-blue-500">
                  {{ $tag->name }}
                </button>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="max-w-sm leading-relaxed truncate">
                <div class="flex items-center justify-start">
                  <span class="pr-1 text-xs text-gray-400">
                    {{ $tag->category->catalog->name }}
                  </span>
                  |
                  <span class="pl-1">
                    {{ $tag->category->name }}
                  </span>
                </div>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                @foreach ($tag->filter as $key => $tagFilter)
                  <div class="flex items-center justify-start">
                    <div class="pr-1 text-xs text-gray-400">
                      {{ $tagFilter['attribute_name'] }}
                    </div>
                    |
                    <div class="pl-1"> {{ $tagFilter['name'] }}
                    </div>
                  </div>
                @endforeach
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                @if ($tag->show_on_page)
                  <x-tabler-eye class="w-6 h-6 text-green-500 stroke-current" />
                @else
                  <x-tabler-eye-off class="w-6 h-6 text-gray-400 stroke-current" />
                @endif
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                @if ($tag->show_in_menu)
                  <x-tabler-eye class="w-6 h-6 text-green-500 stroke-current" />
                @else
                  <x-tabler-eye-off class="w-6 h-6 text-gray-400 stroke-current" />
                @endif
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">

                <button x-on:click="openForm()" wire:click="openForm({{ $tag->id }})" title="Edit"
                  class="p-2 text-gray-500 rounded-lg hover:text-blue-500">
                  <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                    <path
                      d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                    </path>
                  </svg>
                </button>

                @can('delete')
                  <x-dashboard.confirm :confirmId="$tag->id" wire:click="remove({{ $tag->id }})" />
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

        <x-loader wire:target="openForm, save" />

        <div class="flex flex-col justify-between h-screen space-y-2">

          <div class="space-y-6">

            <div class="flex space-x-8">

              <div class="w-6/12 space-y-4">
                <div class="space-y-1">
                  <label class="font-bold">Название тега</label>
                  <input wire:model.defer="editTag.name" type="text">
                  @error('editTag.name') <span class="block text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                  <label class="font-bold">Заголовок страницы</label>
                  <input wire:model.defer="editTag.title" type="text">
                  @error('editTag.title') <span class="block text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
              </div>

              <div class="w-6/12 space-y-4">

                <div class="space-y-1">
                  <label class="font-bold">Meta заголовок</label>
                  <input wire:model.defer="editTag.meta_title" type="text">
                  @error('editTag.meta_title') <span class="block text-sm text-red-500">{{ $message }}</span>
                  @enderror

                </div>

                <div class="pt-8 pl-4 space-y-6">
                  <x-toggle wire:model="editTag.show_on_page" :property="$editTag['show_on_page']"
                    :lable="'Показывать на странице категории'" />
                  <x-toggle wire:model="editTag.show_in_menu" :property="$editTag['show_in_menu']"
                    :lable="'Показывать в меню'" />
                </div>

              </div>

            </div>

            <div class="flex items-start space-x-8">

              <div class="w-6/12">
                <div class="flex flex-col items-start justify-end space-y-1">
                  <label for="tagCategoryId" class="font-bold">Категория</label>
                  <select wire:model="categoryId" name="tagCategoryId" id="tagCategoryId" class="w-full"
                    {{ empty($editTag['filter']) ? '' : 'disabled' }}>
                    @foreach ($catalogs as $catalog)
                      <optgroup label="{{ $catalog->name }}">
                        @foreach ($catalog->categories as $category)
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                      </optgroup>
                    @endforeach
                  </select>
                  @error('editTag.category_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                  @if (!empty($editTag['filter']))
                    <span class="text-xs font-normal text-gray-400">(вы не можете изменить категорию пока не удалите все
                      фильтры)</span>
                  @endif
                </div>
              </div>

              <div class="w-6/12">
              </div>

            </div>

            <div>

              <div class="font-bold">Фильтры</div>

              <div>
                @if ($categoryId)

                  <div class="flex justify-between pt-2 pb-6 space-x-8">

                    <div class="block w-6/12 space-y-2">
                      <label for="selectedTypeFilterId" class="block text-sm">Свойства</label>
                      <select wire:model="selectedTypeFilterId" name="selectedTypeFilterId" id="selectedTypeFilterId"
                        class="w-full ">
                        <option selected value="">Выберите свойство</option>
                        @foreach ($categoryfilters as $categoryfilter)
                          <option value="{{ $categoryfilter['id'] }}">{{ $categoryfilter['name'] }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="block w-6/12 space-y-2">
                      @if ($selectedTypeFilterId)
                        <label for="selectedFilterId" class="block text-sm">Виды свойств</label>
                        <select wire:model="selectedFilterId" name="selectedFilterId" id="selectedFilterId"
                          class="w-full">
                          <option selected value="">Выберите вид свойства</option>
                          @foreach ($filterItems as $filterItem)
                            <option value="{{ $filterItem['id'] }}">{{ $filterItem['name'] }}</option>
                          @endforeach
                        </select>
                      @endif
                    </div>

                  </div>

                @endif
              </div>

            </div>

          </div>

          @if ($categoryId)
            <div x-data="filters" class="relative block h-full overflow-y-auto bg-white divide-y scrollbar">
              <div
                class="sticky top-0 flex justify-start px-4 py-3 space-x-4 text-xs text-gray-400 uppercase bg-white border-b border-gray-100">
                <div class="leading-relaxed w-60">Свойство</div>

                <div class="leading-relaxed">Вид свойства</div>
              </div>
              @foreach ($editTag['filter'] as $key => $tagFilter)

                <div class="flex justify-between w-full px-4 py-3 space-x-4 hover:bg-gray-50 group">

                  <div class="flex justify-start space-x-4">
                    <div class="leading-relaxed truncate w-60">{{ $tagFilter['attribute_name'] }}</div>

                    <div class="leading-relaxed truncate">{{ $tagFilter['name'] }}</div>
                  </div>

                  <button title="Удалить фильтр" wire:click="removeItem({{ $key }})"
                    class="invisible inline-block h-full px-1 text-gray-500 align-middle group-hover:visible hover:text-red-500 focus:outline-none">
                    <svg class="w-6 h-6 mx-auto fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path fill-rule="evenodd"
                        d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z" />
                    </svg>
                  </button>

                </div>

              @endforeach


            </div>
          @else
            <div class="text-gray-400">Выберите сначала категорию</div>
          @endif

          <script>
            document.addEventListener('alpine:initializing', () => {
              Alpine.data('filters', () => ({
                newField: true,
                addNewField() {
                  this.newField = true;
                },
              }))
            })
          </script>

          <div class="flex items-center justify-between w-full pt-3 pb-20 md:space-x-4">
            <div>
            </div>
            <button wire:click="save"
              class="p-2 px-3 text-white bg-pink-500 cursor-pointer rounded-xl hover:bg-pink-700 disabled:opacity-50 disabled:cursor-not-allowed"
              wire:loading.attr="disabled" {{ empty($editTag['filter']) ? 'disabled' : '' }}>
              Сохранить тег
            </button>
          </div>

        </div>

      </x-dashboard.modal>

    </div>

    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $tags->links() }}
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
          saveForm() {
            window.livewire.emit('saveIt', this.items)
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
  @endpush
  @push('header-js')
    <script src="{{ mix('js/tagify.min.js') }}"></script>
  @endpush

</div>
