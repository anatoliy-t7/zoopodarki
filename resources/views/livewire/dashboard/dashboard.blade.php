@section('title')
  Dashboard
@endsection
<div class="flex justify-between min-h-full space-x-6">
  <div
    class="flex flex-col items-start justify-start w-full h-full space-y-6 md:space-y-0 md:space-x-6 md:flex-row md:w-10/12">

    <div class="inline-block w-auto px-5 py-3 bg-white rounded-xl">
      <div class="pb-2">Товары с описанием</div>
      <div class="flex items-center space-x-2">

        <div><span class="text-sm text-gray-400">Сделано: </span>{{ $productsDoneDescription }}</div>
        <div><span class="text-sm text-gray-400">Осталось: </span>{{ $productsDoesNotHaveDescription }}</div>
      </div>
    </div>

    <div class="inline-block w-auto px-5 py-3 bg-white rounded-xl">
      <div class="pb-2">Товары с фото</div>
      <div class="flex items-center space-x-2">

        <div><span class="text-sm text-gray-400">Сделано: </span>{{ $productsDoneImage }}</div>
        <div><span class="text-sm text-gray-400">Осталось: </span>{{ $productsDoesNotHaveImage }}</div>
      </div>
    </div>

  </div>
  <div class="w-full h-full space-y-4 md:w-60">

    <div>
      @if ($pendingReviews->count() > 0)
        <a href="{{ route('dashboard.reviews', ['filteredBy' => 'pending']) }}"
          class="relative inline-flex items-center w-full px-5 py-3 space-x-5 bg-white cursor-pointer rounded-xl hover:shadow-md">
          <span class="absolute flex w-3 h-3 -top-1 -right-1">
            <span class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 animate-ping"></span>
            <span class="relative inline-flex w-3 h-3 bg-orange-500 rounded-full"></span>
          </span>
          <div class="text-2xl font-semibold text-orange-500">
            {{ $pendingReviews->count() }}
          </div>
          <div>
            <h4>Отзывы</h4>
            <div class="text-xs leading-snug text-gray-400">ожидающие проверки</div>
          </div>
        </a>
      @endif
    </div>

    <div>
      @if ($pendingWaitlist->count() > 0)
        <a href="{{ route('dashboard.waitlists', ['filteredBy' => 'pending']) }}"
          class="relative inline-flex items-center w-full px-5 py-3 space-x-5 bg-white cursor-pointer rounded-xl hover:shadow-md">
          <span class="absolute flex w-3 h-3 -top-1 -right-1">
            <span class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 animate-ping"></span>
            <span class="relative inline-flex w-3 h-3 bg-orange-500 rounded-full"></span>
          </span>
          <div class="text-2xl font-semibold text-orange-500">
            {{ $pendingWaitlist->count() }}
          </div>
          <div>
            <h4>Товары</h4>
            <div class="text-xs leading-snug text-gray-400">которые ждут люди</div>
          </div>
        </a>
      @endif
    </div>

  </div>

</div>
