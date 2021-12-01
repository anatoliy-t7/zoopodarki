@include ('layouts.header')

<x-navbar />

<div>
  <div class="block w-full max-w-screen-xl px-4 pb-6 mx-auto mt-10 antialiased xl:mt-20 ">
    @yield ('content')
  </div>
</div>

@include ('layouts.footer')
