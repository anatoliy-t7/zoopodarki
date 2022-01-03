  <div>
    <div class="flex items-center justify-start gap-12 py-4">
      <h1 class="text-xl font-bold text-gray-500">
        Бренды
      </h1>

      <div class="relative w-full max-w-md group">
        <div class="absolute top-0 left-0 z-30 px-3 pt-2 cursor-default">
          <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path class="text-gray-300 stroke-current group-hover:text-gray-400 focus:text-gray-400"
              stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M11.5 21a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19zM22 22l-2-2" />
          </svg>
        </div>

        <input type="searchBrand" name="searchBrand" x-on:input.debounce.750="openResult = 1"
          class="w-full px-5 py-3 pl-12 font-semibold bg-white border border-gray-100 hover:border-gray-200 rounded-2xl focus:outline-none focus:ring focus:bg-white "
          wire:model.debounce.600ms="searchBrand" id="searchBrand" placeholder="Поиск по бренду">

        <div class="absolute top-0 left-0 right-0 z-30 w-full pt-3" wire:loading>

          <svg class="w-6 h-6 mx-auto text-orange-400 animate-spin " xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>

        </div>
      </div>
    </div>

    <div class="grid w-full grid-cols-2 gap-6 pt-2 md:grid-cols-3 lg:grid-cols-4">
      @forelse ($brands as $brand)
        <a class="flex items-center justify-center px-3 py-4 transition-shadow bg-white shadow-sm rounded-xl hover:shadow-lg"
          href="{{ route('site.brand', ['brandslug' => $brand->slug]) }}">
          @if ($brand->logo)
            <img loading="lazy" class="object-scale-down w-full h-16" src="/assets/brands/{{ $brand->logo }}"
              alt="Логотип {{ $brand->name }}">
          @else
            <span class="font-bold text-blue-500">{{ $brand->name }}</span>
          @endif
        </a>
      @empty
        <p>No brands</p>
      @endforelse
    </div>

    <div wire:loading.remove class="pt-6">
      {{ $brands->links() }}
    </div>

  </div>
