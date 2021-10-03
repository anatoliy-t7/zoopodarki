<div>
  @push ('header-css')
  <link href="{{ mix('css/calendar.css') }}" rel="stylesheet">
  @endpush

  <label for="datepicker" class="block pb-2 font-bold text-gray-700">
    <span x-show="toggle === 1">Дата самовывоза</span>
    <span x-show="toggle === 0">Дата доставки</span>
  </label>
  <div wire:ignore class="relative">
    <input wire:model.lazy="date" id="calendar" type="text" readonly class="field" name="date">
    <div class="absolute top-4 right-3">
      <svg class="w-6 h-6 text-gray-400 fill-current" viewBox="0 0 24 24">
        <path
          d="M19,4H17V3a1,1,0,0,0-2,0V4H9V3A1,1,0,0,0,7,3V4H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V7A3,3,0,0,0,19,4Zm1,15a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H20Zm0-9H4V7A1,1,0,0,1,5,6H7V7A1,1,0,0,0,9,7V6h6V7a1,1,0,0,0,2,0V6h2a1,1,0,0,1,1,1Z" />
      </svg>
    </div>
  </div>

  <script src="{{ mix('js/calendar.js') }}"></script>

</div>