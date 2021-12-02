@extends('layouts.app')
@section('content')
  <div>
    <livewire:site.product-card :productslug="$productslug" :tab="$tab" :category="$category" :catalog="$catalog">
  </div>
@endsection
