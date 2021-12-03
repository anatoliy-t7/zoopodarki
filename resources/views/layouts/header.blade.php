<!doctype html>
<html lang="ru" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="format-detection" content="phone=no">
  <meta name="currency" content="ruble">

  {!! SEOMeta::generate() !!}
  {!! OpenGraph::generate() !!}

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('assets/img/favicon.svg') }}" type="image/svg+xml">
  @stack('header-meta')
  <style>
    [x-cloak] {
      display: none !important;
    }

  </style>
  @stack('header-css')
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <livewire:styles>
    @stack('header-script')
</head>

<body class="grid min-h-full antialiased leading-none text-gray-800 bg-gray-50">
