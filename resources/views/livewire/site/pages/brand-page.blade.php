  <div class="space-y-6">

    <div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0">
      <div class="flex items-center justify-between">
        <a class="py-1 pr-1 hover:underline" href="{{ route('site.brands') }}">
          Бренды
        </a>
        <x-tabler-chevron-right class="w-5 h-5" />
      </div>
      <div class="flex items-center justify-between">
        <div class="p-1">
          {{ $brand->name }}
        </div>
      </div>
    </div>

    <div class="flex items-center justify-start gap-3 text-3xl">
      <h1 class="font-bold first-letter">
        {{ $brand->name }}
      </h1>
      <div class="text-3xl font-bold text-gray-700" title="Найдено товаров">{{ $products->total() }}
        {{ trans_choice('titles.count_products', substr($products->total(), -1)) }}
      </div>
    </div>

    <div class="flex items-start justify-start gap-6 p-6 bg-white shadow-sm rounded-2xl">
      @if ($brand->logo)
        <div class="">
          <img loading="lazy" class="w-auto h-24" src="/assets/brands/{{ $brand->logo }}"
            alt="Логотип {{ $brand->name }}">
        </div>
      @endif
      <div class="flex flex-col gap-4">
        @if ($brand->items()->exists())
          <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500">Серии бренда:</div>
            <div class="flex items-center space-x-4 text-sm">
              @foreach ($brand->items as $items)
                <div>{{ $items->name }}</div>
              @endforeach
            </div>
          </div>
        @endif
        @if ($countries)
          <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500">Страна производства:</div>
            <div class="flex items-center space-x-4 text-sm">
              @foreach ($countries as $country)
                <div>{{ $country }}</div>
              @endforeach
            </div>
          </div>
        @endif
        <p>{{ $brand->description }}</p>
      </div>
    </div>

    <div>
      <div class="space-y-6">

        <div class="flex w-full">
          <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">
            <aside class="w-full lg:w-3/12">
              <div class="relative shadow-sm md:p-6 md:bg-white lg:rounded-2xl">
                <!--googleoff: all-->
                <!--noindex-->
                @if (Agent::isMobile())
                  <div x-cloak x-data="{ filter: false}"
                    x-effect="document.body.classList.toggle('overflow-hidden', filter), document.body.classList.toggle('pr-4', filter)">
                    <button x-on:click="filter = true" class="absolute z-20 top-8 left-4">
                      <x-tabler-adjustments class="w-8 h-8 text-gray-600 stroke-current" />
                    </button>

                    <div :class="{'translate-x-0': filter, '-translate-x-full' : !filter}"
                      class="fixed inset-0 z-40 w-full h-full min-h-screen overflow-y-auto transition-transform duration-300 ease-in-out transform translate-x-0 bg-white max-w-screen scrollbar overscroll-x-none">

                      <button x-on:click="filter = false" class="sticky top-0 left-0 z-40 pt-4 pl-3">
                        <x-tabler-chevron-left class="text-gray-500 stroke-current w-7 h-7" />
                      </button>

                      <div class="px-12 pb-28">
                        <div class="flex-col px-1 space-y-6">
                          @if ($maxPrice > 100)
                            <div class="pb-2">
                              <x-range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" :minRange="$minRange"
                                :maxRange="$maxRange" />
                            </div>
                          @endif

                          @if ($catalogs)
                            <div class="space-y-4">
                              <div class="font-bold">Питомец</div>

                              <div class="py-1 space-y-3">

                                @foreach ($catalogs as $catalog)
                                  <label class="container-checkbox">
                                    <span for="cats" class="text-sm">{{ $catalog['name'] }}</span>
                                    <input value="{{ $catalog['id'] }}" wire:model="petF"
                                      id="{{ $catalog['slug'] }}" type="checkbox">
                                    <span class="checkmark"></span>
                                  </label>
                                @endforeach

                              </div>
                            </div>
                          @endif

                          @if ($petF && $categories)
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
                        </div>

                        <div class="pt-6">
                          <button
                            class="inline-block w-full px-3 py-2 font-bold text-gray-600 bg-gray-100 border border-gray-200 md:text-sm rounded-xl hover:bg-gray-200"
                            wire:click.debounce.1000="resetFilters(), $render" wire:loading.attr="disabled">
                            Сбросить фильтры
                          </button>
                        </div>
                      </div>

                    </div>
                  </div>
                @else
                  <div class="flex-col px-1 space-y-6">
                    @if ($maxPrice > 100)
                      <div class="pb-2">
                        <x-range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" :minRange="$minRange"
                          :maxRange="$maxRange" />
                      </div>
                    @endif

                    @if ($catalogs)
                      <div class="space-y-4">
                        <div class="font-bold">Питомец</div>

                        <div class="py-1 space-y-3">

                          @foreach ($catalogs as $catalog)
                            <label class="container-checkbox">
                              <span for="cats" class="text-sm">{{ $catalog['name'] }}</span>
                              <input value="{{ $catalog['id'] }}" wire:model="petF" id="{{ $catalog['slug'] }}"
                                type="checkbox">
                              <span class="checkmark"></span>
                            </label>
                          @endforeach

                        </div>
                      </div>
                    @endif

                    @if ($petF && $categories)
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
                  </div>
                @endif
                <div wire:loading
                  class="absolute top-0 bottom-0 left-0 right-0 z-30 w-full h-full bg-gray-100 bg-opacity-75 rounded-2xl">
                </div>

                @if (Agent::isDesktop())
                  <div class="pt-6">
                    <button
                      class="inline-block w-full px-3 py-2 font-bold text-gray-600 bg-gray-100 border border-gray-200 md:text-sm rounded-xl hover:bg-gray-200"
                      wire:click.debounce.1000="resetFilters(), $render" wire:loading.attr="disabled">
                      Сбросить фильтры
                    </button>
                  </div>
                @endif
                <!--/noindex-->
                <!--googleon: all-->
              </div>

            </aside>

            <article id="top" class="w-full">

              <div class="relative w-full pb-6 md:shadow-sm md:px-4 md:bg-white lg:pt-2 lg:px-6 lg:rounded-2xl">

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
