<footer class="text-white bg-gray-100" itemscope itemtype="http://schema.org/WPFooter">

  <div class="max-w-screen-xl px-24 py-24 mx-auto md:px-4">
    <nav aria-label="Secondary"
      class="flex flex-col flex-wrap justify-between order-first space-y-4 text-left md:space-x-8 md:flex-row md:space-y-0">
      <div class="">
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-300 uppercase title-font">Каталог</h2>
        <ul class="space-y-3 text-gray-500 list-none ">
          <li>
            <a class="hover:text-orange-500">Кошки</a>
          </li>
          <li>
            <a class=" hover:text-orange-500">Собаки</a>
          </li>
          <li>
            <a class=" hover:text-orange-500">Другие питомцы</a>
          </li>
          <li>
            <a class=" hover:text-orange-500">Новинки</a>
          </li>
          <li>
            <a class="hover:text-orange-500">Акции</a>
          </li>
        </ul>
      </div>
      <div class="">
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-300 uppercase title-font">Помощь</h2>
        <ul class="space-y-3 text-gray-500 list-none">
          <li>
            <a class="hover:text-orange-500">Как оформить заказ</a>
          </li>
          <li>
            <a class=" hover:text-orange-500">Доставка и оплата</a>
          </li>
          <li>
            <a class=" hover:text-orange-500">Способы оплаты</a>
          </li>
          <li>
            <a class=" hover:text-orange-500">Возврат товара</a>
          </li>
        </ul>
      </div>
      <div class="">
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-300 uppercase title-font">Компания</h2>
        <ul class="space-y-3 text-gray-500 list-none ">
          <li>
            <a href="{{ route('site.page', ['slug' => 'about']) }}" class="hover:text-orange-500">О нас</a>
          </li>
          <li>
            <a href="{{ route('site.contact') }}" class="hover:text-orange-500">
              Адреса и контакты
            </a>
          </li>
        </ul>
      </div>
      <div class="">
        <x-share-buttons :url="url()->full()" />
        <div class="block pt-4 pb-6 space-y-4 text-gray-500">
          <div>
            пн-пт 8:00 - 20:00
          </div>
          <div>
            +7 123 456-78-90
          </div>
          <div>
            +7 123 456-78-90
          </div>
        </div>
        <span class="inline-flex space-x-4 justify-right">
          <a target="_blank" href="https://vk.com/zoopodarki" class="text-gray-500" title="vk">
            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 12">
              <path
                d="M6.676 1.20602C6.875 1.23102 7.325 1.32302 7.563 1.64302C7.872 2.05502 7.861 2.97902 7.861 2.97902C7.861 2.97902 8.038 5.52402 7.447 5.84002C7.043 6.05802 6.487 5.61502 5.291 3.58902C4.68 2.55202 4.22 1.40602 4.22 1.40602C4.22 1.40602 4.13 1.19202 3.972 1.07602C3.8313 0.98558 3.67384 0.924366 3.509 0.896024L0.653001 0.913024C0.653001 0.913024 0.223001 0.925024 0.0660014 1.10802C-0.0739986 1.27002 0.0550015 1.60802 0.0550015 1.60802C0.0550015 1.60802 2.291 6.74502 4.825 9.33502C7.149 11.709 9.786 11.553 9.786 11.553H10.981C10.981 11.553 11.343 11.514 11.527 11.318C11.697 11.14 11.691 10.803 11.691 10.803C11.691 10.803 11.669 9.23002 12.414 8.99802C13.147 8.76802 14.089 10.518 15.089 11.192C15.844 11.701 16.417 11.589 16.417 11.589L19.089 11.553C19.089 11.553 20.486 11.468 19.822 10.39C19.768 10.302 19.437 9.59202 17.837 8.13502C16.162 6.60802 16.387 6.85502 18.403 4.21602C19.633 2.60902 20.123 1.62702 19.97 1.20802C19.824 0.806024 18.92 0.913024 18.92 0.913024L15.913 0.932024C15.913 0.932024 15.69 0.902024 15.525 0.998024C15.364 1.09402 15.258 1.31502 15.258 1.31502C15.258 1.31502 14.783 2.55902 14.148 3.61702C12.808 5.84802 12.274 5.96702 12.055 5.82902C11.545 5.50502 11.673 4.52902 11.673 3.83802C11.673 1.67402 12.007 0.772024 11.022 0.540024C10.695 0.462024 10.455 0.412024 9.619 0.402024L9.195 0.400024C8.308 0.400024 7.568 0.439024 7.123 0.652024C6.781 0.817024 6.516 1.18402 6.677 1.20602H6.676Z" />
            </svg>
          </a>
          {{-- <a target="_blank" href="" class="ml-3 text-gray-500" title="Одноклассники">
            <svg class="w-6 h-6 fill-current" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M14.505 17.44c1.275-.29 2.493-.794 3.6-1.49.834-.558 1.058-1.686.5-2.52-.536-.802-1.604-1.044-2.435-.553-2.55 1.595-5.79 1.595-8.34 0-.847-.534-1.965-.28-2.5.565 0 .002 0 .004-.002.005-.534.847-.28 1.966.567 2.5l.002.002c1.105.695 2.322 1.2 3.596 1.488l-3.465 3.465c-.707.695-.72 1.83-.028 2.537l.03.03c.344.354.81.53 1.274.53.465 0 .93-.176 1.275-.53L12 20.065l3.404 3.406c.72.695 1.87.676 2.566-.045.678-.703.678-1.818 0-2.52l-3.465-3.466zM12 12.388c3.42-.004 6.19-2.774 6.195-6.193C18.195 2.78 15.415 0 12 0S5.805 2.78 5.805 6.197c.005 3.42 2.776 6.19 6.195 6.192zm0-8.757c1.416.002 2.563 1.15 2.564 2.565 0 1.416-1.148 2.563-2.564 2.565-1.415-.002-2.562-1.148-2.565-2.564C9.437 4.78 10.585 3.633 12 3.63z" />
            </svg>
          </a> --}}

          {{-- <a target="_blank" href="" class="text-gray-500" title="facebook">
            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 20">
              <path
                d="M6.84603 0.208008C3.59803 0.208008 2.45503 1.84501 2.45503 4.59801V6.62501H0.43103V9.99901H2.45503V19.792H6.50703V9.99901H9.21103L9.56903 6.62401H6.50703L6.51203 4.93501C6.51203 4.05401 6.59503 3.58301 7.85903 3.58301H9.54903V0.208008H6.84603Z" />
            </svg>
          </a> --}}
          {{-- <a target="_blank" href="" class="ml-3 text-gray-500" title="Instagam">
            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <defs />
              <path
                d="M11.9963 4.21185C14.5339 4.21185 14.8318 4.22316 15.8347 4.26841C16.7623 4.30988 17.2638 4.46448 17.5994 4.59645C18.0443 4.76989 18.361 4.97351 18.6929 5.30532C19.0247 5.63713 19.232 5.95386 19.4017 6.39879C19.5299 6.73437 19.6883 7.23586 19.7298 8.16343C19.775 9.1664 19.7863 9.46428 19.7863 12.0019C19.7863 14.5395 19.775 14.8374 19.7298 15.8403C19.6883 16.7679 19.5337 17.2694 19.4017 17.605C19.2283 18.0499 19.0247 18.3666 18.6929 18.6985C18.361 19.0303 18.0443 19.2376 17.5994 19.4073C17.2638 19.5355 16.7623 19.6939 15.8347 19.7354C14.8318 19.7806 14.5339 19.7919 11.9963 19.7919C9.45868 19.7919 9.1608 19.7806 8.15782 19.7354C7.23026 19.6939 6.72877 19.5393 6.39319 19.4073C5.94826 19.2339 5.63153 19.0303 5.29972 18.6985C4.96791 18.3666 4.76052 18.0499 4.59085 17.605C4.46265 17.2694 4.30428 16.7679 4.2628 15.8403C4.21756 14.8374 4.20625 14.5395 4.20625 12.0019C4.20625 9.46428 4.21756 9.1664 4.2628 8.16343C4.30428 7.23586 4.45888 6.73437 4.59085 6.39879C4.76429 5.95386 4.96791 5.63713 5.29972 5.30532C5.63153 4.97351 5.94826 4.76612 6.39319 4.59645C6.72877 4.46825 7.23026 4.30988 8.15782 4.26841C9.1608 4.21939 9.45868 4.21185 11.9963 4.21185ZM11.9963 2.5C9.4172 2.5 9.09293 2.51131 8.07864 2.55656C7.06812 2.60181 6.37811 2.76394 5.77481 2.99772C5.14889 3.23904 4.62101 3.56708 4.09313 4.09496C3.56525 4.62284 3.24098 5.1545 2.99589 5.77664C2.76211 6.37994 2.59997 7.06995 2.55473 8.08424C2.50948 9.09476 2.49817 9.41903 2.49817 11.9981C2.49817 14.5772 2.50948 14.9015 2.55473 15.9158C2.59997 16.9263 2.76211 17.6163 2.99589 18.2234C3.2372 18.8493 3.56525 19.3772 4.09313 19.905C4.62101 20.4329 5.15266 20.7572 5.77481 21.0023C6.37811 21.2361 7.06812 21.3982 8.08241 21.4434C9.0967 21.4887 9.4172 21.5 12.0001 21.5C14.5829 21.5 14.9034 21.4887 15.9177 21.4434C16.9282 21.3982 17.6182 21.2361 18.2253 21.0023C18.8512 20.761 19.3791 20.4329 19.907 19.905C20.4349 19.3772 20.7591 18.8455 21.0042 18.2234C21.238 17.6201 21.4001 16.93 21.4454 15.9158C21.4906 14.9015 21.5019 14.581 21.5019 11.9981C21.5019 9.41526 21.4906 9.09476 21.4454 8.08047C21.4001 7.06995 21.238 6.37994 21.0042 5.77287C20.7629 5.14695 20.4349 4.61907 19.907 4.09119C19.3791 3.56331 18.8474 3.23904 18.2253 2.99395C17.622 2.76017 16.932 2.59804 15.9177 2.55279C14.8996 2.51131 14.5754 2.5 11.9963 2.5Z" />
              <path
                d="M11.9963 7.11896C9.30413 7.11896 7.11719 9.30213 7.11719 11.9981 7.11719 14.6941 9.30413 16.8772 11.9963 16.8772 14.6885 16.8772 16.8755 14.6903 16.8755 11.9981 16.8755 9.3059 14.6885 7.11896 11.9963 7.11896zM11.9963 15.1654C10.2468 15.1654 8.82904 13.7477 8.82904 11.9981 8.82904 10.2485 10.2468 8.83081 11.9963 8.83081 13.7459 8.83081 15.1636 10.2485 15.1636 11.9981 15.1636 13.7477 13.7459 15.1654 11.9963 15.1654zM17.0676 8.0654C17.6964 8.0654 18.2063 7.55558 18.2063 6.92668 18.2063 6.29779 17.6964 5.78796 17.0676 5.78796 16.4387 5.78796 15.9288 6.29779 15.9288 6.92668 15.9288 7.55558 16.4387 8.0654 17.0676 8.0654z" />
            </svg>
          </a> --}}


        </span>
      </div>
    </nav>
  </div>

  <div class="text-gray-500 border-t border-gray-200">
    <div class="flex flex-col items-center justify-between max-w-screen-xl px-2 py-5 mx-auto md:flex-row">
      <div class="text-sm ">&copy; {{ date('Y') }} {{ config('app.name') }}
      </div>
      <div class="flex flex-col items-center justify-end text-xs md:space-x-6 md:flex-row">
        <a href="#">Пользовательское соглашение</a>
        <a href="#">Политика конфиденциальности</a>
      </div>
    </div>
  </div>

</footer>

@guest
  <livewire:auth.in />
@endguest


@production
  <livewire:error-catcher>
  @endproduction

  <livewire:scripts />
  <livewire:toasts />
  <script src="{{ mix('js/app.js') }}"></script>

  @stack('footer')
  </body>

  </html>
