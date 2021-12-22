    <article class="max-w-screen-lg py-4 mx-auto space-y-4">
      <h1 class="text-3xl font-bold">{{ $page->title }}</h1>
      <div x-data="{selected:0}" class="p-8 bg-white shadow-sm rounded-2xl">
        @foreach (json_decode($page->content) as $key => $block)

          <ul class="shadow-box">

            <li class="relative border-b border-gray-200">

              <button type="button" class="w-full px-8 py-6 text-left"
                @click="selected !== {{ $key }} ? selected = {{ $key }} : selected = null">
                <div class="flex items-center justify-between">
                  <span>
                    Should I use reCAPTCHA v2 or v3? </span>
                  <span class="ico-plus"></span>
                </div>
              </button>

              <div class="relative overflow-hidden transition-all duration-700 max-h-0"
                x-ref="container{{ $key }}"
                x-bind:style="selected == {{ $key }} ? 'max-height: ' + $refs.container{{ $key }}.scrollHeight + 'px' : ''">
                <div class="p-6">
                  {!! $block !!}
                </div>
              </div>

            </li>

          </ul>

        @endforeach
      </div>
    </article>
