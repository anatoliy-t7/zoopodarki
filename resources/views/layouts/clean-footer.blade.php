@production
  <livewire:error-catcher>
  @endproduction

  <livewire:scripts>

    @stack('footer')
    <livewire:toasts />

    <script src="{{ mix('js/app.js') }}"></script>

    </body>

    </html>
