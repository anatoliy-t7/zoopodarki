@section('title')
  Товары
@endsection
<div class="space-y-2">

  <div class="flex items-center justify-start w-full space-x-8">

    <h3 class="text-2xl">Товары</h3>

    @can('create')
      <a id="add" title="Создать новый товар"
        class="flex w-32 px-3 py-2 space-x-2 text-white bg-green-500 cursor-pointer rounded-xl hover:bg-green-600"
        href="{{ route('dashboard.product.edit', ['id' => '']) }}">
        <x-tabler-file-plus class="w-6 h-6 text-white" />
        <div>Создать</div>
      </a>
    @endcan

  </div>

  <div class="items-center justify-between block w-full lg:flex lg:space-x-8">

    <div class="items-center justify-start block space-y-4 lg:space-y-0 lg:space-x-6 lg:flex lg:w-2/3">

      <x-dashboard.search />

      <div wire:ignore class="flex items-center justify-end py-3">
        <div class="max-w-xs">
          <label for="filteredByCategory"></label>
          <select wire:model="filteredByCategory" name="filteredByCategory" id="filteredByCategory"
            class="w-64">
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
      </div>

    </div>

    <div class="items-center justify-end block lg:flex lg:space-x-12">

      @can('delete')
        <div class="flex items-center justify-end space-x-4">

          @if ($onlyTrashed === true)
            <button
              class="flex space-x-2 px-3 py-2 border rounded-lg hover:bg-red-500 hover:border-red-500 focus:outline-none group {{ $onlyTrashed ? ' bg-red-500 text-white' : ' border-gray-400' }} "
              wire:click="forceDeleteAll">
              <x-tabler-trash class="w-6 h-6 text-white" />
              <div> {{ $countTrash }}</div>
            </button>
          @endif

          <button
            class="relative text-xs text-pink-500 border border-pink-500 btn hover:bg-pink-500 hover:text-white group "
            wire:click="showTrashed">
            @if ($onlyTrashed)
              <div>Скрыть удаленные</div>
            @else
              <div>Показать удаленные</div>
            @endif
          </button>

        </div>
      @endcan

    </div>

  </div>

  <div class="items-center justify-start block pb-3 space-y-4 lg:space-y-0 lg:space-x-8 lg:flex">

    <x-toggle wire:model="filteredByAttribute" :property="$filteredByAttribute" :lable="'Искать по свойству'" />

    <x-toggle wire:model="productsWithoutDescription" :property="$productsWithoutDescription" :lable="'Без описания'" />

    <x-toggle wire:model="productsWithoutImage" :property="$productsWithoutImage" :lable="'Без картинок'" />

    <x-toggle wire:model="variationMoreOne" :property="$variationMoreOne" :lable="'Есть вариации'" />

    <x-toggle wire:model="available" :property="$available" :lable="'В наличии'" />

    <x-toggle wire:model="noCategories" :property="$noCategories" :lable="'Нет категорий, но в наличии'" />

  </div>

  <div>
    <x-dashboard.table>
      <x-slot name="head">
        <x-dashboard.table.head sortable wire:click="sortBy('id')"
          :direction="$sortField === 'id' ? $sortDirection : null">Id
        </x-dashboard.table.head>
        <x-dashboard.table.head sortable wire:click="sortBy('name')"
          :direction="$sortField === 'name' ? $sortDirection : null">
          Название</x-dashboard.table.head>
        <x-dashboard.table.head>Вариативность</x-dashboard.table.head>
        <x-dashboard.table.head>Категории</x-dashboard.table.head>
        <x-dashboard.table.head>Свойства</x-dashboard.table.head>
        <x-dashboard.table.head>
          <div class="flex justify-center">
            <x-tabler-photo class="w-6 h-6 text-gray-400 " />
          </div>
        </x-dashboard.table.head>
        <x-dashboard.table.head></x-dashboard.table.head>
      </x-slot>

      <x-slot name="body">
        @forelse ($products as $product)
          <x-dashboard.table.row>

            <x-dashboard.table.cell>
              {{ $product->id }}
            </x-dashboard.table.cell>

            <x-dashboard.table.cell>
              @if ($onlyTrashed)
                <div class="max-w-xs truncate">
                  {{ $product->name }}
                </div>
              @else
                <a title="{{ $product->name }}" class="block max-w-sm text-blue-500 truncate"
                  href="{{ route('dashboard.product.edit', ['id' => $product->id]) }}">
                  {{ $product->name }}
                </a>
              @endif
            </x-dashboard.table.cell>

            <x-dashboard.table.cell>
              @foreach ($product->variations as $item)
                <div title="{{ $item->name }}" class="block py-1 text-xs font-thin text-gray-500 truncate w-60">
                  @if ($item->vendorcode)
                    <span class="font-semibold">{{ $item->vendorcode }}</span> |
                  @endif
                  {{ $item->name }}
                </div>
              @endforeach
            </x-dashboard.table.cell>

            <x-dashboard.table.cell>
              @foreach ($product->categories as $category)
                <div class="block py-1 text-xs font-thin truncate rounded-2xl">
                  {{ $category->name }} ({{ $category->id }})
                </div>
              @endforeach
            </x-dashboard.table.cell>

            <x-dashboard.table.cell>
              @foreach ($product->attributes as $attribute)
                <div class="block py-1 text-xs font-thin truncate rounded-2xl">
                  {{ $attribute->name }} ({{ $attribute->id }})
                </div>
              @endforeach
            </x-dashboard.table.cell>
            <x-dashboard.table.cell class="text-center">
              @if ($product->media_count > 0)
                <div class="font-semibold">
                  {{ $product->media_count }}
                </div>
              @endif
            </x-dashboard.table.cell>
            <x-dashboard.table.cell>
              <div class="flex items-center justify-end space-x-4">

                @if ($onlyTrashed)
                  <button title="Востановить" wire:click.debounce.500ms="restoreTrashed({{ $product->id }})"
                    class="p-2">
                    <x-tabler-arrow-back-up class="w-6 h-6 text-blue-500" />
                  </button>

                  <button title="Удалить окончательно" wire:click.debounce.500ms="forceDelete({{ $product->id }})"
                    class="p-2">
                    <x-tabler-trash class="w-6 h-6 text-red-500" />
                  </button>

                @else
                  <a title="Редактировать"
                    class="flex invisible p-2 space-x-4 text-gray-400 rounded-lg hover:text-blue-500 group-hover:visible"
                    href="{{ route('dashboard.product.edit', ['id' => $product->id]) }}">
                    <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                      title="edit">
                      <path
                        d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                      </path>
                    </svg>
                  </a>
                @endif

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

  <div class="items-center block px-4 space-y-4 lg:flex lg:space-y-0">
    <div class="w-full lg:w-8/12">
      {{ $products->links() }}
    </div>

    <div class="flex items-center justify-end w-full space-x-4 lg:w-4/12">
      <x-dashboard.items-per-page />
    </div>

  </div>



  <script>
    document.addEventListener("keydown", function(e) {
      if (e.keyCode == 112) {
        e.preventDefault();
        document.getElementById("add").click();
      }
    }, false);
  </script>
</div>
