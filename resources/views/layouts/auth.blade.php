@include ('layouts.header')

<div id="app" class="max-w-5xl mx-auto">
  <div
    class="flex flex-col items-center justify-center w-full min-h-screen px-2 py-4 antialiased  md:flex-row md:divide-x">
    <div class="flex justify-center w-full p-12 md:w-6/12">
      <x-logo class="w-full" />
    </div>
    <div class="flex justify-center w-full md:w-6/12">
      @yield ('content')
    </div>
  </div>
</div>

@include ('layouts.clean-footer')
