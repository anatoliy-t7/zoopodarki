<div>

  @if (Agent::isDesktop())
    <div class="relative z-30 block">
      <header class="text-gray-600 bg-gray-50">
        <div class="flex items-center justify-between max-w-screen-xl py-1 mx-auto text-xs ">
          <div class="pl-2 font-extrabold text-gray-500">Санкт-Петербург</div>
          <div class="flex items-center justify-end space-x-2">
            <a href="#" class="px-3 py-2 hover:underline">
              Акции и Скидки
            </a>
            <a href="{{ route('site.shops') }}" class="px-3 py-2 hover:underline">
              Магазины
            </a>
            <a href="#" class="px-3 py-2 hover:underline">
              Помощь
            </a>
            <a href="#" class="px-3 py-2 hover:underline">
              Контакты
            </a>
          </div>
        </div>
      </header>

      <header id="navbar" class="z-30 w-full px-3 text-gray-700 bg-white navbar">
        <div class="flex items-center justify-between max-w-screen-xl mx-auto">

          <div class="flex items-center justify-start space-x-12">
            <div class="flex items-center justify-between p-2">
              <a href="/" class="text-lg leading-normal font-semibol focus:outline-none">
                <img src="/assets/img/logo.png" alt="Логотип {{ config('app.name') }}"
                  title="{{ config('app.name') }}" class="h-8">
              </a>
            </div>

            <x-mainmenu />

          </div>

          <div class="w-5/12">
            <livewire:site.search.search-com>
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
      </header>
    </div>
  @endif

  @if (Agent::isTablet() || Agent::isMobile())
    <x-mob-menu />
  @endif

</div>
