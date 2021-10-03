@extends('layouts.app')
@section('title', 'Бренды')
@section('content')

<div>
  <div class="py-4">
    <h1 class="text-xl font-bold text-gray-500">
      Бренды
    </h1>
  </div>
  <div class="flex w-full">

    <div class="grid w-full grid-cols-2 gap-6 lg:grid-cols-3 xl:grid-cols-4">
      @forelse ($brands as $brand)
      <div class="px-3 py-4 bg-white ">

        <a href="{{ route('site.brand', $brand->slug) }}">
          @if ($brand->logo)
          <img loading="lazy" class="w-auto h-10" src="/brands/{{ $brand->logo }}" alt="Логотип {{ $brand->name }}">
          @else
          <div class="font-bold text-blue-500">{{ $brand->name }}</div>
          @endif
        </a>

      </div>
      @empty
      <p>No brands</p>
      @endforelse
    </div>

  </div>


  <div wire:loading.remove class="pt-6">
    {{ $brands->links() }}
  </div>


</div>


@endsection