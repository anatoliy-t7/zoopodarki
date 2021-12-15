@extends ('layouts.app')
@section('title', 'Мой аккаунт')

@section('content')
  <div class="space-y-2">
    <div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0">
      <div class="flex items-center justify-between">
        <a class="py-1 pr-1 hover:underline" href="{{ route('account.account') }}">
          Акаунт
        </a>
      </div>
    </div>

    <div class="p-8 space-y-8 bg-white rounded-2xl">

      <div>
        <h1 class="text-2xl font-bold">
          Аккаунт
        </h1>
      </div>

      <div class="grid w-full grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 ">

        <div class="flex h-full">
          <a href="{{ route('account.profile') }}"
            class="flex items-center w-full gap-6 px-6 py-8 border rounded-lg hover:bg-gray-50 group">
            <svg class="text-gray-600 stroke-2 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path class="stroke-current group-hover:text-orange-500" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="1.5"
                d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm8.59 10c0-3.87-3.85-7-8.59-7s-8.59 3.13-8.59 7" />
            </svg>
            <div>
              <h3 class="mb-1 text-lg font-semibold md:text-xl">Профайл</h3>
              <p class="text-sm leading-tight text-gray-500 ">Ваши личные данные</p>
            </div>
          </a>
        </div>

        <div class="flex h-full">
          <a href="{{ route('account.favorites') }}"
            class="flex items-center w-full gap-6 px-6 py-8 border rounded-lg hover:bg-gray-50 group">
            <svg class="text-gray-600 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path class="stroke-current group-hover:text-orange-500" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="1.5"
                d="M12.62 20.81c-.34.12-.9.12-1.24 0C8.48 19.82 2 15.69 2 8.69 2 5.6 4.49 3.1 7.56 3.1c1.82 0 3.43.88 4.44 2.24a5.53 5.53 0 0 1 4.44-2.24C19.51 3.1 22 5.6 22 8.69c0 7-6.48 11.13-9.38 12.12Z" />
            </svg>
            <div>
              <h3 class="mb-1 text-lg font-semibold ">Избранные товары</h3>
              <p class="text-sm leading-tight text-gray-500">Ваши любимые товары</p>
            </div>
          </a>
        </div>

        <div class="flex h-full">
          <a href="{{ route('account.orders') }}"
            class="flex items-center w-full gap-6 px-6 py-8 border rounded-lg hover:bg-gray-50 group">

            <svg class="text-gray-600 w-7 h-7 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path class="stroke-current group-hover:text-orange-500" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="1.5" d="M3.17 7.44 12 12.55l8.77-5.08M12 21.61v-9.07" />
              <path class="stroke-current group-hover:text-orange-500" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="1.5"
                d="M9.93 2.48 4.59 5.45c-1.21.67-2.2 2.35-2.2 3.73v5.65c0 1.38.99 3.06 2.2 3.73l5.34 2.97c1.14.63 3.01.63 4.15 0l5.34-2.97c1.21-.67 2.2-2.35 2.2-3.73V9.18c0-1.38-.99-3.06-2.2-3.73l-5.34-2.97c-1.15-.64-3.01-.64-4.15 0Z" />
              <path class="stroke-current group-hover:text-orange-500" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="1.5" d="M17 13.24V9.58L7.51 4.1" />
            </svg>
            <div>
              <h3 class="mb-1 text-lg font-semibold md:text-xl">Заказы</h3>
              <p class="text-sm leading-tight text-gray-500 ">Ваша история заказов</p>
            </div>
          </a>
        </div>

        @can('dashboard')
          <div class="flex h-full">
            <a href="{{ route('dashboard.dashboard') }}"
              class="flex items-center w-full gap-6 px-6 py-8 border rounded-lg hover:bg-gray-50 group">
              <svg class="text-gray-600 w-7 h-7 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path class="stroke-current group-hover:text-orange-500" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="1.5"
                  d="M6.44 2h11.11C21.11 2 22 2.89 22 6.44v6.33c0 3.56-.89 4.44-4.44 4.44H6.44C2.89 17.22 2 16.33 2 12.78V6.44C2 2.89 2.89 2 6.44 2zM12 17.22V22M2 13h20M7.5 22h9" />
              </svg>

              <div>
                <h3 class="mb-1 text-lg font-semibold md:text-xl">Панель</h3>
                <p class="text-sm leading-tight text-gray-500 ">Панель управления</p>
              </div>
            </a>
          </div>
        @endcan

      </div>

    </div>
  </div>
@endsection
