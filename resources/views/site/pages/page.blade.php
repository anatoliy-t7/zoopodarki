@extends('layouts.app')

@if ($page->meta_title)
  @section('title', $page->meta_title)
@endif
@if ($page->meta_description)
  @section('description', $page->meta_description)
@endif

@section('content')
  @if ($page->temaplate == 'delivery')
    @include ('site.pages.template.delivery')
  @else
    @include ('site.pages.template.plain')
  @endif
@endsection
