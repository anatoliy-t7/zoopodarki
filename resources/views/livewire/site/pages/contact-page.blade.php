<div class="px-2 py-8 space-y-8">
  <h1 class="text-2xl font-bold">Магазины и контакты компании ZooПодарки в Санкт-Петербурге</h1>

  <div class="flex items-start gap-6 px-16 bg-white shadow-sm py-14 rounded-2xl">

    <div class="flex flex-col w-4/12 gap-2 prose prose-h2:text-gray-600" itemscope
      itemtype="http://schema.org/Organization">
      <h2>Наши контакты</h2>
      <div class="flex items-center gap-3 ">
        <x-tabler-mail class="w-6 h-6 text-gray-400" />
        <span><a rel="noopener" itemprop="email"
            href="mailto:{{ config('constants.mail') }}">{{ config('constants.mail') }}</a></span>
      </div>
      <div class="flex items-center gap-3 ">
        <x-tabler-phone class="w-6 h-6 text-gray-400" />
        <span><a rel="noopener" itemprop="telephone"
            href="tel:{{ config('constants.phone') }}">{{ config('constants.phone') }}</a></span>
        <a rel="noopener" target="_blank"
          href="https://wa.me/{{ preg_replace('/[^0-9.]+/', '', config('constants.phone')) }}" class="link-hover">
          <x-tabler-brand-whatsapp class="text-green-500 w-7 h-7 hover:text-green-600" />
        </a>
      </div>
      <div class="flex items-center gap-3 ">
        <x-tabler-phone class="w-6 h-6 text-gray-400" />
        <span><a rel="noopener"
            href="tel:{{ config('constants.phone2') }}">{{ config('constants.phone2') }}</a></span>
      </div>
      <div class="flex items-center gap-3 ">
        <x-tabler-clock class="w-6 h-6 text-gray-400" />
        <span>с 10:00 до 20:00 каждый день</span>
      </div>
      <div class="flex items-start gap-3" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <x-tabler-map-pin class="w-6 h-6 text-gray-400" />
        <span class="leading-snug"><span itemprop="addressLocality">Санкт-Петербург</span>, <span
            itemprop="streetAddress">ул.Коллонтай, дом 5</span>,<br>
          корпус 1, лит А, кв. 673, <span itemprop="postalCode">193318</span></span>
      </div>
      <div class="flex items-center gap-6 pt-6 not-prose">
        <img class="w-20 h-auto" src="/assets/img/zoopodarki-logo.jpg" alt="Логотип компании ZOOподарки">
        <span class="text-xl font-bold" itemprop="name">ZOOподарки</span>
      </div>
    </div>

    <div class="flex w-8/12 pl-16 border-l">

      <form wire:submit.prevent="checkCaptcha" class="flex flex-wrap w-full max-w-screen-md gap-6 mx-auto ">
        @csrf
        <div class="flex flex-col w-full gap-2 ">
          <label for="content" class="block text-sm font-bold text-gray-700">
            Текст сообщения
          </label>
          <textarea wire:model.lazy='data.content' name="content" id="content" rows="4"
            class="field w-full @error('data.content') border-red-500 @enderror">
        </textarea>
          @error('data.content')
            <span class="text-xs italic text-red-500 ">
              {{ $message }}
            </span>
          @enderror
        </div>

        <div class="flex justify-between w-full gap-6">

          <div class="flex flex-col w-full gap-2">
            <label for="name" class="block text-sm font-bold text-gray-700">
              Имя
            </label>
            <input wire:model.lazy='data.name' id="name" type="text"
              class="field w-full @error('data.name') border-red-500 @enderror" name="name">
            @error('data.name')
              <span class="text-xs italic text-red-500 ">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="flex flex-col w-full gap-2">
            <label for="email" class="block text-sm font-bold text-gray-700">
              Адрес эл. почты
            </label>
            <input wire:model.lazy='data.email' id="email" type="email"
              class="field w-full @error('data.email') border-red-500 @enderror" name="email" required
              autocomplete="email">
            @error('data.email')
              <span class="text-xs italic text-red-500 ">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="flex flex-col w-full gap-2">
            <label for="phone" class="block text-sm font-bold text-gray-700">
              Телефон
            </label>
            <input wire:model.lazy='data.phone' id="phone" type="tel"
              class="field w-full @error('data.phone') border-red-500 @enderror" name="phone" autocomplete="phone">
            @error('data.phone')
              <span class="text-xs italic text-red-500 ">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>

        <div class="flex items-start justify-between w-full gap-2 ">
          <button type="submit"
            class="px-4 py-4 font-bold text-gray-100 bg-orange-400 rounded-xl hover:bg-orange-500 focus:outline-none focus:ring hover:shadow-lg hover:shadow-orange-300 disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled">
            <span wire:loading target="checkCaptcha" class="absolute top-4 left-4">
              <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
            </span>
            <span>
              Отправить сообщение
            </span>
          </button>
          <div>
            <x-honey recaptcha />
          </div>
        </div>

        <div class="text-xs text-gray-500">Настоящим подтверждаю, что я ознакомлен и согласен с условиями <a
            href="{{ route('site.page', 'privacy-policy') }}" target="_blank"
            class="text-blue-400 hover:underline">политики
            конфиденциальности</a>.
        </div>

      </form>
    </div>


  </div>

  <div wire:ignore class="bg-white shadow-sm rounded-2xl">
    <livewire:site.map>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      var event = new CustomEvent('init-map');
      window.dispatchEvent(event);
    });
  </script>
</div>
