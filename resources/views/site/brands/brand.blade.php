@extends('layouts.app')

@if ($brand->meta_title)
@section('title', $brand->meta_title)
@endif
@if ($brand->meta_description)
@section('description', $brand->meta_description)
@endif

@section('content')

<div class="pt-2">
  <x-breadcrumbs :category="$brand" />

  <livewire:site.brand :brand="$brand">
</div>

@endsection