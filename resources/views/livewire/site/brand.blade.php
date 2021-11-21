  <div class="space-y-6">

    <div class="flex items-center justify-start pt-4 space-x-4 text-2xl ">
      <h1 class="font-bold">
        {{ $brand->name }}
      </h1>

      <div class="text-lg text-gray-400" title="Найдено товаров">{{ $products->total() }}</div>
    </div>

    <div class="flex w-full">
      <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">
        <div class="w-full lg:w-3/12">
          <div class="relative p-4 pb-6 bg-white lg:rounded-2xl">
            <!--googleoff: all-->
            <!--noindex-->

            @if (Agent::isMobile())
              <x-mob-sidebar :minPrice="$minPrice" :maxPrice="$maxPrice" :attributesRanges="$attributesRanges"
                :brands="null" :attrs="$attrs" />
            @else
              <x-filters :minPrice="$minPrice" :maxPrice="$maxPrice" :attributesRanges="$attributesRanges"
                :brands="null" :attrs="$attrs" />
            @endif
            <!--/noindex-->
            <!--googleon: all-->
          </div>

          @if (Agent::isDesktop())
            <div class="px-4 pt-4 lg:py-4">
              <button
                class="inline-block w-full px-3 py-2 text-sm text-gray-600 bg-gray-200 border border-gray-200 rounded-2xl hover:text-gray-900 hover:bg-gray-400"
                wire:click.debounce.1000="resetFilters" wire:loading.attr="disabled">
                Сбросить фильтры
              </button>
            </div>
          @endif
        </div>

        <div class="space-y-4 sm:w-full">

          @if ($brand->description)
            <div class="flex items-start justify-start p-6 space-x-6 bg-white rounded-2xl">
              @if ($brand->logo)
                <img loading="lazy" class="w-3/12" src="/brands/{{ $brand->logo }}"
                  alt="Логотип {{ $brand->name }}">
              @endif
              <div class="w-9/12">
                <p>{{ $brand->description }}</p>
              </div>
            </div>
          @endif

          <div class="relative w-full px-4 pb-6 bg-white lg:pt-2 lg:px-6 lg:rounded-2xl">

            <x-loader />

            <div class="flex items-center justify-end py-3 ">
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
            </div>

            <div>

              @if (!count($products) == 0)
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                  @foreach ($products as $key => $product)
                    <div>
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

          <div wire:loading.remove class="py-4">
            {{ $products->links() }}
          </div>

        </div>
      </div>
    </div>
  </div>
