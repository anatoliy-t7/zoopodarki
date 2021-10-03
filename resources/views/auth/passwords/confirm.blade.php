@extends('layouts.auth')
@section('title', 'Подтвердите пароль')
@section('content')
<div class="">
  <div class="flex flex-wrap justify-center">
    <div class="w-full max-w-sm">
      <div class="flex flex-col break-words">

        <div class="px-6 py-3 mb-0 font-semibold text-gray-700 ">
          Подтвердите пароль
        </div>

        <form class="w-full p-6" method="POST" action="{{ route('password.confirm') }}">
          @csrf

          <p class="leading-normal">
            Пожалуйста, подтвердите ваш пароль, прежде чем продолжить.
          </p>

          <div class="flex flex-wrap my-6">
            <label for="password" class="block mb-2 text-sm font-bold text-gray-700">
              Пароль
            </label>

            <input id="password" type="password" class="field w-full @error('password') border-red-500 @enderror"
              name="password" required autocomplete="new-password">

            @error('password')
            <p class="mt-4 text-xs italic text-red-500">
              {{ $message }}
            </p>
            @enderror
          </div>

          <div class="flex flex-wrap items-center">
            <button type="submit"
              class="px-4 py-2 font-bold text-gray-100 bg-blue-500 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring">
              Повторите пароль
            </button>

            @if (Route::has('password.request'))
            <a class="ml-auto text-sm text-blue-500 no-underline whitespace-nowrap hover:text-blue-700"
              href="{{ route('password.request') }}">
              Забыли Ваш пароль?
            </a>
            @endif
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection