<div>
  @if(count($favorites) !== 0)
  <div class="grid grid-cols-1 gap-8 p-6 bg-white rounded-2xl md:grid-cols-3 sm:grid-cols-2 ">
    @foreach ($favorites as $favorite)
    <div wire:key="{{ $loop->index }}"
      class="relative flex flex-col justify-between border border-transparent rounded-2xl hover:border-gray-300">

      <a href="{{ route('site.product', [$favorite->product->categories[0]->catalog->slug, $favorite->product->categories[0]->slug, $favorite->product->slug]) }}"
        class="p-2">

        <img loading="lazy" class="object-contain object-center w-full h-64"
          src="{{ $favorite->product->getFirstMediaUrl('product-images', 'thumb') }}" alt="">

        <div class="py-2 text-center text-blue-500 ">
          @if($favorite->product->brand)
          {{ $favorite->product->brand->name }}
          @endif
        </div>
        <div class="text-sm text-center">
          {{ $favorite->product->name ?? '' }}
        </div>
      </a>
      <div class="flex-col items-center justify-between w-full">
        @foreach ($favorite->product->variations->sortBy('price') as $key => $item)
        <div>
          @if ($item->stock > 0)
          <div class="flex items-center justify-between w-full px-3 pb-2 text-xs">

            <div class="w-4/12">

              <x-units :unit="$favorite->product->unit" :value="$item->unit_value" />
            </div>

            <div class="w-4/12">
              <span class="text-sm font-semibold text-blue-500">{{ RUB($item->price) }}</span>
            </div>

            <div>
              <button title="В корзину" wire:click="$emit('addToCart', {{ $item->id }}, 1)"
                class="z-10 text-red-400 transition ease-in-out transform cursor-pointer focus:outline-none hover:text-red-500 active:scale-95">
                <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                  viewBox="0 0 24 24">
                  <path
                    d="M14,18a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,14,18Zm-4,0a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,10,18ZM19,6H17.62L15.89,2.55a1,1,0,1,0-1.78.9L15.38,6H8.62L9.89,3.45a1,1,0,0,0-1.78-.9L6.38,6H5a3,3,0,0,0-.92,5.84l.74,7.46a3,3,0,0,0,3,2.7h8.38a3,3,0,0,0,3-2.7l.74-7.46A3,3,0,0,0,19,6ZM17.19,19.1a1,1,0,0,1-1,.9H7.81a1,1,0,0,1-1-.9L6.1,12H17.9ZM19,10H5A1,1,0,0,1,5,8H19a1,1,0,0,1,0,2Z" />
                </svg>
              </button>
            </div>

          </div>
          @endif
        </div>
        @endforeach
      </div>


    </div>
    @endforeach
  </div>
  @else
  <div class="flex items-center justify-center w-full pt-12">
    <p>У вас нет избранных товаров</p>
  </div>
  @endif

  <div>{{ $favorites->links('pagination::reviews') }}</div>

</div>