    <article class="max-w-screen-lg py-4 mx-auto space-y-4">
      <h1 class="text-3xl font-bold">{{ $page->title }}</h1>
      <div class="space-y-4">
        @foreach (json_decode($page->content) as $block)
          <div class="p-8 bg-white shadow-sm rounded-2xl">
            {!! $block !!}
          </div>
        @endforeach
      </div>
    </article>
