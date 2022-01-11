@extends('layouts.auth')
@section('title', 'Сброс пароля')
@section('content')
  <div class="">
    <div class="flex flex-wrap justify-center">
      <div class="w-full max-w-sm">
        <div class="flex flex-col break-words">

          <div class="px-6 py-3 mb-0 font-semibold text-gray-700 ">
            Сброс пароля
          </div>

          <form class="w-full p-6" method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="flex flex-wrap mb-6">
              <label for="email" class="block mb-2 text-sm font-bold text-gray-700">
                Адрес эл. почты
              </label>

              <input id="email" type="email" class="field w-full @error('email') border-red-500 @enderror" name="email"
                value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

              @error('email')
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap mb-6">
              <label for="password" class="block mb-2 text-sm font-bold text-gray-700">
                Новый пароль
              </label>

              <input id="password" type="password" class="field w-full @error('password') border-red-500 @enderror"
                name="password" required autocomplete="new-password">

              @error('password')
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap mb-6">
              <label for="password" class="block mb-2 text-sm font-bold text-gray-700">
                Повторите новый пароль
              </label>

              <input id="password_confirmation" type="password"
                class="field w-full @error('password_confirmation') border-red-500 @enderror" name="password_confirmation"
                required autocomplete="new-password">

              @error('password_confirmation')
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap">
              <button type="submit"
                class="w-full px-4 py-3 font-bold text-gray-100 bg-blue-500 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring">
                Установить новый пароль
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
