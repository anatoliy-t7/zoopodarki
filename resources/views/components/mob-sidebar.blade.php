<div x-cloak x-data="{ filter: false}"
  x-effect="document.body.classList.toggle('overflow-hidden', filter), document.body.classList.toggle('pr-4', filter)">
  <button x-on:click="filter = true" class="absolute z-20 top-8 left-4">
    <x-tabler-adjustments class="w-8 h-8 text-gray-600 stroke-current" />
  </button>

  <div :class="{'translate-x-0': filter, '-translate-x-full' : !filter}"
    class="fixed inset-0 z-40 w-full h-full min-h-screen overflow-y-auto transition-transform duration-300 ease-in-out transform translate-x-0 bg-white max-w-screen scrollbar overscroll-x-none">

    <button x-on:click="filter = false" class="sticky top-0 left-0 z-40 pt-4 pl-3">
      <x-tabler-chevron-left class="text-gray-500 stroke-current w-7 h-7" />
    </button>

    <div class="px-12 pb-28">
      <x-filters :minPrice="$minPrice" :maxPrice="$maxPrice" :minRange="$minRange" :maxRange="$maxRange"
        :attributesRanges="$attributesRanges" :brands="$brands" :showPromoF="$showPromoF" :catalogId="$catalogId" />

      <div class="pt-6">
        <button
          class="inline-block w-full px-3 py-2 font-bold text-gray-600 bg-gray-100 border border-gray-200 md:text-sm rounded-xl hover:bg-gray-200"
          wire:click.debounce.1000="resetFilters(), $render" wire:loading.attr="disabled">
          Сбросить фильтры
        </button>
      </div>
    </div>

  </div>
</div>
