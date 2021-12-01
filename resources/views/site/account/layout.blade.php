@extends ('layouts.app')
@section('content')
  <div
    class="justify-between block px-4 py-10 my-6 space-y-8 bg-white divide-gray-200 rounded-lg lg:divide-x lg:flex lg:space-y-0">
    <div class="w-full lg:w-2/12 md:pl-4">
      @include ('site.account.sidebar')
    </div>
    <div class="w-full lg:w-10/12 md:px-4">
      @yield ('block')
    </div>
  </div>
@endsection
