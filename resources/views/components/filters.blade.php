 <div class="flex-col px-1 space-y-6 ">

   <div wire:loading
     class="absolute top-0 bottom-0 left-0 right-0 z-30 w-full h-full bg-gray-100 bg-opacity-75 rounded-2xl">
   </div>

   @if ($maxPrice)
     <div wire:ignore>
       <x-range-slider :minRange="$minRange" :maxRange="$maxRange" :minPrice="$minPrice" :maxPrice="$maxPrice" />
     </div>
   @endif

   @if ($catalogId === 14)
     <div class="py-1 space-y-3">
       <label class="container-checkbox">
         <span for="shelterUrgentlyRequired" class="text-sm">Срочно требуется</span>
         <input value="1" wire:model="shelterUrgentlyRequired" id="shelterUrgentlyRequired" type="checkbox">
         <span class="checkmark"></span>
       </label>
       <label class="container-checkbox">
         <span for="shelterMarkdown" class="text-sm">Уценка</span>
         <input value="1" wire:model="shelterMarkdown" id="shelterMarkdown" type="checkbox">
         <span class="checkmark"></span>
       </label>
     </div>
   @else
     @if ($attributesRanges)
       <div wire:ignore>
         @forelse ($attributesRanges as $key => $attrRange)
           <div class="pt-4 pb-3 font-bold">{{ $attrRange['name'] }}</div>
           <x-range-slider-attr :minRange="$attrRange['min']" :maxRange="$attrRange['max']" :idRange="$key" />
         @empty
         @endforelse
       </div>
     @endif

     @if ($showPromoF)
       <div class="pt-4">
         <div class="container-checkbox">
           <label for="promoF" class="text-base sm:text-sm">Акции</label>
           <input id="promoF" wire:model="promoF" type="checkbox">
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
             <label :for="'brand'+item.id" class="container-checkbox">
               <span class="text-base sm:text-sm" x-text="item.name"></span>
               <input :id="'brand'+item.id" :value="item.id" type="checkbox" x-model.number="brandsF">
               <span class="checkmark"></span>
             </label>
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

     <div wire:ignore x-data="listAttributes">
       <div class="space-y-6">
         <template x-for="(attribute, index) in allAttributes" :key="attribute.id" hidden>
           <div class="space-y-3">
             <div class="font-bold" x-text="attribute.name"></div>

             <div x-data="{
                  items: attribute.items,
                   search: '',
                   get filteredAttribute() {
                     if (this.search === '') {
                       return this.items;
                     }
                     return this.items.filter((item) => {
                       return item.name
                         .toLowerCase()
                         .includes(this.search.toLowerCase());
                     })
                   },
                }">
               <div x-show="attribute.items.length >= 10" class="pb-2">
                 <input x-ref="searchField" x-model="search"
                   x-on:keydown.window.prevent.slash="$refs.searchField.focus()" placeholder="Поиск" type="search"
                   class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />
               </div>

               <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">
                 <template x-for="(item, index) in filteredAttribute" :key="item.id" hidden>
                   <label :for="'attr'+item.id" class="container-checkbox">
                     <span class="text-base sm:text-sm" x-text="item.name"></span>
                     <input :id="'attr'+item.id" :value="item.id" type="checkbox"
                       x-model.number.debounce.700="attributeFilter">
                     <span class="checkmark"></span>
                   </label>
                 </template>
               </div>

             </div>

           </div>
         </template>
       </div>

       <script>
         document.addEventListener('alpine:init', () => {
           Alpine.data('listAttributes', () => ({
             attributeFilter: @entangle('attrsF'),
             allAttributes: @entangle('allAttributes'),
           }))
         });
       </script>
     </div>

     <div class="space-y-3">
       <div class="font-bold">Наличие в магазинах</div>
       <div class="flex flex-col space-y-3">
         <label class="inline-flex items-center space-x-2">
           <input type="radio" wire:model="stockF" value="3" name="stockF" class="w-5 h-5 text-orange-400 form-radio"
             checked><span class="text-base text-gray-700 sm:text-sm">Все товары</span>
         </label>
         <label class="inline-flex items-center space-x-2">
           <input type="radio" wire:model="stockF" value="2" name="stockF"
             class="w-5 h-5 text-orange-400 form-radio"><span class="text-base text-gray-700 sm:text-sm ">В
             наличии</span>
         </label>
         <label class="inline-flex items-center space-x-2">
           <input type="radio" wire:model="stockF" value="1" name="stockF"
             class="w-5 h-5 text-orange-400 form-radio"><span class="text-base text-gray-700 sm:text-sm">Под
             заказ</span>
         </label>
       </div>
     </div>
   @endif

 </div>
