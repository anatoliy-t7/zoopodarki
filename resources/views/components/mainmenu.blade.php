<div x-data="{ open: false, tab: 1 }"
  x-effect="document.body.classList.toggle('overflow-hidden', open), document.body.classList.toggle('pr-4', open)">
  <div>
    <button x-on:click="open = !open"
      class="flex items-center px-3 py-2 text-white bg-orange-500 border-2 border-orange-500 rounded-xl focus:outline-none hover:bg-orange-600 focus:bg-orange-600"
      :class="open  ? 'bg-orange-600' : ' '">

      <span x-show="!open">
        <x-tabler-menu-2 class="w-6 h-6" />
      </span>

      <span x-cloak x-show="open">
        <x-tabler-x class="w-6 h-6 " />
      </span>

      <div class="pl-2 font-semibold">Каталог</div>
    </button>
  </div>

  <div x-cloak x-show="open" x-transition.opacity @click.outside="open = false" id="megaMenu"
    class="absolute left-0 z-40 w-full h-screen mt-16 overflow-x-hidden bg-gray-100 top-2 scrollbar">
    <div
      class="relative z-50 flex items-start w-full h-auto mx-auto overflow-y-auto text-gray-700 max-w-screen overscroll-contain pb-28">
      <nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="w-3/12 py-6">
        <div itemprop="about" itemscope itemtype="http://schema.org/ItemList" class="h-full leading-snug bg-transparent">
          @foreach ($menuCatalogs as $menuCatalog)
            <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ItemList"
              class="flex justify-end text-left">
              <a itemprop="url" x-on:mouseover="tab = {{ $menuCatalog->id }}"
                :class="{ 'bg-white text-orange-500 border-orange-400': tab === {{ $menuCatalog->id }} }"
                href="{{ route('site.catalog', ['slug' => $menuCatalog->slug]) }}"
                class="relative flex items-center h-full py-4 pl-6 font-bold text-gray-900 border-r-4 border-transparent rounded-l-lg cursor-pointer hover:border-orange-400 w-80"
                style="word-spacing: 4px;">
                {{ $menuCatalog->name }}
              </a>
              <meta itemprop="name" content="{{ $menuCatalog->name }}" />
              @if ($menuCatalog->icon !== null)
                <div class="">
                  {!! $menuCatalog->icon !!}
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </nav>
      <div class="flex self-stretch w-9/12 p-8 bg-white "
        :class=" { 'rounded-tl-none' : tab==={{ $menuCatalog->id }} }">
        @foreach ($menuCatalogs as $catalog)
          <div x-show="tab == {{ $catalog->id }}" class="w-full">
            @if ($catalog->brands)
              <div class="flex items-center justify-start pb-6 pl-16 space-x-12">
                @foreach ($catalog->brands as $brand)
                  <a href="{{ route('site.brand', ['brand' => $brand->slug]) }}">
                    @if ($brand->logo)
                      <img loading="lazy" class="w-auto h-24" src="/brands/{{ $brand->logo }}">
                    @else
                      <div>{{ $brand->name }}</div>
                    @endif
                  </a>
                @endforeach
              </div>
            @endif
            <div class="flex flex-wrap w-full max-w-screen-lg">
              @foreach ($catalog->categories as $category)
                <div class="py-2 pl-12 w-80">
                  <a href="{{ route('site.category', ['catalog' => $catalog->slug, 'slug' => $category->slug]) }}"
                    class="block p-2 text-lg font-bold text-gray-900 hover:text-orange-500">
                    {{ $category->name }}
                  </a>
                  <div class="px-2 space-y-2">
                    @foreach ($category->tags as $tag)
                      <a href="{{ route('site.category', ['catalog' => $catalog->slug, 'slug' => $category->slug, 'tagslug' => $tag->slug]) }}"
                        class="block text-base text-gray-800 hover:text-orange-500">
                        {{ $tag->name }}
                      </a>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          </div>

        @endforeach
      </div>
    </div>
  </div>

  <div x-cloak x-show="open" x-transition.opacity.duration.300
    class="fixed inset-0 z-10 w-screen h-full overflow-hidden bg-gray-900 bg-opacity-50 pointer-events-auto mt-28">
  </div>

</div>
