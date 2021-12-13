@section('title')
  Dashboard
@endsection
<div class="flex flex-wrap items-start justify-start gap-6">


  @if ($orders)
    <div class="w-full px-8 pt-6 pb-8 bg-white rounded-lg md:w-3/12">
      <h1 class="font-extrabold tracking-wider">Заказы</h1>

      <div class="flex flex-col mt-5 text-sm gap-7">

        @if ($orders->where('status', 'pending_confirm')->first())
          <a href="{{ route('dashboard.orders', ['status' => 'pending_confirm']) }}"
            class="flex items-center justify-between px-4 py-3 rounded shadow-sm bg-yellow-50 hover:bg-yellow-100">
            <div class="font-bold tracking-wider text-gray-700">
              {{ __('constants.order_status.' . $orders->where('status', 'pending_confirm')->first()->status) }}</div>
            <div class="text-xl font-bold text-yellow-500">{{ $orders->where('status', 'pending_confirm')->count() }}
            </div>
          </a>
        @endif

        @if ($orders->where('status', 'pending_payment')->first())
          <a href="{{ route('dashboard.orders', ['status' => 'pending_payment']) }}"
            class="flex items-center justify-between px-4 py-3 rounded shadow-sm bg-pink-50 hover:bg-pink-100">
            <div class="font-bold tracking-wider text-gray-700">
              {{ __('constants.order_status.' . $orders->where('status', 'pending_payment')->first()->status) }}</div>
            <div class="text-xl font-bold text-yellow-500">{{ $orders->where('status', 'pending_payment')->count() }}
            </div>
          </a>
        @endif

        @if ($orders->where('status', 'processing')->first())
          <a href="{{ route('dashboard.orders', ['status' => 'processing']) }}"
            class="flex items-center justify-between px-4 py-3 rounded shadow-sm bg-green-50 hover:bg-green-100">
            <div class="font-bold tracking-wider text-gray-700">
              {{ __('constants.order_status.' . $orders->where('status', 'processing')->first()->status) }}</div>
            <div class="text-xl font-bold text-yellow-500">{{ $orders->where('status', 'processing')->count() }}</div>
          </a>
        @endif

        @if ($orders->where('status', 'hold')->first())
          <a href="{{ route('dashboard.orders', ['status' => 'hold']) }}"
            class="flex items-center justify-between px-4 py-3 rounded shadow-sm bg-indigo-50 hover:bg-indigo-100">
            <div class="font-bold tracking-wider text-gray-700">
              {{ __('constants.order_status.' . $orders->where('status', 'hold')->first()->status) }}</div>
            <div class="text-xl font-bold text-yellow-500">{{ $orders->where('status', 'hold')->count() }}</div>
          </a>
        @endif


      </div>
    </div>
  @endif

  <div class="flex flex-col w-full gap-6 md:w-3/12">
    @if ($newProducts1C)
      <div class="flex items-center justify-between gap-6 px-8 py-6 bg-white rounded-lg">
        <div class="">Не обработанных товаров из 1С</div>
        <div class="text-xl font-bold text-indigo-500">{{ $newProducts1C->count() }}</div>
      </div>
    @endif

    @if ($productsDoesNotHaveDescription > 0)
      <div class="flex items-center justify-between gap-6 px-8 py-6 bg-white rounded-lg">
        <div>Товары без описания</div>
        <div class="text-xl font-bold text-indigo-500">{{ $productsDoesNotHaveDescription }}</div>
      </div>
    @endif
    @if ($productsDoesNotHaveImage > 0)
      <div class="flex items-center justify-between gap-6 px-8 py-6 bg-white rounded-lg">
        <div>Товары без фото</div>
        <div class="text-xl font-bold text-indigo-500">{{ $productsDoesNotHaveImage }}</div>
      </div>
    @endif
  </div>

  <div class="w-full h-full space-y-4 md:w-2/12">

    <div>
      @if ($pendingReviews->count() > 0)
        <a href="{{ route('dashboard.reviews', ['filteredBy' => 'pending']) }}"
          class="relative inline-flex items-center justify-between w-full gap-6 px-8 py-4 bg-white cursor-pointer rounded-xl hover:shadow-md">
          <div>
            <h4 class="pt-0">Отзывы</h4>
            <div class="text-xs leading-snug text-gray-400">ожидающие проверки</div>
            <span class="absolute flex w-3 h-3 -top-1 -right-1">
              <span
                class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 animate-ping"></span>
              <span class="relative inline-flex w-3 h-3 bg-orange-500 rounded-full"></span>
            </span>
          </div>
          <div class="text-2xl font-semibold text-orange-500">
            {{ $pendingReviews->count() }}
          </div>
        </a>
      @endif
    </div>

    <div>
      @if ($pendingWaitlist->count() > 0)
        <a href="{{ route('dashboard.waitlists', ['filteredBy' => 'pending']) }}"
          class="relative inline-flex items-center justify-between w-full gap-6 px-8 py-4 bg-white cursor-pointer rounded-xl hover:shadow-md">
          <div>
            <h4 class="pt-0">Товары</h4>
            <div class="text-xs leading-snug text-gray-400">которые ждут люди</div>
            <span class="absolute flex w-3 h-3 -top-1 -right-1">
              <span
                class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 animate-ping"></span>
              <span class="relative inline-flex w-3 h-3 bg-orange-500 rounded-full"></span>
            </span>
          </div>
          <div class="text-2xl font-semibold text-orange-500">
            {{ $pendingWaitlist->count() }}
          </div>
        </a>
      @endif
    </div>

  </div>




</div>
