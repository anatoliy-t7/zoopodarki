<!doctype html>
<html lang="ru" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
  <meta name="format-detection" content="phone=no">
  <meta name="currency" content="ruble">

  {!! SEOMeta::generate() !!}
  {!! OpenGraph::generate() !!}

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="yandex-verification" content="0811cc7cb6f1e83f" />
  <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
  <link rel="canonical" href="{{ url()->current() }}" />
  <link rel="preload" as="style" href="{{ mix('css/app.css') }}">
  <link rel="preload" as="script" href="{{ mix('js/app.js') }}">
  <link rel="preload" href="{{ asset('assets/fonts/nunito-v20-latin_cyrillic-700.woff2') }}" as="font"
    type="font/woff2" crossorigin="anonymous" />
  <link rel="preload" href="{{ asset('assets/fonts/nunito-v20-latin_cyrillic-800.woff2') }}" as="font"
    type="font/woff2" crossorigin="anonymous" />
  @stack('header-meta')
  <style>
    [x-cloak] {
      display: none !important;
    }

  </style>
  @stack('header-css')
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <livewire:styles>

    @stack('header-js')
</head>

<body class="relative grid min-h-full overflow-x-hidden antialiased leading-none text-gray-800 bg-gray-50">
