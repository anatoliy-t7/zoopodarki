@section('title')
  Настройки
@endsection
<div>

  <div class="flex items-center justify-start pb-6 space-x-4">
    <div>
      <a title="Вернуться" href="javascript:%20history.go(-1)" class="text-gray-300 hover:text-gray-500">
        <x-tabler-arrow-left class="w-6 h-6" />
      </a>
    </div>
    <h3 class="text-xl font-bold text-gray-500">Настройки</h3>
  </div>

  <div class="flex flex-col p-6 space-y-6">
    <div class="flex items-center justify-start p-4 space-x-6 bg-white rounded-2xl">

      <button wire:click='backupDb'
        class="flex items-center justify-between px-3 py-2 text-white bg-blue-500 rounded-xl hover:bg-blue-600">
        <div wire:loading wire:target="backupDb">
          <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </div>
        <div>
          Backup DB
        </div>
      </button>

      <button wire:click='generateSitemap'
        class="flex items-center justify-between px-3 py-2 text-white bg-blue-500 rounded-xl hover:bg-blue-600">
        <div wire:loading wire:target="generateSitemap">
          <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </div>
        <div>
          Generate sitemap
        </div>
      </button>

      <button wire:click='queueRun'
        class="flex items-center justify-between px-3 py-2 text-white bg-blue-500 rounded-xl hover:bg-blue-600">
        <div wire:loading wire:target="queueRun">
          <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </div>
        <div>
          Run queue
        </div>
      </button>
      {{-- <button wire:click='updateProduct'
        class="flex items-center justify-between px-3 py-2 text-white bg-red-500 rounded-xl hover:bg-red-700">
        <div wire:loading wire:target="UpdateProduct">
          <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </div>
        <div>
          UpdateProduct
        </div>
      </button> --}}


    </div>

  </div>

</div>
