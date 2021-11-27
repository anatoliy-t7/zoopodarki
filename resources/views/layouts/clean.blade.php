@include ('layouts.header')

<div class="relative">
  <a href="javascript:%20history.go(-1)" class="absolute p-2 text-gray-500 top-3 left-2 hover:text-blue-500"
    title="Вернуться">
    <x-tabler-chevron-left class="w-8 h-8" />
  </a>
  <div class="block w-full max-w-screen-xl px-4 pt-4 pb-6 mx-auto antialiased ">
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
