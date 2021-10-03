<div id="mobmenu" x-data="mobmenu"
  class="z-30 block w-full text-gray-600 bg-white border-b border-gray-200 max-w-screen mobmenu">

  <div class="flex items-center justify-between px-5 pt-1 space-x-4">

    <div>
      <a class="block p-1" href="/">
        <x-tabler-home-2 class="w-6 h-6" />
      </a>
    </div>

    <div class="pl-4">
      <div class="relative block pl-4">
        <div x-show="menu === false" x-transition @click="open()" class="absolute p-1 -top-4 -left-4">
          <x-tabler-menu-2 class="w-6 h-6" />
        </div>
        <div x-cloak x-show="menu === true" x-transition @click="close()" class="absolute p-1 -top-4 -left-4">
          <x-tabler-x class="w-6 h-6 text-blue-600" />
        </div>
      </div>
    </div>

    <x-search />

    <x-menu-account />

    <livewire:site.shop-cart>

  </div>


  <div x-cloak :class="menu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed left-0 z-20 w-full h-screen max-w-full px-6 py-4 overflow-hidden text-gray-700 transition duration-300 transform bg-white top-12">
    <div>

      <div class="relative w-full min-h-screen mx-auto text-gray-700">

        <div class="relative z-40 min-h-screen bg-white">

          @foreach ($catalogs as $catalog)
          <div>
            <div @click="tab = {{ $catalog->id }}"
              class="flex items-center px-4 py-3 space-x-2 font-semibold cursor-pointer rounded-l-xl">
              <div>{{ $catalog->name }}</div>
              <x-tabler-chevron-right class="w-5 h-5 stroke-current" />
            </div>
          </div>
          @endforeach

        </div>

        @foreach ($catalogs as $catalog)
        <div x-cloak x-show="tab == {{ $catalog->id }}" x-transition
          class="fixed top-0 left-0 z-50 block w-full h-full min-h-screen py-2 transition duration-300 transform bg-white">

          <div
            class="fixed top-0 left-0 z-50 flex items-center justify-between w-full px-8 space-x-4 bg-white border-b">
            <div @click="tab = null">
              <x-tabler-chevron-left class="w-6 h-6 stroke-current" />
            </div>
            <a @mouseover="tab = {{ $catalog->id }}" :class="{ 'bg-white': tab === {{ $catalog->id }} }"
              href="{{ route('site.catalog', ['slug' => $catalog->slug ]) }}"
              class="block px-4 py-4 font-semibold cursor-pointer">{{ $catalog->name }}
            </a>
          </div>

          <div class="relative z-40 h-full min-h-screen px-8 py-16 overflow-y-auto ">
            <div class="flex-col justify-between h-auto space-y-2">
              @foreach ($catalog->categories as $category)
              <div class="">
                <a href="{{ route('site.category', ['catalog' => $catalog->slug, 'slug' => $category->slug ]) }}"
                  class="block p-1 text-sm font-semibold text-gray-800 ">
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