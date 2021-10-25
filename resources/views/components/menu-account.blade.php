<div class="flex items-center justify-between space-x-4">
  @guest

    <livewire:auth.in />

  @else
    <div x-data="{ open: false }" @click.outside="open = false" class="relative">

      <button @mouseenter="open = true"
        class="flex items-center w-auto p-1 text-xs rounded-lg hover:text-orange-500 focus:text-orange-500 focus:outline-none">
        <div>

          <svg class="fill-current stroke-2 w-7 h-7" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round"
            xmlns="http://www.w3.org/2000/svg" aria-label="profile" viewBox="0 0 32 32" title="profile">
            <g>
              <path
                d="M25.698,22.196c0.248,-1.511 0.302,-3.475 0.302,-6.196c0,-2.721 -0.053,-4.685 -0.302,-6.196c-0.235,-1.45 -0.6,-2.127 -0.987,-2.515c-0.388,-0.387 -1.065,-0.752 -2.515,-0.987c-1.511,-0.249 -3.475,-0.302 -6.196,-0.302c-2.721,0 -4.685,0.053 -6.196,0.302c-1.45,0.235 -2.127,0.6 -2.515,0.987c-0.387,0.388 -0.752,1.065 -0.987,2.515c-0.249,1.511 -0.302,3.475 -0.302,6.196c0,2.721 0.053,4.685 0.302,6.196c0.235,1.45 0.6,2.127 0.987,2.515c0.388,0.387 1.065,0.752 2.515,0.987c1.511,0.249 3.475,0.302 6.196,0.302c2.721,0 4.685,-0.053 6.196,-0.302c1.45,-0.235 2.127,-0.6 2.515,-0.987c0.387,-0.388 0.752,-1.065 0.987,-2.515Zm-9.698,5.804c11,0 12,-1 12,-12c0,-11 -1,-12 -12,-12c-11,0 -12,1 -12,12c0,11 1,12 12,12Z">
              </path>
              <path
                d="M19,14c0,1.683 -0.271,2.241 -0.469,2.456c-0.163,0.176 -0.68,0.544 -2.531,0.544c-1.85,0 -2.367,-0.368 -2.53,-0.544c-0.198,-0.215 -0.47,-0.773 -0.47,-2.456c0,-1.657 1.343,-3 3,-3c1.657,0 3,1.343 3,3Zm0.835,3.977c0.879,-0.804 1.165,-2.104 1.165,-3.977c0,-2.761 -2.238,-5 -5,-5c-2.761,0 -5,2.239 -5,5c0,1.873 0.287,3.173 1.166,3.977c-1.665,0.911 -2.97,2.396 -3.649,4.189c-0.124,0.328 -0.154,0.708 0.051,0.993c0.569,0.789 1.674,-0.111 2.13,-0.97c1.008,-1.897 3.004,-3.189 5.302,-3.189c2.298,0 4.295,1.292 5.303,3.189c0.456,0.859 1.561,1.759 2.129,0.97c0.205,-0.285 0.176,-0.665 0.052,-0.993c-0.68,-1.793 -1.985,-3.278 -3.649,-4.189Z">
              </path>
            </g>
          </svg>

        </div>

        @if (Agent::isDesktop())
          <div>
            <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
              class="w-4 h-4 align-middle transition-transform duration-200 transform md:-mt-1">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
            </svg>
          </div>
        @endif
      </button>

      <div x-cloak x-show="open" x-transition class="absolute right-0 z-30 w-40 shadow-lg top-10 rounded-2xl">
        <div class="flex flex-col items-start justify-between text-gray-700 bg-white shadow-lg rounded-xl">

          <a href="{{ route('account.profile') }}"
            class="flex items-center justify-start w-full px-3 py-1 space-x-2 text-sm rounded-t-xl hover:bg-gray-50 focus:bg-gray-50 focus:outline-none focus:ring group">
            <svg class="w-8 h-8 text-gray-600 fill-current group-hover:text-orange-500" fill-rule="evenodd"
              clip-rule="evenodd" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-label="person"
              viewBox="0 0 32 32" fill="currentColor" title="person">
              <path
                d="M19,13c0,1.683 -0.271,2.241 -0.47,2.456c-0.162,0.176 -0.679,0.544 -2.53,0.544c-1.851,0 -2.368,-0.368 -2.53,-0.544c-0.199,-0.215 -0.47,-0.773 -0.47,-2.456c0,-1.657 1.343,-3 3,-3c1.657,0 3,1.343 3,3Zm0.835,3.977c0.879,-0.804 1.165,-2.104 1.165,-3.977c0,-2.761 -2.239,-5 -5,-5c-2.761,0 -5,2.239 -5,5c0,1.873 0.286,3.173 1.165,3.977c-1.664,0.911 -2.969,2.396 -3.649,4.189c-0.124,0.328 -0.153,0.708 0.052,0.993c0.568,0.789 1.674,-0.111 2.13,-0.97c1.007,-1.897 3.004,-3.189 5.302,-3.189c2.298,0 4.295,1.292 5.302,3.189c0.456,0.859 1.562,1.759 2.13,0.97c0.205,-0.285 0.176,-0.665 0.052,-0.993c-0.68,-1.793 -1.985,-3.278 -3.649,-4.189Z">
              </path>
            </svg>
            <span>Профайл</span>
          </a>

          <a href="{{ route('account.orders') }}"
            class="flex items-center justify-start w-full px-4 py-2 space-x-3 text-sm hover:bg-gray-50 focus:bg-gray-50 focus:outline-none focus:ring group">
            <svg class="w-6 h-6 text-gray-600 fill-current group-hover:text-orange-500" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24">
              <path
                d="M20.49,7.52a.19.19,0,0,1,0-.08.17.17,0,0,1,0-.07l0-.09-.06-.15,0,0h0l0,0,0,0a.48.48,0,0,0-.09-.11l-.09-.08h0l-.05,0,0,0L16.26,4.45h0l-3.72-2.3A.85.85,0,0,0,12.25,2h-.08a.82.82,0,0,0-.27,0h-.1a1.13,1.13,0,0,0-.33.13L4,6.78l-.09.07-.09.08L3.72,7l-.05.06,0,0-.06.15,0,.09v.06a.69.69,0,0,0,0,.2v8.73a1,1,0,0,0,.47.85l7.5,4.64h0l0,0,.15.06.08,0a.86.86,0,0,0,.52,0l.08,0,.15-.06,0,0h0L20,17.21a1,1,0,0,0,.47-.85V7.63S20.49,7.56,20.49,7.52ZM12,4.17l1.78,1.1L8.19,8.73,6.4,7.63Zm-1,15L5.5,15.81V9.42l5.5,3.4Zm1-8.11L10.09,9.91l5.59-3.47L17.6,7.63Zm6.5,4.72L13,19.2V12.82l5.5-3.4Z" />
            </svg>
            <span>Заказы</span>
          </a>

          @can('dashboard')
            <a href="{{ route('dashboard.dashboard') }}"
              class="flex items-center justify-start w-full px-4 py-2 space-x-3 text-sm hover:bg-gray-50 focus:bg-gray-50 focus:outline-none focus:ring group">
              <x-tabler-rocket class="w-6 h-6 text-gray-600 stroke-current group-hover:text-orange-500" />
              <span>Панель</span>
            </a>
          @endcan

          <span class="w-full border-t border-gray-200"></span>
          <a title="выйти" href="{{ route('logout') }}"
            class="flex items-center justify-start w-full px-4 py-2 space-x-3 text-sm rounded-b-xl hover:bg-gray-50 focus:bg-gray-50 focus:outline-none focus:ring group"
            onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
            <svg class="text-gray-600 w-7 h-7 group-hover:text-orange-500" fill-rule="evenodd" clip-rule="evenodd"
              stroke-linejoin="round" stroke-miterlimit="1.414" xmlns="http://www.w3.org/2000/svg" aria-label="door-leave"
              viewBox="0 0 32 32" fill="currentColor" title="выйти">
              <g>
                <path
                  d="M27.708,15.293c0.39,0.39 0.39,1.024 0,1.414l-4,4c-0.391,0.391 -1.024,0.391 -1.415,0c-0.39,-0.39 -0.39,-1.024 0,-1.414l2.293,-2.293l-11.586,0c-0.552,0 -1,-0.448 -1,-1c0,-0.552 0.448,-1 1,-1l11.586,0l-2.293,-2.293c-0.39,-0.39 -0.39,-1.024 0,-1.414c0.391,-0.391 1.024,-0.391 1.415,0l4,4Z">
                </path>
                <path
                  d="M11.999,8c0.001,0 0.001,0 0.002,0c1.699,-0.001 2.859,0.045 3.77,0.25c0.005,0.001 0.01,0.002 0.015,0.003c0.789,0.173 1.103,0.409 1.291,0.638c0,0 0,0.001 0,0.001c0.231,0.282 0.498,0.834 0.679,2.043c0,0.001 0,0.002 0.001,0.003c0.007,0.048 0.014,0.097 0.021,0.147c0.072,0.516 0.501,0.915 1.022,0.915c0.584,0 1.049,-0.501 0.973,-1.08c-0.566,-4.332 -2.405,-4.92 -7.773,-4.92c-7,0 -8,1 -8,10c0,9 1,10 8,10c5.368,0 7.207,-0.588 7.773,-4.92c0.076,-0.579 -0.389,-1.08 -0.973,-1.08c-0.521,0 -0.95,0.399 -1.022,0.915c-0.007,0.05 -0.014,0.099 -0.021,0.147c-0.001,0.001 -0.001,0.002 -0.001,0.003c-0.181,1.209 -0.448,1.762 -0.679,2.044l0,0c-0.188,0.229 -0.502,0.465 -1.291,0.638c-0.005,0.001 -0.01,0.002 -0.015,0.003c-0.911,0.204 -2.071,0.25 -3.77,0.25c-0.001,0 -0.001,0 -0.002,0c-1.699,0 -2.859,-0.046 -3.77,-0.25c-0.005,-0.001 -0.01,-0.002 -0.015,-0.003c-0.789,-0.173 -1.103,-0.409 -1.291,-0.638l0,0c-0.231,-0.282 -0.498,-0.835 -0.679,-2.043c0,-0.001 0,-0.003 -0.001,-0.005c-0.189,-1.247 -0.243,-2.848 -0.243,-5.061c0,0 0,0 0,0c0,-2.213 0.054,-3.814 0.243,-5.061c0.001,-0.002 0.001,-0.004 0.001,-0.005c0.181,-1.208 0.448,-1.76 0.679,-2.042c0,0 0,-0.001 0,-0.001c0.188,-0.229 0.502,-0.465 1.291,-0.638c0.005,-0.001 0.01,-0.002 0.015,-0.003c0.911,-0.205 2.071,-0.251 3.77,-0.25Z">
                </path>
              </g>
            </svg>
            <span>Выйти</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            {{ csrf_field() }}
          </form>

        </div>
      </div>
    </div>
  @endguest
</div>
