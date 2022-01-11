<div>
  <x-modal :width="'screen-lg'" :modalId="'-user-address-map'">
    <x-slot name="button">
      <div @click.once="$dispatch('init-map-user-address'), $dispatch('reset-delivery-place')"
        class="flex items-center justify-center w-full gap-2 px-3 py-3 space-x-1 border border-gray-200 border-dashed cursor-pointer hover:border-gray-200 rounded-xl hover:bg-gray-50">
        <x-tabler-circle-plus class="w-8 h-8 text-gray-400 stroke-current stroke-1 hover:text-gray-600" />
        <div>Добавить</div>
      </div>
    </x-slot>

    <x-slot name="content">
      <div x-data="userMapAddress" @set-coordinates.window="setCoordinates(event)"
        @set-address.window="setExistsAddress(event)" class="w-full"
        @init-map-user-address.window="initMap(event)" @reset-delivery-place.window="reset(event)">

        <div class="block w-full min-h-full gap-4 overflow-hidden md:flex">

          <div class="w-full pt-1">
            <div class="flex gap-4 px-1 pb-4 ">
              <div class="w-7/12">
                <input class="field @error('deliveryPlace.address') border-red-500 border @enderror" type="text"
                  autofocus x-model="deliveryPlace.address" id="suggest" placeholder="Новый адрес" />
                <div id="message" class="pt-1 text-sm text-red-500"></div>
                @error('deliveryPlace.address')
                  <p class="text-xs italic text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="w-2/12">
                <input name="extra" x-model="deliveryPlace.extra"
                  class="field @error('deliveryPlace.extra') border-red-500 border @enderror" type=" text"
                  placeholder="подъезд, домофон и тд." />
                @error('deliveryPlace.extra')
                  <p class="text-xs italic text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="flex w-auto gap-2">
                <button aria-label="Добавить адрес" id="addAddress" @click="$wire.call('addNewAddress', deliveryPlace)"
                  disabled
                  class="flex items-center justify-center w-full gap-2 px-3 py-2 mb-1 font-bold text-white bg-blue-400 border border-blue-400 cursor-pointer disabled:text-gray-500 disabled:border-gray-200 disabled:bg-gray-50 rounded-xl hover:bg-blue-500 ">
                  <x-tabler-circle-plus class="w-8 h-8 stroke-current hover:text-gray-600" />
                  <div>Добавить</div>
                </button>
                <div x-show="deliveryPlace.id !== null">
                  <button aria-label="Удалить" @click="$wire.call('removeAddress', deliveryPlace.id)"
                    class="flex items-center justify-center px-3 py-3 mb-1 font-bold text-gray-400 border border-gray-300 cursor-pointer rounded-xl hover:text-red-500">
                    <x-tabler-trash class="w-6 h-6 stroke-current " />
                  </button>
                </div>
              </div>
            </div>
            <div class="h-full">
              <div id="mapZones" class="w-full overflow-hidden h-96 rounded-xl">
              </div>
            </div>
          </div>

        </div>
        @push('header-meta')
          <link rel="dns-prefetch" href="//api-maps.yandex.ru">
        @endpush

        <script>
          var clusterer;
          var myGeoObjects;

          document.addEventListener('alpine:init', () => {
            Alpine.data('userMapAddress', () => ({
              key: '{{ config('constants.yandex_map_key') }}',
              message: null,
              deliveryPlace: {
                id: null,
                address: null,
                extra: null,
                zip: null,
                lat: null,
                lng: null,
                delivery_zone: null,
              },
              script: null,
              showOnMap(coord, index, storeId) {
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

                console.log();
                if (!window.script) {
                  const script = document.createElement('script');
                  script.src =
                    'https://api-maps.yandex.ru/2.1/?apikey=' + this.key + '&lang=ru_RU';
                  document.head.appendChild(script);
                  script.addEventListener('load', () => {
                    ymaps.ready(init);
                  });
                  window.script = script;
                } else {
                  ymaps.ready(init);
                }

                function init() {
                  createMap();
                  var suggestView = new ymaps.SuggestView('suggest', {
                      results: 10,
                      boundedBy: [
                        [59.744310, 29.609402],
                        [60.158246, 30.654747]
                      ],
                    }),
                    map,
                    placemark;

                  suggestView.events.add("select", function(e) {
                    geocode(e.get('item').value);
                  })

                  function geocode(value) {
                    ymaps.geocode(value).then(function(res) {
                        var obj = res.geoObjects.get(0),
                          error, hint;
                        if (obj) {
                          switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                            case 'exact':
                              break;
                            case 'number':
                            case 'near':
                            case 'range':
                              error = 'Неточный адрес, требуется уточнение.';
                              hint = ' Уточните номер дома';
                              break;
                            case 'street':
                              error = 'Неполный адрес, требуется уточнение.';
                              hint = ' Уточните номер дома';
                              break;
                            case 'other':
                            default:
                              error = 'Неточный адрес, требуется уточнение.';
                              hint = ' Уточните адрес';
                          }
                        } else {
                          error = 'Адрес не найден';
                          hint = ' Уточните адрес';
                        }
                        if (error) {
                          document.getElementById('message').innerText = error + hint;
                          document.getElementById('addAddress').addAttribute('disabled');
                        } else {
                          showResult(obj);
                        }
                      },
                      function(e) {
                        document.getElementById('addAddress').addAttribute('disabled');
                        console.log(e)
                      })
                  }

                  function showResult(obj) {
                    document.getElementById('addAddress').removeAttribute('disabled');
                    document.getElementById('message').innerText = '';
                    const setCoordinates = new CustomEvent('set-coordinates', {
                      detail: {
                        address: obj.properties.get('name'),
                        coordinates: obj.geometry.getCoordinates(),
                        zip: obj.properties.get('metaDataProperty').GeocoderMetaData.Address.postal_code
                      }
                    });
                    window.dispatchEvent(setCoordinates);

                    var mapContainer = document.getElementById('map');
                    bounds = obj.properties.get('boundedBy');
                    mapState = ymaps.util.bounds.getCenterAndZoom(
                      bounds,
                      [mapContainer.offsetWidth, mapContainer.offsetHeight],
                    );
                    address = [obj.getCountry(), obj.getAddressLine()].join(', ');
                    shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
                    mapState.controls = ['fullscreenControl', 'geolocationControl']
                    createMap(mapState, shortAddress);
                  }

                  function createMap(state, caption) {
                    if (!map) {
                      map = new ymaps.Map('mapZones', {
                        center: [59.938951, 30.315635],
                        zoom: 9,
                        controls: ['fullscreenControl', 'geolocationControl']
                      });
                      deliveryPoint = new ymaps.GeoObject({
                          geometry: {
                            type: 'Point'
                          },
                          properties: {
                            iconCaption: 'Адрес'
                          }
                        }, {
                          preset: 'islands#blackDotIconWithCaption',
                          iconCaptionMaxWidth: '215'
                        }),

                        map.geoObjects.add(deliveryPoint);
                      // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
                    } else {
                      map.setCenter(state.center, 16);
                      deliveryPoint.geometry.setCoordinates(state.center);
                      deliveryPoint.properties.set({
                        iconCaption: caption,
                      });
                    }
                  }


                }
              },
              reset() {
                this.deliveryPlace = {
                  id: null,
                  address: null,
                  extra: null,
                  zip: null,
                  lat: null,
                  lng: null,
                  delivery_zone: null,
                };
              },
              setCoordinates(coordinates) {
                this.deliveryPlace.address = coordinates.detail.address;
                this.deliveryPlace.lat = coordinates.detail.coordinates[0];
                this.deliveryPlace.lng = coordinates.detail.coordinates[1];
                this.deliveryPlace.zip = coordinates.detail.zip;
              },
              setExistsAddress(address) {
                this.deliveryPlace = address.detail;
                const openMap = new CustomEvent('open-modal-user-address-map');
                window.dispatchEvent(openMap);
                this.initMap();
                document.getElementById('suggest').setAttribute('value', this.deliveryPlace.address);
              }

            }))
          })
        </script>

      </div>
    </x-slot>

  </x-modal>
</div>
