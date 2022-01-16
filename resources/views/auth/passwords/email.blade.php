@extends('layouts.auth')
@section('title', 'Сброс пароля')
@section('content')
  <div class="">
    <div class="flex flex-wrap justify-center">
      <div class="w-full max-w-sm">

        @if (session('status'))
          <div class="px-3 py-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('status') }}
          </div>
        @endif

        <div class="flex flex-col break-words">

          <div class="px-6 py-3 mb-0 font-semibold text-gray-700 ">
            Сброс пароля
          </div>

          <form class="w-full p-6" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="flex flex-wrap mb-6">
              <label for="email" class="block mb-2 text-sm font-bold text-gray-700">
                Электронная почта
              </label>

              <input id="email" type="email" class="field w-full @error('email') border-red-500 @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>

              @error('email')
                <p class="mt-4 text-xs italic text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>

            <div class="flex flex-wrap">
              <button type="submit"
                class="w-full px-4 py-3 text-sm font-bold text-gray-100 bg-blue-500 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring">
                Отправить ссылку для сброса пароля
              </button>

              <p class="w-full mt-8 -mb-4 text-xs text-center text-grey-dark">
                <a class="text-blue-500 no-underline hover:text-blue-700" href="{{ route('login') }}">
                  Вернуться на страницу входа
                </a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
