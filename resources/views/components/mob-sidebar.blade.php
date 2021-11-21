<div x-cloak x-data="{ filter: false}"
  x-effect="document.body.classList.toggle('overflow-hidden', filter), document.body.classList.toggle('pr-4', filter)">
  <button x-on:click="filter = true" class="absolute z-20 right-4 top-2">
    <x-tabler-adjustments class="w-6 h-6 text-gray-500 stroke-current" />
  </button>

  <div :class="{'translate-x-0': filter, '-translate-x-full' : !filter}"
    class="fixed inset-0 z-40 w-full h-full min-h-screen overflow-y-auto transition-transform duration-300 ease-in-out transform translate-x-0 bg-white max-w-screen scrollbar overscroll-x-none">

    <button x-on:click="filter = false" class="sticky top-0 right-0 z-40 pt-3 pl-2">
      <x-tabler-chevron-left class="w-6 h-6 text-gray-500 stroke-current" />
    </button>

    <div class="px-8 pb-8">
      <x-filters :minPrice="$minPrice" :maxPrice="$maxPrice" :attributesRanges="$attributesRanges" :brands="$brands"
        :attrs="$attrs" />
    </div>

  </div>
</div>
