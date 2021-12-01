@extends('layouts.app')
@section('title', 'Ваш заказ - Zoo Подарки')
@section('content')
  <div class="flex items-center">
    <div class="container py-8 md:mx-auto">
      <div class="flex items-center">
        <div class="container py-8 md:mx-auto">
          <div class="w-full p-6 bg-white rounded-2xl">
            @if ($order)
              <p>
                Заказ: <b>{{ $order->order_number }}</b>
              </p>
            @endif
            <p>
              {{ $comment }}
            </p>
            @if ($order->payment_status == 'succeeded')
              <p>Статус заказа вы можете проверять <a href="{{ route('account.orders') }}">здесь</a></p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
