@extends('layouts.app')

@if ($page->meta_title)
  @section('title', $page->meta_title)
@endif
@if ($page->meta_description)
  @section('description', $page->meta_description)
@endif

@section('content')

  <article class="py-4 space-y-4">
    <h1 class="text-2xl font-bold">{{ $page->title }}</h1>
    <div class="p-8 bg-white shadow-sm rounded-2xl">
      {!! $page->content !!}
    </div>
  </article>

@endsection
