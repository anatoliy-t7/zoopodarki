<div
  class="relative flex flex-col justify-between h-full border border-gray-100 lg:border-white hover:border-green-300 rounded-2xl">

  <a itemprop="url" href="{{ route('site.product', [$catalog, $category, $product->slug]) }}" class="p-2"
    title="{{ $product->name }}">

    <img itemprop="image" loading="lazy" class="object-contain object-center w-full h-64 lozad"
      src="/assets/img/placeholder.svg" data-src="{{ $product->getFirstMediaUrl('product-images', 'thumb') }}"
      alt="{{ $product->name }}">

    <div class="py-2 text-center text-green-600">
      @if ($product->brand)
        {{ $product->brand->name }}
      @endif
    </div>

    <div itemprop="name" class="text-sm text-center text-gray-800">
      {{ $product->name }}
    </div>
  </a>

  <div class="flex-col items-center justify-between w-full" itemprop="offers" itemscope
    itemtype="https://schema.org/Offer">
    @foreach ($product->variations->sortBy('price') as $key => $item)
      @if ($category !== 'pomogi-priyutu' and $item->promotion_type !== 1)
        @if ($item->promotion_type != 0 and $item->promotion_type != 1)
          <div class="absolute top-0 left-0">
            <x-tabler-discount-2 class="w-8 h-8 text-orange-500 stroke-current" />
          </div>
        @endif
        <div>
          @if ($item->stock > 0)
            <div class="flex items-center justify-between w-full px-3 pb-2 text-xs">

              <div class="w-4/12 whitespace-nowrap">

                <x-units :unit="$product->unit" :value="$item->unit_value" :wire:key="$product->id" />

              </div>

              <div class="flex items-center justify-end w-4/12 space-x-2 whitespace-nowrap">
                @if ($item->promotion_type === 3)
                  <div class="text-xs text-gray-500 line-through">
                    {{ RUB(discountRevert($item->price, $item->promotion_percent)) }}</div>
                  <div class="text-sm font-semibold text-gray-800" itemprop="price">{{ RUB($item->price) }}</div>
                @elseif ($item->promotion_type === 4)
                  <div class="text-xs text-gray-500 line-through">{{ RUB($item->price) }}</div>
                  <div class="text-sm font-semibold text-gray-800" itemprop="price">
                    {{ RUB(discount($item->price, $item->promotion_percent)) }}</div>
                @else
                  <div class="text-sm font-semibold text-gray-800" itemprop="price">{{ RUB($item->price) }}
                  </div>
                @endif

              </div>

              <div>
                <button title="В корзину" wire:click="$emit('addToCart', {{ $item->id }}, 1)"
                  class="z-10 text-blue-500 transition ease-in-out transform cursor-pointer focus:outline-none hover:text-blue-600 active:scale-95 link-hover">
                  <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                      d="M14,18a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,14,18Zm-4,0a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,10,18ZM19,6H17.62L15.89,2.55a1,1,0,1,0-1.78.9L15.38,6H8.62L9.89,3.45a1,1,0,0,0-1.78-.9L6.38,6H5a3,3,0,0,0-.92,5.84l.74,7.46a3,3,0,0,0,3,2.7h8.38a3,3,0,0,0,3-2.7l.74-7.46A3,3,0,0,0,19,6ZM17.19,19.1a1,1,0,0,1-1,.9H7.81a1,1,0,0,1-1-.9L6.1,12H17.9ZM19,10H5A1,1,0,0,1,5,8H19a1,1,0,0,1,0,2Z" />
                  </svg>
                </button>
              </div>

            </div>
          @endif
        </div>
      @else


        <div class="absolute top-0 left-0">
          <x-tabler-discount-2 class="w-8 h-8 text-orange-500 stroke-current" />
        </div>


        <div>
          @if ($item->stock > 0)
            <div class="flex items-center justify-between w-full px-3 pb-2 text-xs">

              <div class="w-4/12 whitespace-nowrap">

                <x-units :unit="$product->unit" :value="$item->unit_value" :wire:key="$product->id" />
              </div>

              <div class="flex items-center justify-end w-4/12 space-x-2 whitespace-nowrap">

                <div class="text-xs text-gray-500 line-through">{{ RUB($item->price) }}</div>
                <div class="text-sm font-semibold text-orange-500" itemprop="price">
                  {{ RUB($item->promotion_price) }}</div>

              </div>

              <div>
                <button title="В корзину" wire:click="$emit('addToCart', {{ $item->id }}, 1)"
                  class="z-10 text-blue-500 transition ease-in-out transform cursor-pointer focus:outline-none hover:text-blue-600 active:scale-95 link-hover">
                  <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                      d="M14,18a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,14,18Zm-4,0a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,10,18ZM19,6H17.62L15.89,2.55a1,1,0,1,0-1.78.9L15.38,6H8.62L9.89,3.45a1,1,0,0,0-1.78-.9L6.38,6H5a3,3,0,0,0-.92,5.84l.74,7.46a3,3,0,0,0,3,2.7h8.38a3,3,0,0,0,3-2.7l.74-7.46A3,3,0,0,0,19,6ZM17.19,19.1a1,1,0,0,1-1,.9H7.81a1,1,0,0,1-1-.9L6.1,12H17.9ZM19,10H5A1,1,0,0,1,5,8H19a1,1,0,0,1,0,2Z" />
                  </svg>
                </button>
              </div>

            </div>
          @endif
        </div>
      @endif
    @endforeach
  </div>

</div>
