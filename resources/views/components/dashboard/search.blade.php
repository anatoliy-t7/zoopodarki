@props(['placeholder' => 'Поиск'])
<div class="relative w-full max-w-lg">
  <input type="search"
    class="w-full py-2 pl-10 pr-4 font-medium text-gray-600 placeholder-gray-300 border-2 border-pink-50 rounded-xl focus:outline-none focus:ring hover:border-pink-200 focus:border-transparent"
    wire:model.debounce.600ms="search" placeholder="{{ $placeholder }}" autocomplete="off" inputmode="search">
  <div class="absolute top-0 inline-flex items-center p-2 left-1">
    <x-tabler-search class="w-6 h-6 text-gray-400" />
  </div>
</div>