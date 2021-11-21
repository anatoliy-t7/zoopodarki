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
          <a class="flex items-center justify-center px-3 py-4 bg-white rounded-xl hover:shadow-lg"
            href="{{ route('site.brand', $brand->slug) }}">
            @if ($brand->logo)
              <img loading="lazy" class="object-scale-down w-full h-16" src="/brands/{{ $brand->logo }}"
                alt="Логотип {{ $brand->name }}">
            @else
              <span class="font-bold text-blue-500">{{ $brand->name }}</span>
            @endif
          </a>
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
