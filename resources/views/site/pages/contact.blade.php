@extends('layouts.app')

@section('title', 'Контакты и магазины ' . config('app.name') . ' в Санкт-Петербурге')
@section('description', 'Посмотреть расположение магазинов ' . config('app.name') . ' в Санкт-Петербурге')

@section('content')

  <div class="px-2 py-4 space-y-4">
    <h1 class="text-2xl font-bold">Магазины ZooПодарки в Санкт-Петербурге</h1>
    <div class="p-4 bg-white shadow-sm rounded-2xl">
      <livewire:site.map>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      var event = new CustomEvent('init-map');
      window.dispatchEvent(event);
    });
  </script>

@endsection
