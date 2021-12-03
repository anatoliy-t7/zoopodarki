@include ('layouts.header')

<x-navbar />

<main>
  <div class="block w-full max-w-screen-xl px-4 pb-12 mx-auto mt-10 antialiased xl:mt-20">
    @yield ('content')
  </div>
</main>

@include ('layouts.footer')
