<div id="mobmenu" x-data="mobmenu"
  class="z-30 block w-full text-gray-600 bg-white border-b border-gray-200 max-w-screen mobmenu">

  <div class="flex items-center justify-between px-5 space-x-4">

    <div>
      <a class="block group" href="/">
        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <path class="text-gray-600 stroke-current group-hover:text-orange-500 focus:text-orange-500"
            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="m9.02 2.84-5.39 4.2C2.73 7.74 2 9.23 2 10.36v7.41c0 2.32 1.89 4.22 4.21 4.22h11.58c2.32 0 4.21-1.9 4.21-4.21V10.5c0-1.21-.81-2.76-1.8-3.45l-6.18-4.33c-1.4-.98-3.65-.93-5 .12zM12 17.99v-3" />
        </svg>
      </a>
    </div>

    <div class="pl-4">
      <div class="relative block pl-4">
        <div x-show="menu === false" x-transition @click="open()" class="absolute -top-3 -left-4">
          <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path class="text-gray-600 stroke-current " stroke-linecap="round" stroke-width="1.5"
              d="M3 7h18M3 12h18M3 17h18" />
          </svg>
        </div>
        <div x-cloak x-show="menu === true" x-transition @click="close()" class="absolute p-1 -top-4 -left-4">
          <x-tabler-x class="text-blue-600 w-7 h-7" />
        </div>
      </div>
    </div>

    <x-search />

    <x-menu-account />

    <livewire:site.shop-cart>

  </div>


  <div x-cloak :class="menu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed left-0 z-20 w-full h-screen max-w-full overflow-hidden text-gray-700 transition duration-300 transform bg-gray-50 top-14">
    <div>

      <div class="relative w-full min-h-screen mx-auto text-gray-700">

        <div class="relative z-40 h-screen px-6 pt-4 pb-32 overflow-y-auto bg-gray-50">
          @foreach ($catalogs as $catalog)
            <div @click="tab = {{ $catalog->id }}"
              class="flex items-center px-4 py-3 space-x-2 text-lg font-semibold cursor-pointer rounded-l-xl">
              <div>{{ $catalog->name }}</div>
              <x-tabler-chevron-right class="w-5 h-5 stroke-current" />
            </div>
          @endforeach
        </div>

        @foreach ($catalogs as $catalog)
          <div x-cloak x-show="tab == {{ $catalog->id }}" x-transition
            class="fixed left-0 z-50 block w-full transition duration-300 transform top-2 bg-gray-50">

            <div
              class="fixed top-0 left-0 z-50 flex items-center justify-between w-full px-8 pb-2 space-x-4 border-b bg-gray-50">
              <div @click="tab = null">
                <x-tabler-chevron-left class="w-6 h-6 stroke-current" />
              </div>
              <a @mouseover="tab = {{ $catalog->id }}" :class="{ 'bg-gray-50': tab === {{ $catalog->id }} }"
                href="{{ route('site.catalog', ['slug' => $catalog->slug]) }}"
                class="block px-4 py-4 font-semibold cursor-pointer">{{ $catalog->name }}
              </a>
            </div>

            <div class="relative z-40 flex-col justify-between h-screen px-6 pt-16 space-y-2 overflow-y-auto pb-36">

              @foreach ($catalog->categories as $category)
                <a href="{{ route('site.category', ['catalog' => $catalog->slug, 'slug' => $category->slug]) }}"
                  class="block p-1 text-lg font-semibold text-gray-800">
                  {{ $category->name }}
                </a>
              @endforeach

            </div>

          </div>

        @endforeach


      </div>

    </div>
  </div>

  <script>
    document.addEventListener('alpine:initializing', () => {
      Alpine.data('mobmenu', () => ({
        body: document.body,
        menu: false,
        tab: null,
        open() {
          this.menu = true
          this.body.classList.add('overflow-hidden');
        },
        close() {
          this.menu = false
          this.body.classList.remove('overflow-hidden');
        },
      }))
    })
  </script>

</div>
