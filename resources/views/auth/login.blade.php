@extends('layouts.auth')
@section('title', 'Войти в личный кабинет')
@section('content')
<div class="">
  <div class="flex flex-wrap justify-center">
    <div class="w-full max-w-sm">
      <div class="flex flex-col break-words">

        <div class="px-6 py-3 mb-0 font-semibold text-gray-700">
          Войти в личный кабинет
        </div>

        <form class="w-full p-6" method="POST" action="{{ route('login') }}">
          @csrf

          <div class="flex flex-wrap mb-6">
            <label for="email" class="block mb-2 text-sm font-bold text-gray-700">
              Адрес эл. почты
            </label>

            <input id="email" type="email" class="field w-full @error('email') border-red-500 @enderror" name="email"
              value="{{ old('email') }}" required autocomplete="email" autofocus>

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
              name="password" required placeholder="не менее 8 символов">

            @error('password')
            <p class="mt-4 text-xs italic text-red-500">
              {{ $message }}
            </p>
            @enderror
          </div>


          <div class="flex flex-wrap items-center">
            <button id="sign-in-button" type="submit"
              class="px-4 py-2 font-bold text-gray-100 bg-blue-500 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring">
              Войти
            </button>

            @if (Route::has('password.request'))
            <a class="ml-auto text-sm text-blue-500 no-underline whitespace-nowrap hover:text-blue-700"
              href="{{ route('password.request') }}">
              Забыли пароль?
            </a>
            @endif

            @if (Route::has('register'))
            <p class="w-full mt-8 -mb-4 text-xs text-center text-gray-700">
              У вас нет аккаунта?
              <a class="text-blue-500 no-underline hover:text-blue-700" href="{{ route('register') }}">
                Регистрация
              </a>
            </p>
            @endif
          </div>
        </form>

      </div>
    </div>
  </div>

</div>
@endsection
