<div>
  <div class="flex w-full space-x-4">


    <div class="w-full lg:w-3/12">
      <div class="p-4 pb-5 space-y-6 bg-white lg:rounded-2xl">


        <livewire:site.range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" :wire:key="'range-'.$brand->id" />

        <div class="space-y-6 ">
          @forelse ($attributes as $attribute)
          <div>
            @if ($attribute->items->count() !== 0)

            <div x-data="searchAttribute{{ $attribute->id }}" wire:key="{{ $loop->index }}" class="space-y-3">
              <div class="font-bold">{{ $attribute->name }}</div>

              <div>
                @if ($attribute->items->count() > 10)

                <input x-ref="searchField" x-model="search"
                  x-on:keydown.window.prevent.slash="$refs.searchField.focus()" placeholder="Поиск..." type="search"
                  class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />

                @endif
              </div>

              <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">

                <template x-for="(item, index) in filteredAttribute" :key="index" hidden>
                  <div class="container-checkbox">
                    <span class="text-xs" x-text="item.name"></span>
                    <input :value="item.id" type="checkbox" x-model.number.debounce.1000="attributeFilter">
                    <span class="checkmark"></span>
                  </div>
                </template>

                <script>
                  document.addEventListener('alpine:initializing', () => {
                    Alpine.data('searchAttribute{{ $attribute->id }}', () => ({
                      search: "",
                                      attributeFilter: @entangle('attFilter'),
                                      attributerData: attribute{{ $attribute->id }},
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
                            }))
                      })
                    var attribute{{ $attribute->id }} = @json($attribute->items);
                </script>
              </div>
            </div>

            @endif
          </div>
          @empty
          @endforelse
        </div>



      </div>

      <div class="px-4 pt-4 lg:py-4">
        <button
          class="inline-block w-full px-3 py-2 text-sm text-gray-600 bg-gray-200 border border-gray-200 rounded-2xl hover:text-gray-900 hover:bg-gray-400"
          wire:click.debounce.1000="resetFilters" wire:loading.attr="disabled">
          Сбросить фильтр
        </button>
      </div>

    </div>

    <div class="space-y-4 sm:w-full">

      <div class="p-6 bg-white rounded-2xl">
        @if($brand->logo)
        <img loading="lazy" class="float-left w-auto h-10 mb-4 mr-6" src="/brands/{{ $brand->logo }}"
          alt="Логотип {{ $brand->name }}">
        @endif

        <h1 class="pb-4 text-xl font-bold">
          {{ $brand->name }}
        </h1>
        <div>
          {{ $brand->description }}
        </div>
      </div>


      <div class="w-full px-4 pb-6 bg-white lg:pt-2 lg:px-6 lg:rounded-2xl">

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


        <x-loader />
        <div wire:loading.remove>

          @if(!count($products) == 0)
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