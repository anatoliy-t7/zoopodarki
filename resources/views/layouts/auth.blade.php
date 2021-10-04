@include ('layouts.header')

<div id="app" class="max-w-5xl mx-auto">
  <div class="flex items-center justify-center w-full min-h-screen px-2 py-4 antialiased divide-x">
    <div class="flex justify-center w-6/12">
      <a href="{{ route('site.home') }}">
        <img loading="lazy" src="/assets/img/favicon.svg" width="200px" height="200px" alt="">
      </a>
    </div>
    <div class="flex justify-center w-6/12">
      @yield ('content')
    </div>
  </div>
</div>

@include ('layouts.footer')
