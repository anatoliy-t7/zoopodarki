  <div class="space-y-2">

    <div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0">
      <div class="flex items-center justify-between">
        <div class="py-1 pr-1">
          Выгодные предложения
        </div>
        <x-tabler-chevron-right class="w-5 h-5" />
      </div>
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-between">
          <a class="p-1 hover:underline" href="{{ route('site.discounts') }}">
            <span>Скидки</span>
          </a>
        </div>
      </div>
    </div>

    <div>
      <div class="space-y-6">

        <div class="flex items-center justify-start space-x-4 text-2xl ">
          <h1 class="font-bold first-letter">
            Скидки
          </h1>

          <div class="text-lg text-gray-400" title="Найдено товаров">{{ $products->total() }}</div>
        </div>


        <div class="flex w-full">
          <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">
            <aside class="w-full lg:w-3/12">
              <div class="relative p-4 pb-6 bg-white lg:rounded-2xl">
                <!--googleoff: all-->
                <!--noindex-->
                @if (Agent::isMobile())
                  {{-- <x-mob-sidebar :minPrice="$minPrice" :maxPrice="$maxPrice" :attributesRanges="$attributesRanges"
                    :brands="$brands" /> --}}
                @else
                  <div class="flex-col px-1 space-y-6">

                    <div wire:loading
                      class="absolute top-0 bottom-0 left-0 right-0 z-30 w-full h-full bg-gray-100 bg-opacity-75 rounded-2xl">
                    </div>

                    <div class="">
                      <x-range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" />
                    </div>

                    <div class="space-y-4">
                      <div class="font-bold">Питомец</div>

                      <div class="py-1 space-y-3">

                        <label class="container-checkbox">
                          <span for="cats" class="text-sm">Кошки</span>
                          <input value="1" wire:model="petF" id="cats" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        <label class="container-checkbox">
                          <span for="dogs" class="text-sm">Собаки</span>
                          <input value="2" wire:model="petF" id="dogs" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        <label class="container-checkbox">
                          <span for="rodents" class="text-sm">Грызуны и хорьки</span>
                          <input value="3" wire:model="petF" id="rodents" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        <label class="container-checkbox">
                          <span for="birds" class="text-sm">Птицы</span>
                          <input value="4" wire:model="petF" id="birds" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        <label class="container-checkbox">
                          <span for="fish" class="text-sm">Рыбки, раки и улитки</span>
                          <input value="5" wire:model="petF" id="fish" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        <label class="container-checkbox">
                          <span for="rabbits" class="text-sm">Кролики</span>
                          <input value="6" wire:model="petF" id="rabbits" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        <label class="container-checkbox">
                          <span for="reptiles" class="text-sm">Рептилии, черепахи и ежи</span>
                          <input value="9" wire:model="petF" id="reptiles" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                      </div>
                    </div>

                    @if ($categories)
                      <div class="space-y-4">
                        <div class="font-bold">Категории</div>
                        <div class="py-1 space-y-3">
                          @foreach ($categories as $category)
                            <label class="container-checkbox">
                              <span for="category-{{ $category['id'] }}"
                                class="text-sm">{{ $category['name'] }}</span>
                              <input value="{{ $category['id'] }}" wire:model="catF"
                                id="category-{{ $category['id'] }}" type="checkbox">
                              <span class="checkmark"></span>
                            </label>
                          @endforeach
                        </div>
                      </div>
                    @endif

                    @if ($brands)
                      <div class="space-y-4">
                        <div class="font-bold">Бренды</div>
                        <div class="py-1 space-y-3">
                          @foreach ($brands as $brand)
                            <label class="container-checkbox">
                              <span for="brand-{{ $brand['id'] }}"
                                class="text-sm">{{ $brand['name'] }}</span>
                              <input value="{{ $brand['id'] }}" wire:model="brandF" id="brand-{{ $brand['id'] }}"
                                type="checkbox">
                              <span class="checkmark"></span>
                            </label>
                          @endforeach
                        </div>
                      </div>
                    @endif

                    {{-- <div x-data="searchBrand" class="space-y-4">
                        <div class="font-bold">Бренд</div>

                        <div>
                          @if ($brands->count() > 10)
                            <input x-ref="searchField" x-model="search"
                              x-on:keydown.window.prevent.slash="$refs.searchField.focus()" placeholder="Поиск"
                              type="search" class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />
                          @endif
                        </div>

                        <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">

                          <template x-for="(item, index) in filteredBrands" :key="item.id" hidden>
                            <div class=" container-checkbox">
                              <span class="text-sm" x-text="item.name"></span>
                              <input :value="item.id" type="checkbox" x-model.number="brandsF">
                              <span class="checkmark"></span>
                            </div>
                          </template>

                          <script>
                            document.addEventListener('alpine:init', () => {
                              Alpine.data('searchBrand', () => ({
                                search: "",
                                brandsF: @entangle('brandsF'),
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
                      </div> --}}



                  </div>

                @endif
                <!--/noindex-->
                <!--googleon: all-->
              </div>

              @if (Agent::isDesktop())
                <div class="px-4 pt-4 lg:py-4">
                  <button
                    class="inline-block w-full px-3 py-2 text-sm text-gray-600 bg-gray-200 border border-gray-200 rounded-2xl hover:text-gray-900 hover:bg-gray-400"
                    wire:click.debounce.1000="resetFilters(), $render" wire:loading.attr="disabled">
                    Сбросить фильтры
                  </button>
                </div>
              @endif
            </aside>

            <article id="top" class="w-full">

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

                            <livewire:site.card-products :product="$product"
                              :catalog="$product->categories[0]->catalog->slug"
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

              <div class="py-4">
                {{ $products->links() }}
              </div>

            </article>
          </div>

        </div>
      </div>
    </div>
  </div>
