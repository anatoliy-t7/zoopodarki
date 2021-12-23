    <article class="max-w-screen-lg py-4 mx-auto space-y-4">
      <h1 class="text-3xl font-bold">{{ $page->title }}</h1>
      <div class="space-y-4">
        @foreach (json_decode($page->content, true) as $block)
          <div class="p-12 prose bg-white shadow-sm max-w-none rounded-2xl ">
            @if ($block['title'] !== '')
              <h2>{{ $block['title'] }}</h2>
            @endif
            {!! blockToHtml(json_decode($block['content'], true)) !!}
          </div>
        @endforeach
      </div>
    </article>
