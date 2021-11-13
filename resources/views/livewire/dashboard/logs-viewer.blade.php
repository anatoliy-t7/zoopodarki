@section('title')
  Log Files
@endsection
<div x-data="handlerlogs" @close.window="closeForm(event)" class="space-y-2">

  <div>
    <div class="flex items-center justify-between w-full pb-2 space-x-6">

      <h3 class="text-2xl">Log Files</h3>

    </div>
  </div>

  <div class="flex items-center justify-start w-full space-x-6">
    @if ($filename !== '')
      <div class="flex items-center justify-start space-x-2 text-sm">
        @foreach ($types as $key => $type)
          <button
            class="px-2 py-1 rounded-xl text-white focus:outline-none focus:ring-4 focus:ring-blue-400 cursor-pointer hover:ring-2 hover:ring-blue-400 {{ Arr::has($type, 'class') ? $type['class'] : '' }}"
            wire:click="$set('selectedType', '{{ $type['name'] }}')">
            {{ $type['name'] }}</button>
        @endforeach
        <button
          class="px-2 py-1 text-gray-500 cursor-pointer btn rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-400 hover:ring-2 hover:ring-blue-400"
          wire:click="$set('selectedType', '')">
          All</button>
      </div>

      <div>
        <label for="filteredByCategory"></label>
        <select wire:model="date" name="filteredByCategory" id="filteredByCategory" class="w-40">
          @foreach ($availableDates as $availableDate)
            <option wire:ignore value="{{ $availableDate }}" {{ $availableDate === $date ? 'selected' : '' }}>
              {{ $availableDate }}</option>
          @endforeach
        </select>
      </div>

      <button class="text-sm btn" wire:click="delete">
        Delete day ({{ $this->filename }})
      </button>
    @endif


  </div>

  <div class="py-4">
    <x-dashboard.table>
      <x-slot name="head">
        <x-dashboard.table.head>Timestamp</x-dashboard.table.head>
        <x-dashboard.table.head>Env</x-dashboard.table.head>
        <x-dashboard.table.head>Type</x-dashboard.table.head>
        <x-dashboard.table.head>Message</x-dashboard.table.head>
      </x-slot>

      <x-slot name="body">
        @if ($logs)
          @foreach ($logs->sortByDesc('timestamp') as $key => $log)
            <x-dashboard.table.row class="cursor-pointer " x-on:click="openForm"
              wire:click="openForm('{{ $log['id'] }}')">

              <x-dashboard.table.cell class="w-3/12">
                {{ $log['timestamp'] }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="w-1/12">
                {{ $log['env'] }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="w-2/12">
                {{ $log['type'] }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell class="w-6/12 max-w-6xl truncate">
                {{ $log['message'] }}
              </x-dashboard.table.cell>

            </x-dashboard.table.row>
          @endforeach
        @else
          <x-dashboard.table.row>
            <x-dashboard.table.cell colspan="4" class="py-4 space-y-6 text-center">
              <x-tabler-hand-little-finger class="w-12 h-12 mx-auto stroke-1" />
              <div>Пусто, нет логов</div>
            </x-dashboard.table.cell>
          </x-dashboard.table.row>
        @endif
      </x-slot>

    </x-dashboard.table>
  </div>

  <div>
    <x-overflow-bg />
    <x-dashboard.modal>

      <div class="flex flex-col justify-between space-y-4">
        @if ($selectedLog)

          <div class="flex items-center space-x-4">
            <div class="w-24">Timestamp:</div>
            <div class="font-bold ">{{ $selectedLog['timestamp'] }}</div>
          </div>

          <div class="flex items-center space-x-4">
            <div class="w-24">Env:</div>
            <div class="font-bold">{{ $selectedLog['env'] }}</div>
          </div>

          <div class="flex items-center space-x-4">
            <div class="w-24">Type:</div>
            <div class="font-bold">{{ $selectedLog['type'] }}</div>
          </div>

          <div class="space-y-1">
            <div class="">Message:</div>
            <div class="max-w-full font-bold ">{{ $selectedLog['message'] }}
            </div>
          </div>


        @endif
    </x-dashboard.modal>
  </div>

  <script>
    document.addEventListener('alpine:initializing', () => {
      Alpine.data('handlerlogs', () => ({
        form: false,
        body: document.body,
        openForm() {
          this.form = true
          this.body.classList.add("overflow-hidden")
        },
        closeForm() {
          this.form = false
          this.body.classList.remove("overflow-hidden")
        },
      }))
    })
  </script>


</div>
