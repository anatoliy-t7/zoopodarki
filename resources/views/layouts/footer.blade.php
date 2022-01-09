<footer class="text-white bg-gray-100" itemscope itemtype="http://schema.org/WPFooter">

  <div class="max-w-screen-xl px-12 py-12 mx-auto md:px-4">
    <nav aria-label="Secondary"
      class="flex flex-col flex-wrap justify-between order-first space-y-4 text-left md:space-x-8 md:flex-row md:space-y-0">
      <div class="">
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-400 uppercase title-font">Компания</h2>
        <ul class="space-y-3 text-gray-600 list-none ">
          <li>
            <a href="{{ route('site.contact') }}" class="hover:text-orange-500">
              Магазины и контакты
            </a>
          </li>
          <li>
            <a href="{{ route('site.page', ['slug' => 'sale-rules']) }}" class="hover:text-orange-500">Правила
              продаж</a>
          </li>
          <li>
            <a href="{{ route('site.page', 'privacy-policy') }}" class="hover:text-orange-500">
              Политика конфиденциальности
            </a>
          </li>
        </ul>
      </div>
      <div class="">
        <h2 class="mb-4 text-sm font-medium tracking-widest text-gray-400 uppercase title-font">Покупателям</h2>
        <ul class="space-y-3 text-gray-600 list-none">
          <li>
            <a href="{{ route('site.discounts') }}" class="hover:text-orange-500">
              Акции и скидки
            </a>
          </li>
          <li>
            <a href="{{ route('site.page', 'how-to-place-an-order') }}" class="hover:text-orange-500">
              Как оформить заказ
            </a>
          </li>
          <li>
            <a href="{{ route('site.page', 'payment-methods') }}" class="hover:text-orange-500">
              Способы оплаты
            </a>
          </li>
        </ul>
      </div>
      <div class="">
        <div class="h-8 mb-1"></div>
        <ul class="space-y-3 text-gray-600 list-none ">
          <li>
            <a href="{{ route('site.page', 'delivery') }}" class="hover:text-orange-500">
              Доставка и самовывоз
            </a>
          <li>
            <a href="{{ route('site.page', 'terms-of-return-of-goods') }}" class="hover:text-orange-500">
              Условия возврата товара
            </a>
          <li>
            <a href="{{ route('site.category', ['catalogslug' => 'help-shelter', 'categoryslug' => 'priyut-dlya-sobak']) }}"
              class="hover:text-orange-500">
              Помощь приюту
            </a>
        </ul>
      </div>
      <div>
        <div class="w-10">
          <x-share-buttons :url="url()->full()" />
        </div>
        <div class="block py-2 space-y-2 text-gray-600">
          <div class="flex items-center gap-3 ">
            <x-tabler-clock class="w-5 h-5 text-gray-400" />
            <span>с 10:00 до 20:00 каждый день</span>
          </div>
          <a rel="noopener" itemprop="telephone" class="flex items-center gap-3 hover:underline"
            href="tel:{{ config('constants.phone') }}">
            <x-tabler-phone class="w-5 h-5 text-gray-400 " />
            {{ config('constants.phone') }}
          </a>
          <a rel="noopener" class="flex items-center gap-3 hover:underline"
            href="tel:{{ config('constants.phone2') }}">
            <x-tabler-phone class="w-5 h-5 text-gray-400" />
            {{ config('constants.phone2') }}
          </a>
        </div>
        <div class="flex gap-4 pt-2">
          <a rel="noopener, nofollow" target="_blank" href="https://vk.com/zoopodarki"
            class="text-gray-500 hover:text-blue-700 link-hover" title="ZOOподарки в VK">
            <x-tabler-brand-vk class="w-7 h-7" />
          </a>
          <a rel="noopener, nofollow" target="_blank" href="https://www.instagram.com/zoopodarki_spb/"
            class="text-gray-500 hover:text-red-500 link-hover" title="ZOOподарки в Instagram">
            <x-tabler-brand-instagram class="w-7 h-7" />
          </a>
          <a rel="noopener, nofollow" target="_blank"
            href="https://wa.me/{{ preg_replace('/[^0-9.]+/', '', config('constants.phone')) }}"
            class="text-gray-500 link-hover hover:text-green-500">
            <x-tabler-brand-whatsapp class="w-7 h-7 " />
          </a>
        </div>

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

<x-cookie />
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
@production
  <x-metrika />
@endproduction
</body>

</html>
