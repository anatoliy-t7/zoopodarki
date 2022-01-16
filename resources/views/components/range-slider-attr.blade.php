<div x-data="rangeAttr{{ $keyRange }}" x-init="moveThumbAttr" @reset-range-attr.window="reset()">
  <div class="flex flex-col items-center w-full select-none">
    <div class="flex items-center justify-between w-full pb-4">
      <div x-text="minrange">
      </div>
      <div x-text="maxrange">
      </div>
    </div>
    <div class="relative w-full"
      @mouseup="$wire.emit('updatedMinMaxRange', minrange, maxrange, {{ $keyRange }}, {{ $idRange }})"
      @touchend="$wire.emit('updatedMinMaxRange', minrange, maxrange, {{ $keyRange }}, {{ $idRange }})">
      <label>
        <input type="range" step="1" :min="min" :input="moveThumbAttr" :max="max" x-model="minrange"
          class="absolute z-30 w-full h-6 opacity-0 appearance-none cursor-pointer pointer-events-none">
      </label>
      <label>
        <input type="range" step="1" :min="min" :input="moveThumbAttr" :max="max" x-model="maxrange"
          class="absolute z-30 w-full h-6 opacity-0 appearance-none cursor-pointer pointer-events-none">
      </label>
      <div class="relative z-10 h-1">
        <div class="absolute inset-0 z-10 bg-gray-200 rounded-md"></div>
        <div class="absolute top-0 bottom-0 z-20 bg-yellow-300 rounded-md "
          x-bind:style="'right:'+range+'%; left:'+minthumb+'%'"></div>
        <div class="relative mr-5">
          <div class="absolute top-0 z-20 w-5 h-5 -mt-2 bg-yellow-400 rounded-full shadow-lg"
            x-bind:style="'left: '+minthumb+'%'"></div>

          <div class="absolute top-0 z-20 w-5 h-5 -mt-2 bg-yellow-400 rounded-full shadow-lg"
            x-bind:style="'left: '+maxthumb+ '%'"></div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('rangeAttr{{ $keyRange }}', () => ({
          minrange: {{ $minRange }},
          maxrange: {{ $maxRange }},
          min: {{ $minRange }},
          max: {{ $maxRange }},
          minthumb: {{ $minRange }},
          maxthumb: {{ $maxRange }},
          range: 100,
          moveThumbAttr() {
            this.minrange = Math.min(this.minrange, this.maxrange);
            this.maxrange = Math.max(this.maxrange, this.minrange);

            this.maxthumb = ((this.maxrange - this.min) / (this.max - this.min)) * 100;
            this.minthumb = ((this.minrange - this.min) / (this.max - this.min)) * 100;

            if (this.minthumb >= this.maxthumb) {
              this.maxrange = this.maxrange + 1;
              this.maxrange >= this.max ? this.max : this.maxrange;
              this.minrange >= this.maxrange ? this.minrange - 1 : this.minrange;
            }

            this.range = 100 - (((this.maxrange - this.min) / (this.max - this.min)) * 100);
          },
          reset() {
            this.minrange = {{ $minRange }};
            this.maxrange = {{ $maxRange }};
            this.min = {{ $minRange }};
            this.max = {{ $maxRange }};
            this.minthumb = {{ $minRange }};
            this.maxthumb = {{ $maxRange }};
          }
        }))
      })
    </script>

  </div>
</div>
