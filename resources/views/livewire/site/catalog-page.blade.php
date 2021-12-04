@if ($catalog->meta_title)
  @section('title', $catalog->meta_title)
@endif
@if ($catalog->meta_description)
  @section('description', $catalog->meta_description)
@endif

<div class="py-2">

  <div class="flex w-full pt-4">
    <div class="flex flex-col w-full space-y-4 lg:space-y-0 lg:space-x-4 lg:flex-row">
      <div class="w-full lg:w-80">
        <div class="p-4 pb-5 space-y-6 bg-white lg:rounded-2xl">

          <div class="pt-2 text-center lg:pt-4">
            <h1 class="text-xl font-bold">
              {{ $catalog->name }}
            </h1>
          </div>

          <div class="flex-col px-1 space-y-1">

            @forelse ($catalog->categories as $category)

              <a href="{{ route('site.category', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug]) }}"
                class="block px-3 py-1 font-semibold leading-tight hover:underline">
                {{ $category->name }}
              </a>

            @empty
              <p>No categories</p>
            @endforelse

          </div>

        </div>

      </div>

      <div class="w-full">

        <div class="w-full px-4 pb-6 space-y-6 bg-white lg:pt-4 lg:px-6 lg:rounded-2xl">

          @foreach ($selectedCategories as $selectedCategory)
            <div>
              <div class="p-3 font-bold">Популярное в "{{ $selectedCategory->name }}"</div>
              <div class="block max-w-xs mx-auto md:max-w-4xl splide slider-in-catalog">
                <div class="splide__track">
                  <div class="splide__list ">
                    @foreach ($selectedCategory->products as $key => $product)
                      <div class="splide__slide">
                        <livewire:site.card-products :product="$product" :catalog="$catalog->slug"
                          :category="$selectedCategory->slug" :key="$key.'-'.$product->id" />
                      </div>
                    @break($key == 9)
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @endforeach

</div>

</div>

</div>
</div>

<script src="{{ mix('js/splide.min.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var elms = document.getElementsByClassName('slider-in-catalog');
    for (var i = 0, len = elms.length; i < len; i++) {
      new Splide(elms[i], {
        perPage: 4,
        arrows: true,
        cover: false,
        lazyLoad: 'nearby',
        rewind: true,
        pagination: false,
        breakpoints: {
          640: {
            perPage: 1,
          },
        }
      }).mount();
    }
  });
</script>

</div>
