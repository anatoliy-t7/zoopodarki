@section('title')
  Главная страница
@endsection
<div>

  <div class="flex items-center justify-start pb-6 space-x-4">
    <div>
      <a title="Вернуться" href="javascript:%20history.go(-1)" class="text-gray-300 hover:text-gray-500">
        <x-tabler-arrow-left class="w-6 h-6" />
      </a>
    </div>
    <h3 class="text-xl font-bold text-gray-500">Главная страница</h3>
  </div>

  <div x-data="handler" wire:init="sendDataToFrontend" @set-home-page-brand.window="setHomePageBrandFromServer(event)"
    @set-brands.window="setBrandsFromServer(event)" @save.window="saveIt(event)">

    <div class="flex-wrap w-full p-6 space-y-6 bg-white rounded-2xl">
      <div class="space-y-2">
        <label for="brands" class="font-semibold">Бренды <span class="text-xs">(max: <span
              x-text="maxTags"></span>)</span></label>
        <div wire:ignore>
          <input id="brands" name='brands' class="field">
        </div>
      </div>

    </div>
    <div class="flex justify-end pt-4">
      <div>
        <button class="text-white bg-green-500 btn hover:bg-green-600" x-on:click="saveIt()">Save</button>
      </div>
    </div>

    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('handler', () => ({
          brands: null,
          tagifyBrands: null,
          homePageBrands: null,
          maxTags: 7,

          setHomePageBrandFromServer(homePageBrands) {
            this.homePageBrands = null;

            this.homePageBrands = homePageBrands.detail;
            this.homePageBrands = this.homePageBrands.map(({
              id: id,
              name: value
            }) => ({
              id,
              value
            }));

          },

          setBrandsFromServer(brands) {
            this.brands = [];
            this.brands = brands.detail;
            this.brands = this.brands.map(({
              id: id,
              name: value
            }) => ({
              id,
              value
            }));

            this.initBrands();
          },

          initBrands() {
            var inputElm = document.querySelector('input[name=brands]');
            this.tagifyBrands = new Tagify(inputElm, {
              whitelist: this.brands,
              dropdown: {
                classname: "w-full",
                enabled: 0,
                maxItems: 100,
                position: "all",
                closeOnSelect: true,
                highlightFirst: true,
                searchKeys: ["value"],
                fuzzySearch: false,
              },
              addTagOnBlur: false,
              editTags: false,
              maxTags: this.maxTags,
              skipInvalid: true,
              enforceWhitelist: true,
              delimiters: "`",
            });
            this.tagifyBrands.addTags(this.homePageBrands)

            this.tagifyBrands.on('change', e => {

              if (e.detail.value) {
                this.homePageBrands = JSON.parse(e.detail.value)
              } else {
                this.homePageBrands = []
              }

            })
          },

          saveIt() {
            homePageBrands = this.mapItBack();
            window.livewire.emit('save', homePageBrands)
          },

          mapItBack() {
            if (this.homePageBrands !== null) {
              homePageBrands = this.homePageBrands.map(({
                id: id,
                value: name
              }) => ({
                id,
                name
              }));
            } else {
              homePageBrands = [];
            }
            return homePageBrands;
          }
        }))
      })
    </script>

    <script>
      document.addEventListener("keydown", function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
          e.preventDefault();
          var event = new CustomEvent('save');
          window.dispatchEvent(event);
        }
      }, false);
    </script>


    @push('header-css')
      <link href="{{ mix('css/tagify.css') }}" rel="stylesheet">
    @endpush
    @push('header-js')
      <script src="{{ mix('js/tagify.min.js') }}"></script>
    @endpush

  </div>
</div>
