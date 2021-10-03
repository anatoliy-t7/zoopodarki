<div wire:init="getAddresses()">
  <x-modal>
    <x-slot name="button">
      <div x-on:click="open()"
        class="flex items-center justify-center w-full px-4 py-3 space-x-1 bg-gray-100 border border-gray-100 cursor-pointer hover:border-gray-300 h-14 rounded-xl">
        <div>Мои адреса</div>
        <x-tabler-chevron-right class="w-6 h-6 text-gray-400 stroke-current" />
      </div>
    </x-slot>

    <x-slot name="content">

      <x-loader />
      <div wire:loading.remove>

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

                  <div class="w-full space-y-1">
                    <span class="block font-bold text-gray-700">Индекс</span>
                    <input wire:model.defer="newAddress.zip" name="address" class="field" type="text"
                      autocomplete="street-address">
                    @error('newAddress.zip')
                      <span class="text-xs text-red-600">
                        {{ $message }}
                      </span>
                    @enderror
                  </div>

                  <div class="w-full space-y-1">
                    <span class="block font-bold text-gray-700">Адрес</span>
                    <input wire:model.defer="newAddress.address" name="address" class="field" type="text"
                      autocomplete="street-address" placeholder="Улица, дом, квартира">
                    @error('newAddress.address')
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
                    class="font-bold text-white bg-green-500 btn hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <div wire:loading wire:target="addNewAddress">
                      <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                    </div>
                    <div>сохранить</div>
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
