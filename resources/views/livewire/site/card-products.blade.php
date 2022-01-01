<div
  class="relative flex flex-col justify-between h-full p-2 bg-white border border-gray-100 md:p-0 lg:border-white hover:border-green-300 rounded-2xl">
  <div class="p-2">
    <a itemprop="url"
      href="{{ route('site.product', ['catalogslug' => $catalog, 'categoryslug' => $category, 'productslug' => $product->slug]) }}"
      class="block " title="{{ $product->name }}">
      <img width="198" height="256" itemprop="image" class="object-contain object-center w-full h-64 lozad "
        src="/assets/img/placeholder.svg" data-src="{{ $product->getFirstMediaUrl('product-images', 'thumb') }}"
        alt="{{ $product->name }}">
    </a>

    @if ($product->brand)
      <div class="py-2 font-semibold text-center text-green-600 md:pb-2 md:pt-3">
        <a itemprop="url"
          href="{{ route('site.product', ['catalogslug' => $catalog, 'categoryslug' => $category, 'productslug' => $product->slug]) }}"
          title="{{ $product->brand->name }}">
          {{ $product->brand->name }}
        </a>
      </div>
    @endif

    <div itemprop="name" class="text-base text-center text-gray-800 md:text-sm line-clamp-2">
      <a itemprop="url"
        href="{{ route('site.product', ['catalogslug' => $catalog, 'categoryslug' => $category, 'productslug' => $product->slug]) }}"
        title="{{ $product->name }}">
        {{ $product->name }}
      </a>
    </div>
  </div>

  <div class="flex-col items-center justify-between w-full pt-1" itemprop="offers" itemscope
    itemtype="https://schema.org/Offer">
    @foreach ($product->variations->sortBy('price') as $key => $item)

      @if ($item->promotion_type != 0)
        <div class="absolute top-0 left-0">
          <x-tabler-discount-2 class="w-8 h-8 text-orange-500 stroke-current" />
        </div>
      @endif
      <div>

        <div class="flex items-center justify-between w-full px-3 pb-3 space-x-2 text-xs">
          <div class="w-5/12 whitespace-nowrap">
            <x-units :unit="$product->unit" :value="$item->unit_value" :wire:key="$product->id" />
          </div>

          <div class="flex items-center justify-end w-5/12 space-x-2 whitespace-nowrap">
            @if ($item->promotion_type === 0)
              <div class="text-base font-semibold text-gray-800 md:text-sm" itemprop="price">
                {{ RUB($item->price) }}</div>
            @elseif ($item->promotion_type === 1 || $item->promotion_type === 3)
              <div class="text-xs text-gray-500 line-through">{{ RUB($item->promotion_price) }}</div>
              <div class="text-base font-semibold text-orange-500 md:text-sm" itemprop="price">
                {{ RUB($item->price) }}</div>
            @elseif ($item->promotion_type === 2 || $item->promotion_type === 4)
              <div class="text-xs text-gray-500 line-through">{{ RUB($item->price) }}</div>
              <div class="text-base font-semibold text-orange-500 md:text-sm" itemprop="price">
                {{ RUB(discount($item->price, $item->promotion_percent)) }}</div>
            @endif
          </div>

          <div class="flex items-start justify-end w-2/12 -mt-1">
            @if ($item->stock > 0)
              <button wire:click="$emit('addToCart', {{ $item->id }}, 1, {{ $catalogId }}, 1000)"
                aria-label="Добавить в корзину"
                class="z-10 transition ease-in-out cursor-pointer focus:outline-none active:scale-95 link-hover group">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <path class="text-blue-400 stroke-current group-hover:text-blue-500" stroke-linecap="round"
                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"
                    d="M8.5 14.25c0 1.92 1.58 3.5 3.5 3.5s3.5-1.58 3.5-3.5M8.81 2 5.19 5.63m10-3.63 3.62 3.63" />
                  <path class="text-blue-400 stroke-current group-hover:text-blue-500" stroke-width="1.5"
                    d="M2 7.85c0-1.85.99-2 2.22-2h15.56c1.23 0 2.22.15 2.22 2 0 2.15-.99 2-2.22 2H4.22C2.99 9.85 2 10 2 7.85Z" />
                  <path class="text-blue-400 stroke-current group-hover:text-blue-500" stroke-linecap="round"
                    stroke-width="1.5"
                    d="m3.5 10 1.41 8.64C5.23 20.58 6 22 8.86 22h6.03c3.11 0 3.57-1.36 3.93-3.24L20.5 10" />
                </svg>
              </button>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
