@include ('layouts.header')

<div class="relative">
  <a href="{{ url()->previous() }}" class="absolute p-2 text-gray-500 top-3 left-2 hover:text-blue-500"
    title="Вернуться">
    <x-tabler-chevron-left class="w-8 h-8" />
  </a>
  <div class="block w-full max-w-screen-xl pt-4 mx-auto antialiased md:pb-6 md:px-4 ">
    @yield ('content')
  </div>
</div>


@production
  <livewire:error-catcher>
  @endproduction

  <livewire:scripts>

    @stack('footer')
    <livewire:toasts />

    <script src="{{ mix('js/app.js') }}"></script>

    </body>

    </html>
