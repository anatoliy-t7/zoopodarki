<div class="space-y-5 text-gray-500">
  <div class="pb-4 pl-10 font-bold text-gray-800">Профайл</div>
  <div>
    <a class="block p-2 w-full hover:text-gray-800 {{ request()->is('account/profile') ? 'border-r-4 text-gray-800 border-green-500' : '' }}"
      href="{{ route('account.profile') }}">Личные данные</a>
  </div>
  <div>
    <a class="block p-2 w-full hover:text-gray-800 {{ request()->is('account/favorites') ? 'border-r-4 text-gray-800 border-green-500' : '' }}"
      href="{{ route('account.favorites') }}">Избранные товары</a>
  </div>
  <div>
    <a class="block p-2 w-full hover:text-gray-800 {{ request()->is('account/orders*') ? 'border-r-4 text-gray-800 border-green-500' : '' }}"
      href="{{ route('account.orders') }}">Заказы</a>
  </div>
</div>
