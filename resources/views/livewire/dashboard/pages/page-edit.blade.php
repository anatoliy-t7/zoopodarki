@section('title')
  @if ($title)
    {{ $title }}
  @else
    Новая страница
  @endif
@endsection
<div>

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

    <div class="flex items-start justify-between w-full gap-6">
      <div class="flex flex-col w-8/12 gap-4">
        <div class="flex flex-col gap-1">
          <div class="font-bold">Заголовок</div>
          <input wire:model.defer="title" type="text">
          @error('title') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="flex flex-col gap-1">
          <div class="font-bold">Meta загаловок</div>
          <input wire:model.defer="meta_title" type="text">
          @error('meta_title') <span class="text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>

        <div class="flex flex-col gap-1">
          <div class="font-bold">Meta описание</div>
          <textarea rows="2" wire:model.defer="meta_description"></textarea>
          @error('meta_description') <span class="text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="flex flex-col w-4/12 gap-4">
        <div class="flex flex-col gap-1">
          <div class="font-bold">URL (slug)</div>
          <input wire:model.defer="slug" type="text">
          @error('slug') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="flex flex-col gap-1">
          <label for="template" class="font-bold">Дизайн страницы</label>
          <div class="relative">
            <select wire:model.defer="pageTemplate" name="template" id="template" class="field">
              @foreach ($templates as $template)
                <option value="{{ $template }}">{{ $template }}</option>
              @endforeach
            </select>
          </div>
          @error('pageTemplate')
            <span class="text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>
      </div>
    </div>

    <div>

    </div>

    <div class="flex flex-col gap-6">
      @foreach ($editor as $index => $block)

        <div class="flex items-start gap-6" wire:key="content-field-{{ $index }}">
          <div wire:ignore class="flex flex-col w-full max-w-screen-md gap-4">
            <input type="text" wire:model.defer="editor.{{ $index }}.title" placeholder="Заголовок блока">
            <x-editor :index="$index" :content="$block['content']"
              wire:model.defer="editor.{{ $index }}.content" />
          </div>
          <button class="text-gray-500 border border-gray-100 hover:text-red-500 btn hover:border-red-300"
            wire:click="removeBlockOfEditor({{ $index }})">
            <x-tabler-trash class="w-6 h-6" />
          </button>
        </div>
      @endforeach
      <div>
        <button class="text-white bg-blue-400 btn hover:bg-blue-500 hover:shadow-blue-200 hover:shadow-md"
          wire:click="addBlockOfEditor">Добавить блок</button>
      </div>
    </div>

    <div class="space-y-6">

      <div class="flex items-center justify-end space-x-6">


        <div class="flex items-center justify-end space-x-6">

          <x-toggle wire:model="isActive" :property="$isActive" :lable="'Опубликована'" />

          <button wire:click="save"
            class="p-2 px-3 text-white bg-pink-500 cursor-pointer rounded-2xl hover:bg-pink-700">
            Сохранить
          </button>
        </div>

      </div>

    </div>
    <script>
      document.addEventListener("keydown", function(e) {
        if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) {
          e.preventDefault();
          window.livewire.emit('save')
        }
      }, false);
    </script>
  </div>
