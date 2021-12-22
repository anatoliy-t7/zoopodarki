    <article class="max-w-screen-lg py-4 mx-auto space-y-4">
      <h1 class="text-3xl font-bold">{{ $page->title }}</h1>
      <div class="space-y-4">
        @foreach (json_decode($page->content, true) as $block)
          @if ($block['title'] !== '')
            <h2 class="text-xl font-semibold">{{ $block['title'] }}</h2>
          @endif
          <div class="p-12 prose bg-white shadow-sm max-w-none rounded-2xl ">
            {!! blockToHtml(json_decode($block['content'], true)) !!}
          </div>
        @endforeach
      </div>
    </article>
