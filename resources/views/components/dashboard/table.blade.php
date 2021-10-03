<div class="relative z-20">

  <x-loader
    wire:target="search, filteredByCategory, showTrashed, productsWithoutDescription, productsWithoutImage, variationMoreOne, itemsPerPage, sortBy, gotoPage, nextPage, previousPage, available, remove" />

  <div
    class="relative z-20 h-full min-w-full overflow-y-auto text-base align-middle bg-white lg:overflow-x-auto sm:rounded-xl h-table scrollbar">

    <table class="min-w-full bg-white divide-y divide-pink-50">
      <thead>
        <tr>
          {{ $head }}
        </tr>
      </thead>

      <tbody id="top" class="bg-white divide-y divide-pink-50">
        {{ $body }}
      </tbody>
    </table>

  </div>
</div>