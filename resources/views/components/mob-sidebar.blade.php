<div x-cloak x-data="{ filter: false}"
  x-effect="document.body.classList.toggle('overflow-hidden', filter), document.body.classList.toggle('pr-4', filter)">
  <button x-on:click="filter = true" class="absolute z-20 top-8 left-4">
    <x-tabler-adjustments class="text-gray-500 stroke-current w-7 h-7" />
  </button>

  <div :class="{'translate-x-0': filter, '-translate-x-full' : !filter}"
    class="fixed inset-0 z-40 w-full h-full min-h-screen overflow-y-auto transition-transform duration-300 ease-in-out transform translate-x-0 bg-white max-w-screen scrollbar overscroll-x-none">

    <button x-on:click="filter = false" class="sticky top-0 left-0 z-40 pt-4 pl-3">
      <x-tabler-chevron-left class="text-gray-500 stroke-current w-7 h-7" />
    </button>

    <div class="px-12 pb-28">
      <x-filters :minPrice="$minPrice" :maxPrice="$maxPrice" :attributesRanges="$attributesRanges" :brands="$brands"
        :showPromoF="$showPromoF" />
    </div>

  </div>
</div>
