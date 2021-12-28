@if ($catalog->meta_title)
  @section('title', $catalog->meta_title)
@endif
@if ($catalog->meta_description)
  @section('description', $catalog->meta_description)
@endif

<div class="py-2">

  <div class="w-full space-y-4">
    <h1 class="text-2xl font-bold">
      {{ $catalog->name }}
    </h1>

    <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">

      <div class="w-full">
        <div class="masonry">
          @foreach ($catalog->categories->sortBy('sort') as $category)
            <div
              class="inline-block w-full px-4 pt-4 pb-6 mb-6 space-y-2 bg-white item lg:px-6 rounded-2xl break-inside-avoid">
              <a href="{{ route('site.category', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug]) }}"
                class="block px-2 py-2 text-lg font-semibold leading-tight hover:underline">
                {{ $category->menu_name }}
              </a>
              @if ($category->tags->count() > 0)
                <div class="px-2 space-y-1">
                  @foreach ($category->tags as $tag)
                    <a href="{{ route('site.tag', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug, 'tagslug' => $tag->slug]) }}"
                      class="block text-base text-gray-800 lowercase hover:text-orange-500">
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
