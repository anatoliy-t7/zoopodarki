@extends('layouts.auth')
@section('title', 'Регистрация')
@section('content')
  <div class="">
    <div class="flex flex-wrap justify-center">
      <div class="w-full max-w-sm">
        <div class="flex flex-col break-words ">

          <div class="px-6 py-3 mb-0 font-semibold text-gray-700 ">
            Регистрация
          </div>

          <form class="w-full p-6" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="flex flex-wrap mb-6">
              <label for="email" class="block mb-2 text-sm font-bold text-gray-700">
                Электронная почта
              </label>

              <input id="email" type="email" class="field w-full @error('email') border-red-500 @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email">

              @error('email')
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap mb-6">
              <label for="password" class="block mb-2 text-sm font-bold text-gray-700">
                Пароль
              </label>

              <input id="password" type="password" class="field w-full @error('password') border-red-500 @enderror"
                name="password" required autocomplete="new" placeholder="не менее 8 символов">

              @error('password')
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap">
              <button type="submit"
                class="inline-block px-4 py-2 text-base font-bold leading-normal text-center text-gray-100 no-underline align-middle bg-blue-500 border rounded-lg select-none whitespace-nowrap hover:bg-blue-700">
                Зарегистрироваться
              </button>

              <p class="w-full mt-8 -mb-4 text-xs text-center text-gray-700">
                Уже регестрировались?
                <a class="text-blue-500 no-underline hover:text-blue-700" href="{{ route('login') }}">
                  Войти
                </a>
              </p>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
