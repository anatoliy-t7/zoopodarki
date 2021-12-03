 <div class="flex-col px-1 space-y-6">

   <div wire:loading
     class="absolute top-0 bottom-0 left-0 right-0 z-30 w-full h-full bg-gray-100 bg-opacity-75 rounded-2xl">
   </div>

   <x-range-slider :minPrice="$minPrice" :maxPrice="$maxPrice" />

   <div>
     @forelse ($attributesRanges as $key => $attrRange)
       <div class="pt-6 pb-5 text-sm font-bold">{{ $attrRange['name'] }}</div>
       <x-range-slider-attr :minRange="$attrRange['min']" :maxRange="$attrRange['max']" :idRange="$key" />
     @empty
     @endforelse
   </div>

   @if ($showPromoF)
     <div class="pt-4">
       <div class="container-checkbox">
         <label>Акции</label>
         <input wire:model="promoF" type="checkbox">
         <span class="checkmark"></span>
       </div>
     </div>
   @endif

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

   <div class="space-y-6">
     @if ($attrs)
       @foreach ($attrs as $key => $attr)
         <div wire:key="{{ $attr->id }}" x-data="searchAttribute{{ $attr->id }}">
           <div x-show="filteredAttribute.length >= 1" class="space-y-3">
             <div class="font-bold">{{ $attr->name }} {{ $attr->items->count() }}</div>

             <div>
               @if ($attr->items->count() >= 10)
                 <input x-ref="searchField" x-model="search"
                   x-on:keydown.window.prevent.slash="$refs.searchField.focus()" placeholder="Поиск" type="search"
                   class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />
               @endif
             </div>

             <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">

               <template x-for="(item, index) in filteredAttribute" :key="item.id" hidden>
                 <div class="container-checkbox">
                   <span class="text-sm" x-text="item.name"></span>
                   <input :value="item.id" type="checkbox" x-model.number.debounce.700="attributeFilter">
                   <span class="checkmark"></span>
                 </div>
               </template>

               <script>
                 document.addEventListener('alpine:init', () => {
                   Alpine.data('searchAttribute' + {{ $attr->id }}, () => ({
                     search: "",
                     attributeFilter: @entangle('attrsF'),
                     attributerData: @json($attr->items),
                     get filteredAttribute() {
                       if (this.search === "") {
                         return this.attributerData;
                       }
                       return this.attributerData.filter((item) => {
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
         </div>
       @endforeach
     @endif
   </div>

   <div class="space-y-3">
     <div class="font-bold">Наличие в магазинах</div>
     <div class="flex flex-col space-y-3">
       <label class="inline-flex items-center space-x-2">
         <input type="radio" wire:model="stockF" value="2" name="stockF" class="w-5 h-5 text-orange-400 form-radio"
           checked><span class="text-sm text-gray-700 ">Все товары</span>
       </label>
       <label class="inline-flex items-center space-x-2">
         <input type="radio" wire:model="stockF" value="1" name="stockF"
           class="w-5 h-5 text-orange-400 form-radio"><span class="text-sm text-gray-700 ">В наличии</span>
       </label>
       <label class="inline-flex items-center space-x-2">
         <input type="radio" wire:model="stockF" value="0" name="stockF"
           class="w-5 h-5 text-orange-400 form-radio"><span class="text-sm text-gray-700">Под заказ</span>
       </label>
     </div>
   </div>


 </div>
