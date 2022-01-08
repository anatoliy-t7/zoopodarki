<div wire:ignore x-data="{ confirmId: null }" x-init="
$watch('confirmId', value => {
  const body = document.body;
  if(confirmId == null) {
     body.classList.remove('h-screen');
     return body.classList.remove('overflow-hidden');
  } else {
      body.classList.add('h-screen');
      return body.classList.add('overflow-hidden');
  }
});" {{ $attributes->whereStartsWith('wire:key') }}>

  <div x-cloak x-show="confirmId == {{ $confirmId }}" x-transition.opacity
    class="fixed top-0 left-0 z-50 flex items-center justify-center w-full h-screen min-w-full bg-gray-500 bg-opacity-50"
    role="dialog" aria-modal="true">

    <div x-on:click.outside="confirmId = null" x-on:keydown.esc="confirmId = null"
      class="absolute flex flex-col w-full max-w-xs bg-white shadow-2xl rounded-xl">


      <button
        class="absolute top-0 right-0 p-1 text-gray-500 transition-all duration-300 bg-white rounded-full hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:shadow-outline"
        x-on:click="confirmId = null">
        <x-tabler-x class="w-6 h-6 stroke-current" />
      </button>


      <div class="flex items-center justify-center px-5 pt-4 pb-2">
        <h2 class="text-lg leading-snug text-center text-gray-700">
          Подтвердите удаление. <br>id:<b class="text-base">{{ $confirmId }}</b>
        </h2>
      </div>

      <div class="flex items-center justify-center px-5 pb-4 space-x-8">
        <button x-on:click="confirmId = null"
          class="px-5 py-2 font-semibold text-gray-600 transition duration-150 border border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-100 hover:text-gray-900 focus:outline-none focus:shadow-outline">Отмена</button>

        <button x-on:click="confirmId = null" wire:click="remove({{ $confirmId }})"
          class="px-5 py-2 font-semibold text-white transition duration-150 bg-red-500 rounded-lg hover:bg-red-600 focus:outline-none focus:shadow-outline">Удалить</button>
      </div>

    </div>


  </div>

  <button x-on:click="confirmId = {{ $confirmId }}"
    class="px-2 py-2 font-semibold text-gray-400 transition duration-200 hover:text-red-500 focus:outline-none focus:shadow-outline">
    <x-tabler-trash class="w-6 h-6 stroke-current" />
  </button>

</div>
