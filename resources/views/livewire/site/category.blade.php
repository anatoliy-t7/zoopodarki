<div>
  <div class="space-y-6">

    <div class="flex items-center justify-start pt-4 space-x-4 text-2xl ">
      <h1 class="font-bold">
        {{ $name }}
      </h1>

      <div class="text-lg text-gray-400" title="Найдено товаров">{{ $products->total() }}</div>
    </div>

    @if ($category->tags->isNotEmpty())
      <div class="flex flex-wrap items-center justify-start lg:px-0">
        @forelse ($category->tags as $tagItem)
          <div class="p-1">
            <a href="{{ route('site.tag', ['catalog' => $catalog->slug, 'slug' => $category->slug, 'tagslug' => $tagItem->slug]) }}"
              class="block px-3 py-2 text-xs border rounded-full hover:bg-blue-500 hover:border-blue-500 hover:text-white {{ request()->is('pet/' . $catalog->slug . '/' . $category->slug . '/f/' . $tagItem->slug) ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-blue-500 border-blue-200' }}">
              {{ $tagItem->name }}
            </a>
          </div>
        @empty
        @endforelse
      </div>
    @endif

    <div class="flex w-full">
      <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">
        <div class="w-full lg:w-3/12">
          <div class="relative p-4 pb-6 bg-white lg:rounded-2xl">
            <!--googleoff: all-->
            <!--noindex-->
            @if (Agent::isMobile())
              <x-mob-sidebar :minPrice="$minPrice" :maxPrice="$maxPrice" :attributesRanges="$attributesRanges"
                :brands="$brands" :attrs="$attrs" :filterStock="$filterStock" />
            @else
              <x-filters :minPrice="$minPrice" :maxPrice="$maxPrice" :attributesRanges="$attributesRanges"
                :brands="$brands" :attrs="$attrs" :filterStock="$filterStock" />
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

        <div id="top" class="w-full">

          <div class="relative w-full px-4 pb-6 bg-white lg:pt-2 lg:px-6 lg:rounded-2xl">

            <div class="relative ">
              <div class="flex items-center justify-end py-3">
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

              <x-loader />
              <div itemscope itemtype="https://schema.org/ItemList">
                @if ($products->total() !== 0)
                  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                    @foreach ($products as $product)
                      <div itemprop="itemListElement" itemscope itemtype="https://schema.org/Product">

                        <livewire:site.card-products :product="$product" :catalog="$catalog->slug"
                          :category="$category->slug" :wire:key="'product-'.$product->id" />

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
  </div>
