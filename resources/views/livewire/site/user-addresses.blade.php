<div>
  <x-modal :width="'sm'">
    <x-slot name="button">
      <div
        class="flex items-center justify-center w-full px-4 py-3 space-x-1 bg-gray-100 border border-gray-300 cursor-pointer hover:border-gray-400 h-14 rounded-xl">
        <div>Мои адреса</div>
        <x-tabler-chevron-right class="w-6 h-6 text-gray-400 stroke-current" />
      </div>
    </x-slot>

    <x-slot name="content">
      <div>

        <h4 class="text-xl font-bold text-center">Мои адреса</h4>

        <div class="space-y-4">

          <div x-cloak x-data="{ editAddress: false }" x-on:close-modal.window="editAddress = false">

            <div x-show="editAddress === false" x-transition>

              <div>
                @if ($addresses)
                  <div class="space-y-4">
                    @foreach ($addresses as $addressItem)
                      <div class="relative block">

                        <div wire:click="setAddress({{ $addressItem['id'] }}), $refresh"
                          class="px-4 py-3 mt-4 bg-white border border-gray-200 cursor-pointer hover:border-green-400 rounded-xl">
                          <div>
                            {{ $addressItem['address'] }}
                          </div>
                          @if (array_key_exists('extra', $addressItem))
                            <div class="text-xs text-gray-400">
                              {{ $addressItem['extra'] }}
                            </div>
                          @endif
                        </div>

                        <div wire:click="editAddress({{ $addressItem['id'] }})"
                          class="absolute z-30 cursor-pointer top-4 right-2">
                          <x-tabler-edit class="w-5 h-5 text-gray-300 stroke-current hover:text-blue-400" />
                        </div>

                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              @if (empty($addresses) || count($addresses) <= 4)
                <div class="flex justify-center pt-4">
                  <x-user-address-map />
                </div>
              @endif

            </div>


          </div>
        </div>
      </div>
    </x-slot>

  </x-modal>
</div>
