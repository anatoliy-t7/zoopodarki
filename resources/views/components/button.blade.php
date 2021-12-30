@props(['color' => 'orange'])
<button
  {{ $attributes->merge(['type' => 'submit', 'class' => 'relative inline-flex items-center px-4 pt-3 pb-2 disabled:cursor-not-allowed bg-' . $color . '-500 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-' . $color . '-600 active:bg-' . $color . '-600 focus:outline-none focus:border-' . $color . '-600 focus:ring ring-' . $color . '-600 disabled:bg-opacity-50 justify-center']) }}
  wire:loading.attr="disabled">
  <span wire:loading.flex wire:target="{{ $attributes->whereStartsWith('wire:click')->first() }}"
    class="absolute inset-0 flex items-center justify-center w-full h-full">
    <svg class="w-5 h-5 text-orange-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
      </path>
    </svg>
  </span>
  <span>
    {{ $slot }}
  </span>
</button>
