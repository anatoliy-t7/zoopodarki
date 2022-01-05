@if ($catalog->meta_title)
  @section('title', $catalog->meta_title)
@endif
@if ($catalog->meta_description)
  @section('description', $catalog->meta_description)
@endif

<div class="py-2">

  <div class="w-full space-y-4">
    <h1 class="text-3xl font-bold text-gray-700">
      {{ $catalog->name }}
    </h1>

    <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">

      <div class="w-full">
        <div class="masonry">
          @foreach ($catalog->categories->sortBy('sort') as $category)
            <div
              class="inline-block w-full px-4 pt-4 pb-6 mb-6 space-y-2 bg-white shadow-sm item lg:px-6 rounded-2xl break-inside-avoid">
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
