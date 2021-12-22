@extends('layouts.app')

@if ($page->meta_title)
  @section('title', $page->meta_title)
@endif
@if ($page->meta_description)
  @section('description', $page->meta_description)
@endif

@section('content')
  @if ($page->template == 'accordion')
    @include ('site.pages.templates.accordion')
  @else
    @include ('site.pages.templates.plain')
  @endif
@endsection
