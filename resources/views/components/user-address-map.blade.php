<div x-data="userMapAddress" @set-coordinates.window="setCoordinates(event)">
  <x-modal :width="'screen-lg'">
    <x-slot name="button">
      <div @click.once="initMap()"
        class="flex items-center justify-center w-full gap-2 px-3 py-3 space-x-1 border border-gray-200 border-dashed cursor-pointer hover:border-gray-200 rounded-xl hover:bg-gray-50">
        <x-tabler-circle-plus class="w-8 h-8 text-gray-400 stroke-current stroke-1 hover:text-gray-600" />
        <div>Добавить</div>
      </div>
    </x-slot>

    <x-slot name="content">
      <div class="w-full">

        <div class="block w-full min-h-full gap-4 overflow-hidden md:flex">

          <div class="w-full pt-1">
            <div class="flex gap-4 px-1 pb-4 ">
              <div class="w-7/12">
                <input class="field @error('editAddress.address') border-red-500 border @enderror" type="text"
                  id="suggest" placeholder="Новый адрес" />
                <div id="message" class="pt-1 text-sm text-red-500"></div>
                @error('editAddress.address')
                  <p class="text-xs italic text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="w-3/12">
                <input name="extra" x-model="editAddress.extra"
                  class="field @error('editAddress.extra') border-red-500 border @enderror" type=" text"
                  placeholder="подъезд, домофон и тд." />
                @error('editAddress.extra')
                  <p class="text-xs italic text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="w-2/12">
                <button id="addAddress" @click="$wire.call('addNewAddress', editAddress), map.destroy()" disabled
                  class="flex items-center justify-center w-full gap-2 px-3 py-2 mb-1 font-bold text-white bg-blue-400 border border-blue-400 cursor-pointer disabled:text-gray-500 disabled:border-gray-200 disabled:bg-gray-50 rounded-xl hover:bg-blue-500 ">
                  <x-tabler-circle-plus class="w-8 h-8 stroke-current stroke-1 hover:text-gray-600" />
                  <div>Добавить</div>
                </button>
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
              editAddress: null,
              message: null,
              editAddress: ({
                id: '',
                address: '',
                extra: '',
                zip: '',
                lat: '',
                lng: '',
                delivery_zone: '',
              }),
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
                const script = document.createElement('script');
                script.src =
                  'https://api-maps.yandex.ru/2.1/?apikey=' + this.key + '&lang=ru_RU';

                document.head.appendChild(script);

                script.addEventListener('load', () => {
                  ymaps.ready(init);
                });

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

                    // Геокодируем введённые данные.
                    ymaps.geocode(value).then(function(res) {
                        var obj = res.geoObjects.get(0),
                          error, hint;

                        if (obj) {
                          // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
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

                        // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
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

                    // console.log(obj)

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
                    // Рассчитываем видимую область для текущего положения пользователя.
                    mapState = ymaps.util.bounds.getCenterAndZoom(
                      bounds,
                      [mapContainer.offsetWidth, mapContainer.offsetHeight]
                    );
                    // Сохраняем полный адрес для сообщения под картой.
                    address = [obj.getCountry(), obj.getAddressLine()].join(', ');
                    // Сохраняем укороченный адрес для подписи метки.
                    shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');

                    // Убираем контролы с карты.
                    mapState.controls = ['fullscreenControl', 'geolocationControl']
                    // Создаём карту.

                    createMap(mapState, shortAddress);
                  }

                  function createMap(state, caption) {
                    // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
                    if (!map) {
                      map = new ymaps.Map('mapZones', {
                        center: [59.938951, 30.315635],
                        zoom: 9,
                        controls: ['fullscreenControl', 'geolocationControl']
                      });
                      deliveryPoint = new ymaps.Placemark(
                        map.getCenter(), {
                          iconCaption: '',
                          // balloonContent: ''
                        }, {
                          preset: 'islands#redDotIconWithCaption'
                        });
                      // deliveryPoint.options.set({
                      //  draggable: true,
                      // });
                      deliveryPoint.events.add('dragstart', function() {
                        deliveryPoint.properties.set({
                          iconCaption: '',
                          balloonContent: ''
                        });
                      });
                      deliveryPoint.events.add('dragend', function() {
                        highlightResult(deliveryPoint);
                      });
                      map.geoObjects.add(deliveryPoint);
                      // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
                    } else {
                      map.setCenter(state.center, state.zoom);
                      deliveryPoint.geometry.setCoordinates(state.center);
                      deliveryPoint.properties.set({
                        iconCaption: caption,
                        // balloonContent: caption
                      });
                    }
                  }

                  function highlightResult(obj) {
                    // Сохраняем координаты переданного объекта.
                    var coords = obj.geometry.getCoordinates();
                    // Задаем подпись для метки.
                    if (typeof(obj.getThoroughfare) === 'function') {
                      setData(obj);
                    } else {
                      // Если вы не хотите, чтобы при каждом перемещении метки отправлялся запрос к геокодеру,
                      // закомментируйте код ниже.
                      ymaps.geocode(coords, {
                        results: 1
                      }).then(function(res) {
                        var obj = res.geoObjects.get(0);
                        setData(obj);

                      });
                    }

                    function setData(obj) {
                      var address = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
                      //  suggestView.destroy();
                      if (address.trim() === '') {
                        address = obj.getAddressLine();
                      }

                      document.getElementById('suggest').setAttribute('value', address);

                      deliveryPoint.properties.set({
                        iconCaption: address,
                        // balloonContent: address,
                      });

                    }
                  }

                }

              },

              setCoordinates(coordinates) {
                this.editAddress.address = coordinates.detail.address;
                this.editAddress.lat = coordinates.detail.coordinates[0];
                this.editAddress.lng = coordinates.detail.coordinates[1];
                this.editAddress.zip = coordinates.detail.zip;

                //console.log(coordinates.detail);
              }

            }))
          })
        </script>

      </div>
    </x-slot>

  </x-modal>
</div>
