<div x-data="{ openResult: true }" class="relative" @click.outside="openResult = false">
  <div wire:ignore.self class="max-w-4xl mx-auto text-gray-700 ">

    <div class="relative ">

      <div class="absolute top-0 left-0 z-30 pt-3 pl-2 cursor-default">
        <div class="w-6 h-6 mx-auto text-gray-300 fill-current">
          <x-tabler-search />
        </div>
      </div>

      <input type="search" x-on:input.debounce.750="openResult = 1"
        class="w-full px-5 py-3 pl-10 font-semibold border border-gray-50 hover:border-gray-200 bg-gray-50 rounded-2xl focus:outline-none focus:ring focus:bg-white"
        wire:model.debounce.600ms="search" placeholder="Поиск" autocomplete="off" inputmode="search">

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
  <div x-show="openResult">
    @if ($result)
    <div wire:loading.remove
      class="absolute top-0 left-0 right-0 z-40 w-full max-w-2xl max-h-screen p-2 mx-auto mt-12 overflow-x-hidden overflow-y-auto text-gray-800 bg-white shadow-2xl md:p-6 rounded-2xl">
      <div class="divide-y">
        @forelse ($result['hits'] as $item)
        <div>
          @if (array_key_exists('category', $item)) <a
            href="{{ route('site.product', [ $item['catalog'], $item['category'], $item['slug']]) }}"
            class="flex items-center justify-start px-4 py-2 space-x-2 text-sm hover:bg-gray-50">
            @endif

            @if (array_key_exists('image', $item))
            <img loading="lazy" class="object-contain object-center w-10 h-10" src="{{ $item['image'] }}" alt="Image">
            @endif

            <div>{!! $item['_formatted']['name'] !!}</div>
            @if (array_key_exists('category', $item))
          </a>
          @endif
        </div>
        @empty
        <div>По этому запросу ничего не найдено</div>
        @endforelse
        <div class="pt-6 text-center">
          <a href="#" class="text-xs text-white bg-orange-400 btn">
            Посмотреть остальные результаты
          </a>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>