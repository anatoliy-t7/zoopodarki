<div x-data>
  @if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
      <div class="flex items-center justify-between flex-1 lg:hidden">
        <span>
          @if ($paginator->onFirstPage())
            <span
              class="relative inline-flex items-center px-3 py-2 text-sm font-medium leading-5 text-gray-500 rounded-md cursor-default">
              {!! __('pagination.previous') !!}
            </span>
          @else
            <button x-on:click="document.getElementById('top').scrollIntoView({
          behavior: 'smooth'
        });" wire:click="previousPage" wire:loading.attr="disabled" dusk="previousPage.before"
              class="relative inline-flex items-center px-3 py-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out rounded-lg hover:bg-pink-100 focus:outline-none focus:ring-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-500">
              {!! __('pagination.previous') !!}
            </button>
          @endif
        </span>

        <span>
          @if ($paginator->hasMorePages())
            <button x-on:click="document.getElementById('top').scrollIntoView({
          behavior: 'smooth'
        });" wire:click="nextPage" wire:loading.attr="disabled" dusk="nextPage.before"
              class="relative inline-flex items-center px-4 py-1 ml-3 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out rounded-lg hover:bg-pink-100 focus:outline-none focus:ring-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-500">
              {!! __('pagination.next') !!}
            </button>
          @else
            <span
              class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 text-gray-500 rounded-md cursor-default">
              {!! __('pagination.next') !!}
            </span>
          @endif
        </span>
      </div>

      <div class="hidden space-x-4 lg:flex-1 lg:flex lg:items-center lg:justify-start">
        <div>
          <p class="text-sm leading-5 text-gray-500">
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            {!! __('pagination.to') !!}
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            {!! __('pagination.of') !!}
            <span class="font-medium">{{ $paginator->total() }}</span>
            {!! __('pagination.results') !!}
          </p>
        </div>

        <div>
          <span class="relative z-0 inline-flex space-x-1">
            <span>
              {{-- Previous Page Link --}}
              @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                  <span
                    class="relative inline-flex items-center px-2 py-1 text-sm font-medium leading-5 text-gray-300 cursor-default rounded-l-md"
                    aria-hidden="true">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                    </svg>
                  </span>
                </span>
              @else
                <button wire:click="previousPage" dusk="previousPage.after" rel="prev"
                  class="relative inline-flex items-center px-1 py-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out rounded-lg hover:bg-pink-100 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring-blue active:bg-gray-100 active:text-gray-500"
                  aria-label="{{ __('pagination.previous') }}">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
              @endif
            </span>

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
              {{-- "Three Dots" Separator --}}
              @if (is_string($element))
                <span aria-disabled="true">
                  <span
                    class="relative inline-flex items-center px-3 py-1 -ml-px text-sm font-medium leading-5 text-gray-300 cursor-default">{{ $element }}</span>
                </span>
              @endif

              {{-- Array Of Links --}}
              @if (is_array($element))
                @foreach ($element as $page => $url)
                  <span wire:key="paginator-page{{ $page }}">
                    @if ($page == $paginator->currentPage())
                      <span aria-current="page">
                        <span
                          class="relative inline-flex items-center px-3 py-1 -ml-px text-sm font-bold leading-5 text-gray-500 bg-white rounded-lg cursor-default">{{ $page }}</span>
                      </span>
                    @else
                      <button x-on:click="document.getElementById('top').scrollIntoView({
              behavior: 'smooth'
            });" wire:click="gotoPage({{ $page }})"
                        class="relative inline-flex items-center px-3 py-1 -ml-px text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out rounded-lg hover:bg-pink-100 focus:z-10 focus:outline-none active:bg-gray-100 active:text-gray-500"
                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                        {{ $page }}
                      </button>
                    @endif
                  </span>
                @endforeach
              @endif
            @endforeach

            <span>
              {{-- Next Page Link --}}
              @if ($paginator->hasMorePages())
                <button wire:click="nextPage" dusk="nextPage.after" rel="next"
                  class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out rounded-lg hover:bg-pink-100 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring-blue active:bg-gray-100 active:text-gray-500"
                  aria-label="{{ __('pagination.next') }}">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
              @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                  <span
                    class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium leading-5 text-gray-300 cursor-default rounded-r-md"
                    aria-hidden="true">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                    </svg>
                  </span>
                </span>
              @endif
            </span>
          </span>
        </div>
      </div>
    </nav>
  @endif


</div>
