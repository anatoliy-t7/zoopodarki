<footer class="text-white bg-gray-100" itemscope itemtype="http://schema.org/WPFooter">

  <div class="max-w-screen-xl px-24 py-24 mx-auto md:px-4">
    <nav aria-label="Secondary"
      class="flex flex-col flex-wrap justify-between order-first space-y-4 text-left md:space-x-8 md:flex-row md:space-y-0">
      <div class="">
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-400 uppercase title-font">Каталог</h2>
        <ul class="space-y-3 text-gray-600 list-none ">
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
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-400 uppercase title-font">Помощь</h2>
        <ul class="space-y-3 text-gray-600 list-none">
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
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-400 uppercase title-font">Компания</h2>
        <ul class="space-y-3 text-gray-600 list-none ">
          <li>
            <a href="{{ route('site.page', ['slug' => 'about']) }}" class="hover:text-orange-500">О нас</a>
          </li>
          <li>
            <a href="{{ route('site.contact') }}" class="hover:text-orange-500">
              Адреса и контакты
            </a>
          </li>
          <li>
            <a href="{{ route('site.page', 'privacy-policy') }}" class="hover:text-orange-500">
              Политика конфиденциальности
            </a>
          </li>
        </ul>
      </div>
      <div class="">
        <x-share-buttons :url="url()->full()" />
        <div class="block pt-4 pb-6 space-y-4 text-gray-600">
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
          <a rel="noopener" target="_blank" href="https://vk.com/zoopodarki" class="text-gray-500 hover:text-orange-500"
            title="ZOOподарки в VK">
            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 12">
              <path
                d="M6.676 1.20602C6.875 1.23102 7.325 1.32302 7.563 1.64302C7.872 2.05502 7.861 2.97902 7.861 2.97902C7.861 2.97902 8.038 5.52402 7.447 5.84002C7.043 6.05802 6.487 5.61502 5.291 3.58902C4.68 2.55202 4.22 1.40602 4.22 1.40602C4.22 1.40602 4.13 1.19202 3.972 1.07602C3.8313 0.98558 3.67384 0.924366 3.509 0.896024L0.653001 0.913024C0.653001 0.913024 0.223001 0.925024 0.0660014 1.10802C-0.0739986 1.27002 0.0550015 1.60802 0.0550015 1.60802C0.0550015 1.60802 2.291 6.74502 4.825 9.33502C7.149 11.709 9.786 11.553 9.786 11.553H10.981C10.981 11.553 11.343 11.514 11.527 11.318C11.697 11.14 11.691 10.803 11.691 10.803C11.691 10.803 11.669 9.23002 12.414 8.99802C13.147 8.76802 14.089 10.518 15.089 11.192C15.844 11.701 16.417 11.589 16.417 11.589L19.089 11.553C19.089 11.553 20.486 11.468 19.822 10.39C19.768 10.302 19.437 9.59202 17.837 8.13502C16.162 6.60802 16.387 6.85502 18.403 4.21602C19.633 2.60902 20.123 1.62702 19.97 1.20802C19.824 0.806024 18.92 0.913024 18.92 0.913024L15.913 0.932024C15.913 0.932024 15.69 0.902024 15.525 0.998024C15.364 1.09402 15.258 1.31502 15.258 1.31502C15.258 1.31502 14.783 2.55902 14.148 3.61702C12.808 5.84802 12.274 5.96702 12.055 5.82902C11.545 5.50502 11.673 4.52902 11.673 3.83802C11.673 1.67402 12.007 0.772024 11.022 0.540024C10.695 0.462024 10.455 0.412024 9.619 0.402024L9.195 0.400024C8.308 0.400024 7.568 0.439024 7.123 0.652024C6.781 0.817024 6.516 1.18402 6.677 1.20602H6.676Z" />
            </svg>
          </a>
        </span>
      </div>
    </nav>
  </div>

  <div class="text-gray-500 border-t border-gray-200">
    <div class="flex flex-col items-center justify-between max-w-screen-xl px-2 py-3 mx-auto md:flex-row">
      <div class="text-sm ">&copy; {{ date('Y') }} ZOOподарки
      </div>
      <div class="flex flex-col items-center justify-end text-xs md:space-x-6 md:flex-row">
        <a href="#" title="Наверх страницы" class="text-gray-400 hover:text-gray-600 link-hover">
          <x-tabler-chevron-up class="w-7 h-7" />
        </a>
      </div>
    </div>
  </div>
</footer>

@guest
  <livewire:auth.auth-com />
@endguest
@production
  <livewire:error-catcher />
@endproduction
<livewire:scripts />
<livewire:toasts />
<script src="{{ mix('js/app.js') }}"></script>
@stack('footer')
</body>

</html>
