<div wire:init="getContacts()" class="w-full">
  <x-modal>
    <x-slot name="button">
      <div
        class="flex items-center justify-center w-full px-4 py-3 space-x-1 bg-gray-100 border border-gray-300 cursor-pointer hover:border-gray-500 h-14 rounded-xl">
        <div>Мои контакты</div>
        <x-tabler-chevron-right class="w-6 h-6 text-gray-400 stroke-current" />
      </div>
    </x-slot>

    <x-slot name="content">
      <x-loader />
      <div wire:loading.remove>
        <h4 class="text-xl font-bold text-center ">Мои контакты</h4>

        <div class="space-y-4">

          <div x-cloak x-data="{ newContact: false }" x-on:close-form.window="newContact = false"
            x-on:edit-contact.window="newContact = true">

            <div :class="newContact === false ? 'block' : 'hidden'">

              @if ($contacts)
                <div class="space-y-4">
                  @foreach ($contacts as $contactItem)
                    <div wire:key="{{ $loop->index }}"
                      class="relative block px-4 py-3 mt-4 bg-white border border-gray-200 hover:border-green-400 rounded-xl ">

                      <div>

                        <div x-cloak x-data="{ open: false }" class="absolute top-0 right-0 z-40 p-2 rounded-r-xl"
                          :class="open ? 'h-full bg-gray-100' : ''">
                          <div x-show="open === false" x-transition.opacity class="cursor-pointer"
                            x-on:click="open = true" x-on:click.outside="open = false">
                            <x-tabler-dots-vertical class="w-5 h-5 text-gray-300 stroke-current hover:text-gray-500" />
                          </div>
                          <div x-show="open" x-transition.opacity
                            class="flex flex-col items-center justify-around h-full space-y-2">

                            <div x-on:click="open = false" wire:click="editContact({{ $contactItem->id }})"
                              class="cursor-pointer">
                              <x-tabler-edit class="w-5 h-5 text-gray-300 stroke-current hover:text-blue-400" />
                            </div>
                            @if ($contactItem->id !== $contact['id'])
                              <div x-on:click="open = false" wire:click="removeContact({{ $contactItem->id }})"
                                class="cursor-pointer">
                                <x-tabler-trash class="w-5 h-5 text-gray-300 stroke-current hover:text-red-400" />
                              </div>
                            @endif
                          </div>
                        </div>

                      </div>

                      <div wire:click="setContact({{ $contactItem->id }}), $render" class="space-y-1 cursor-pointer">
                        <div>{{ $contactItem->name }}</div>
                        <div>{{ $contactItem->phone }}</div>
                        <div class="text-sm text-gray-400">{{ $contactItem->email }}</div>
                      </div>

                    </div>
                  @endforeach
                </div>
              @endif

              @if (empty($contacts) || $contacts->count() <= 4)
                <div x-on:click="newContact = true"
                  class="flex items-center justify-center py-3 mt-4 space-x-1 border border-gray-200 border-dashed cursor-pointer hover:border-gray-200 rounded-xl hover:bg-gray-50">
                  <x-tabler-circle-plus class="w-8 h-8 text-gray-400 stroke-current stroke-1 hover:text-gray-600" />
                  <div>Добавить</div>
                </div>
              @endif

            </div>

            <div :class="newContact === true ? 'block' : 'hidden'" x-transition.opacity>

              <div class="absolute top-4 left-4" x-on:click="newContact = false; $wire.set('newContact', [])">
                <x-tabler-chevron-left class="w-6 h-6 text-gray-500 cursor-pointer stroke-current" />
              </div>

              <div class="block w-full space-y-4">
                <div class="w-full">
                  <span class="block pb-1 font-bold text-gray-700">Имя</span>
                  <input wire:model.defer="newContact.name" name="name" class="field" type="text"
                    autocomplete="name">
                  @error('newContact.name')
                    <span class="text-xs text-red-600">
                      Требуеться ваше имя
                    </span>
                  @enderror
                </div>

                <div class="w-full">
                  <span class="block pb-1 font-bold text-gray-700">Телефон</span>
                  <input wire:model.defer="newContact.phone" name="phone" class="field" type="phone"
                    autocomplete="phone" inputmode="tel">
                  @error('newContact.phone')
                    <span class="text-xs text-red-600">
                      {{ $message }}
                    </span>
                  @enderror
                </div>

                <div class="w-full">
                  <span class="block pb-1 font-bold text-gray-700">Email</span>
                  <input wire:model.defer="newContact.email" name="email" class="field" type="email"
                    autocomplete="email" inputmode="email">
                  @error('newContact.email')
                    <span class="text-xs text-red-600">
                      {{ $message }}
                    </span>
                  @enderror
                </div>

                <div>
                  <button wire:click="addNewContact()" class="font-bold text-white bg-green-500 btn hover:bg-green-600">
                    <div wire:loading wire:target="addNewContact">
                      <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                    </div>
                    <div>Сохранить</div>
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
