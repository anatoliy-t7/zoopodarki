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

      <header id="navbar" class="z-30 w-full text-gray-700 bg-white navbar">
        <div class="flex items-center justify-between max-w-screen-xl mx-auto">

          <div class="flex items-center justify-start space-x-12">
            <div class="flex items-center justify-between p-2">
              <a href="/" class="text-lg leading-normal font-semibol focus:outline-none focus:ring">
                <img src="/assets/img/logo.png" alt="{{ config('app.name') }}" title="{{ config('app.name') }}"
                  class="h-8">
              </a>
            </div>

            <x-mainmenu />

          </div>

          <div class="w-5/12">
            <livewire:site.search-com>
          </div>


          <div class="flex items-center justify-between space-x-4">

            @auth
              <a title="Избранное" href="{{ route('account.favorites') }}"
                class="flex items-center justify-center mr-3 text-sm focus:outline-none focus:ring group">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                  role="img" class="w-6 h-6 fill-current stroke-2 hover:text-orange-500 focus:text-orange-500" width="1em"
                  height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512">
                  <path
                    d="M352.92 80C288 80 256 144 256 144s-32-64-96.92-64c-52.76 0-94.54 44.14-95.08 96.81c-1.1 109.33 86.73 187.08 183 252.42a16 16 0 0 0 18 0c96.26-65.34 184.09-143.09 183-252.42c-.54-52.67-42.32-96.81-95.08-96.81z"
                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32">
                  </path>
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
