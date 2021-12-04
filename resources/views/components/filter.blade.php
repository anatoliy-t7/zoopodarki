 <div wire:ignore x-data="searchAttribute{{ $idf }}">
               <div class="pb-2">
                 @if (count($items) >= 10)
                   <input x-ref="searchField" x-model="search"
                     x-on:keydown.window.prevent.slash="$refs.searchField.focus()" placeholder="Поиск" type="search"
                     class="h-8 text-xs placeholder-gray-400 bg-gray-50 field" />
                 @endif
               </div>

               <div class="h-full py-1 space-y-3 overflow-y-auto scrollbar" style="max-height: 248px;">
                 <template x-for="(item, index) in filteredAttribute" :key="index" hidden>
                   <label for="item.id" class="container-checkbox">
                     <span class="text-sm" x-text="item.name"></span>
                     <input id="item.id" :value="item.id" type="checkbox" x-model.number.debounce.700="attributeFilter">
                     <span class="checkmark"></span>
                   </label>
                 </template>
               </div>

             <script>
               document.addEventListener('alpine:init', () => {
                 Alpine.data('searchAttribute{{ $idf }}', () => ({
                   search: "",
                   attributerData: @json($items),
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
