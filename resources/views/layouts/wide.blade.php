@include ('layouts.header')

<x-navbar />

<main class="bg-white">
  <div class="block w-full pb-12 mt-2 antialiased md:mt-10 lg:mt-20">
    @yield ('content')
  </div>
</main>

@include ('layouts.footer')
