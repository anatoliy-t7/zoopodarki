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

      <div class="flex items-center justify-between w-full gap-6 ">

        <div class="flex w-4/12 h-40">
          <a href="{{ route('account.profile') }}" class="block w-full p-6 border rounded-lg hover:bg-gray-50">
            <h3 class="mb-2 text-lg font-semibold md:text-xl">Профайл</h3>
            <p class="mb-4 text-gray-500">Ваши личные данные</p>
          </a>
        </div>
        <div class="flex w-4/12 h-40">
          <a href="{{ route('account.favorites') }}" class="block w-full p-6 border rounded-lg hover:bg-gray-50">
            <h3 class="mb-2 text-lg font-semibold md:text-xl">Избранные товары</h3>
            <p class="mb-4 text-gray-500">Ваши любимые товары</p>
          </a>
        </div>
        <div class="flex w-4/12 h-40">
          <a href="{{ route('account.orders') }}" class="block w-full p-6 border rounded-lg hover:bg-gray-50">
            <h3 class="mb-2 text-lg font-semibold md:text-xl">Заказы</h3>
            <p class="mb-4 text-gray-500">Ваша история заказов</p>
          </a>
        </div>
      </div>

    </div>
  </div>
@endsection
