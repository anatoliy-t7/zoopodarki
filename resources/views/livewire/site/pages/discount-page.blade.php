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
                  // TODO
                @else
                  <div class="flex-col px-1 space-y-6">

                    @if ($maxPrice > 100)
                      <div class="pb-2">
                        <x-range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" />
                      </div>
                    @endif

                    <div class="space-y-3">
                      <div class="font-bold">Виды скидок</div>
                      <div class="flex flex-col space-y-3">
                        <label class="inline-flex items-center space-x-2">
                          <input type="radio" wire:model="typeF" value="0" name="stockF"
                            class="w-5 h-5 text-orange-400 form-radio" checked><span
                            class="text-base text-gray-700 sm:text-sm">Все виды</span>
                        </label>
                        <label class="inline-flex items-center space-x-2">
                          <input type="radio" wire:model="typeF" value="2" name="stockF"
                            class="w-5 h-5 text-orange-400 form-radio"><span
                            class="text-base text-gray-700 sm:text-sm ">Акции</span>
                        </label>
                        <label class="inline-flex items-center space-x-2">
                          <input type="radio" wire:model="typeF" value="1" name="stockF"
                            class="w-5 h-5 text-orange-400 form-radio"><span
                            class="text-base text-gray-700 sm:text-sm">Уценка</span>
                        </label>
                      </div>
                    </div>

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
                  </div>

                @endif
                <div wire:loading
                  class="absolute top-0 bottom-0 left-0 right-0 z-30 w-full h-full bg-gray-100 bg-opacity-75 rounded-2xl">
                </div>
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
