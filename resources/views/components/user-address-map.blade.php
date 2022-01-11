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
      <div x-data="userMapAddress" @set-coordinates.window="setCoordinates(event)" @set-zone.window="setZone(event)"
        @set-address.window="setExistsAddress(event)" class="w-full"
        @init-map-user-address.window="initMap(event)" @reset-delivery-place.window="reset(event)">

        <div class="block w-full min-h-full gap-4 overflow-hidden md:flex">

          <div class="w-full pt-1">
            <div x-show="showFields" x-transition.duration.600ms class="flex gap-4 px-1 pb-4">
              <div class="w-5/12">
                <input class="field" type="text" autofocus x-model="deliveryPlace.address" readonly />
              </div>
              <div class="w-4/12">
                <input name="extra" x-model="deliveryPlace.extra" class="field " type=" text"
                  placeholder="подъезд, домофон и тд." />
              </div>
              <div class="flex w-3/12 gap-4">
                <button aria-label="Добавить адрес" id="addAddress" @click="$wire.call('addNewAddress', deliveryPlace)"
                  :disabled="deliveryPlace.address === null"
                  class="flex items-center justify-center w-full gap-2 px-3 py-2 font-bold text-white bg-blue-400 border border-blue-400 cursor-pointer disabled:text-gray-500 disabled:border-gray-200 disabled:bg-gray-50 rounded-xl hover:bg-blue-500 ">
                  <x-tabler-circle-plus class="w-8 h-8 stroke-current " />
                  <div>Добавить</div>
                </button>
                <div x-show="deliveryPlace.id !== null">
                  <button aria-label="Удалить" @click="$wire.call('removeAddress', deliveryPlace.id)"
                    class="flex items-center justify-center px-3 py-2 font-bold text-gray-400 border border-gray-300 cursor-pointer rounded-xl hover:text-red-500">
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
              showFields: false,
              deliveryPlace: {
                id: null,
                address: null,
                extra: null,
                zip: null,
                lat: null,
                lng: null,
                delivery_zone: 0,
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
                  var myMap = new ymaps.Map('mapZones', {
                      center: [59.91995, 30.470315],
                      zoom: 9,
                      controls: ['geolocationControl', 'searchControl', 'fullscreenControl']
                    }),
                    deliveryPoint = new ymaps.GeoObject({
                      geometry: {
                        type: 'Point'
                      },
                      properties: {
                        iconCaption: 'Адрес'
                      }
                    }, {
                      preset: 'islands#blackDotIconWithCaption',
                      draggable: true,
                      iconCaptionMaxWidth: '215'
                    }),
                    searchControl = myMap.controls.get('searchControl');
                  searchControl.options.set({
                    noPlacemark: true,
                    placeholderContent: 'Введите адрес доставки',
                    boundedBy: [
                      [59.744310, 29.609402],
                      [60.158246, 30.654747]
                    ],
                  });
                  myMap.geoObjects.add(deliveryPoint);

                  function onZonesLoad(json) {
                    // Добавляем зоны на карту.
                    var deliveryZones = ymaps.geoQuery(json).addToMap(myMap);
                    // Задаём цвет и контент балунов полигонов.
                    deliveryZones.each(function(obj) {
                      obj.options.set({
                        fillColor: obj.properties.get('fill'),
                        fillOpacity: obj.properties.get('fill-opacity'),
                        strokeColor: obj.properties.get('stroke'),
                        strokeWidth: obj.properties.get('stroke-width'),
                        strokeOpacity: obj.properties.get('stroke-opacity')
                      });
                      obj.properties.set('balloonContentHeader', obj.properties.get('header'));
                      obj.properties.set('balloonContent', obj.properties.get('description'));
                    });

                    // Проверим попадание результата поиска в одну из зон доставки.
                    searchControl.events.add('resultshow', function(e) {
                      highlightResult(searchControl.getResultsArray()[e.get('index')]);
                    });

                    // Проверим попадание метки геолокации в одну из зон доставки.
                    myMap.controls.get('geolocationControl').events.add('locationchange', function(e) {
                      highlightResult(e.get('geoObjects').get(0));
                    });

                    // При перемещении метки сбрасываем подпись, содержимое балуна и перекрашиваем метку.
                    deliveryPoint.events.add('dragstart', function() {
                      deliveryPoint.properties.set({
                        iconCaption: '',
                        balloonContent: ''
                      });
                      deliveryPoint.options.set('iconColor', 'black');
                    });

                    // По окончании перемещения метки вызываем функцию выделения зоны доставки.
                    deliveryPoint.events.add('dragend', function() {
                      highlightResult(deliveryPoint);
                    });

                    function highlightResult(obj) {

                      // Сохраняем координаты переданного объекта.
                      var coords = obj.geometry.getCoordinates(),
                        // Находим полигон, в который входят переданные координаты.
                        polygon = deliveryZones.searchContaining(coords).get(0);

                      if (polygon) {

                        // Увеличиваем прозрачность всех полигонов, кроме того, в который входят переданные координаты.
                        deliveryZones.setOptions('fillOpacity', 0.1);
                        polygon.options.set('fillOpacity', 0.4);

                        const setZone = new CustomEvent('set-zone', {
                          detail: {
                            zone: polygon.properties.get('zone'),
                          }
                        });
                        window.dispatchEvent(setZone);

                        // Перемещаем метку с подписью в переданные координаты и перекрашиваем её в цвет полигона.
                        deliveryPoint.geometry.setCoordinates(coords);
                        deliveryPoint.options.set('iconColor', polygon.properties.get('fill'));
                        if (typeof(obj.getThoroughfare) === 'function') {
                          setData(obj);
                        } else {
                          ymaps.geocode(coords, {
                            results: 1
                          }).then(function(res) {
                            var obj = res.geoObjects.get(0);
                            setData(obj);
                          });
                        }
                      } else {
                        // Если переданные координаты не попадают в полигон, то задаём стандартную прозрачность полигонов.
                        deliveryZones.setOptions('fillOpacity', 0.4);
                        // Перемещаем метку по переданным координатам.
                        deliveryPoint.geometry.setCoordinates(coords);
                        // Задаём контент балуна и метки.
                        deliveryPoint.properties.set({
                          iconCaption: 'Пожалуйста выберите адрес в пределах СПБ КАД',
                          balloonContent: 'Дорогой покупатель, в настоящее время мы доставляем только в пределах СПБ КАД, но совсем скоро начнем доставку и за его пределами!',
                          balloonContentHeader: ''
                        });
                        // Перекрашиваем метку в чёрный цвет.
                        deliveryPoint.options.set('iconColor', 'black');
                        const reset = new CustomEvent('reset-delivery-place');
                        window.dispatchEvent(reset);
                      }

                      function setData(obj) {
                        var address = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
                        if (address.trim() === '') {
                          address = obj.getAddressLine();
                        }
                        var price = polygon.properties.get('description');
                        deliveryPoint.properties.set({
                          iconCaption: address,
                          balloonContent: address,
                        });
                        const setCoordinates = new CustomEvent('set-coordinates', {
                          detail: {
                            address: obj.properties.get('name'),
                            coordinates: obj.geometry.getCoordinates(),
                            zip: obj.properties.get('metaDataProperty').GeocoderMetaData.Address.postal_code
                          }
                        });
                        window.dispatchEvent(setCoordinates);
                      }
                    }
                  }

                  fetch('/json/delivery_zones.json')
                    .then(response => response.json())
                    .then(data => onZonesLoad(data));
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
                  delivery_zone: 0,
                };
              },
              setCoordinates(coordinates) {
                console.log(coordinates.detail)
                this.deliveryPlace.address = coordinates.detail.address;
                this.deliveryPlace.lat = coordinates.detail.coordinates[0];
                this.deliveryPlace.lng = coordinates.detail.coordinates[1];
                this.deliveryPlace.zip = coordinates.detail.zip;
                this.showFields = true;
              },
              setZone(zone) {
                this.deliveryPlace.delivery_zone = zone.detail.zone;
              },
              setExistsAddress(address) {
                this.deliveryPlace = address.detail;
                const openMap = new CustomEvent('open-modal-user-address-map');
                window.dispatchEvent(openMap);
                this.initMap();
                this.showFields = true;
              }

            }))
          })
        </script>

      </div>
    </x-slot>

  </x-modal>
</div>
