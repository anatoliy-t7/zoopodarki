@extends('layouts.auth')
@section('title', 'Подтверждение почты')
@section('content')
<div class="">
  <div class="flex flex-wrap justify-center">
    <div class="w-full max-w-sm">

      @if (session('resent'))
      <div class="px-3 py-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
        На ваш адрес электронной почты была отправлена ​​новая ссылка для подтверждения.
      </div>
      @endif

      <div class="flex flex-col break-words">
        <div class="px-6 py-3 mb-0 font-semibold text-gray-700 ">
          Проверьте свой адрес электронной почты
        </div>

        <div class="flex flex-wrap w-full p-6">
          <p class="leading-normal">
            Прежде чем продолжить, проверьте свою электронную почту на наличие ссылки для подтверждения.
          </p>

          <p class="mt-6 leading-normal">
            Если вы не получили письмо, <a class="text-blue-500 no-underline hover:text-blue-700"
              onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();">нажмите
              здесь, чтобы запросить другое</a>.
          </p>

          <form id="resend-verification-form" method="POST" action="{{ route('verification.resend') }}" class="hidden">
            @csrf
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection