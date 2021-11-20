<div wire:init="getAddresses()">
  <x-modal>
    <x-slot name="button">
      <div x-on:click="open()"
        class="flex items-center justify-center w-full px-4 py-3 space-x-1 bg-gray-100 border border-gray-300 cursor-pointer hover:border-gray-400 h-14 rounded-xl">
        <div>Мои адреса</div>
        <x-tabler-chevron-right class="w-6 h-6 text-gray-400 stroke-current" />
      </div>
    </x-slot>

    <x-slot name="content">


      <div>

        <h4 class="text-xl font-bold text-center ">Адреса</h4>

        <div class="space-y-4">

          <div x-cloak x-data="{ newAddress: false }" x-on:close-form.window="newAddress = false"
            x-on:edit-address.window="newAddress = true">

            <div x-show="newAddress === false" x-transition>

              @if ($addresses)
                <div class="space-y-4">
                  @foreach ($addresses as $addressItem)
                    <div wire:key="{{ $loop->index }}"
                      class="relative block px-4 py-3 mt-4 bg-white border border-gray-200 hover:border-green-400 rounded-xl ">

                      <div>
                        @if ($addressItem->id === $address['id'])
                          <div class="absolute z-40 -top-2 -left-2">
                            <x-tabler-circle-check class="w-6 h-6 text-green-400 bg-white stroke-current" />
                          </div>
                        @endif

                        <div x-cloak x-data="{ open: false }" class="absolute top-0 right-0 z-40 p-2 rounded-r-xl"
                          :class="open ? 'h-full bg-gray-100' : ''">
                          <div x-show="open === false" x-transition class="cursor-pointer" x-on:click="open = true"
                            x-on:click.outside="open = false">
                            <x-tabler-dots-vertical class="w-5 h-5 text-gray-300 stroke-current hover:text-gray-500" />
                          </div>
                          <div x-show="open" x-transition
                            class="flex flex-col items-center justify-around h-full space-y-2">

                            <div wire:click="editAddress({{ $addressItem->id }})" class="cursor-pointer">
                              <x-tabler-edit class="w-5 h-5 text-gray-300 stroke-current hover:text-blue-400" />
                            </div>
                            @if ($addressItem->id !== $address['id'])
                              <div wire:click="removeAddress({{ $addressItem->id }})" class="cursor-pointer">
                                <x-tabler-trash class="w-5 h-5 text-gray-300 stroke-current hover:text-red-400" />
                              </div>
                            @endif
                          </div>
                        </div>

                      </div>

                      <div wire:click="setAddress({{ $addressItem->id }})" class="cursor-pointer">
                        <div>
                          {{ $addressItem->address }},
                        </div>
                        <div class="text-xs text-gray-400">
                          {{ $addressItem->extra }}
                        </div>
                      </div>

                    </div>
                  @endforeach
                </div>
              @endif

              @if (empty($addresses) || $addresses->count() <= 4)
                <div x-on:click="newAddress = true"
                  class="flex items-center justify-center py-3 mt-4 space-x-1 border border-gray-200 border-dashed cursor-pointer hover:border-gray-200 rounded-xl hover:bg-gray-50">
                  <x-tabler-circle-plus class="w-8 h-8 text-gray-400 stroke-current stroke-1 hover:text-gray-600" />
                  <div>Добавить</div>
                </div>
              @endif

            </div>

            <div x-show="newAddress" x-transition>

              <div class="absolute top-4 left-4" x-on:click="newAddress = false">
                <x-tabler-chevron-left class="w-6 h-6 text-gray-500 cursor-pointer stroke-current" />
              </div>

              <div class="block w-full space-y-4">

                <div class="block space-y-4">

                  <div class="w-full space-y-2">
                    <span class="block font-bold text-gray-700">Улица *</span>

                    <div class="relative">

                      <div class="relative">
                        <input type="text"
                          class="relative w-full py-3 pl-3 pr-10 text-left bg-white border border-gray-300 rounded-md shadow-sm cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm field"
                          placeholder="проспект Большевиков" wire:model.debounce.1000ms="query"
                          wire:keydown.escape="hideDropdown" wire:keydown.tab="hideDropdown"
                          wire:keydown.Arrow-Up="decrementHighlight" wire:keydown.Arrow-Down="incrementHighlight"
                          wire:keydown.enter.prevent="selectAddress" autocomplete="street-address" />

                        <input type="hidden" name="address" id="address" wire:model="newAddress.address">

                        @if ($newAddress['address'])
                          <a class="absolute text-gray-500 cursor-pointer top-3 right-2" wire:click="reset">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" fill="none" viewBox="0 0 24 24"
                              stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                          </a>
                        @endif
                        <div wire:loading wire:target="query" class="absolute right-0 z-40 top-3">
                          <svg class="w-5 h-5 mr-3 -ml-1 text-orange-400 animate-spin"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                              stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                          </svg>
                        </div>
                      </div>

                      @if (!empty($query) && $newAddress['address'] == '' && $showDropdown)
                        <div class="absolute z-10 w-full mt-1 shadow-xl bg-gray-50">
                          @if (!empty($sugestionAddresses))
                            <div class="h-48 overflow-y-auto divide-y">
                              @foreach ($sugestionAddresses as $i => $adr)
                                <a wire:click="selectAddress({{ $i }})"
                                  class="block py-2 px-3 text-sm cursor-pointer hover:bg-blue-50 {{ $highlightIndex === $i ? 'bg-blue-50' : '' }}">{{ $adr }}</a>
                              @endforeach
                            </div>
                          @else
                            <span class="block px-2 py-1 text-sm">Адреса не найдены</span>
                          @endif
                        </div>
                      @endif

                    </div>

                    @error('newAddress.address')
                      <span class="text-xs text-red-600">
                        {{ $message }}
                      </span>
                    @enderror


                  </div>

                  <div class="w-full space-y-2">
                    <label class="block pb-1 font-bold text-gray-700">Здание *</label>
                    <input wire:model.defer="newAddress.building" name="building" class="field" type="text"
                      placeholder="7" />
                    @error('newAddress.building')
                      <span class="text-xs text-red-600">
                        {{ $message }}
                      </span>
                    @enderror
                  </div>

                  <div class="w-full space-y-2">
                    <label class="block pb-1 font-bold text-gray-700">Квартира</label>
                    <input wire:model.defer="newAddress.apartment" name="apartment" class="field" type="text"
                      placeholder="43" />
                    @error('newAddress.apartment')
                      <span class="text-xs text-red-600">
                        {{ $message }}
                      </span>
                    @enderror
                  </div>

                </div>

                <div x-data="{ extra: false }" class="space-y-2">
                  <div x-on:click="extra = !extra" class="flex space-x-2 text-xs text-blue-500 cursor-pointer">
                    <span>Указать доп. информацию</span>
                    <x-tabler-chevron-down x-show="extra" class="w-5 h-5 stroke-current" />
                    <x-tabler-chevron-up x-show="extra === false" class="w-5 h-5 stroke-current" />
                  </div>
                  <div x-show="extra" x-transition class="space-y-4">
                    <div class="w-full space-y-1">
                      <span class="block pb-1 font-bold text-gray-700">Доп. информация</span>
                      <textarea wire:model.defer="newAddress.extra" name="comment" class="field" type="text"
                        placeholder="подъезд, домофон, этаж..." rows="3">
                      </textarea>
                      @error('newAddress.comment')
                        <span class="text-xs text-red-600">
                          {{ $message }}
                        </span>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="flex items-center justify-center">
                  <button wire:click="addNewAddress()"
                    class="font-bold text-white uppercase bg-green-500 btn hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <span wire:loading wire:target="addNewAddress">
                      <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                    </span>
                    <span class="px-1 py-2">сохранить</span>
                  </button>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </x-slot>

  </x-modal>
</div>
