<div itemscope itemtype="http://schema.org/WPHeader">

  @if (Agent::isDesktop())
    <header class="relative z-40 block">
      <div class="text-gray-600 bg-gray-50">
        <div class="flex items-center justify-between max-w-screen-xl px-4 py-1 mx-auto text-xs">
          <div class="font-bold text-gray-500">Санкт-Петербург</div>
          <nav itemscope itemtype="http://schema.org/SiteNavigationElement" aria-label="Primary">
            <div itemprop="about" itemscope itemtype="http://schema.org/ItemList"
              class="flex items-center justify-end space-x-2">
              <a itemprop="itemListElement" href="{{ route('site.page', 'delivery') }}"
                class="px-3 py-2 hover:underline">
                Доставка и самовывоз
              </a>
              <a itemprop="itemListElement" href="{{ route('site.contact') }}" class="px-3 py-2 hover:underline">
                Магазины и контакты
              </a>
            </div>
          </nav>
        </div>
      </div>

      <div class="z-40 w-screen text-gray-700 bg-white navbar">
        <div class="flex items-center justify-between max-w-screen-xl px-4 mx-auto">

          <div class="flex items-center justify-start space-x-12">
            <div class="flex items-center justify-between">
              <x-logo />
            </div>
            <x-mainmenu />
          </div>

          <div class="w-5/12">
            <livewire:site.search.search-com />
          </div>

          <div class="flex items-center justify-between space-x-4">

            @auth
              <a title="Избранное" href="{{ route('account.favorites') }}"
                class="block mr-3 text-sm focus:outline-none focus:ring group">
                <svg class="text-gray-600 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <path class="text-gray-600 stroke-current group-hover:text-orange-500 focus:text-orange-500"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12.62 20.81c-.34.12-.9.12-1.24 0C8.48 19.82 2 15.69 2 8.69 2 5.6 4.49 3.1 7.56 3.1c1.82 0 3.43.88 4.44 2.24a5.53 5.53 0 0 1 4.44-2.24C19.51 3.1 22 5.6 22 8.69c0 7-6.48 11.13-9.38 12.12Z" />
                </svg>
              </a>
            @endauth

            <x-menu-account />

            <livewire:site.shop-cart>

          </div>

        </div>
      </div>
    </header>
  @endif

  @if (Agent::isTablet() || Agent::isMobile())
    <x-mob-menu />
  @endif

</div>
