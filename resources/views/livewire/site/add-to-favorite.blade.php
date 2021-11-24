<div>
  @if ($mode)
    <button wire:click="removeFavorite({{ $model->id }})" title="Убрать из избранных"
      class="inline-flex items-center justify-center p-0 text-red-500 transition ease-in-out transform link-hover hover:text-gray-500 active:scale-95">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
        class="w-6 h-6 fill-current" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512">
        <path
          d="M352.92 80C288 80 256 144 256 144s-32-64-96.92-64c-52.76 0-94.54 44.14-95.08 96.81c-1.1 109.33 86.73 187.08 183 252.42a16 16 0 0 0 18 0c96.26-65.34 184.09-143.09 183-252.42c-.54-52.67-42.32-96.81-95.08-96.81z"
          fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32">
        </path>
      </svg>
    </button>
  @else
    <button wire:click="setFavorite()" title="В избранные"
      class="inline-flex items-center justify-center p-0 text-gray-500 transition ease-in-out transform link-hover hover:text-red-500 active:scale-95">

      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
        class="w-6 h-6 fill-current" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512">
        <path
          d="M352.92 80C288 80 256 144 256 144s-32-64-96.92-64c-52.76 0-94.54 44.14-95.08 96.81c-1.1 109.33 86.73 187.08 183 252.42a16 16 0 0 0 18 0c96.26-65.34 184.09-143.09 183-252.42c-.54-52.67-42.32-96.81-95.08-96.81z"
          fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"></path>
      </svg>

    </button>
  @endif
</div>
