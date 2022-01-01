@props(['width' => 'sm'])
<div x-cloak x-data="{ open: false }"
  x-effect="document.body.classList.toggle('overflow-hidden', open), document.body.classList.toggle('pr-4', open)"
  x-on:close-modal.window="open = false">

  <button @click="open = true">
    {{ $button }}
  </button>

  <div x-show="open" @keydown.escape.prevent.stop="open = false" role="dialog" aria-modal="true" x-id="['modal-title']"
    :aria-labelledby="$id('modal-title')" class="fixed inset-0 z-50 overflow-y-auto">

    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-50 z-60"></div>

    <div x-show="open" x-transition @click="open = false"
      class="relative flex items-center justify-center min-h-screen p-4 z-70">
      <div @click.stop
        class="relative w-full  max-w-{{ $width }} px-6 py-5 overflow-y-auto bg-white shadow-xl rounded-xl ">
        <div class="flex items-center justify-between">
          <div></div>
          <button x-on:click="open = false" class="link-hover">
            <x-tabler-x class="w-6 h-6 text-gray-500 stroke-current" />
          </button>
        </div>
        <div>
          {{ $content }}
        </div>
      </div>
    </div>
  </div>
</div>
