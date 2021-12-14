<div class="space-y-2">

  <div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0">
    <div class="flex items-center justify-between">
      <a class="py-1 pr-1 hover:underline" href="{{ route('account.account') }}">
        Акаунт
      </a>
      <x-tabler-chevron-right class="w-5 h-5" />
    </div>
    <div class="flex items-center justify-between">
      <div class="p-1">
        Профайл
      </div>
    </div>
  </div>

  <div class="p-8 space-y-8 bg-white rounded-2xl">

    <div>
      <h1 class="text-2xl font-bold">
        Профайл
      </h1>
    </div>


    <div class="flex items-center">
      <div class="md:w-6/12 md:mx-auto">

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 sm:grid-cols-2">
          <label class="block">
            <span class="block pb-1 font-bold text-gray-700">Имя</span>
            <input wire:model="user.name" class="block w-full mt-1 field" name="name">
            @error('user.name')
              <span class="text-xs text-red-600">
                {{ $message }}
              </span>
            @enderror
          </label>

          <label class="block">
            <span class="block pb-1 font-bold text-gray-700">Email</span>
            <input wire:model="user.email" class="block w-full mt-1 field" name="email">
            @error('user.email')
              <span class="text-xs text-red-600">
                {{ $message }}
              </span>
            @enderror
          </label>

          <label class="block space-y-1">

            <x-tooltip :width="'200px'">
              <x-slot name="title">
                <span class="block mb-1 font-bold text-gray-700">Телефон</span>
                <svg class="w-6 h-4 pl-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M12,14a1,1,0,1,0,1,1A1,1,0,0,0,12,14ZM12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20ZM12,8a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0V9A1,1,0,0,0,12,8Z" />
                </svg>
              </x-slot>
              Для изменения телефона вам необходимо написать письмо с email этого акаунта.
            </x-tooltip>

            <div class="relative">

              <div class="absolute top-0 left-0 z-20 pt-4 pl-3 cursor-default">
                <div class="w-6 h-6 mx-auto text-gray-400 fill-current">
                  +7
                </div>
              </div>
              <input value="{{ $user['phone'] }}" disabled
                class="w-full px-4 py-3 pl-10 font-semibold text-gray-400 border border-gray-200 cursor-not-allowed bg-gray-50 rounded-2xl focus:outline-none focus:ring focus:bg-white"
                name="phone">

            </div>
          </label>

          <label class="block">

            <x-tooltip :width="'170px'">
              <x-slot name="title">
                <span class="block mb-1 font-bold text-gray-700">Новый пароль</span>
                <svg class="w-6 h-4 pl-1 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path
                    d="M12,14a1,1,0,1,0,1,1A1,1,0,0,0,12,14ZM12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20ZM12,8a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0V9A1,1,0,0,0,12,8Z" />
                </svg>
              </x-slot>
              Не заполняйте если хотите оставить старый.
            </x-tooltip>

            <input wire:model="user.password" class="block w-full mt-1 field " name="password">
            @error('user.password')
              <span class="text-xs text-red-600">
                {{ $message }}
              </span>
            @enderror
          </label>
        </div>

        @if ($user['discount'])
          <div class="pt-4">
            У вас есть дисконтная карта <b>{{ $user['discount'] }}%</b>
          </div>
        @endif

        <div class="flex justify-end pt-8">
          <button wire:click="save" class="text-white bg-blue-400 btn hover:shadow-blue-400/50 hover:shadow-lg ">
            Сохранить
          </button>
        </div>


      </div>
    </div>


  </div>


</div>
