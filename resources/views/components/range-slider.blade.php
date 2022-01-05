<div x-cloak x-data="range" x-init="moveThumb" @reset-range.window="reset()">
  <div class="pb-3 font-bold">Цена, руб.</div>
  <div class="flex flex-col items-center w-full select-none">
    <div class="flex items-center justify-between w-full pb-4">
      <div x-text="minprice">
      </div>
      <div x-text="maxprice">
      </div>
    </div>
    <div class="relative w-full" @mouseup="$wire.emit('updatedMinMaxPrice', minprice, maxprice)"
      @touchend="$wire.emit('updatedMinMaxPrice', minprice, maxprice)">
      <label>
        <input type="range" step="100" :min="min" :input="moveThumb" :max="max" x-model="minprice"
          class="absolute z-30 w-full h-6 opacity-0 appearance-none cursor-pointer pointer-events-none">
      </label>
      <label>
        <input type="range" step="10" :min="min" :input="moveThumb" :max="max" x-model="maxprice"
          class="absolute z-30 w-full h-6 opacity-0 appearance-none cursor-pointer pointer-events-none">
      </label>
      <div class="relative z-10 h-1">
        <div class="absolute inset-0 z-10 bg-gray-200 rounded-md"></div>
        <div class="absolute top-0 bottom-0 z-20 bg-yellow-300 rounded-md select-none"
          x-bind:style="'right:'+range+'%; left:'+minthumb+'%'"></div>
        <div class="relative mr-5 select-none">
          <div class="absolute top-0 z-20 w-5 h-5 -mt-2 bg-yellow-400 rounded-full shadow-lg select-none"
            x-bind:style="'left: '+minthumb+'%'"></div>

          <div class="absolute top-0 z-20 w-5 h-5 -mt-2 bg-yellow-400 rounded-full shadow-lg"
            x-bind:style="'left: '+maxthumb+ '%'"></div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('range', () => ({
          minprice: {{ $minPrice }},
          maxprice: {{ $maxPrice }},
          min: {{ $minRange }},
          max: {{ $maxRange }},
          minthumb: {{ $minPrice }},
          maxthumb: {{ $maxPrice }},
          range: 100,
          moveThumb() {
            this.minprice = Math.min(this.minprice, this.maxprice);
            this.maxprice = Math.max(this.maxprice, this.minprice);

            this.maxthumb = ((this.maxprice - this.min) / (this.max - this.min)) * 100;
            this.minthumb = ((this.minprice - this.min) / (this.max - this.min)) * 100;

            if (this.minthumb >= this.maxthumb) {
              this.maxprice = this.maxprice + 100;
              this.maxprice >= this.max ? this.max : this.maxprice;
              this.minprice >= this.maxprice ? this.minprice - 100 : this.minprice;
            }

            this.range = 100 - (((this.maxprice - this.min) / (this.max - this.min)) * 100);
          },
          reset() {
            this.minprice = {{ $minPrice }};
            this.maxprice = {{ $maxPrice }};
            this.min = {{ $minRange }};
            this.max = {{ $maxRange }};
            this.minthumb = {{ $minPrice }};
            this.maxthumb = {{ $maxPrice }};
          }
        }))
      })
    </script>

  </div>
</div>
