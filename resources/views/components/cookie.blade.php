<div x-cloak x-data="{ cookie: $persist(0) }" class="fixed bottom-0 right-0 z-20 p-4">
  <div x-show="cookie == 0" x-transition
    class="flex items-center justify-start max-w-md gap-4 px-6 py-5 text-sm bg-orange-100 shadow-xl rounded-xl">
    <p>Мы используем <a href="{{ route('site.page', 'privacy-policy') }}" class="hover:text-orange-500">файлы
        cookie</a>, чтобы вам было удобнее пользоваться интернет-магазином. Продолжая пользование сайтом,
      вы соглашаетесь с использованием файлов cookie.</p>
    <button @click="cookie = 1"
      class="px-3 py-2 text-lg font-bold text-white bg-green-500 rounded-xl hover:bg-green-600">Ok</button>
  </div>
</div>
