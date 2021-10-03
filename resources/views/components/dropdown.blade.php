<div x-data="{ open: false }" class="relative max-w-xs">
  <button @click="open = !open" @click.outside="open = false"
    class="flex items-center w-auto py-1 pl-2 pr-1 text-xs text-left text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:border-blue-400 focus:outline-none">
    <span>{{ $title }}</span>
    <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
      class="inline w-4 h-4 align-middle transition-transform duration-200 transform ">
      <path fill-rule="evenodd"
        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
        clip-rule="evenodd"></path>
    </svg>
  </button>
  <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    class="absolute right-0 z-30 w-40 mt-2 origin-top shadow-xl rounded-2xl">
    <div class="w-auto p-2 text-gray-700 bg-gray-50 divide-y divide-gray-200 shadow-sm rounded-2xl"
      @click="open = !open">
      {{ $slot }}
    </div>
  </div>
</div>