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
            <x-tabler-dashboard class="w-5 h-5" />
            <span class="mx-4">Панель</span>
          </a>

          <div>
            <div x-on:click="productMenu = ! productMenu"
              class="flex items-center justify-between px-4 py-3 bg-transparent cursor-pointer hover:bg-gray-800 hover:text-gray-400 focus:bg-gray-800 focus:outline-none focus:ring">
              <div class="flex items-center space-x-4">
                <x-tabler-package class="w-5 h-5" />
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
              <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                viewBox="0 0 24 24">
                <path
                  d="M14,18a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,14,18Zm-4,0a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,10,18ZM19,6H17.62L15.89,2.55a1,1,0,1,0-1.78.9L15.38,6H8.62L9.89,3.45a1,1,0,0,0-1.78-.9L6.38,6H5a3,3,0,0,0-.92,5.84l.74,7.46a3,3,0,0,0,3,2.7h8.38a3,3,0,0,0,3-2.7l.74-7.46A3,3,0,0,0,19,6ZM17.19,19.1a1,1,0,0,1-1,.9H7.81a1,1,0,0,1-1-.9L6.1,12H17.9ZM19,10H5A1,1,0,0,1,5,8H19a1,1,0,0,1,0,2Z" />
              </svg>
              <span class="mx-4">Заказы</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/reviews*') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.reviews') }}">
              <x-tabler-message class="w-5 h-5 stroke-current" />
              <span class="mx-4">Отзывы</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/page*') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.pages') }}">
              <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                viewBox="0 0 24 24">
                <path
                  d="M9,10h1a1,1,0,0,0,0-2H9a1,1,0,0,0,0,2Zm0,2a1,1,0,0,0,0,2h6a1,1,0,0,0,0-2ZM20,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.32.32,0,0,0-.09,0A.88.88,0,0,0,13.05,2H7A3,3,0,0,0,4,5V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V9S20,9,20,8.94ZM14,5.41,16.59,8H15a1,1,0,0,1-1-1ZM18,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V5A1,1,0,0,1,7,4h5V7a3,3,0,0,0,3,3h3Zm-3-3H9a1,1,0,0,0,0,2h6a1,1,0,0,0,0-2Z" />
              </svg>
              <span class="mx-4">Страницы</span>

            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/excel') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.excel') }}">
              <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M21,2H3A1,1,0,0,0,2,3V21a1,1,0,0,0,1,1H21a1,1,0,0,0,1-1V3A1,1,0,0,0,21,2ZM8,20H4V16H8Zm0-6H4V10H8ZM8,8H4V4H8Zm6,12H10V16h4Zm0-6H10V10h4Zm0-6H10V4h4Zm6,12H16V16h4Zm0-6H16V10h4Zm0-6H16V4h4Z" />
              </svg>
              <span class="mx-4">Import & Export</span>
            </a>
          </div>
        @endcan

        @can('admin')
          <div class="mx-4 my-3 border-b border-gray-700"></div>
          <div class="flex-growmd:block md:overflow-y-auto">
            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/users') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.users') }}">
              <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z" />
              </svg>
              <span class="mx-4">Users</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/roles') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.roles') }}">
              <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M14.81,12.28a3.73,3.73,0,0,0,1-2.5,3.78,3.78,0,0,0-7.56,0,3.73,3.73,0,0,0,1,2.5A5.94,5.94,0,0,0,6,16.89a1,1,0,0,0,2,.22,4,4,0,0,1,7.94,0A1,1,0,0,0,17,18h.11a1,1,0,0,0,.88-1.1A5.94,5.94,0,0,0,14.81,12.28ZM12,11.56a1.78,1.78,0,1,1,1.78-1.78A1.78,1.78,0,0,1,12,11.56ZM19,2H5A3,3,0,0,0,2,5V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V5A3,3,0,0,0,19,2Zm1,17a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V5A1,1,0,0,1,5,4H19a1,1,0,0,1,1,1Z" />
              </svg>
              <span class="mx-4">Roles</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/permissions') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.permissions') }}">
              <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M12,13a1.49,1.49,0,0,0-1,2.61V17a1,1,0,0,0,2,0V15.61A1.49,1.49,0,0,0,12,13Zm5-4V7A5,5,0,0,0,7,7V9a3,3,0,0,0-3,3v7a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V12A3,3,0,0,0,17,9ZM9,7a3,3,0,0,1,6,0V9H9Zm9,12a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V12a1,1,0,0,1,1-1H17a1,1,0,0,1,1,1Z" />
              </svg>
              <span class="mx-4">Permissions</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/settings') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.settings') }}">
              <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                viewBox="0 0 24 24">
                <path
                  d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z" />
              </svg>
              <span class="mx-4">Settings</span>
            </a>

            <a class="flex items-center px-4 py-3 hover:bg-gray-800 focus:bg-gray-800 hover:text-gray-400 focus:outline-none focus:ring {{ request()->is('dashboard/logs') ? 'bg-gray-800' : '' }}"
              href="{{ route('dashboard.logs') }}">
              <x-tabler-tornado class="w-5 h-5 fill-current" />
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
