@section('title')
  Отзывы
@endsection
<div>

  <div x-data="handler" @get-items.window="getItems(event)" @new.window="openForm(event)"
    @close.window="closeForm(event)" class="space-y-2">

    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Отзывы</h3>

    </div>

    <div class="flex items-center justify-between w-full space-x-6">

      <x-dashboard.search />

      <label class="flex items-center justify-end space-x-4">
        <span class="text-gray-700">Фильтр по статусу</span>
        <div class="relative">
          <select wire:model="filteredBy" name="status" class="field">
            <option default value="">Все</option>
            @foreach ($statuses as $filterStatus)
              <option value="{{ $filterStatus }}"> {{ __('constants.review_status.' . $filterStatus) }}</option>
            @endforeach
          </select>
        </div>
      </label>

    </div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head sortable wire:click="sortBy('id')"
            :direction="$sortField === 'id' ? $sortDirection : null">Id
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Клиент</x-dashboard.table.head>
          <x-dashboard.table.head>Товар</x-dashboard.table.head>
          <x-dashboard.table.head sortable wire:click="sortBy('rating')"
            :direction="$sortField === 'rating' ? $sortDirection : null">
            <x-tabler-star class="w-5 h-5 text-gray-500 stroke-current" />
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            <x-tabler-photo class="w-6 h-6 text-gray-500 stroke-current" />
          </x-dashboard.table.head>
          <x-dashboard.table.head>Статус</x-dashboard.table.head>
          <x-dashboard.table.head></x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">
          @forelse ($reviews as $review)
            <x-dashboard.table.row wire:key="{{ $review->id }}" @click="openForm"
              wire:click="openForm({{ $review->id }})" class="cursor-pointer">

              <x-dashboard.table.cell>
                {{ $review->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $review->user->name }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                <div class="max-w-xs text-sm truncate" title="{{ $review->revieweable->name }}">
                  {{ $review->revieweable->name }}
                </div>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="pl-8">
                {{ $review->rating }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="pl-8">
                {{ $review->getMedia('product-customers-photos')->count() }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                <div class="flex items-center justify-start">

                  <div class="flex items-center py-1 space-x-2 text-sm">
                    {{ __('constants.review_status.' . $review->status) }}
                  </div>

                </div>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="flex items-center justify-end invisible group-hover:visible">

                <button @click="openForm" wire:click="openForm({{ $review->id }})" title="Редактировать"
                  class="p-2 text-gray-400 rounded-lg hover:text-indigo-500">
                  <svg class="fill-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" title="edit">
                    <path
                      d="M26.957,4.886c-0.39,-0.391 -1.024,-0.391 -1.414,0l-10.896,10.896c-0.593,0.593 -1.07,1.291 -1.407,2.058l-0.003,0.006c-0.307,0.7 0.403,1.413 1.104,1.11c0.777,-0.337 1.484,-0.817 2.083,-1.416l10.886,-10.887c0.391,-0.39 0.391,-1.023 0,-1.414l-0.353,-0.353Zm-8.039,3.245c0.311,0.032 0.622,-0.071 0.843,-0.292l0.737,-0.737c0.274,-0.274 0.145,-0.736 -0.236,-0.804c-1.184,-0.21 -2.592,-0.298 -4.262,-0.298c-8,0 -10,2 -10,10c0,8 2,10 10,10c8,0 10,-2 10,-10c0,-1.507 -0.071,-2.801 -0.24,-3.909c-0.059,-0.39 -0.53,-0.529 -0.808,-0.251l-0.757,0.757c-0.215,0.215 -0.319,0.517 -0.293,0.821c0.064,0.734 0.098,1.587 0.098,2.582c0,4.015 -0.55,5.722 -1.414,6.586c-0.864,0.864 -2.572,1.414 -6.586,1.414c-4.014,0 -5.722,-0.55 -6.586,-1.414c-0.864,-0.864 -1.414,-2.571 -1.414,-6.586c0,-4.014 0.55,-5.721 1.414,-6.585c0.864,-0.864 2.572,-1.415 6.586,-1.415c1.151,0 2.112,0.046 2.918,0.131Z">
                    </path>
                  </svg>
                </button>

              </x-dashboard.table.cell>

            </x-dashboard.table.row>
          @empty
            <x-dashboard.table.row>
              <x-dashboard.table.cell>
                Пусто
              </x-dashboard.table.cell>
            </x-dashboard.table.row>
          @endforelse
        </x-slot>

      </x-dashboard.table>
    </div>

    <div>
      <x-overflow-bg />

      <x-dashboard.modal class="overflow-y-auto">
        @if ($reviewEdit)
          <x-loader wire:target="openForm" />

          <div class="block space-y-4">



            <div class="flex justify-start w-full space-x-4">
              <div class="w-1/12 font-bold">Клиент</div>
              <div class="w-11/12">{{ $reviewEdit->user->name }}</div>
            </div>

            <div class="flex justify-start w-full space-x-4">
              <div class="w-1/12 font-bold">Товар</div>
              <div class="w-11/12 max-w-2xl truncate">{{ $reviewEdit->revieweable->name }}</div>
            </div>

            <div class="flex justify-start w-full space-x-4">
              <div class="w-1/12 font-bold">Рейтинг</div>
              <div class="w-11/12 truncate">{{ $reviewEdit->rating }}</div>
            </div>


            @if ($reviewEdit->media->isNotEmpty())
              <div class="py-4">
                <div class="pb-2 font-bold">Фотографии клиента:</div>
                <div id="lightbox" class="flex items-center justify-start space-x-2">
                  @forelse ($reviewEdit->getMedia('product-customers-photos') as $image)
                    <div x-data="{ deleteIt: false }" x-on:mouseover="deleteIt = true"
                      x-on:mouseleave="deleteIt = false" class="relative z-20 cursor-pointer">
                      <img loading="lazy" class="object-cover object-center w-16 h-16"
                        src="{{ $image->getUrl('thumb') }}" alt="image" data-bp="{{ $image->getUrl() }}">

                      <div x-show="deleteIt" x-transition class="absolute inset-x-auto bottom-0 z-30 w-full -mb-6">
                        <button title="Удалить фото" wire:click="deletePhoto({{ $image->id }})"
                          class="block w-6 h-6 mx-auto text-red-500 bg-white rounded-full shadow-lg hover:bg-red-500 hover:text-white">
                          <x-tabler-circle-x class="w-6 h-6 stroke-current" />
                        </button>
                      </div>

                    </div>
                  @empty
                  @endforelse
                </div>
              </div>
            @else
              <div>Клиент не загружал фотографий</div>
            @endif



            <div class="space-y-1">
              <label class="font-bold">Отзыв</label>
              <textarea rows="3" wire:model.defer="body" class=" field">
          </textarea>
              @error('body') <span class="text-sm text-red-500">Поле обязательно для заполнения</span> @enderror
            </div>




            <div class="flex justify-between space-x-4">

              @can('edit')

                <label class="flex items-center justify-end space-x-4">
                  <span class="text-gray-700">Статус</span>
                  <div class="relative">
                    <select wire:model="status" name="status" class="field">
                      @foreach ($statuses as $statusForReview)
                        <option value="{{ $statusForReview }}">
                          {{ __('constants.review_status.' . $statusForReview) }}</option>
                      @endforeach
                    </select>
                  </div>
                  @error('status') <span class="text-xs text-red-500">Поле обязательно для
                    заполнения</span> @enderror
                </label>

              @endcan
              <div class="flex justify-between space-x-4">
                <x-dashboard.confirm :confirmId="$reviewEdit->id" wire:click="remove({{ $reviewEdit->id }})" />
                <button wire:click="sandEmail" class="text-gray-900 bg-gray-100 btn hover:bg-gray-200">
                  Оповестить
                </button>
                <button wire:click="save" class="text-white bg-green-500 btn hover:bg-green-600">
                  Сохранить
                </button>
              </div>
            </div>

          </div>

        @endif
      </x-dashboard.modal>

    </div>

    <div class="flex items-center px-4">
      <div class="w-8/12">
        {{ $reviews->links() }}
      </div>

      <div class="flex items-center justify-end w-4/12 space-x-4">
        <x-dashboard.items-per-page />
      </div>
    </div>

    <script>
      document.addEventListener('alpine:initializing', () => {
        Alpine.data('handler', () => ({
          form: false,
          confirm: null,
          description: null,
          body: document.body,
          openForm() {
            this.form = true
            this.body.classList.add("overflow-hidden")
          },

          closeForm() {
            this.form = false
            this.body.classList.remove("overflow-hidden")
          },

          askDelete($id) {
            this.confirm = $id
          },
          closeConfirm() {
            this.confirm = null
          },
        }))
      })
    </script>

    <script>
      document.addEventListener("keydown", function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
          e.preventDefault();
          window.livewire.emit('save')
        }

        if (e.keyCode == 27) {
          e.preventDefault();
          var event = new CustomEvent('close');
          window.dispatchEvent(event);
        }

      }, false);
    </script>

  </div>
</div>
