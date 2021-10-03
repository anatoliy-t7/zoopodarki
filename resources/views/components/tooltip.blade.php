<div x-data="{ tooltip: false }" class="relative z-20 inline-flex">
  <div x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false"
    class="relative z-30 flex items-center justify-start w-full space-x-1">
    {{ $title }}
  </div>

  <div class="relative" x-cloak x-show="tooltip" x-transition.origin.top>
    <div style="width: {{ $width }}"
      class="absolute top-0 z-50 p-3 -mt-1 text-xs leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-green-500 shadow-lg rounded-2xl">
      {{ $slot }}</div>
  </div>
</div>