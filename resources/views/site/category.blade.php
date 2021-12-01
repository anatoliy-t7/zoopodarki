@extends('layouts.app')
@section('content')

  <div class="py-2">

    <x-breadcrumbs :category="$category" :catalog="$catalog" />

    <livewire:site.category :category="$category" :catalog="$catalog" :tag="$tag">

  </div>
@endsection
