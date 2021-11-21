<div class="space-y-6">

  @if ($homePageBlockOne)
    <div class="px-6 py-5 bg-white rounded-2xl">
      <h3 class="pb-2 text-xl font-bold text-gray-500">{{ $homePageBlockOneTitle }}</h3>
      <div class="flex items-center justify-between">
        <div class="flex flex-col items-center justify-between space-x-2 md:flex-row">
          @forelse ($homePageBlockOne as $item)
            <div class="w-3/12">
              <livewire:site.card-products :product="$item" :catalog="$item->categories[0]->catalog"
                :category="$item->categories[0]" :key="'block-one-'.$item->id" />
            </div>
          @empty
          @endforelse
        </div>
      </div>
    </div>
  @endif

  @if ($homePageBlockTwo)
    <div class="px-6 py-5 bg-white rounded-2xl">
      <h3 class="pb-2 text-xl font-bold text-gray-500">{{ $homePageBlockTwoTitle }}</h3>
      <div class="flex items-center justify-between">
        <div class="flex flex-col items-center justify-between space-x-2 md:flex-row">
          @forelse ($homePageBlockTwo as $item)
            <div class="w-3/12">
              <livewire:site.card-products :product="$item" :catalog="$item->categories[0]->catalog"
                :category="$item->categories[0]" :key="'block-two-'.$item->id" />
            </div>
          @empty
          @endforelse
        </div>
      </div>
    </div>
  @endif


  <div class="px-6 py-5 bg-white rounded-2xl">
    <h3 class="pb-2 text-xl font-bold text-gray-500">Популярные бренды</h3>
    <div>
      <div class="flex flex-col items-center justify-between space-x-2 md:flex-row">
        @forelse ($brandsSlider as $brand)
          <div>
            <a href="{{ route('site.brand', $brand->slug) }}">
              @if ($brand->logo)
                <img src="/brands/{{ $brand->logo }}" alt="{{ $brand->name }}">
              @else
                <div class="text-xl font-bold">{{ $brand->name }}</div>
              @endif

            </a>
          </div>
        @empty
        @endforelse
        <div class="">
          <a class="block px-4 py-2 text-lg leading-normal bg-white border cursor-pointer rounded-xl hover:border-blue-300 "
            href="{{ route('site.brands') }}">Остальные<br />бренды</a>
        </div>
      </div>
    </div>
  </div>

</div>
