<div>

  <div class="flex items-center justify-start py-4 space-x-4 text-2xl ">
    <h1>
      Поиск по запросу <span class="font-bold">"{{ $q }}"</span>
    </h1>

    <div class="text-lg text-gray-400" title="Найдено товаров">
      @if ($products)
        {{ $products->total() }}
      @endif
    </div>
  </div>

  <div class="flex w-full">
    <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">

      <div id="top" class="w-full">

        <div class="w-full px-4 pb-6 bg-white lg:pt-2 lg:px-6 lg:rounded-2xl">

          <div class="relative ">
            {{-- <div class="flex items-center justify-end py-3">
              <x-dropdown>
                <x-slot name="title">
                  {{ $sortSelectedName }}
                </x-slot>
                @foreach ($sortType as $type)
                  <div class="p-2 text-xs cursor-pointer hover:bg-gray-200"
                    wire:click="sortIt('{{ $type['type'] }}', '{{ $type['sort'] }}', '{{ $type['name'] }}')">
                    {{ $type['name'] }}
                  </div>
                @endforeach
              </x-dropdown>
            </div> --}}

            <x-loader />
            <div itemscope itemtype="https://schema.org/ItemList">
              @if ($products->total() !== 0)
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                  @foreach ($products as $product)
                    <div itemprop="itemListElement" itemscope itemtype="https://schema.org/Product">

                      <livewire:site.card-products :product="$product" :catalog="$product->categories[0]->catalog->slug"
                        :category="$product->categories[0]->slug" :wire:key="'product-'.$product->id" />

                    </div>
                  @endforeach

                </div>
              @else
                <p>По этому фильтру ничего не найдено</p>
              @endif
            </div>
          </div>

        </div>

        <div wire:loading.remove class="py-4">
          {{ $products->links() }}
        </div>

      </div>
    </div>

  </div>
