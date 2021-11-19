<div>

  <div class="flex items-center justify-start px-4 pt-4 space-x-4 text-2xl ">
    <h1 class="font-bold capitalize">
      {{ $name }}
    </h1>

    <div class="text-lg text-gray-400" title="Найдено товаров">{{ $products->total() }}</div>
  </div>

  <div class="flex flex-wrap items-center justify-start py-6 lg:px-0">
    @forelse ($category->tags as $tagItem)
      <div class="p-1">
        <a href="{{ route('site.tag', ['catalog' => $catalog->slug, 'slug' => $category->slug, 'tagslug' => $tagItem->slug]) }}"
          class="block px-3 py-2 text-xs border rounded-full hover:bg-blue-500 hover:border-blue-500 hover:text-white
  {{ request()->is('pet/' . $catalog->slug . '/' . $category->slug . '/f/' . $tagItem->slug) ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-blue-500 border-blue-200' }}">
          {{ $tagItem->name }}
        </a>
      </div>
    @empty
    @endforelse
  </div>

  <div class="flex w-full">
    <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">
      <div class="w-full lg:w-3/12">
        <div class="relative p-4 pb-6 bg-white lg:rounded-2xl">
          <!--googleoff: all-->
          <!--noindex-->
          <div wire:loading
            class="absolute top-0 bottom-0 left-0 right-0 z-30 w-full h-full bg-gray-100 bg-opacity-75 rounded-2xl">
          </div>
          @if (Agent::isMobile())
            <div x-cloak x-data="{ filter: false}">
              <button x-on:click="filter = true" class="absolute right-4 top-2">
                <x-tabler-adjustments class="w-6 h-6 text-gray-500 stroke-current" />
              </button>

              <div :class="{'translate-x-0': filter, '-translate-x-full' : !filter}"
                class="fixed top-0 bottom-0 left-0 z-30 w-full h-full min-h-screen overflow-y-auto transition-transform duration-300 ease-in-out transform translate-x-0 bg-white max-w-screen scrollbar">
                <button x-on:click="filter = false" class="sticky top-0 right-0 z-40 pt-3 pl-2">
                  <x-tabler-chevron-left class="w-6 h-6 text-gray-500 stroke-current" />
                </button>
                <div class="p-8 ">

          @endif

          <div class="flex-col px-1 space-y-6">
            <x-range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" />

            <div>
              @forelse ($attributesRanges as $key => $attrRange)
                <div class="pt-6 pb-5 text-sm font-bold">{{ $attrRange['name'] }}</div>
                <x-range-slider-attr :minRange="$attrRange['min']" :maxRange="$attrRange['max']" :idRange="$key" />
              @empty
              @endforelse
            </div>

            <div x-data="searchBrand" class="space-y-3">
              <div class="font-bold">Бренд</div>
              <div>
                @if ($brands->count() > 10)
                  <input x-ref="searchField" x-model="search"
                    x-on:keydown.window.prevent.slash="$refs.searchField.focus()" placeholder="Поиск" type="search"
                    class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />
                @endif
              </div>

              <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">

                <template x-for="(item, index) in filteredBrands" :key="index" hidden>
                  <div class="container-checkbox">
                    <span class="text-xs" x-text="item.name"></span>
                    <input :value="item.id" type="checkbox" x-model.number="brandFilter">
                    <span class="checkmark"></span>
                  </div>
                </template>

                <script>
                  document.addEventListener('alpine:init', () => {
                    Alpine.data('searchBrand', () => ({
                      search: "",
                      brandFilter: @entangle('brandFilter'),
                      brandsData: @json($brands),
                      get filteredBrands() {
                        if (this.search === "") {
                          return this.brandsData;
                        }
                        return this.brandsData.filter((item) => {
                          return item.name
                            .toLowerCase()
                            .includes(this.search.toLowerCase());
                        });
                      },
                    }))
                  });
                </script>
              </div>
            </div>

            <div class="space-y-6 ">
              @forelse ($attrs as $attribute)
                <div>
                  @if ($attribute->items->count() !== 0 and $attribute->id !== 46)

                    <div x-data="searchAttribute{{ $attribute->id }}" class="space-y-3">
                      <div class="font-bold">{{ $attribute->name }}</div>

                      <div>
                        @if ($attribute->items->count() >= 10)

                          <input x-ref="searchField" x-model="search"
                            x-on:keydown.window.prevent.slash="$refs.searchField.focus()" placeholder="Поиск"
                            type="search" class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />

                        @endif
                      </div>

                      <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">

                        <template x-for="(item, index) in filteredAttribute" :key="item.id" hidden>
                          <div class="container-checkbox">
                            <span class="text-xs" x-text="item.name"></span>
                            <input :value="item.id" type="checkbox" x-model.number.debounce.700="attributeFilter"
                              x-on:click="moveUp(index, $event.target.checked)">
                            <span class="checkmark"></span>
                          </div>
                        </template>

                        <script>
                          document.addEventListener('alpine:init', () => {
                            Alpine.data('searchAttribute{{ $attribute->id }}', () => ({
                              search: "",
                              attributeFilter: @entangle('attFilter'),
                              attributerData: @json($attribute->items),
                              get filteredAttribute() {
                                if (this.search === "") {
                                  return this.attributerData;
                                }
                                return this.attributerData.filter((item) => {
                                  return item.name
                                    .toLowerCase()
                                    .includes(this.search.toLowerCase());
                                });
                              },
                              moveUp(from, checked) {
                                if (checked) {
                                  var f = this.attributerData.splice(from, 1)[0];
                                  this.attributerData.splice(0, 0, f);
                                }
                              }
                            }))
                          });
                        </script>
                      </div>
                    </div>

                  @endif
                </div>
              @empty
              @endforelse
            </div>

          </div>

          @if (Agent::isMobile())
        </div>
      </div>
    </div>
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

  <div class="w-full px-4 pb-6 bg-white lg:pt-2 lg:px-6 lg:rounded-2xl">

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

                <livewire:site.card-products :product="$product" :catalog="$catalog->slug" :category="$category->slug"
                  :wire:key="'product-'.$product->id" />

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
