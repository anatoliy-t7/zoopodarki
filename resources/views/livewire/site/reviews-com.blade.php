<div class="flex flex-col pb-6 lg:space-x-12 lg:flex-row">

  <div class="w-full lg:w-3/12">
    <div class="pt-6 ">
      <div class="flex flex-col items-center justify-between p-5 space-y-6 bg-gray-50 rounded-2xl">
        <div class="flex items-center justify-start space-x-2 cursor-pointer" title="Посмотреть отзывы">
          <div x-data="{
                rating: '{{ ceil((int) $reviews->avg('rating')) }}',
                hoverRating: 0,
                ratings: [ 1, 2, 3, 4, 5]
                  }" class="flex items-center space-x-1">

            <template x-for="(star, index) in ratings" :key="index" hidden>
              <div aria-hidden="true" class="p-px rounded-sm focus:outline-none focus:ring">
                <svg :class="{ 'text-gray-600' : hoverRating >= star, 'text-red-400' : rating >= star }"
                  class="w-6 text-gray-400 fill-current" viewBox="0 -10 511.991 511" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M510.652 185.883a27.177 27.177 0 00-23.402-18.688l-147.797-13.418-58.41-136.75C276.73 6.98 266.918.497 255.996.497s-20.738 6.483-25.023 16.53l-58.41 136.75-147.82 13.418c-10.837 1-20.013 8.34-23.403 18.688a27.25 27.25 0 007.937 28.926L121 312.773 88.059 457.86c-2.41 10.668 1.73 21.7 10.582 28.098a27.087 27.087 0 0015.957 5.184 27.14 27.14 0 0013.953-3.86l127.445-76.203 127.422 76.203a27.197 27.197 0 0029.934-1.324c8.851-6.398 12.992-17.43 10.582-28.098l-32.942-145.086 111.723-97.964a27.246 27.246 0 007.937-28.926zM258.45 409.605" />
                </svg>
              </div>
            </template>
            <div class="hidden">
              <input type="number" name="rating" x-model="rating">
            </div>
          </div>
          <div class="pt-1 font-semibold text-gray-700">{{ ceil((int) $reviews->avg('rating')) }} / 5</div>
        </div>
        <div>
          @auth
            @if ($done)
              <div class="text-sm text-center">Вы уже добавили отзыв<br /> к этому товару</div>
            @else
              <div>
                <!--googleoff: all-->
                <!--noindex-->
                <livewire:site.review-write :modelId="$model->id" />
                <!--/noindex-->
                <!--googleon: all-->
              </div>
            @endif
          @else
            <div class="text-sm leading-tight text-center">
              Пожалуйста, <button x-on:click="$dispatch('auth')"
                class="font-semibold text-blue-500">авторизируйтесь</button>, что бы
              оставить отзыв.
            </div>
          @endauth
        </div>
      </div>
    </div>
  </div>

  <div class="relative w-full pt-6 lg:w-9/12">

    @if ($gallery->isNotEmpty())
      <div class="relative pb-12">
        <div class="block pb-4 text-base font-bold">Фотографии покупателей</div>
        <div wire:ignore id="customersGallery"
          class="relative block {{ $gallery->count() > 10 ? 'px-12' : 'visible' }} splide">

          <div class="text-gray-500 splide__arrows {{ $gallery->count() > 10 ? '' : 'hidden' }}">
            <button class="absolute top-0 left-0 mt-2 splide__arrow--prev">
              <x-tabler-chevron-right class="w-12 h-12 stroke-current" />
            </button>
            <button class="absolute top-0 right-0 mt-2 splide__arrow--next">
              <x-tabler-chevron-right class="w-12 h-12 stroke-current" />
            </button>
          </div>

          <div class="w-full splide__track">
            <ul id="customersGalleryLightbox" class="flex items-center justify-start space-x-2 splide__list">
              @forelse ($gallery as $image)
                <li class="cursor-pointer splide__slide">
                  <img loading="lazy" class="object-cover object-center w-16 h-16" alt=""
                    data-bp="{{ $image->getUrl() }}"
                    {{ $gallery->count() > 10 ? 'data-splide-lazy=' : 'src=' }}"{{ $image->getUrl('thumb') }}">
                </li>
              @empty
              @endforelse
            </ul>
          </div>
        </div>

      </div>
    @endif

    @if ($reviews->count() !== 0)
      <div x-data="{ open: false }" class="relative max-w-xs">
        <button @click="open = !open" @click.outside="open = false"
          class="flex items-center w-auto py-1 pl-2 pr-1 text-xs text-left text-gray-700 border border-gray-200 rounded-lg bg-gray-50 hover:text-yellow-900 focus:text-yellow-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none">
          <span>{{ $sortSelectedName }}</span>
          <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
            class="inline w-4 h-4 align-middle transition-transform duration-200 transform ">
            <path fill-rule="evenodd"
              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
        <div x-cloak x-show="open" x-transition
          class="absolute left-0 z-30 mt-2 origin-top-right shadow-xl rounded-2xl">
          <div class="p-2 text-gray-700 divide-y divide-gray-200 shadow-sm bg-gray-50 rounded-2xl"
            @click="open = !open">
            @foreach ($sortType as $type)
              <div class="p-2 text-xs cursor-pointer hover:bg-gray-200"
                wire:click="sortIt('{{ $type['type'] }}', '{{ $type['sort'] }}', '{{ $type['name'] }}')">
                {{ $type['name'] }}
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif

    <div class="divide-y">

      @forelse ($reviews as $review)
        <div wire:key="{{ $loop->index }}" class="flex flex-col gap-3 py-4 md:py-8" itemprop="review" itemscope
          itemtype="https://schema.org/Review">

          <div itemprop="name">
            <span class="block text-sm font-bold" itemprop="author">{{ $review->user->name }}</span>
            <span class="block text-sm text-gray-500" itemprop="datePublished"
              content="{{ dataYmd($review->created_at) }}"> {{ simpleDate($review->created_at) }}</span>
          </div>

          <div class="flex gap-0.5 -ml-1">
            <div x-data="{
              rating: '{{ $review->rating }}',
              hoverRating: 0,
              ratings: [ 1, 2, 3, 4, 5]
            }" class="flex items-center space-x-1">
              <template x-for="star in ratings" hidden>
                <div aria-hidden="true" class="p-px rounded-sm focus:outline-none focus:ring">
                  <svg :class="{ 'text-gray-600' : hoverRating >= star, 'text-red-400' : rating >= star }"
                    class="w-4 text-gray-400 cursor-default fill-current" viewBox="0 -10 511.991 511"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M510.652 185.883a27.177 27.177 0 00-23.402-18.688l-147.797-13.418-58.41-136.75C276.73 6.98 266.918.497 255.996.497s-20.738 6.483-25.023 16.53l-58.41 136.75-147.82 13.418c-10.837 1-20.013 8.34-23.403 18.688a27.25 27.25 0 007.937 28.926L121 312.773 88.059 457.86c-2.41 10.668 1.73 21.7 10.582 28.098a27.087 27.087 0 0015.957 5.184 27.14 27.14 0 0013.953-3.86l127.445-76.203 127.422 76.203a27.197 27.197 0 0029.934-1.324c8.851-6.398 12.992-17.43 10.582-28.098l-32.942-145.086 111.723-97.964a27.246 27.246 0 007.937-28.926zM258.45 409.605" />
                  </svg>
                </div>
              </template>
            </div>
          </div>

          <div itemprop="reviewBody">
            {!! tagP($review->body) !!}
          </div>

          @if ($review->getMedia('product-customers-photos')->count())
            <div class="flex items-start justify-start w-full pt-2 space-x-2 review">
              @foreach ($review->getMedia('product-customers-photos') as $image)
                <div class="w-12 h-12 cursor-pointer">
                  <img loading="lazy" class="object-cover object-center w-full h-12 rounded-lg lozad"
                    data-src="{{ $image->getUrl('thumb') }}"
                    alt="Фото {{ $review->revieweable->name }} от {{ $review->user->name }}"
                    data-bp="{{ $image->getUrl() }}">
                </div>
              @endforeach
            </div>
          @endif
        </div>
      @empty
        <div class="py-4">Напишите первым об этом товаре</div>
      @endforelse
    </div>

    <div>{{ $reviews->links('pagination::reviews') }}</div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      items = {{ $gallery->count() }};
      width = '100%';
      if (items >= 10) {
        items = 10;
      } else {
        width = (items * 100) / 10 + '%';
      }
      if (items >= 10) {
        var customersGallery = new Splide('#customersGallery', {
          rewind: true,
          perPage: items,
          height: '64px',
          width: width,
          autoWidth: false,
          gap: 4,
          pagination: false,
          lazyLoad: 'nearby',
        }).mount();
      }
    });
  </script>

</div>
