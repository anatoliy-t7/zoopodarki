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
        <div class="flex items-center justify-start gap-3 text-3xl">
          <h1 class="font-bold first-letter">
            Скидки
          </h1>
          <div class="text-3xl font-bold text-gray-700" title="Найдено товаров">{{ $products->total() }}
            {{ trans_choice('titles.count_products', substr($products->total(), -1)) }}
          </div>
        </div>

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
                          <div class="space-y-3">

                            <div class="font-bold">Виды скидок</div>
                            <div class="flex flex-col space-y-3">
                              <label for="typeF2" class="container-checkbox">
                                <span class="text-sm lowercase">Акции</span>
                                <input value="2" wire:model="typeF" id="typeF2" type="checkbox">
                                <span class="checkmark"></span>
                              </label>

                              <label for="typeF1" class="container-checkbox">
                                <span class="text-sm lowercase">Уценка</span>
                                <input value="1" wire:model="typeF" id="typeF1" type="checkbox">
                                <span class="checkmark"></span>
                              </label>

                              @foreach ($attributes as $attribute)
                                <label for="attribute{{ $attribute['id'] }}" class="container-checkbox">
                                  <span class="text-sm lowercase">{{ $attribute['name'] }}</span>
                                  <input value="{{ $attribute['id'] }}" wire:model="attrsF"
                                    id="attribute{{ $attribute['id'] }}" type="checkbox">
                                  <span class="checkmark"></span>
                                </label>
                              @endforeach
                            </div>
                          </div>

                          @if ($catalogs)
                            <div class="space-y-4">
                              <div class="font-bold">Питомец</div>

                              <div class="py-1 space-y-3">

                                @foreach ($catalogs as $catalog)
                                  <label class="container-checkbox">
                                    <span for="cats" class="text-sm lowercase">{{ $catalog['name'] }}</span>
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
                                      class="text-sm lowercase">{{ $category['name'] }}</span>
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
                                    <input value="{{ $brand['id'] }}" wire:model="brandF"
                                      id="brand-{{ $brand['id'] }}" type="checkbox">
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

                    <div class="space-y-3">

                      <div class="font-bold">Виды скидок</div>
                      <div class="flex flex-col space-y-3">
                        <label for="typeF2" class="container-checkbox">
                          <span class="text-sm lowercase">Акции</span>
                          <input value="2" wire:model="typeF" id="typeF2" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        <label for="typeF1" class="container-checkbox">
                          <span class="text-sm lowercase">Уценка</span>
                          <input value="1" wire:model="typeF" id="typeF1" type="checkbox">
                          <span class="checkmark"></span>
                        </label>

                        @foreach ($attributes as $attribute)
                          <label for="attribute{{ $attribute['id'] }}" class="container-checkbox">
                            <span class="text-sm lowercase">{{ $attribute['name'] }}</span>
                            <input value="{{ $attribute['id'] }}" wire:model="attrsF"
                              id="attribute{{ $attribute['id'] }}" type="checkbox">
                            <span class="checkmark"></span>
                          </label>
                        @endforeach

                      </div>
                    </div>

                    @if ($catalogs)
                      <div class="space-y-4">
                        <div class="font-bold">Питомец</div>
                        <div class="py-1 space-y-3">

                          @foreach ($catalogs as $catalog)
                            <label class="container-checkbox">
                              <span for="catalog{{ $catalog['id'] }}"
                                class="text-sm lowercase">{{ $catalog['name'] }}</span>
                              <input value="{{ $catalog['id'] }}" wire:model="petF"
                                id="catalog{{ $catalog['id'] }}" type="checkbox">
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
                            <label for="category{{ $category['id'] }}" class="container-checkbox">
                              <span class="text-sm lowercase">{{ $category['name'] }}</span>
                              <input value="{{ $category['id'] }}" wire:model="catF"
                                id="category{{ $category['id'] }}" type="checkbox">
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
                            <label for="brand{{ $brand['id'] }}" class="container-checkbox">
                              <span class="text-sm lowercase">{{ $brand['name'] }}</span>
                              <input value="{{ $brand['id'] }}" wire:model="brandF" id="brand{{ $brand['id'] }}"
                                type="checkbox">
                              <span class="checkmark"></span>
                            </label>
                          @endforeach
                        </div>
                      </div>
                    @endif
                  </div>
                @endif
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
