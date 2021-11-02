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

                @can('admin')

                  <a class="flex items-center px-4 py-2 pl-6  hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/settings/main') ? 'bg-gray-800' : '' }}"
                    href="{{ route('dashboard.settings.main') }}">Основные</a>

                  <a class="flex items-center px-4 py-2 pl-6 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/settings/backup') ? 'bg-gray-800' : '' }}"
                    href="{{ route('dashboard.settings.backup') }}">BackUp DB</a>
                @endcan

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
              <x-tabler-tornado class="w-6 h-6" />
              <span class="mx-4">Logs</span>
            </a>
          </div>
        @endcan

      </nav>

    </div>
    <div>
      <a title="Выйти" href="{{ route('logout') }}" class="block px-4 py-3" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
        <svg class="text-gray-400 fill-current w-7 h-7" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round"
          xmlns="http://www.w3.org/2000/svg" aria-label="door-leave" viewBox="0 0 32 32" title="door-leave">
          <g>
            <path
              d="M27.708,15.293c0.39,0.39 0.39,1.024 0,1.414l-4,4c-0.391,0.391 -1.024,0.391 -1.415,0c-0.39,-0.39 -0.39,-1.024 0,-1.414l2.293,-2.293l-11.586,0c-0.552,0 -1,-0.448 -1,-1c0,-0.552 0.448,-1 1,-1l11.586,0l-2.293,-2.293c-0.39,-0.39 -0.39,-1.024 0,-1.414c0.391,-0.391 1.024,-0.391 1.415,0l4,4Z">
            </path>
            <path
              d="M11.999,8c0.001,0 0.001,0 0.002,0c1.699,-0.001 2.859,0.045 3.77,0.25c0.005,0.001 0.01,0.002 0.015,0.003c0.789,0.173 1.103,0.409 1.291,0.638c0,0 0,0.001 0,0.001c0.231,0.282 0.498,0.834 0.679,2.043c0,0.001 0,0.002 0.001,0.003c0.007,0.048 0.014,0.097 0.021,0.147c0.072,0.516 0.501,0.915 1.022,0.915c0.584,0 1.049,-0.501 0.973,-1.08c-0.566,-4.332 -2.405,-4.92 -7.773,-4.92c-7,0 -8,1 -8,10c0,9 1,10 8,10c5.368,0 7.207,-0.588 7.773,-4.92c0.076,-0.579 -0.389,-1.08 -0.973,-1.08c-0.521,0 -0.95,0.399 -1.022,0.915c-0.007,0.05 -0.014,0.099 -0.021,0.147c-0.001,0.001 -0.001,0.002 -0.001,0.003c-0.181,1.209 -0.448,1.762 -0.679,2.044l0,0c-0.188,0.229 -0.502,0.465 -1.291,0.638c-0.005,0.001 -0.01,0.002 -0.015,0.003c-0.911,0.204 -2.071,0.25 -3.77,0.25c-0.001,0 -0.001,0 -0.002,0c-1.699,0 -2.859,-0.046 -3.77,-0.25c-0.005,-0.001 -0.01,-0.002 -0.015,-0.003c-0.789,-0.173 -1.103,-0.409 -1.291,-0.638l0,0c-0.231,-0.282 -0.498,-0.835 -0.679,-2.043c0,-0.001 0,-0.003 -0.001,-0.005c-0.189,-1.247 -0.243,-2.848 -0.243,-5.061c0,0 0,0 0,0c0,-2.213 0.054,-3.814 0.243,-5.061c0.001,-0.002 0.001,-0.004 0.001,-0.005c0.181,-1.208 0.448,-1.76 0.679,-2.042c0,0 0,-0.001 0,-0.001c0.188,-0.229 0.502,-0.465 1.291,-0.638c0.005,-0.001 0.01,-0.002 0.015,-0.003c0.911,-0.205 2.071,-0.251 3.77,-0.25Z">
            </path>
          </g>
        </svg>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        {{ csrf_field() }}
      </form>
    </div>
  </div>
</div>
