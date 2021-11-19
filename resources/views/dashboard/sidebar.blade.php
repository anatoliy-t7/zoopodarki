<div x-cloak wire:ignore :class="{'translate-x-0': sidebar, '-translate-x-full sm:-translate-x-56': !sidebar}"
  class="fixed top-0 bottom-0 left-0 z-30 block w-full h-full min-h-screen overflow-y-auto font-light text-gray-400 transition-transform duration-300 ease-in-out transform translate-x-0 bg-gray-900 shadow-lg sm:w-56 scrollbar">

  <div class="flex flex-col items-stretch justify-between h-full">
    <div class="flex flex-col flex-shrink-0 w-full">
      <div class="flex items-center justify-center px-8 py-3 text-center">
        <a href="/"
          class="text-lg leading-normal text-orange-400 hover:text-orange-500 focus:outline-none focus:ring">{{ config('app.name') }}</a>
      </div>

      <nav>
        <div class="flex-grow md:block md:overflow-y-auto ">
          <a class="flex justify-start items-center px-4 py-3 hover:bg-gray-800 hover:text-gray-400 focus:bg-gray-800 focus:outline-none focus:ring {{ request()->is('dashboard') ? 'bg-gray-800' : '' }}"
            href="{{ route('dashboard.dashboard') }}">
            <x-tabler-dashboard class="w-6 h-6" />
            <span class="mx-4">Панель</span>
          </a>

          <div>
            <div x-on:click="productMenu = ! productMenu"
              class="flex items-center justify-between px-4 py-3 bg-transparent cursor-pointer hover:bg-gray-800 hover:text-gray-400 focus:bg-gray-800 focus:outline-none focus:ring">
              <div class="flex items-center space-x-4">
                <x-tabler-package class="w-6 h-6" />
                <span class="mx-4">Товары</span>
              </div>
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path x-bind:class="{ 'block': !productMenu, 'hidden': productMenu }" d="M6 9l6 6 6-6" />
                  <path x-bind:class="{ 'block': productMenu, 'hidden': !productMenu }" d="M18 15l-6-6-6 6" />
                </svg>
              </span>
            </div>
            <div x-cloak x-show="productMenu" x-transition.in.duration.300ms.out.duration.300ms class="text-sm ">
              <a class="flex items-center px-4 py-2 pl-6 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/products*') ? 'bg-gray-800' : '' }}"
                href="{{ route('dashboard.products.index') }}">Товары</a>

              @can('see')
                <a class="flex items-center px-4 py-2 pl-6 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/variations*') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.products1c') }}">Акции и товары 1C</a>

                <a class="flex items-center px-4 py-2 pl-6  hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/catalogs') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.catalogs') }}">Каталоги и категории</a>

                <a class="flex items-center px-4 py-2 pl-6 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/attributes*') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.attributes') }}">Свойства</a>

                <a class="flex items-center px-4 py-2 pl-6  hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/tags') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.tags') }}">Теги</a>

                <a class="flex items-center px-4 py-2 pl-6 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/brands') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.brands') }}">Бренды</a>

                <a class="flex items-center px-4 py-2 pl-6 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/units') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.units') }}">Единицы измерения</a>
              @endcan

            </div>
          </div>

          @can('create')
            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/orders*') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.orders') }}">
              <x-tabler-basket class="w-6 h-6" />
              <span class="mx-4">Заказы</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/waitlists') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.waitlists') }}">
              <x-tabler-notes class="w-6 h-6" />
              <span class="mx-4">Лист ожидания</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/reviews*') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.reviews') }}">
              <x-tabler-message class="w-6 h-6" />
              <span class="mx-4">Отзывы</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/page*') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.pages') }}">
              <x-tabler-template class="w-6 h-6" />
              <span class="mx-4">Страницы</span>

            </a>

            <div>
              <div x-on:click="settingMenu = ! settingMenu"
                class="flex items-center justify-between px-4 py-3 bg-transparent cursor-pointer hover:bg-gray-800 hover:text-gray-400 focus:bg-gray-800 focus:outline-none focus:ring">
                <div class="flex items-center space-x-4">
                  <x-tabler-settings class="w-6 h-6" />
                  <span class="mx-4">Настройки</span>
                </div>
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path x-bind:class="{ 'block': !settingMenu, 'hidden': settingMenu }" d="M6 9l6 6 6-6" />
                    <path x-bind:class="{ 'block': settingMenu, 'hidden': !settingMenu }" d="M18 15l-6-6-6 6" />
                  </svg>
                </span>
              </div>
              <div x-cloak x-show="settingMenu" x-transition.in.duration.300ms.out.duration.300ms class="text-sm ">

                <a class="flex items-center px-4 py-2 pl-6  hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/settings/main') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.settings.main') }}">Основные</a>

                <a class="flex items-center px-4 py-2 pl-6 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/settings/backup') ? 'bg-gray-800' : '' }}"
                  href="{{ route('dashboard.settings.backup') }}">BackUp DB</a>

              </div>
            </div>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/excel') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.excel') }}">
              <x-tabler-table class="w-6 h-6" />
              <span class="mx-4">Import & Export</span>
            </a>
          </div>
        @endcan

        @can('admin')
          <div class="mx-4 my-3 border-b border-gray-700"></div>
          <div class="flex-growmd:block md:overflow-y-auto">
            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/users') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.users') }}">
              <x-tabler-users class="w-6 h-6" />
              <span class="mx-4">Users</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/roles') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.roles') }}">
              <x-tabler-lock-access class="w-6 h-6" />
              <span class="mx-4">Roles</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/permissions') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.permissions') }}">
              <x-tabler-lock class="w-6 h-6" />
              <span class="mx-4">Permissions</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/logs') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.logs') }}">
              <x-tabler-bug class="w-6 h-6" />
              <span class="mx-4">Logs</span>
            </a>
          </div>
        @endcan

      </nav>

    </div>
    <div>
      <a title="Выйти" href="{{ route('logout') }}" class="block px-4 py-3 group"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <path class="text-gray-600 stroke-current group-hover:text-orange-500 focus:text-orange-500"
            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M8.9 7.56c.31-3.6 2.16-5.07 6.21-5.07h.13c4.47 0 6.26 1.79 6.26 6.26v6.52c0 4.47-1.79 6.26-6.26 6.26h-.13c-4.02 0-5.87-1.45-6.2-4.99M15 12H3.62m2.23-3.35L2.5 12l3.35 3.35" />
        </svg>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        {{ csrf_field() }}
      </form>
    </div>
  </div>
</div>
