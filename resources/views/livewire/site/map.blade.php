<div x-data="mapToggle" x-on:init-map.once.window="initMap()" class="w-full">

  <div class="block w-full min-h-full overflow-y-auto md:overflow-hidden md:flex ">

    <div wire:ignore class="w-full md:w-8/12 block-map">
      <div class="h-full">
        <div id="map" class="w-full overflow-hidden block-map rounded-l-xl">
        </div>
      </div>
    </div>

    <div class="w-full h-full pt-4 pb-4 md:pt-10 md:w-4/12 block-map">
      <div class="flex flex-col h-full overflow-y-auto divide-y divide-gray-100 scrollbar">
        @foreach ($addresses as $address)
          <div wire:key="{{ $loop->index }}"
            x-on:click="showOnMap([{{ $address['lat'] }}, {{ $address['lng'] }}],  {{ $loop->index }}, {{ $address['id'] }})"
            :class="{ 'bg-gray-100': store === {{ $address['id'] }} }"
            class="block px-5 py-2 space-y-2 text-sm cursor-pointer hover:bg-gray-50">
            <div class="text-gray-400">
              <x-tabler-map-pin class="inline w-5 h-5 mr-1 stroke-current" />
              {{ $address['metro'] }},
            </div>
            <div> {{ $address['adr'] }}</div>


            @if ($checkout)
              <div x-cloak x-show="showButton === {{ $address['id'] }}" x-transition>
                <button wire:click="showStore({{ $address['id'] }} )"
                  class="text-white bg-blue-500 btn hover:bg-blue-600">
                  Заберу отсюда
                </button>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>

  </div>

  @push('header-meta')
    <link rel="dns-prefetch" href="//api-maps.yandex.ru">
    <link rel="preload"
      href="https://yastatic.net/s3/front-maps-static/maps-front-jsapi-v2-1/2.1.79-29/build/release/full-d1de67c44ff77d445058e8457ca6578da7094d3a.js"
      as="script">
  @endpush

  <script>
    var clusterer;
    var myGeoObjects;

    document.addEventListener('alpine:initializing', () => {
      Alpine.data('mapToggle', () => ({
        tab: 'list',
        showButton: 0,
        store: @entangle('storeId'),

        showOnMap(coord, index, storeId) {
          this.showButton = storeId;
          map.setCenter(coord, 16);

          var objectState = clusterer.getObjectState(myGeoObjects[index]);
          if (objectState.isClustered) {
            objectState.cluster.state.set('activeObject', myGeoObjects[index]);
            clusterer.balloon.open(objectState.cluster);
          } else if (objectState.isShown) {
            myGeoObjects[index].balloon.open();
          }

        },

        initMap() {
          const script = document.createElement('script');
          script.src = 'https://api-maps.yandex.ru/2.1/?apikey=cd408cfa-b066-4543-8d65-dc5be38d9c48&lang=ru_RU';
          document.head.appendChild(script);

          script.addEventListener('load', function() {
            ymaps.ready(init);
          });

          function init() {
            // Создание карты.
            map = new ymaps.Map("map", {
              center: [59.938951, 30.315635],
              zoom: 12,
              controls: ['zoomControl', 'fullscreenControl']
            }, {
              searchControlProvider: 'yandex#search'
            });

            setData();
          }

          function setData() {

            data = @json($addresses);

            myGeoObjects = data.map(item => {
              return new ymaps.GeoObject({
                geometry: {
                  type: "Point",
                  coordinates: [item.lat, item.lng]
                },
                properties: {
                  clusterCaption: item.adr,
                  balloonContentBody: [
                    '<div>', '<b>Адрес: </b> ' + item.adr + '</div>',
                    '<div>', '<b>Телефон: </b> ' + item.tel + '</div>',
                    '<div>', '<b>Время работы: </b> ' + item.time + '</div>',
                  ].join('')
                }
              }, {
                iconLayout: 'default#image',
                iconImageHref: '/assets/img/marker.svg',
                iconImageSize: [24, 24],
                iconImageOffset: [-12, -12]
              });

            });

            clusterer = new ymaps.Clusterer({
              preset: 'islands#invertedVioletClusterIcons',
              clusterIcons: [{
                href: '/assets/img/marker.svg',
                size: [36, 36],
                offset: [-18, -18]
              }],
              clusterDisableClickZoom: false,
              clusterBalloonContentLayoutWidth: 400,
              clusterBalloonLeftColumnWidth: 160,
              clusterHideIconOnBalloonOpen: false,
              geoObjectHideIconOnBalloonOpen: false
            });
            clusterer.add(myGeoObjects);
            map.geoObjects.add(clusterer);
            map.setBounds(clusterer.getBounds(), {
              checkZoomRange: true
            })


          }
        }
      }))
    })
  </script>

</div>
