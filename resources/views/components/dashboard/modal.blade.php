<div id="modal" :class="form ? 'translate-x-0' : 'translate-x-full'"
  {{ $attributes->merge(['class' => 'fixed top-0 right-0 z-40 w-full h-screen max-w-md min-w-full px-8 py-6 transition-all duration-500 ease-in-out transform bg-pink-50 lg:min-w-half text-gray-600']) }}>

  <div class="pb-2">
    <button x-on:click="closeForm" wire:click="closeForm" class="text-gray-600 link-hover">
      <x-tabler-circle-x class="w-6 h-6 stroke-current" />
    </button>
  </div>

  {{ $slot }}

</div>
