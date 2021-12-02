@extends ('layouts.app')
@section('title', 'Профайл')

@section('content')
  <div class="space-y-2">
    <div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0">
      <div class="flex items-center justify-between">
        <a class="py-1 pr-1 hover:underline" href="{{ route('account.account') }}">
          Акаунт
        </a>
        <x-tabler-chevron-right class="w-5 h-5" />
      </div>
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-between">
          <a class="p-1 hover:underline" href="{{ route('account.profile') }}">
            <span>Профайл</span>
          </a>
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
        <div class="md:w-8/12 md:mx-auto">
          <form method="POST" action="{{ route('account.user.update', $user->id) }}">
            @method ('PATCH')
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 sm:grid-cols-2">
              <label class="block">
                <span class="block pb-1 font-bold text-gray-700">Имя</span>
                <input class="block w-full mt-1 field" name="name" value="{{ old('name', $user->name) }}">
                @error('name')
                  <span class="text-xs text-red-600">
                    {{ $message }}
                  </span>
                @enderror
              </label>

              <label class="block">
                <span class="block pb-1 font-bold text-gray-700">Email</span>
                <input class="block w-full mt-1 field" name="email" value="{{ old('email', $user->email) }}">
                @error('email')
                  <span class="text-xs text-red-600">
                    {{ $message }}
                  </span>
                @enderror
              </label>

              <label class="block">
                <span class="block pb-1 font-bold text-gray-700">Телефон</span>
                <input class="block w-full mt-1 field" name="phone" value="{{ old('phone', $user->phone) }}"
                  data-format="(***) ***-****" data-mask="(___) ___-____">
                @error('phone')
                  <span class="text-xs text-red-600">
                    {{ $message }}
                  </span>
                @enderror
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

                <input class="block w-full mt-1 field " name="password">
                @error('password')
                  <span class="text-xs text-red-600">
                    {{ $message }}
                  </span>
                @enderror
              </label>

            </div>

            @if ($user->discount)
              <div class="pt-4">
                У вас есть дисконтная карта <b>{{ $user->discount }}%</b>
              </div>
            @endif


            <div class="flex justify-end pt-6">
              <button type="submit" class="text-white bg-blue-500 btn hover:bg-blue-600">
                Сохранить
              </button>
            </div>


          </form>

        </div>
      </div>
    </div>
  @endsection
