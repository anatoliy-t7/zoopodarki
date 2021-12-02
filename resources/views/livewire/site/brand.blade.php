  <div class="space-y-6">

    <div class="flex items-center justify-start pt-4 space-x-4 text-2xl ">
      <h1 class="font-bold">
        {{ $brand->name }}
      </h1>
    </div>


    <div class="flex items-start justify-start p-6 space-x-6 bg-white rounded-2xl">
      @if ($brand->logo)
        <img loading="lazy" class="w-3/12" src="/brands/{{ $brand->logo }}" alt="Логотип {{ $brand->name }}">
      @endif
      <div class="w-9/12">
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

    @if ($catalogs)
      <div class="flex flex-wrap gap-6">
        @foreach ($catalogs->sortBy('sort', 0) as $catalog)
          <div class="space-y-2">
            <h3 class="text-xl font-bold">{{ $catalog->name }}</h3>
            @if ($catalog->categories->count() > 0)
              <div class="w-full px-6 py-5 space-y-2 bg-white lg:rounded-2xl">
                @foreach ($catalog->categories->sortBy('name') as $category)
                  <a class="block p-1 font-semibold cursor-pointer hover:underline"
                    href="{{ route('site.category', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug]) }}?brandFilter[0]={{ $brand->id }}">
                    {{ $category->name }}
                  </a>
                @endforeach
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif

  </div>
