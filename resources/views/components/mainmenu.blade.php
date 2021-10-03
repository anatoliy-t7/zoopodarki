<div x-data="mainMenu">
  <div>
    <button x-on:click="toggle()"
      class="flex items-center p-3 space-x-2 text-white bg-orange-500 border border-white rounded-xl hover:bg-orange-600 focus:bg-orange-600"
      :class="{ 'bg-gray-700': open}">
      <x-tabler-layout-2 class="w-5 h-5" />
      <div>Все товары</div>
    </button>
  </div>

  <div x-cloak x-show="open" x-transition.opacity @click.outside="open = false" id="megaMenu"
    class="absolute left-0 right-0 z-30 h-auto mt-16 overflow-hidden bg-gray-100 top-2 min-w-screen">
    <div class="relative z-40 flex items-start max-w-screen-xl mx-auto text-gray-700">
      <nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="w-2/12 py-6">
        <div itemprop="about" itemscope itemtype="http://schema.org/ItemList" class="h-full leading-snug bg-transparent">
          @foreach ($menuCatalogs as $catalog)
            <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ItemList">
              <a itemprop="url" x-on:mouseover="tab = {{ $catalog->id }}"
                :class="{ 'bg-white text-orange-500': tab === {{ $catalog->id }} }"
                href="{{ route('site.catalog', ['slug' => $catalog->slug]) }}"
                class="relative z-20 flex items-center h-full px-4 py-4 font-semibold cursor-pointer rounded-l-xl"
                style="word-spacing: 4px;">
                {{ $catalog->name }}
              </a>
              <meta itemprop="name" content="{{ $catalog->name }}" />
              @if ($catalog->icon !== null)
                <div
                  class="">
              {!! $catalog->icon !!}
            </div>
            @endif
          </div>
          @endforeach
        </div>
      </nav>
      <div class="flex self-stretch w-10/12 p-8 bg-white " :class=" { 'rounded-tl-none' : tab==={{ $catalog->id }} }">
                  @foreach ($menuCatalogs as $catalog)

                    <div x-show="tab == {{ $catalog->id }}" class="w-full">
                      <div class="flex justify-between space-x-6">
                        <div class="grid w-full grid-flow-col grid-rows-6 max-h-64 gap-x-4 gap-y-1">
                          @foreach ($catalog->categories as $category)
                            <div class="">
                <a href="
                              {{ route('site.category', ['catalog' => $catalog->slug, 'slug' => $category->slug]) }}"
                              class="block p-2 text-base text-gray-800 hover:text-orange-500">
                              {{ $category->name }}
                              </a>
                            </div>
                          @endforeach
                        </div>

                      </div>
                    </div>

                  @endforeach
                </div>
            </div>
        </div>

        <div x-cloak x-show="open" x-transition.opacity.duration.300
          class="fixed top-0 bottom-0 left-0 right-0 z-0 w-screen h-full mt-32 overflow-hidden bg-gray-900 bg-opacity-50 pointer-events-auto">
        </div>

        <script>
          document.addEventListener('alpine:initializing', () => {
            Alpine.data('mainMenu', () => ({
              open: false,
              tab: 1,
              body: document.body,
              toggle() {
                if (this.open === false) {
                  this.open = true;
                  this.body.classList.add("overflow-hidden", "pr-4");
                } else {
                  this.open = false;
                  this.body.classList.remove("overflow-hidden", "pr-4")
                }
              },

            }))
          })
        </script>

    </div>
