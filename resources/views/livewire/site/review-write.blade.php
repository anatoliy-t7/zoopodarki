<div x-cloak x-data="writeRating" @close-writer.window="close(event)" class="relative">

  <button @click="open" aria-label="Написать отзыв"
    class="px-4 py-3 text-white bg-green-500 rounded-lg focus:outline-none hover:bg-green-600">Написать отзыв
  </button>

  <div x-cloak @click="close" x-show="write" x-transition.opacity
    class="fixed top-0 bottom-0 left-0 right-0 z-40 w-screen h-screen overflow-hidden bg-gray-700 bg-opacity-50 pointer-events-auto">
  </div>

  <div x-cloak :class="write ? 'translate-y-0 ease-out' : 'translate-y-full ease-in'"
    class="fixed bottom-0 left-0 z-50 w-full h-full px-6 pt-4 overflow-y-auto transition duration-300 transform bg-white md:h-auto md:pb-6"
    style="min-height: 400px">

    <div class="absolute top-0 right-0 mt-4 mr-4">
      <button @click="close" class="link-hover">
        <x-tabler-x class="w-6 h-6 text-gray-500 stroke-current" />
      </button>
    </div>

    <div class="block max-w-3xl py-6 mx-auto space-y-4">

      <div class="text-xl font-semibold text-center md:text-left">Напишите ваш отзыв</div>
      <div class="flex flex-col justify-between md:space-x-6 md:flex-row md:space-y-0">

        <div class="w-full md:w-8/12 ">
          <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            class="relative h-24 p-2 border-2 border-dashed rounded-lg bg-gray-50 hover:border-orange-400">

            <div class="absolute top-0 left-0 flex items-center justify-start w-full h-full px-2 space-x-2">
              @forelse ($photos as $photo)
                <div class="relative z-10 w-24 h-24 py-2">
                  <img loading="lazy" class="object-cover object-center w-full h-full rounded-lg"
                    src="{{ $photo->temporaryUrl() }}">
                </div>
              @empty
                <div class="flex flex-col items-center justify-center w-full space-y-2 text-gray-500">
                  <x-tabler-camera-plus class="w-6 h-6 stroke-current " />
                  <div class="flex text-sm text-center">Нажмите сюда для загрузки фотографий (max: 5)</div>
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
          <div class="pt-1">
            @error('photos') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            @error('photos.*') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="order-first w-full pb-4 text-center md:w-4/12 md:order-last md:pb-0">

          <div wire:ignore x-data="{
            rating: 0,
            hoverRating: 0,
            ratings: [ 1, 2, 3, 4, 5]
          }" class="flex items-center justify-center space-x-1">

            <template x-for="star in ratings" hidden>
              <button @click="rating = star" @mouseover="hoverRating = star" @mouseleave="hoverRating = rating"
                x-on:click.prevent="$wire.set('stars', rating)" aria-hidden="true"
                class="p-1 rounded-sm focus:outline-none focus:ring">
                <svg :class="{ 'text-yellow-500' : hoverRating >= star, 'text-yellow-400' : rating >= star }"
                  class="w-6 text-gray-400 transition duration-150 cursor-pointer fill-current"
                  viewBox="0 -10 511.991 511" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M510.652 185.883a27.177 27.177 0 00-23.402-18.688l-147.797-13.418-58.41-136.75C276.73 6.98 266.918.497 255.996.497s-20.738 6.483-25.023 16.53l-58.41 136.75-147.82 13.418c-10.837 1-20.013 8.34-23.403 18.688a27.25 27.25 0 007.937 28.926L121 312.773 88.059 457.86c-2.41 10.668 1.73 21.7 10.582 28.098a27.087 27.087 0 0015.957 5.184 27.14 27.14 0 0013.953-3.86l127.445-76.203 127.422 76.203a27.197 27.197 0 0029.934-1.324c8.851-6.398 12.992-17.43 10.582-28.098l-32.942-145.086 111.723-97.964a27.246 27.246 0 007.937-28.926zM258.45 409.605" />
                </svg>
              </button>
            </template>

            <div class="hidden">
              <label for="rating">Ваш рейтинг</label>
              <input type="number" name="rating" x-model="rating">
            </div>

          </div>

          <div class="py-2 text-xs text-gray-500">Оцените товар от 1 до 5 звезд</div>
          @error('stars')
            <div class="pt-1 text-sm text-red-500">Вы не поставили рейтинг</div>
          @enderror
        </div>

      </div>

      <div class="flex flex-col items-start justify-between space-y-4 md:space-y-0 md:space-x-6 md:flex-row">
        <div class="relative w-full md:w-8/12">
          <textarea wire:model.defer="body"
            class="w-full px-3 py-4 font-semibold border border-gray-50 hover:border-gray-200 bg-gray-50 rounded-xl focus:outline-none focus:ring focus:bg-white scrollbar"
            placeholder="Напишите свой отзыв" rows="6">
          </textarea>
          @error('body')
            <div class="pt-1 text-sm text-red-500">Вы не написали отзыв</div>
          @enderror

          <div x-data="{ alert: false }">
            <div x-show="alert" x-transition.opacity class="relative px-5 py-4 bg-blue-100 rounded-2xl">

              <div class="flex items-center justify-between pb-2">
                <h3 class="text-sm font-semibold leading-tight">Мы ожидаем от вас честный и корректный отзыв!</h3>
                <button x-on:click="alert = false" class="-mt-1 -mr-2 text-gray-600 rounded-full link-hover">
                  <x-tabler-x class="w-6 h-6 stroke-current" />
                </button>
              </div>

              <div>
                <p class="text-xs">Отзыв не пройдет модерацию, если:</p>
                <ul class="pl-2 ml-2 space-y-1 text-xs list-disc">
                  <li class="">Использованы нецензурные выражения, оскорбления и угрозы</li>
                  <li class="">Указаны адреса, телефоны и ссылки, содержащие прямую рекламу
                  </li>
                  <li class="">Обсуждается цена товара и ее изменения</li>
                  <li class="">Отзыв не относится к теме</li>
                </ul>
              </div>

            </div>
            <div class="absolute px-2 bottom-2 right-2">
              <button x-on:click="alert = true" x-show="alert == false" x-transition.opacity
                class="-mt-1 -mr-2 text-blue-400 rounded-full hover:text-blue-500">
                <x-tabler-alert-circle class="w-6 h-6 stroke-current" />
              </button>
            </div>
          </div>
        </div>
        <div class="w-full md:w-4/12">

          <button type="submit" wire:click="saveReview" wire:loading.attr="disabled" title="Отправить"
            class="relative flex items-center justify-center w-full px-3 py-3 text-white bg-green-500 rounded-lg g-recaptcha hover:bg-green-600">
            <div class="absolute top-0 left-0 mt-3 ml-3 fill-current" wire:loading wire:target="saveReview">
              <svg class="w-4 h-4 mx-auto text-white animate-spin " xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
            </div>
            <span>Отправить отзыв</span>
          </button>

        </div>
      </div>



    </div>
  </div>

  <script>
    document.addEventListener('alpine:initializing', () => {
      Alpine.data('writeRating', () => ({
        body: document.body,
        write: false,
        open() {
          this.write = true;
          this.body.classList.add('overflow-hidden')
        },

        close() {
          this.write = false;
          this.body.classList.remove('overflow-hidden');
        },
      }))
    })
  </script>
</div>
