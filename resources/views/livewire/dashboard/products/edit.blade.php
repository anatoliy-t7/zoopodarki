@section('title')
  {{ $name }}
@endsection
<div x-data="handler" @set-attributes.window="setAttributes(event)" @set-ready-items.window="setReadyItems(event)"
  @set-catalogs.window="setCatalogs(event)" @set-product-brand.window="setProductBrandFromServer(event)"
  @set-brands.window="setBrandsFromServer(event)" @set-series.window="setSeriesFromServer(event)"
  @set-product-serie.window="setProductSerieFromServer(event)" @set-description.window="setDescContent(event)"
  @set-consist.window="setConsistContent(event)" @update-categories.window="updateCategoriesFromServer(event)"
  @update-brands.window="updateBrandsFromServer(event)" @save-it.window="saveIt(event)"
  @update-query-id.window="updateQueryId(event)" wire:init="sendDataToFrontend" class="relative">
  <x-loader wire:dirty />
  <div class="flex justify-between w-full space-x-6">

    <div class="flex items-center justify-between pb-2 space-x-4">

      <h3 class="text-xl font-bold text-gray-500">Товар</h3>

      @if ($productId)
        <div class="px-3 py-2 text-gray-500 bg-white rounded-2xl">
          {{-- @if ($product->categories()->exists() and $product->categories[0]->catalog)
        <a target="_blank" class="flex items-center space-x-2 group"
          href="{{ route('site.product', [$product->categories[0]->catalog->slug, $product->categories[0]->slug, $product->slug]) }}">
        @endif --}}

          ID <span class="pl-2 font-bold">{{ $productId }}</span>

          {{-- @if ($product->categories->exists() and $product->categories[0]->catalog)
          <x-tabler-external-link class="w-5 h-5 text-gray-500 stroke-current group-hover:text-blue-500" />
        </a>
        @endif --}}

        </div>

        @can('create')
          <button id="duplicate" title="Дублировать" wire:click="duplicate"
            class="p-2 font-bold text-white bg-yellow-500 rounded-lg hover:bg-gray-700">
            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M21,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.32.32,0,0,0-.09,0A.88.88,0,0,0,14.05,2H10A3,3,0,0,0,7,5V6H6A3,3,0,0,0,3,9V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V9S21,9,21,8.94ZM15,5.41,17.59,8H16a1,1,0,0,1-1-1ZM15,19a1,1,0,0,1-1,1H6a1,1,0,0,1-1-1V9A1,1,0,0,1,6,8H7v7a3,3,0,0,0,3,3h5Zm4-4a1,1,0,0,1-1,1H10a1,1,0,0,1-1-1V5a1,1,0,0,1,1-1h3V7a3,3,0,0,0,3,3h3Z" />
            </svg>
          </button>
        @endcan

      @endif

      @can('create')
        <a id="add" href="{{ route('dashboard.product.edit', ['id' => null]) }}" title="Создать новый товар"
          class="p-2 text-white bg-green-500 border border-b rounded-lg cursor-pointer hover:bg-green-700">
          <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
        </a>
      @endcan

    </div>

    <div class="flex items-center justify-between space-x-4">

      <a href="{{ route('dashboard.products.index') }}"
        class="bg-white border border-transparent rounded-lg btn hover:border-gray-500">
        Отмена
      </a>

      @can('delete')
        @if ($productId)
          <button wire:click="destroy({{ $productId }})" title="Удалить"
            class="p-2 bg-white border border-transparent rounded-lg hover:border-red-500">
            <svg class="w-6 h-6 text-red-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18ZM20,6H16V5a3,3,0,0,0-3-3H11A3,3,0,0,0,8,5V6H4A1,1,0,0,0,4,8H5V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V8h1a1,1,0,0,0,0-2ZM10,5a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1V6H10Zm7,14a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V8H17Zm-3-1a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
            </svg>
          </button>
        @endif
      @endcan

    </div>

  </div>



  <div class="mt-2">
    <div class="flex-col w-full space-y-6">

      <div class="flex w-full space-y-4">

        <div class="flex justify-between w-full space-x-6">
          <div class="w-full p-6 space-y-6 bg-white rounded-2xl ">

            <div class="space-y-1">
              <label class="font-bold">Название</label>
              <input wire:model.defer="name" name="name">
              @error('name')
                <span class="text-xs text-red-500">{{ $message }}</span>
              @enderror
            </div>

            <div x-data="{open: false}" class="flex flex-col">

              <div class="block pb-4">
                <button x-on:click="open = !open"
                  class="flex items-center justify-between space-x-2 text-sm text-white bg-indigo-500 btn hover:bg-indigo-600">
                  <span>Контент</span>
                  <x-tabler-caret-down x-show="!open" class="w-6 h-6" />
                  <x-tabler-caret-up x-show="open" class="w-6 h-6" />
                </button>
              </div>
              <div x-cloak x-show="open" x-transition.opacity class="space-y-6">

                <div class="space-y-1">
                  <label for="meta_title" class="font-bold">Meta заголовок</label>
                  <input wire:model.defer="meta_title" id="meta_title" name="meta_title">
                  @error('meta_title')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                  @enderror
                </div>


                <div class="space-y-1">
                  <label for="meta_description" class="font-bold">Meta описание</label>
                  <textarea rows="2" wire:model.defer="meta_description" name="meta_description"></textarea>
                  @error('meta_description')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                  @enderror
                </div>

                <div class="space-y-1">
                  <label for="description" class="font-bold">Описание</label>
                  <input id="description" name="description" value='{{ $description }}' type="hidden" />
                  <div wire:ignore x-on:trix-change.defer="$wire.set('description', $refs.descriptionInput.value, true)"
                    x-on:trix-attachment-add="uploadFileAttachment($event.attachment)"
                    x-on:trix-attachment-remove="removeFileAttachment($event.attachment)">
                    <trix-editor x-ref="descriptionInput" input="description">
                    </trix-editor>
                  </div>
                </div>

                <div class="space-y-1">
                  <label for="consist" class="font-bold">Состав</label>
                  <input id="consist" name="consist" value='{{ $consist }}' type="hidden" />
                  <div wire:ignore x-on:trix-change.defer="$wire.set('consist', $refs.consistInput.value, true)"
                    x-on:trix-attachment-add="uploadFileAttachment($event.attachment)"
                    x-on:trix-attachment-remove="removeFileAttachment($event.attachment)">
                    <trix-editor x-ref="consistInput" input="consist">
                    </trix-editor>
                  </div>
                </div>

                <div>
                  <label for="consapplyingist" class="font-bold">Применение</label>
                  <input id="applying" name="applying" value='{{ $applying }}' type="hidden" />
                  <div wire:ignore x-on:trix-change.defer="$wire.set('applying', $refs.applyingInput.value, true)"
                    x-on:trix-attachment-add="uploadFileAttachment($event.attachment)"
                    x-on:trix-attachment-remove="removeFileAttachment($event.attachment)">
                    <trix-editor x-ref="applyingInput" input="applying">
                    </trix-editor>
                  </div>
                </div>

              </div>

            </div>

          </div>

          <div class="w-full space-y-6">

            <div class="w-full p-6 space-y-6 bg-white rounded-2xl">

              <div x-data="confirmer" class="flex justify-start space-x-4 overflow-x-auto flex-nowrap">
                @forelse ($media as $key => $image)
                  <div class="relative z-0 group">

                    <img loading="lazy" class="object-scale-down w-32 h-32 rounded-lg"
                      src="{{ $image->getUrl('thumb') }}">

                    <div class="absolute z-10 opacity-0 -top-1 -right-1 group-hover:opacity-100">
                      <button wire:key="{{ $key }}" x-on:click="askDelete({{ $key }})" type="button"
                        title="remove"
                        class="p-1 text-sm text-red-500 rounded-full hover:text-white focus:outline-none focus:ring hover:bg-red-500">
                        <x-tabler-circle-x class="w-6 h-6" />
                      </button>
                    </div>

                    <div x-show="confirm == {{ $key }}" x-transition.opacity
                      class="absolute top-0 left-0 z-30 w-40 px-4 py-3 bg-white shadow-xl rounded-2xl">
                      <h3 class="text-sm">Вы уверены?</h3>
                      <div class="flex justify-around">
                        <button x-on:click="closeConfirm()"
                          class="px-3 py-2 text-red-500 rounded-lg hover:text-red-600 focus:outline-none focus:ring hover:bg-gray-200"
                          type="button">
                          Нет
                        </button>
                        <button x-on:click="closeConfirm" wire:click="removeImage({{ $key }})"
                          class="px-3 py-2 text-green-500 rounded-lg hover:text-green-600 focus:outline-none focus:ring hover:bg-gray-200"
                          type="button">
                          Удалить
                        </button>
                      </div>
                    </div>
                  </div>
                @empty
                @endforelse

                <script>
                  document.addEventListener('alpine:initializing', () => {
                    Alpine.data('confirmer', () => ({
                      confirm: null,
                      askDelete($id) {
                        this.confirm = $id
                      },
                      closeConfirm() {
                        this.confirm = null
                      },
                    }))
                  })
                </script>

              </div>
              <div class="w-full">

                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                  x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                  x-on:livewire-upload-progress="progress = $event.detail.progress"
                  class="relative h-24 p-2 border-4 border-pink-200 border-dashed rounded-lg bg-pink-50 hover:border-pink-300">

                  <div class="absolute top-0 left-0 flex items-center justify-start w-full h-full px-2 space-x-2">

                    @forelse ($photos as $photo)
                      <div class="relative z-10 w-24 h-24 py-2">
                        <img loading="lazy" class="object-scale-down w-full h-full rounded-lg"
                          src="{{ $photo->temporaryUrl() }}">

                      </div>
                    @empty
                      <div class="flex flex-col items-center justify-center w-full space-y-2 text-gray-500">
                        <x-tabler-camera-plus class="w-6 h-6 stroke-current " />
                        <div class="flex text-sm ">Нажмите сюда для загрузки фотографий (max: 5шт, 1Mb)</div>
                      </div>
                    @endforelse
                  </div>

                  <div x-show="isUploading" x-transition.opacity
                    class="absolute top-0 left-0 right-0 z-30 flex items-center justify-start h-full ">
                    <span class="relative flex items-center w-64 h-2 mx-auto overflow-hidden bg-gray-300 rounded-2xl">
                      <span class="absolute left-0 w-full h-full transition-all duration-300 bg-green-500"
                        :style="`width: ${ progress }%`"></span>
                    </span>
                  </div>

                  <input type="file" wire:model.defer="photos" multiple
                    class="absolute z-20 block w-full h-full outline-none opacity-0 cursor-pointer">

                </div>
                @error('photos') <span class="error">{{ $message }}</span> @enderror
              </div>


            </div>

            @can('edit')

              <div class="w-full p-6 space-y-4 bg-white rounded-2xl">
                <div class="flex w-full ">

                  <div class="w-full space-y-2">

                    <div class="flex items-center justify-start w-full space-x-6">
                      <div>
                        <span wire:ignore class="font-bold">Категории</span>
                      </div>
                      <div class="block">
                        <div class="flex items-center justify-start space-x-4">

                          <button title="Добавить каталог" x-on:click="addNewFieldCatalog()"
                            class="text-sm text-white bg-indigo-500 btn hover:bg-indigo-600">
                            <svg class="w-6 h-6 text-white fill-current" xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 24 24">
                              <path
                                d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>

                    <div>
                      <div x-show="Array.isArray(readyCategories)" x-transition.opacity>
                        <div class="flex flex-wrap items-center justify-start">

                          <template x-for="(item, index) in readyCategories" hidden>
                            <div class="p-1">
                              <div
                                class="flex items-center py-1 pl-3 space-x-2 text-sm border text-cyan-600 bg-cyan-100 border-cyan-100 rounded-2xl">
                                <span class="leading-relaxed truncate" x-text="item.catalogName"></span>:
                                <span class="pl-2 leading-relaxed truncate" x-text="item.name"></span>
                                <button x-on:click.prevent="removeCategory(index)"
                                  class="inline-block h-full px-1 text-gray-500 align-middle hover:text-red-500 focus:outline-none">
                                  <svg class="w-6 h-6 mx-auto fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                      d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z" />
                                  </svg>
                                </button>
                              </div>
                            </div>
                          </template>
                        </div>
                      </div>
                      @error('readyCategories')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                      @enderror
                    </div>

                    <div class="flex justify-between w-full pt-2 space-x-4">

                      <div class="w-6/12">
                        <div x-show="catalog_field" x-transition.opacity>
                          <div class="relative">
                            <select x-on:input.debounce.750="catalogIsSelected($event.target.value)"
                              class="h-12 field">
                              <option default>Каталоги</option>
                              <template x-for="(catalog, index) in catalogs" :key="index" hidden>
                                <option :key="index" x-text="catalog.name" x-model="catalog.id">
                                </option>
                              </template>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="w-6/12">

                        <div x-show="category_field" x-transition.opacity>
                          <div wire:ignore class="pt-1">
                            <input placeholder="Категории" name='categories'>
                          </div>
                        </div>

                      </div>

                    </div>
                  </div>

                </div>

                <div class="items-center justify-between block space-x-6 lg:flex">

                  <div class="w-6/12">
                    <div class="flex items-center justify-start space-x-2">
                      <span class="font-bold">Бренд</span>
                      @error('productBrand')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                      @enderror
                    </div>
                    <div wire:ignore class="pt-1">
                      <input name='brands' class="field">
                    </div>

                  </div>
                  <div class="w-6/12">
                    <div x-show="brand_series_field" x-transition.opacity>
                      <div>
                        <span class="font-bold">Серии</span>
                        <div class="relative pt-1">
                          <select x-on:input.debounce="brandSerieSelected($event.target.value)" class="field">
                            <option default value="">Серии</option>
                            <template x-for="(item, index) in brand_series" :key="index" hidden>
                              <option :selected="serieSelected == item.id" x-bind:value="item.id" x-text="item.name">
                              </option>
                            </template>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>


                </div>

                <div class="space-y-2">

                  <div class="flex items-center justify-start space-x-6">
                    <div>
                      <span wire:ignore class="font-bold">Свойства</span>
                    </div>
                    <div class="block">
                      <div class="flex items-center justify-start space-x-4">

                        <button title="Добавить пункт" x-on:click="addNewField()"
                          class="text-sm text-white bg-indigo-500 btn hover:bg-indigo-600">
                          <svg class="w-6 h-6 text-white fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path d="M19,11H13V5a1,1,0,0,0-2,0v6H5a1,1,0,0,0,0,2h6v6a1,1,0,0,0,2,0V13h6a1,1,0,0,0,0-2Z" />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div x-show="Array.isArray(readyItems)" x-transition.opacity>
                    <div class="flex flex-wrap items-center justify-start ">

                      <template x-for="(item, index) in readyItems" hidden>
                        <div class="p-1">
                          <div
                            class="flex items-center py-1 pl-3 space-x-2 text-sm text-gray-600 bg-gray-100 border border-gray-500 rounded-2xl">
                            <span class="leading-relaxed truncate" x-text="item.attName"></span>:
                            <span class="pl-2 leading-relaxed truncate" x-text="item.name"></span>
                            <button x-on:click.prevent="removeItem(index)"
                              class="inline-block h-full px-1 text-gray-500 align-middle hover:text-red-500 focus:outline-none">
                              <svg class="w-6 h-6 mx-auto fill-current" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                  d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z" />
                              </svg>
                            </button>
                          </div>
                        </div>
                      </template>
                    </div>
                  </div>


                  <div class="flex justify-between pt-2 space-x-4">

                    <div class="w-6/12">
                      <div x-show="att_field" x-transition.opacity>
                        <div class="relative">
                          <select x-on:input.debounce.750="attributeSelected($event.target.value)"
                            class="h-12 field">
                            <option default>Свойства</option>
                            <template x-for="(attribute, index) in attributes" :key="index" hidden>
                              <option :key="index" x-text="attribute.name" x-model="attribute.id">
                              </option>
                            </template>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="w-6/12">

                      <div x-show="att_item_field" x-transition.opacity>
                        <div wire:ignore class="pt-1">
                          <input placeholder="Вид свойства" name='attribute_items' class="field">
                        </div>
                      </div>


                    </div>

                  </div>
                </div>
              </div>
            @endcan
          </div>

        </div>

      </div>

      @can('edit')
        <div class="flex w-full p-6 space-x-4 bg-white rounded-2xl">
          <div class="flex w-6/12">
            <div class="w-full">
              <div class="p-6 space-y-4 border-2 border-gray-100 rounded-2xl">
                <div class="flex items-center justify-between">

                  <label>
                    <span class="text-gray-700">Товары 1C</span>
                  </label>

                  <x-toggle wire:model="emptyStock" :property="$emptyStock" :lable="'В наличии'" />


                  <x-toggle wire:model="taken" :property="$taken" :lable="'Скрыть взятые'" />

                  <x-dashboard.items-per-page />

                </div>

                <x-dashboard.search placeholder="Поиск по имени" wire:model.debounce.600ms="search"
                  class="w-full" />

                <div class="py-2 ">
                  {{ $products_1c->onEachSide(1)->links() }}
                </div>

                <div class="py-2 overflow-y-auto" style="max-height:600px">

                  @foreach ($products_1c as $item)
                    <div wire:key="{{ $item->id }}" wire:click="setVariation({{ $item->id }})"
                      class="flex justify-between p-2 space-x-4 text-sm border-b cursor-pointer hover:bg-gray-200">
                      <div>{{ $item->name }} <span class="text-gray-500"> (Артикул:
                          {{ $item->vendorcode }})</span></div>

                      @if ($item->product_id !== null)
                        <div>
                          <svg class="w-6 h-6 text-green-500 fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path
                              d="M10.21,14.75a1,1,0,0,0,1.42,0l4.08-4.08a1,1,0,0,0-1.42-1.42l-3.37,3.38L9.71,11.41a1,1,0,0,0-1.42,1.42ZM21,2H3A1,1,0,0,0,2,3V21a1,1,0,0,0,1,1H21a1,1,0,0,0,1-1V3A1,1,0,0,0,21,2ZM20,20H4V4H20Z" />
                          </svg>
                        </div>
                      @endif
                    </div>
                  @endforeach
                </div>

              </div>

            </div>


          </div>

          <div class="flex w-6/12">
            <div class="w-full p-2 space-y-4 text-gray-700">
              <div class="flex items-center justify-between">
                <div class="pb-2 text-gray-700">Вариативность ({{ count($this->variations) }})</div>

                <div>
                  <div class="flex items-center justify-between space-x-4">

                    <label class="flex items-center justify-between space-x-2">
                      <span class="text-sm text-gray-700">Единицы измерения</span>
                      <div class="relative">
                        <select wire:model="unitId" name="unitId" class="w-32">
                          <option default value>Выбрать</option>
                          @foreach ($units as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      @error('unitId') <span class="text-xs text-red-500">Поле обязательно для
                        заполнения</span> @enderror
                    </label>
                  </div>
                </div>
              </div>
              <div class="overflow-y-auto" style="max-height:600px">

                <div>
                  @forelse ($variations as $key => $item)
                    <div wire:key="{{ $loop->index }}"
                      class="flex items-center justify-between p-2 border-b hover:bg-pink-100">
                      <a class="text-sm" target="_blank"
                        href="{{ route('dashboard.products1c', ['search' => $item['name']]) }}">
                        {{ $item['name'] }}
                      </a>

                      <div class="flex items-center justify-between space-x-4">

                        <div class="w-32">
                          <input wire:model="variations.{{ $key }}.unit_value"
                            value="{{ $item['unit_value'] }}" placeholder="100" type="text">

                        </div>
                        <div wire:click="removeVariation({{ $item['id'] }})" class="cursor-pointer ">
                          <svg class="w-6 h-6 text-red-500 fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path
                              d="M15.71,8.29a1,1,0,0,0-1.42,0L12,10.59,9.71,8.29A1,1,0,0,0,8.29,9.71L10.59,12l-2.3,2.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l2.29,2.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42L13.41,12l2.3-2.29A1,1,0,0,0,15.71,8.29ZM19,2H5A3,3,0,0,0,2,5V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V5A3,3,0,0,0,19,2Zm1,17a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V5A1,1,0,0,1,5,4H19a1,1,0,0,1,1,1Z" />
                          </svg>
                        </div>
                      </div>
                    </div>

                  @empty
                    <div class="text-sm text-gray-500">Выберите товары слева для создания вариативности
                    </div>
                  @endforelse
                </div>
                <div class="w-full">
                  <x-loader wire:target="setVariation" />
                </div>
              </div>
            </div>
          </div>
        </div>
      @endcan

    </div>


    <div class="flex justify-end w-full mt-8 space-x-4">
      <div class="flex items-center justify-between space-x-4">

        @can('edit')
          <div class="px-3 py-2 bg-white rounded-2xl">
            <label class="flex items-center justify-end space-x-4">
              <span class="text-gray-700">Статус</span>
              <div class="relative">
                <select wire:model="status" name="status" class="mt-1 field">
                  @foreach ($statuses as $item)
                    <option value="{{ $item }}">{{ __('constants.product_status.' . $item) }}</option>
                  @endforeach
                </select>
              </div>
              @error('status') <span class="text-xs text-red-500">Поле обязательно для
                заполнения</span> @enderror
            </label>
          </div>
        @endcan

        <button x-on:click="saveIt()" class="px-4 py-2 font-bold text-white bg-pink-500 rounded-lg hover:bg-pink-700"
          wire:loading.attr="disabled">
          Сохранить
        </button>

      </div>
    </div>

    <div id="toolbar">
    </div>

  </div>

  @can('create')
    @push('header-css')
      <link href="{{ mix('css/tagify.css') }}" rel="stylesheet">
    @endpush
    @push('header-script')
      <script src="{{ mix('js/tagify.min.js') }}"></script>
    @endpush
  @endcan

  <script>
    function uploadFileAttachment(attachment) {

      @this.upload('newFiles', attachment.file, function(uploadedUrl) {
        const eventName = 'zoo:trix-upload-completed:${btoa(unescape(encodeURIComponent(uploadedUrl)))}';
        const listener = function(event) {
          attachment.setAttributes(event.detail);
          window.removeEventListener(eventName, listener);
        }
        window.addEventListener(eventName, listener)
        @this.call('completeUpload', uploadedUrl, eventName);

      }, () => {
        console.log('Error');
      }, function(event) {
        attachment.setUploadProgress(event.detail.progress)
      })

    }

    function removeFileAttachment(attachment) {
      @this.call('removeFileAttachment', attachment.attachment.attributes.values.url.split("/").pop());
    }

    document.addEventListener('alpine:initializing', () => {
      Alpine.data('handler', () => ({
        attributes: [],
        attribute_items: [],
        att_selected: [],
        readyItems: [],
        attSelected: null,
        attItemSelected: [],
        att_field: false,
        att_item_field: false,
        AttributeItemsInputElm: null,
        tagifyAttributeItems: null,

        catalogs: [],
        categories: [],

        catalog_selected: [],
        readyCategories: @entangle('readyCategories'),

        catalogSelected: null,
        categorySelected: [],

        catalog_field: false,
        category_field: false,
        categoriesInputElm: null,
        tagifyCategories: null,

        brands: [],
        productBrand: null,
        tagifyBrands: null,
        brand_series_field: false,
        brand_series: [],
        serieSelected: null,

        addNewField() {
          this.att_field = true;
          window.livewire.emit('getAttributes')
        },

        removeField(index) {
          this.attributes.splice(index, 1);
        },

        setAttributes(attributes) {
          this.attributes = [];
          this.attributes = attributes.detail;
        },

        attributeSelected(attribute_id) {

          this.attSelected = [];
          this.attribute_items = [];
          this.attSelected = this.attributes.find(attribute => attribute.id == attribute_id);
          this.attribute_items = this.attSelected.items;

          this.attribute_items = this.attribute_items.map(({
            id: id,
            attribute_id: attribute_id,
            name: value
          }) => ({
            id,
            value,
            attribute_id
          }));

          this.att_selected = this.attSelected;
          this.att_item_field = true;


          this.attItemSelected = [];
          this.att_selected.items = [];

          if (this.tagifyAttributeItems === null) {
            this.initAttributeItems()
          } else {
            this.updateAttributeItems()
          }

        },

        updateAttributeItems() {
          this.tagifyAttributeItems.settings.whitelist = this.attribute_items
          this.tagifyAttributeItems.loading(false).dropdown.show.call(this.tagifyAttributeItems);
        },

        setAttributeItemSelected(att_item_id) {

          this.attItemSelected = this.attribute_items.find(item => item.id == att_item_id);

          let data = Object.assign({}, this.attItemSelected);

          this.att_selected.items.push(data);

          if (Array.isArray(this.readyItems)) {
            this.readyItems.push({
              'attName': this.att_selected.name,
              'name': data.value,
              'id': data.id,
              'attribute_id': this.att_selected.id,
            });

          } else {
            this.readyItems = [{
              'attName': this.att_selected.name,
              'name': data.value,
              'id': data.id,
              'attribute_id': this.att_selected.id,
            }];

          }

          this.att_field = false;
          this.att_item_field = false;
          this.att_selected = [];
          this.tagifyAttributeItems.removeAllTags()
          this.tagifyAttributeItems.destroy()
          this.tagifyAttributeItems = null
          this.attItemSelected = [];
        },

        initAttributeItems() {

          this.AttributeItemsInputElm = document.querySelector('input[name=attribute_items]');
          this.tagifyAttributeItems = new Tagify(this.AttributeItemsInputElm, {
            whitelist: this.attribute_items,
            dropdown: {
              classname: "w-full",
              enabled: 0,
              maxItems: 100,
              position: "all",
              closeOnSelect: true,
              highlightFirst: true,
              searchKeys: ["value"],
              fuzzySearch: true,
            },
            addTagOnBlur: false,
            editTags: false,
            maxTags: 1,
            skipInvalid: true,
            enforceWhitelist: true,
            delimiters: "`",
        });

        this.tagifyAttributeItems.addTags(this.attItemSelected)
        this.tagifyAttributeItems
          .on('change', e => {
            if (e.detail.value) {
              attItemSelected = JSON.parse(e.detail.value)
              att_item_id = attItemSelected[0]['id']
              this.setAttributeItemSelected(att_item_id)
            }
          })

      },

      removeItem(index) {
        this.readyItems.splice(index, 1)
      },

      saveIt() {
        window.livewire.emit('save', this.readyItems, this.readyCategories, this.productBrand, this
          .serieSelected, )

      },

      removeVariation($id, ) {
        window.livewire.emit('removeVariation', $id)
      },

      setReadyItems(att_selected) {
        this.readyItems = [];
        this.readyItems = att_selected.detail;
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

      updateBrandsFromServer(brands) {
        this.brands = [];
        this.brands = brands.detail;
        this.brands = this.brands.map(({
          id: id,
          name: value
        }) => ({
          id,
          value
        }));

        this.tagifyBrands.settings.whitelist = this.brands
        this.tagifyBrands.loading(false).dropdown.show.call(this.tagifyBrands);
      },


      setProductBrandFromServer(productBrand) {
        this.productBrand = null;
        this.productBrand = productBrand.detail;
        this.productBrand = this.productBrand.map(({
          id: id,
          name: value
        }) => ({
          id,
          value
        }));
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
          maxTags: 1,
          skipInvalid: true,
          enforceWhitelist: true,
          delimiters: "`",
          });
          this.tagifyBrands.addTags(this.productBrand)

          this.tagifyBrands
            .on('change', e => {
              if (e.detail.value) {
                this.productBrand = JSON.parse(e.detail.value)
                window.livewire.emit('getBrandSeries', this.productBrand, )
                this.serieSelected = null;
              } else {
                this.productBrand = null
              }

            })

          if (this.productBrand !== null) {
            window.livewire.emit('getBrandSeries', this.productBrand)
          }
        },

        setSeriesFromServer(series) {
          this.brand_series = [];
          this.brand_series = series.detail;
          if (series.detail.length !== 0) {
            this.brand_series_field = true
          } else {
            this.brand_series_field = false
          }

        },

        setProductSerieFromServer(productSerie) {
          this.serieSelected = null;
          this.brand_series_field = true
          this.serieSelected = productSerie.detail;
        },

        brandSerieSelected(serie) {
          this.serieSelected = null;
          this.serieSelected = serie;
        },

        updateQueryId(productId) {
          window.history.replaceState(null, null, "?id=" + productId.detail);
        },


        addNewFieldCatalog() {
          this.catalog_field = true;
          window.livewire.emit('getCatalogs')
        },

        removeFieldCatalog(index) {
          this.catalogs.splice(index, 1);
        },

        setCatalogs(catalogs) {
          this.catalogs = [];
          this.catalogs = catalogs.detail;
        },

        catalogIsSelected(catalog_id) {

          this.catalogSelected = [];
          this.categories = [];
          this.catalogSelected = this.catalogs.find(catalog => catalog.id == catalog_id);

          this.categories = this.catalogSelected.categories;

          this.categories = this.categories.map(({
            id: id,
            catalog_id: catalog_id,
            name: value
          }) => ({
            id,
            value,
            catalog_id
          }));

          this.catalog_selected = this.catalogSelected;
          this.category_field = true;


          this.categorySelected = [];
          this.catalog_selected.categories = [];

          if (this.tagifyCategories === null) {
            this.initCategories()
          } else {
            this.updateCategories()
          }

        },

        updateCategories() {
          this.tagifyCategories.settings.whitelist = this.categories
          this.tagifyCategories.loading(false).dropdown.show.call(this.tagifyCategories);
        },

        setCategoriesSelected(category_id) {

          this.categorySelected = this.categories.find(item => item.id == category_id);

          let data = Object.assign({}, this.categorySelected);

          this.catalog_selected.categories.push(data);

          if (Array.isArray(this.readyCategories)) {
            this.readyCategories.push({
              'catalogName': this.catalog_selected.name,
              'name': data.value,
              'id': data.id,
              'catalog_id': this.catalog_selected.id,
            });

          } else {
            this.readyCategories = [{
              'catalogName': this.catalog_selected.name,
              'name': data.value,
              'id': data.id,
              'catalog_id': this.catalog_selected.id,
            }];

          }

          this.catalog_field = false;
          this.category_field = false;
          this.catalog_selected = [];
          this.tagifyCategories.removeAllTags()
          this.tagifyCategories.destroy()
          this.tagifyCategories = null
          this.categorySelected = [];
        },

        initCategories() {

          this.categoriesInputElm = document.querySelector('input[name=categories]');
          this.tagifyCategories = new Tagify(this.categoriesInputElm, {
            whitelist: this.categories,
            dropdown: {
              classname: "w-full",
              enabled: 0,
              maxItems: 100,
              position: "all",
              closeOnSelect: true,
              highlightFirst: true,
              searchKeys: ["value"],
              fuzzySearch: true,
            },
            addTagOnBlur: false,
            editTags: false,
            maxTags: 1,
            skipInvalid: true,
            enforceWhitelist: true,
            delimiters: "`",
          });

          this.tagifyCategories.addTags(this.categorySelected)
          this.tagifyCategories
            .on('change', e => {
              if (e.detail.value) {
                categorySelected = JSON.parse(e.detail.value)
                category_id = categorySelected[0]['id']
                this.setCategoriesSelected(category_id)
              }
            })

        },

        removeCategory(index) {
          this.readyCategories.splice(index, 1)
        },
      }))
    })

    document.addEventListener("keydown", function(e) {
      if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
        e.preventDefault();
        var event = new CustomEvent('save-it');
        window.dispatchEvent(event);
      }
    }, false);

    document.addEventListener("keydown", function(e) {
      if (e.keyCode == 112) {
        e.preventDefault();
        document.getElementById("add").click();
      }
    }, false);

    document.addEventListener("keydown", function(e) {
      if (e.keyCode == 113) {
        e.preventDefault();
        document.getElementById("duplicate").click();
      }
    }, false);
  </script>
</div>
