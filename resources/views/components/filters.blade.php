 <div class="flex-col px-1 space-y-6">

   <div wire:loading
     class="absolute top-0 bottom-0 left-0 right-0 z-30 w-full h-full bg-gray-100 bg-opacity-75 rounded-2xl">
   </div>

    <div wire:ignore>
      <x-range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" />
    </div>

   <div>
     @forelse ($attributesRanges as $key => $attrRange)
       <div class="pt-6 pb-5 text-sm font-bold">{{ $attrRange['name'] }}</div>
       <x-range-slider-attr :minRange="$attrRange['min']" :maxRange="$attrRange['max']" :idRange="$key" />
     @empty
     @endforelse
   </div>

   <div>
     @if ($showPromoF)
       <div class="pt-4">
         <div class="container-checkbox">
           <label>Акции</label>
           <input wire:model="promoF" type="checkbox">
           <span class="checkmark"></span>
         </div>
       </div>
     @endif
   </div>

   <div>
     @if ($brands)
       <div x-data="searchBrand" class="space-y-4">
         <div class="font-bold">Бренд</div>

         <div>
           @if ($brands->count() > 10)
             <input x-ref="searchField" x-model="search" x-on:keydown.window.prevent.slash="$refs.searchField.focus()"
               placeholder="Поиск" type="search" class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />
           @endif
         </div>

         <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">

           <template x-for="(item, index) in filteredBrands" :key="item.id" hidden>
             <div class=" container-checkbox">
               <span class="text-sm" x-text="item.name"></span>
               <input :value="item.id" type="checkbox" x-model.number="brandsF">
               <span class="checkmark"></span>
             </div>
           </template>

           <script>
             document.addEventListener('alpine:init', () => {
               Alpine.data('searchBrand', () => ({
                 search: "",
                 brandsF: @entangle('brandsF'),
                 brandsData: @json($brands),
                 get filteredBrands() {
                   if (this.search === "") {
                     return this.brandsData;
                   }
                   return this.brandsData.filter((item) => {
                     return item.name
                       .toLowerCase()
                       .includes(this.search.toLowerCase());
                   });
                 },
               }))
             });
           </script>
         </div>
       </div>
     @endif
   </div>

   <div x-data="{attributeFilter: @entangle('attrsF')}" class="space-y-6">
     @foreach ($allAttributes as $attr)
       <div wire:key="{{ $attr->id }}">
         @if ($attr->items->count() > 0)
              <div class="space-y-3">
                <div class="font-bold">{{ $attr->name }}</div>
                <x-filter :items="$attr->items"  :idf="$attr->id"/>
              </div>
         @endif
       </div>
     @endforeach
   </div>
 </div>
