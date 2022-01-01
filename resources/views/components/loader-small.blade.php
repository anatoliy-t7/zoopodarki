@props(['bg' => false, 'color' => 'orange'])
<div wire:loading.delay {{ $attributes->whereStartsWith('wire:target') }}
  class="w-full z-40 absolute h-full inset-0  {{ $bg ? 'bg-white bg-opacity-75' : '' }}">
  <div class="flex items-start justify-center w-full ">
    <svg class="w-6 h-6 text-{{ $color }}-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
      viewBox="0 0 24 24">
      <circle class="opacity-50" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
      </path>
    </svg>
  </div>
</div>
