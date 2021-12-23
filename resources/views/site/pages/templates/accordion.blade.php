    <article class="max-w-screen-lg py-4 mx-auto space-y-4">
      <h1 class="text-3xl font-bold">{{ $page->title }}</h1>
      <div x-data="{selected:0}" class="p-12 bg-white shadow-sm rounded-2xl ">
        @foreach (json_decode($page->content, true) as $key => $block)
          <div class="relative border-t border-gray-200">
            <div class="w-full px-8 py-6 text-left cursor-pointer hover:bg-gray-50"
              @click="selected !== {{ $key }} ? selected = {{ $key }} : selected = null">
              <div class="flex items-center justify-between ">
                <h3 class="text-xl font-semibold">{{ $block['title'] }}</h3>
                <div class="transition-transform duration-200"
                  :class="selected === {{ $key }} ? 'rotate-180' : 'rotate-0'">
                  <x-tabler-chevron-down class="w-6 h-6" />
                </div>
              </div>
            </div>

            <div class="relative overflow-hidden transition-all duration-700 max-h-0"
              x-ref="container{{ $key }}"
              x-bind:style="selected == {{ $key }} ? 'max-height: ' + $refs.container{{ $key }}.scrollHeight + 'px' : ''">
              <div class="px-8 pt-4 pb-6 prose max-w-none">
                {!! blockToHtml(json_decode($block['content'], true)) !!}
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </article>
