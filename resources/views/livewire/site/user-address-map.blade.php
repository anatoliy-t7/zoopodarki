<div x-data="userMapAddress">
  <x-modal :width="'screen-lg'">
    <x-slot name="button">
      <div @click.once="loadScript()"
        class="flex items-center justify-center w-full px-4 py-3 space-x-1 bg-gray-100 border border-gray-300 cursor-pointer hover:border-gray-400 h-14 rounded-xl">
        <div>Мои адреса</div>
        <x-tabler-chevron-right class="w-6 h-6 text-gray-400 stroke-current" />
      </div>
    </x-slot>

    <x-slot name="content">
      <div class="w-full">

        <div class="block w-full min-h-full gap-4 overflow-y-auto md:overflow-hidden md:flex">

          <div class="w-full md:w-9/12 block-map">
            <div class="flex gap-4 px-1 pb-4 ">
              <div class="w-full space-y-1">
                <input class="field" type="text" id="suggest" placeholder="Новый адрес" />
                <div id="message" class="text-sm text-red-500"></div>
              </div>
              @if (empty($addresses) || count($addresses) <= 4)
                <button x-on:click="editAddress = true, getAddressFromInput()" :disabled="canAdd === false"
                  class="flex items-center justify-center gap-1 px-3 py-2 border border-gray-200 border-dashed cursor-pointer hover:border-gray-200 rounded-xl hover:bg-gray-50">
                  <x-tabler-circle-plus class="w-8 h-8 text-gray-400 stroke-current stroke-1 hover:text-gray-600" />
                  <div>Добавить</div>
                </button>
              @endif
            </div>
            <div class="h-full">
              <div id="map" class="w-full max-w-lg overflow-hidden block-map rounded-l-xl">
              </div>
            </div>
          </div>

          <div class="w-full h-full block-map">
            <div class="flex flex-col overflow-y-auto divide-y divide-gray-100 scrollbar">
              @if ($addresses)
                @foreach ($addresses as $address)
                  <div>
                    <div class="flex items-center justify-between gap-2">
                      <div
                        x-on:click="showOnMap([{{ $address['lat'] }}, {{ $address['lng'] }}],  {{ $loop->index }}, {{ $address['id'] }})"
                        class="block px-5 py-2 space-y-2 text-sm cursor-pointer hover:bg-gray-50">
                        <div class="text-gray-400">
                          <x-tabler-map-pin class="inline w-5 h-5 mr-1 stroke-current" />

                        </div>
                        <div> {{ $address['address'] }}</div>

                      </div>
                      <div class="p-2">
                        <button @click="editAddress = {{ $address['id'] }}">
                          <x-tabler-edit class="w-5 h-5 text-gray-300 stroke-current hover:text-blue-400" />
                        </button>
                      </div>
                    </div>

                    <div x-show="editAddress == {{ $address['id'] }}" x-transition
                      class="p-4 space-y-2 text-sm bg-gray-50 rounded-xl">
                      <div class="w-full space-y-2">
                        <label class="block pb-1 font-bold text-gray-700">Адрес</label>
                        <input name="address" wire:model.defer="address.address" class="field" type="text" />
                      </div>
                      <div class="w-full space-y-2">
                        <label class="block pb-1 font-bold text-gray-700">Дом, корпус, подъезд *</label>
                        <input name="building" wire:model.defer="address.building" class="field" type="text"
                          placeholder="7" />
                      </div>
                    </div>
                  </div>

                @endforeach
              @endif
            </div>

            <div x-show="editAddress == true" class="p-4 space-y-2 rounded-xl bg-gray-50">
              <div x-transition class="space-y-2">
                <div class="w-full space-y-1 text-sm">
                  <label class="inline-block font-bold text-gray-700">Адрес</label>
                  <input name="address" x-model="address" wire:model.defer="addresses.*.address" class="field"
                    type="text" />
                </div>
                <div class="w-full space-y-1 text-sm">
                  <label class="block font-bold text-gray-700">подъезд, домофон и тд.</label>
                  <input name="building" wire:model.defer="addresses.*.building" class="field" type="text"
                    placeholder="7" />
                </div>
              </div>
              <button x-on:click="addAddress"
                class="w-full py-3 font-bold text-white bg-blue-400 cursor-pointer rounded-xl hover:bg-blue-500">
                Сохранить
              </button>
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
              address: null,
              canAdd: false,
              script: null,
              showOnMap(coord, index, storeId) {

                mapAdr.setCenter(coord, 16);

                var objectState = clusterer.getObjectState(myGeoObjects[index]);
                if (objectState.isClustered) {
                  objectState.cluster.state.set('activeObject', myGeoObjects[index]);
                  clusterer.balloon.open(objectState.cluster);
                } else if (objectState.isShown) {
                  myGeoObjects[index].balloon.open();
                }
              },

              loadScript() {
                this.script = document.createElement('script');
                this.script.src =
                  'https://api-maps.yandex.ru/2.1/?apikey=' + this.key + '&lang=ru_RU';

                document.head.appendChild(this.script);
                this.script.addEventListener('load', () => {
                  ymaps.ready(this.initMap());
                });
              },

              initMap() {

                this.createMap();

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
                  this.geocode(e.get('item').value);
                })

              },

              createMap(state, caption) {
                // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
                // console.log(map);
                if (!map) {
                  map = new ymaps.Map('map', {
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
                    this.highlightResult(deliveryPoint);
                  });
                  map.geoObjects.add(deliveryPoint);
                  // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
                } else {
                  if (!state) {
                    let state =
                      center = [59.938951, 30.315635];
                    zoom = setZoom(9);;

                  }
                  map.setCenter(state.center, state.zoom);
                  deliveryPoint.geometry.setCoordinates(state.center);
                  deliveryPoint.properties.set({
                    iconCaption: caption,
                    // balloonContent: caption
                  });
                }
              },
              geocode(value) {

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
                        error = 'Неточный адрес.';
                        hint = ' Уточните номер дома';
                        break;
                      case 'street':
                        error = 'Неполный адрес.';
                        hint = ' Уточните номер дома';
                        break;
                      case 'other':
                      default:
                        error = 'Неточный адрес.';
                        hint = ' Уточните адрес';
                    }
                  } else {
                    error = 'Адрес не найден';
                    hint = ' Уточните адрес';
                  }

                  // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                  if (error) {
                    document.getElementById('message').innerText = error + hint;
                    this.canAdd = false;
                  } else {
                    this.showResult(obj);
                    this.canAdd = true;
                    console.log(this.canAdd);

                  }
                }, function(e) {
                  this.canAdd = false;
                  console.log(e)
                })

              },
              showResult(obj) {
                // Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
                document.getElementById('message').innerText = '';
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

                this.createMap(mapState, shortAddress);
              },



              highlightResult(obj) {
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
              },

              getAddressFromInput() {
                this.address = document.getElementById('suggest').value;
              }

            }))
          })
        </script>

      </div>
    </x-slot>

  </x-modal>
</div>
