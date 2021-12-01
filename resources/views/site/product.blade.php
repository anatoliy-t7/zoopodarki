@extends('layouts.app')
@section('content')
  <div class="space-y-2">

    <x-breadcrumbs :category="$category" :catalog="$catalog" />

    <livewire:site.product-card :slug="$slug" :tab="$tab" :category="$category" :catalog="$catalog">

  </div>
@endsection
