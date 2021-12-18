<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title', config('app.name')) | {{ config('app.name') }} &#128054</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#FB923C">
  <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}">
  @stack('header-meta')

  <style>
    [x-cloak] {
      display: none !important;
    }

  </style>

  <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
  @stack('header-css')
  <livewire:styles>

    <script src="{{ mix('js/dashboard.js') }}" defer></script>
    @stack('header-js')

</head>

<body class="w-full min-h-screen overflow-x-hidden bg-pink-50">
