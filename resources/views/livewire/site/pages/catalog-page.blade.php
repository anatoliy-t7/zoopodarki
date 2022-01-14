@if ($catalog->meta_title)
  @section('title', $catalog->meta_title)
@endif
@if ($catalog->meta_description)
  @section('description', $catalog->meta_description)
@endif

<div class="py-2">

  <div class="w-full space-y-5">
    <h1 class="text-3xl font-bold text-gray-700">
      {{ $catalog->name }}
    </h1>

    <div class="flex items-center justify-between p-6 space-x-12 bg-white shadow-sm rounded-2xl">
      @foreach ($catalog->brandsById as $brand)
        <a href="{{ route('site.brand', ['brandslug' => $brand->slug]) }}" class="font-bold hover:text-orange-500">
          @if ($brand->logo)
            <img loading="lazy" class="object-contain w-auto h-20" src="/assets/brands/{{ $brand->logo }}">
          @else
            <div>{{ $brand->name }}</div>
          @endif
        </a>
      @endforeach
      <div class="flex items-center justify-center md:px-4 ">
        <a href="{{ route('site.brands') }}"
          class="flex items-center justify-between gap-1 px-3 py-2 border border-gray-300 w-36 bg-gray-50 hover:bg-gray-100 rounded-2xl">
          <span> Все бренды</span>
          <x-tabler-chevron-right class="w-5 h-5" />
        </a>
      </div>
    </div>

    <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">
      <div class="w-full">
        <div class="masonry">
          @foreach ($catalog->categories->sortBy('sort') as $category)
            <div
              class="inline-block w-full px-4 pt-4 pb-6 mb-6 space-y-2 bg-white shadow-sm lg:px-6 rounded-2xl break-inside-avoid">
              <a href="{{ route('site.category', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug]) }}"
                class="block px-2 py-2 text-xl font-semibold leading-tight text-orange-400 hover:underline">
                {{ $category->menu_name }}
              </a>
              @if ($category->tags->count() > 0)
                <div class="flex flex-wrap gap-2 px-2">
                  @foreach ($category->tags->sortBy('name') as $tag)
                    <a href="{{ route('site.tag', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug, 'tagslug' => $tag->slug]) }}"
                      class="block px-3 py-1 text-sm font-semibold text-gray-500 lowercase bg-gray-50 hover:bg-blue-100 rounded-2xl">
                      {{ $tag->name }}
                    </a>
                  @endforeach
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>

    </div>

  </div>

</div>
