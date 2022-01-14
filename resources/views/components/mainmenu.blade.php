<div x-data="{ open: false, tab: 1 }"
  x-effect="document.body.classList.toggle('overflow-hidden', open), document.body.classList.toggle('pr-4', open)"
  @keydown.escape="open = false">
  <div>
    <button x-on:click="open = !open"
      class="flex items-center px-3 py-2 text-white bg-orange-400 border-2 border-orange-400 rounded-xl focus:outline-none hover:bg-orange-500 focus:bg-orange-500"
      :class="open  ? 'bg-orange-500' : ' '">

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
    class="absolute left-0 z-40 w-full mt-2 overflow-x-hidden text-gray-800 bg-gray-100 top-16">
    <div class="relative z-50 flex items-start w-full h-auto mx-auto max-w-screen pb-28 sm:pb-0">
      <nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="w-3/12 py-6">
        <div itemprop="about" itemscope itemtype="http://schema.org/ItemList" class="h-full leading-snug bg-transparent">
          @foreach ($menuCatalogs as $menuCatalog)
            <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ItemList"
              class="flex justify-end text-left">
              <a itemprop="url" x-on:mouseover="tab = {{ $menuCatalog->id }}"
                :class="{ 'bg-white text-orange-500 border-orange-400': tab === {{ $menuCatalog->id }} }"
                href="{{ route('site.catalog', ['catalogslug' => $menuCatalog->slug]) }}"
                class="relative flex items-center h-full py-4 pl-6 text-lg font-bold border-r-4 border-transparent rounded-l-lg cursor-pointer hover:border-orange-400 w-80"
                style="word-spacing: 4px;">
                {{ $menuCatalog->name }}
              </a>
              <meta itemprop="name" content="{{ $menuCatalog->name }}" />
            </div>
          @endforeach
        </div>
      </nav>
      <div class="flex self-stretch w-9/12 h-screen p-8 overflow-y-auto bg-white scrollbar"
        :class=" { 'rounded-tl-none' : tab==={{ $menuCatalog->id }} }">
        @foreach ($menuCatalogs as $catalog)
          <div x-show="tab == {{ $catalog->id }}" class="w-full">
            @if ($catalog->brandsById)
              <div class="flex items-center justify-between pt-6 pb-10 pl-16 space-x-12">
                @foreach ($catalog->brandsById as $brand)
                  <a href="{{ route('site.brand', ['brandslug' => $brand->slug]) }}"
                    class="font-bold hover:text-orange-500">
                    @if ($brand->logo)
                      <img loading="lazy" class="object-scale-down w-auto h-24"
                        src="/assets/brands/{{ $brand->logo }}">
                    @else
                      <div>{{ $brand->name }}</div>
                    @endif
                  </a>
                @endforeach
                <div class="flex items-center justify-center w-full md:px-4 ">
                  <a href="{{ route('site.brands') }}"
                    class="flex items-center justify-between gap-1 px-3 py-2 border border-gray-300 bg-gray-50 hover:bg-gray-100 rounded-2xl">
                    <span> Все бренды</span>
                    <x-tabler-chevron-right class="w-5 h-5" />
                  </a>
                </div>
              </div>
            @endif
            <div class="w-full max-w-screen-lg min-h-full pb-40 menuMasonry">
              @foreach ($catalog->categories->sortBy('sort') as $menuCategory)
                <div class="w-full h-full py-2 pl-12">
                  <a href="{{ route('site.category', ['catalogslug' => $catalog->slug, 'categoryslug' => $menuCategory->slug]) }}"
                    class="block p-2 text-lg font-bold hover:text-orange-500">
                    @if ($menuCategory->menu_name !== null)
                      {{ $menuCategory->menu_name }}
                    @else
                      {{ $menuCategory->name }}
                    @endif

                  </a>
                  @if ($menuCategory->tags->count() > 0)
                    <div class="flex flex-wrap gap-2 px-2">
                      @foreach ($menuCategory->tags as $tag)
                        <a href="{{ route('site.tag', ['catalogslug' => $catalog->slug, 'categoryslug' => $menuCategory->slug, 'tagslug' => $tag->slug]) }}"
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
        @endforeach
      </div>
    </div>
  </div>

  <div x-cloak x-show="open" x-transition.opacity.duration.300
    class="fixed inset-0 z-10 w-screen h-full overflow-hidden bg-gray-900 bg-opacity-50 pointer-events-auto mt-28">
  </div>

</div>
