@extends('layouts.app')

@section('content')

  <div class="pt-2">
    <x-breadcrumbs :category="$brand" />

    <livewire:site.brand :brand="$brand">
  </div>

@endsection
