@section('title')
  @if ($title)
    {{ $title }}
  @else
    Новая страница
  @endif
@endsection
<div x-data="editor">

  <div class="flex items-center justify-between w-full pb-4 space-x-6">

    <h3 class="text-2xl">
      @if ($title)
        {{ $title }}
      @else
        Новая страница
      @endif
    </h3>

  </div>

  <div class="block max-w-5xl p-6 space-y-6 bg-white rounded-2xl">

    <div class="flex items-center justify-between w-full space-x-6">
      <div class="w-8/12 space-y-1">
        <div class="font-bold">Заголовок</div>
        <input wire:model.defer="title" type="text">
        @error('title') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
      </div>
      <div class="w-4/12 space-y-1">
        <div class="font-bold">URL (slug)</div>
        <input wire:model.defer="slug" type="text">
        @error('slug') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
      </div>

    </div>
    <div class="space-y-6">
      <div class="space-y-6">
        <div x-cloak class="space-y-6">
          <template x-for="(block, index) in content" :key="index" hidden>
            <div class="flex gap-6">

              <div wire:ignore x-on:trix-change="content[index] = document.getElementById(`content${index}`).value"
                x-on:trix-initialize="document.getElementById(`content${index}`).editor.loadHTML(block)"
                x-on:trix-attachment-add="uploadFileAttachment($event.attachment)"
                x-on:trix-attachment-remove="removeFileAttachment($event.attachment)" class="w-full">

                <trix-editor :id="'content' + index"></trix-editor>

              </div>
              <button class="text-gray-500 border border-gray-100 hover:text-red-500 btn hover:border-red-300"
                x-on:click="removeBlock(index)">
                <x-tabler-trash class="w-6 h-6" />
              </button>

            </div>
          </template>
        </div>
        <div>
          <button class="text-white bg-blue-400 btn hover:bg-blue-500 hover:shadow-blue-200 hover:shadow-md"
            @click="addNewField">Добавить блок</button>
        </div>

        <script>
          document.addEventListener('alpine:init', () => {
            Alpine.data('editor', () => ({
              content: [' '],
              init() {
                this.content = JSON.parse(@json($content));
              },
              addNewField() {
                this.content.push('');
              },
              removeBlock(index) {

                this.content.splice(index, 1);
              },
              save() {
                window.livewire.emit('save', JSON.stringify(this.content))
              },
            }))
          })
        </script>
      </div>

      <div class="flex items-center justify-between space-x-6">

        <div class="flex items-center justify-end space-x-6">

          <x-toggle wire:model="isActive" :property="$isActive" :lable="'Опубликована'" />


          @if ($pageId)
            <div x-data="{ confirm: false }" class="relative">

              <button x-on:click="confirm = true" type="button" title="remove"
                class="p-2 text-gray-400 rounded-lg hover:text-red-500">
                <x-tabler-trash class="w-6 h-6 stroke-current" />
              </button>

              <div x-show="confirm === true" x-transition
                class="absolute top-0 left-0 px-4 py-2 -mt-10 bg-white shadow-xl rounded-2xl">
                <h3 class="">Вы уверены?</h3>
                <div class="flex justify-around">
                  <button x-on:click="confirm = false"
                    class="px-3 py-2 text-blue-400 rounded-lg hover:text-blue-500 focus:outline-none focus:ring hover:bg-gray-200"
                    type="button">
                    Нет
                  </button>
                  <button x-on:click="confirm = false" wire:click="remove"
                    class="px-3 py-2 text-red-400 rounded-lg hover:text-red-600 focus:outline-none focus:ring hover:bg-gray-200"
                    type="button">
                    Удалить
                  </button>
                </div>
              </div>

            </div>
          @endif


          <button @click="save()" class="p-2 px-3 text-white bg-pink-500 cursor-pointer rounded-2xl hover:bg-pink-700">
            Сохранить
          </button>
        </div>

      </div>

    </div>
    <script>
      function uploadFileAttachment(attachment) {
        console.log(attachment)

        @this.upload('newFiles', attachment.file, function(uploadedUrl) {
          const eventName = 'zoo:trix-upload-completed:${btoa(uploadedUrl)}';
          const listener = function(event) {
            attachment.setAttributes(event.detail);
            window.removeEventListener(eventName, listener);
          }
          window.addEventListener(eventName, listener)
          @this.call('completeUpload', uploadedUrl, eventName);

        }, () => {}, function(event) {
          attachment.setUploadProgress(event.detail.progress)
        })

      }

      function removeFileAttachment(attachment) {
        @this.call('removeFileAttachment', attachment.attachment.attributes.values.url.split("/").pop());
      }
      document.addEventListener("keydown", function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
          e.preventDefault();
          window.livewire.emit('save');
        }

      }, false);
    </script>
  </div>
