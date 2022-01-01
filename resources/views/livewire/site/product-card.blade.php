  <div class="space-y-2">

    <x-breadcrumbs :category="$category" :catalog="$catalog" />

    <article x-cloak x-data="tabs" @tab-reviews.window="tabReviews(event)" class="space-y-6" itemscope
      itemtype="https://schema.org/Product">

      <div class="px-4 py-8 space-y-2 bg-white lg:px-8 lg:rounded-2xl">
        <div class="flex flex-col justify-between space-y-2 lg:space-y-0 lg:space-x-4 lg:items-start lg:flex-row">
          <h1 class="w-9/12 font-semibold text-left text-md lg:text-xl">
            <span class="pr-1" x-show="tab == 2" x-transition>
              Состав:
            </span>
            <span class="pr-1" x-show="tab == 3" x-transition>
              Применение:
            </span>
            <span itemprop="name">{{ $product->name }}</span>
            @if ($category->id !== $product->categories[0]->id)
              <span class="pl-0.5">{{ $catalog->extra_title }}</span>
            @endif
          </h1>
          <div class="flex items-center justify-between space-x-6">
            <div>
              @if ($product->brand()->exists())
                <a href="{{ route('site.brand', ['brandslug' => $product->brand->slug]) }}">
                  @if ($product->brand->logo)
                    <img loading="lazy" class="w-auto h-10" src="/assets/brands/{{ $product->brand->logo }}"
                      alt="Логотип {{ $product->brand->name }}">
                  @else
                    <div class="font-bold text-blue-500 hover:underline">{{ $product->brand->name }}</div>
                  @endif
                </a>
              @endif
            </div>
            @auth
              <livewire:site.add-to-favorite :model="$product" :key="'product-'.$product->id" />
            @endauth

            <x-share-buttons
              :url="route('site.product', ['catalogslug' => $catalog, 'categoryslug' => $category, 'productslug' => $productslug])" />

            @can('dashboard')
              <a class="text-gray-500 link-hover group"
                href="{{ route('dashboard.product.edit', ['id' => $product->id]) }}" target="_blank">
                <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <path class="text-gray-500 stroke-current group-hover:text-orange-500" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="1.5" d="M11 2H9C4 2 2 4 2 9v6c0 5 2 7 7 7h6c5 0 7-2 7-7v-2" />
                  <path class="text-gray-500 stroke-current group-hover:text-orange-500" stroke-linecap="round"
                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"
                    d="M16.04 3.02 8.16 10.9c-.3.3-.6.89-.66 1.32l-.43 3.01c-.16 1.09.61 1.85 1.7 1.7l3.01-.43c.42-.06 1.01-.36 1.32-.66l7.88-7.88c1.36-1.36 2-2.94 0-4.94-2-2-3.58-1.36-4.94 0Z" />
                  <path class="text-gray-500 stroke-current group-hover:text-orange-500" stroke-linecap="round"
                    stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"
                    d="M14.91 4.15a7.144 7.144 0 0 0 4.94 4.94" />
                </svg>
              </a>
            @endcan

          </div>
        </div>

        <div class="flex items-center justify-between space-x-4">
          <div onclick="reviews()" class="flex items-center justify-start space-x-2 cursor-pointer"
            title="Посмотреть отзывы">
            <div>
              <div x-cloak x-data="{
                rating: '{{ ceil((int) $product->reviews->avg('rating')) }}',
                hoverRating: 0,
                ratings: [ 1, 2, 3, 4, 5]
                }" class="flex items-center space-x-1">

                <template x-for="(star, index) in ratings" :key="index" hidden>
                  <div aria-hidden="true" class="p-px rounded-sm focus:outline-none focus:ring">
                    <svg :class="{ 'text-gray-600' : hoverRating >= star, 'text-orange-400' : rating >= star }"
                      class="w-4 text-gray-400 fill-current" viewBox="0 -10 511.991 511"
                      xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M510.652 185.883a27.177 27.177 0 00-23.402-18.688l-147.797-13.418-58.41-136.75C276.73 6.98 266.918.497 255.996.497s-20.738 6.483-25.023 16.53l-58.41 136.75-147.82 13.418c-10.837 1-20.013 8.34-23.403 18.688a27.25 27.25 0 007.937 28.926L121 312.773 88.059 457.86c-2.41 10.668 1.73 21.7 10.582 28.098a27.087 27.087 0 0015.957 5.184 27.14 27.14 0 0013.953-3.86l127.445-76.203 127.422 76.203a27.197 27.197 0 0029.934-1.324c8.851-6.398 12.992-17.43 10.582-28.098l-32.942-145.086 111.723-97.964a27.246 27.246 0 007.937-28.926zM258.45 409.605" />
                    </svg>
                  </div>
                </template>
                <div class="hidden">
                  <input type="number" name="rating" x-model="rating">
                </div>
              </div>
            </div>
            <div class="pt-1 text-xs text-gray-500" itemprop="aggregateRating" itemscope
              itemtype="https://schema.org/AggregateRating"><span
                itemprop="ratingValue">{{ ceil((int) $product->reviews->avg('rating')) }}</span>/<span>5</span> (<span
                itemprop="reviewCount">{{ $product->reviews_count }}</span>)
            </div>
          </div>
          <div class="flex items-center justify-end space-x-2 text-sm text-gray-400">
            @if ($product->vendorcode)
              <div>Артикул:</div>
              <div>{{ $product->vendorcode }}</div>
            @endif
          </div>
        </div>

        <div class="flex flex-col items-center pt-4 lg:flex-row">
          <div wire:ignore class="w-full md:w-5/12">
            @if ($product->media()->count())
              <div class="flex flex-col pb-12 lg:flex-row lg:pb-0">
                <div id="thumbSlider"
                  class="items-center justify-center hidden w-full overflow-hidden lg:flex lg:w-2/12 lg:max-h-96 splide">
                  <div class="splide__track">
                    <ul class="space-y-3 splide__list lg:max-h-60">

                      @foreach ($product->getMedia('product-images') as $thumb)
                        <li wire:key="{{ $loop->index }}" class="py-1 overflow-hidden splide__slide">
                          <img class="object-contain w-full h-full" data-splide-lazy="{{ $thumb->getUrl('thumb') }}"
                            alt="{{ $product->name }}">
                        </li>
                      @endforeach

                    </ul>
                  </div>
                </div>
                <div id="slider" class="w-full lg:w-10/12 splide">
                  <div class="splide__track">
                    <ul id="lightbox" class="splide__list">

                      @foreach ($product->getMedia('product-images') as $image)
                        <li wire:key="{{ $loop->index }}" class="relative cursor-pointer splide__slide">

                          <img width="315" height="320" itemprop="image" class="object-scale-down w-full h-80"
                            data-splide-lazy="{{ $image->getUrl('thumb') }}" alt="{{ $product->name }}"
                            data-bp="{{ $image->getUrl('medium') }}">

                        </li>
                      @endforeach

                    </ul>
                  </div>
                </div>
              </div>
            @else
              <img loading="lazy" class="object-contain object-center w-full h-64 lozad"
                src="/assets/img/placeholder.svg"
                data-src="{{ $product->getFirstMediaUrl('product-images', 'thumb') }}" alt="{{ $product->name }}">
            @endif
          </div>


          <div x-cloak x-data="variationsToggle" x-init="$watch('count', value => { validate('count') })"
            @close-modal.window="close()" class="relative flex flex-col w-full h-full lg:flex-row md:w-7/12">

            <div class="w-full pb-10 space-y-6 lg:w-8/12 lg:pb-0">
              <div itemprop="offers" itemscope itemtype="https://schema.org/Offer"
                class="grid w-full grid-cols-3 gap-4 lg:grid-cols-4">
                @foreach ($product->variations as $key => $item)

                  <div wire:key="{{ $loop->index }}"
                    :class="item.id === {{ $item->id }} ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-gray-50 cursor-pointer'"
                    class="relative flex flex-col items-start justify-between px-3 py-2 space-y-1 border rounded-xl"
                    x-on:click="item.id = {{ $item->id }}, item.stock = {{ $item->stock }}, item.unit_value = '{{ $item->unit_value }}'">

                    @if ($item->promotion_type === 1)
                      <div class="absolute z-20 block -top-3 -left-3">
                        <div class="relative font-bold tooltip" data-title="Уценка">
                          <x-tabler-discount-2 class="w-6 h-6 text-orange-500 stroke-current" />
                        </div>
                      </div>
                    @endif

                    @if ($item->promotion_type === 2)
                      <div class="absolute z-20 -top-3 -left-3">
                        <div class="font-bold tooltip" data-title="Второй товар из этой акции бесплатно">
                          <x-tabler-discount-2 class="w-6 h-6 text-orange-500 stroke-current" />
                        </div>
                      </div>
                    @endif
                    @if ($item->promotion_type === 3)
                      <div class="absolute z-20 block -top-3 -left-3">
                        <div class="font-bold tooltip" data-title="Скидка -{{ $item->promotion_percent }}%">
                          <x-tabler-discount-2 class="w-6 h-6 text-orange-500 stroke-current" />
                        </div>
                      </div>
                    @endif
                    @if ($item->promotion_type === 4)
                      <div class="absolute z-20 block -top-3 -left-3">
                        <div class="font-bold tooltip" data-title="Скидка -{{ $item->promotion_percent }}%">
                          <x-tabler-discount-2 class="w-6 h-6 text-orange-500 stroke-current" />
                        </div>
                      </div>
                    @endif

                    <div wire:key="{{ $loop->index }}" class="absolute w-24 text-xs text-gray-500 -top-6"
                      x-show="item.id === {{ $item->id }}" x-transition>
                      {{ $item->cod1c }}
                    </div>

                    <x-units :unit="$product->unit" :value="$item->unit_value" :wire:key="$product->id" />

                    <div class="relative z-10 w-full">
                      <link itemprop="availability" href="http://schema.org/InStock" />
                      @if ($item->stock <= 5 && $item->stock > 0)
                        <div class="flex items-center justify-center w-full mb-1 space-x-2 text-sm tooltip"
                          data-title="Осталось на складе {{ $item->stock }}">
                          <div>{{ $item->stock }}</div>
                          <div>
                            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                              viewBox="-8 -14.5 100 100">
                              <path
                                d="M78 29v32l-2 2-34 11h-2L8 59l-1-2V22L-3 4l1-3h2l31 14 7-1 7-15 2-2L86 8c2 0 2 1 2 3l-9 18zm-36 4l25-5-28-10-4 1 7 14zm32-2l-31 6v32l31-10V31zM48 2l-6 13 33 10 7-14-34-9zm-9 34L11 24v31l28 13V36zM4 8l7 11 25 12-5-11L4 8z" />
                            </svg>
                          </div>
                        </div>
                      @elseif ($item->stock > 5)
                        <div title="В наличии" class="text-xs font-semibold text-green-700">
                          В наличии
                        </div>
                      @endif

                      <div>
                        @if ($item->stock === 0)
                          <div class="relative w-full text-xs text-red-600">
                            <div class="w-24 ">Нет в наличии</div>
                          </div>
                        @endif
                      </div>

                    </div>

                    <div class="w-full font-semibold text-right "
                      :class="item.id === {{ $item->id }} ? 'text-orange-600' : 'text-gray-600'">
                      @if ($item->promotion_type === 0)
                        <span>{{ RUB($item->price) }}</span>
                        <span itemprop="price" content="{{ $item->price }}"></span>
                      @elseif ($item->promotion_type === 1 || $item->promotion_type === 3)
                        <div class="text-xs text-gray-500 line-through">{{ RUB($item->promotion_price) }}</div>
                        <div>{{ RUB($item->price) }}</div>
                        <span itemprop="price" content="{{ $item->price }}"></span>
                      @elseif ($item->promotion_type === 2 || $item->promotion_type === 4)
                        <div class="text-xs text-gray-500 line-through">{{ RUB($item->price) }}</div>
                        <div>{{ RUB(discount($item->price, $item->promotion_percent)) }}</div>
                        <span itemprop="price"
                          content="{{ discount($item->price, $item->promotion_percent) }}"></span>
                      @endif
                      <span itemprop="priceCurrency" content="RUB"></span>
                    </div>

                  </div>

                @endforeach
              </div>

              <div>

                <div class="flex flex-col items-start justify-start w-full pt-1 space-y-1">
                  @forelse ($productAttributes as $items)
                    <div class="flex flex-row flex-wrap items-center justify-start w-full max-w-lg text-sm">
                      @foreach ($items as $item)
                        @if ($loop->first)
                          <div class="relative z-10 w-4/12 pr-4 my-1 text-gray-500 attribute">
                            <span class="relative z-10 pr-1 bg-white">{{ $item['attribute']['name'] }}</span>
                          </div>
                        @endif
                        {{ $loop->first ? '' : ', ' }}
                        <div class="relative z-10 pl-1 my-1 bg-white whitespace-nowrap"><a
                            class="text-blue-600 hover:underline"
                            href="{{ route('site.category', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug]) . '?attrsF[0]=' . $item['id'] }}">{{ $item['name'] }}</a>
                        </div>
                      @endforeach
                    </div>
                  @empty
                  @endforelse
                </div>
              </div>
            </div>

            <div class="flex flex-col items-center justify-start w-full space-y-5 lg:items-end lg:w-4/12">

              <div>
                <div x-show="item.stock > 0 && item.unit_value != 'на развес'"
                  class="flex justify-center w-40 leading-none text-gray-500">

                  <button x-on:click="decrement()" aria-label="Уменьшить"
                    class="flex items-center justify-center w-12 h-10 text-xl bg-gray-200 border border-gray-200 rounded-l-lg hover:bg-gray-300">
                    <x-tabler-minus class="w-6 h-6 stroke-current" />
                  </button>
                  <label class="relative z-10">
                    <input id="number" type="number" min="1" max="50"
                      class="w-16 h-10 p-3 text-lg font-bold text-center border border-gray-200 focus:outline-none focus:ring "
                      x-model.debounce.500="count">
                  </label>
                  <button x-on:click="count++" aria-label="Увеличить"
                    class="flex items-center justify-center w-12 h-10 text-xl bg-gray-200 border border-gray-200 rounded-r-lg hover:bg-gray-300">
                    <x-tabler-plus class="w-6 h-6 stroke-current" />
                  </button>

                </div>

                <div x-show="item.stock > 0 && item.unit_value == 'на развес'" x-transition.opacity
                  class="w-40">
                  <select name="byWeight" id="byWeight" x-model="byWeight" class="field">
                    <option value="500">500 гр</option>
                    <option selected value="1000">1 кг</option>
                    <option x-show="item.stock >= 1.5" value="1500">1.5 кг</option>
                    <option x-show="item.stock >= 2" value="2000">2 кг</option>
                    <option x-show="item.stock >= 2.5" value="2500">2.5 кг</option>
                    <option x-show="item.stock >= 3" value="3000">3 кг</option>
                    <option x-show="item.stock >= 3.5" value="3500">3.5 кг</option>
                    <option x-show="item.stock >= 4" value="4000">4 кг</option>
                    <option x-show="item.stock >= 4.5" value="4500">4.5 кг</option>
                    <option x-show="item.stock >= 5" value="5000">5 кг</option>
                    <option x-show="item.stock >= 6" value="6000">6 кг</option>
                    <option x-show="item.stock >= 8" value="8000">8 кг</option>
                    <option x-show="item.stock >= 10" value="10000">10 кг</option>
                    <option x-show="item.stock >= 12" value="12000">12 кг</option>
                    <option x-show="item.stock >= 14" value="14000">14 кг</option>
                    <option x-show="item.stock >= 16" value="16000">16 кг</option>
                    <option x-show="item.stock >= 18" value="18000">18 кг</option>
                    <option x-show="item.stock >= 20" value="20000">20 кг</option>
                  </select>
                </div>
              </div>

              <button x-show="item.stock > 0" x-transition.opacity x-on:click="addToCart()"
                class="flex items-center justify-center w-40 px-4 py-2.5 space-x-3 font-bold text-white transition ease-in-out transform bg-orange-500 cursor-pointer rounded-lg active:scale-95 hover:bg-orange-600 "
                wire:loading.attr="disabled" aria-label="Добавить в корзину">
                <span>В корзину</span>
                <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                  viewBox="0 0 24 24">
                  <path
                    d="M14,18a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,14,18Zm-4,0a1,1,0,0,0,1-1V15a1,1,0,0,0-2,0v2A1,1,0,0,0,10,18ZM19,6H17.62L15.89,2.55a1,1,0,1,0-1.78.9L15.38,6H8.62L9.89,3.45a1,1,0,0,0-1.78-.9L6.38,6H5a3,3,0,0,0-.92,5.84l.74,7.46a3,3,0,0,0,3,2.7h8.38a3,3,0,0,0,3-2.7l.74-7.46A3,3,0,0,0,19,6ZM17.19,19.1a1,1,0,0,1-1,.9H7.81a1,1,0,0,1-1-.9L6.1,12H17.9ZM19,10H5A1,1,0,0,1,5,8H19a1,1,0,0,1,0,2Z" />
                </svg>
              </button>

              <div x-show="item.stock > 0" x-transition.opacity>
                <div x-cloak x-show="openModal" x-transition.opacity
                  class="fixed top-0 left-0 z-40 flex items-center justify-center w-screen h-screen bg-gray-500 bg-opacity-50"
                  role="dialog" aria-modal="true">

                  <div x-on:click.outside="close()" @keydown.window.escape="openModal = false"
                    class="absolute z-50 flex flex-col w-full max-w-sm bg-white divide-y divide-gray-200 shadow-lg rounded-xl">

                    <div class="py-6 px-7">
                      <div class="flex items-start justify-between">
                        <h2 class="text-xl font-bold leading-tight text-gray-700">
                          Покупка в 1 клик
                        </h2>
                        <button class="text-gray-400 hover:text-gray-600" x-on:click="close()">
                          <x-tabler-x class="w-6 transition duration-150 stroke-current" />
                        </button>
                      </div>

                      <div class="pt-2 text-xs text-gray-500">Наш оператор перезвонит вам в ближайшее
                        время!</div>
                    </div>

                    <div class="pt-4 space-y-4 pb-7 px-7">

                      <label class="block w-full">
                        <span class="block pb-1 pl-3 text-sm font-bold text-gray-700 ">Телефон</span>
                        <div class="relative text-lg">
                          <div class="absolute z-30 cursor-default top-3 left-3">
                            <div class="w-6 h-6 mx-auto text-gray-400 fill-current">
                              +7
                            </div>
                          </div>
                          <input x-model="orderOneClick.phone" x-bind:oninput="validatePhone()" type="tel" name="phone"
                            minlength="10" maxlength="10"
                            class="w-full px-4 py-3 pl-10 font-semibold border border-gray-200 bg-gray-50 rounded-2xl focus:outline-none focus:ring focus:bg-white"
                            required>
                        </div>
                      </label>

                      <label class="block w-full">
                        <span class="block pb-1 pl-3 text-sm font-bold text-gray-700 ">Имя</span>
                        <input x-model="orderOneClick.name" type="text" name="name" class="field" required>
                      </label>

                      <label class="block w-full">
                        <span class="block pb-1 pl-3 text-sm font-bold text-gray-700">Email<span
                            class="pl-1 text-xs font-normal text-gray-500">(необязательно)</span></span>
                        <input x-model="orderOneClick.email" type="email" name="email" class="field">
                      </label>

                      <label class="block w-full">
                        <span class="block pb-1 pl-3 text-sm font-bold text-gray-700">Адрес<span
                            class="pl-1 text-xs font-normal text-gray-500">(необязательно)</span></span>
                        <input x-model="orderOneClick.address" type="text" name="address" class="field">
                      </label>

                      <div class="flex justify-center">
                        <button x-on:click="$wire.buyOneClick(orderOneClick, item.id, count)"
                          x-bind:disabled="valid === false"
                          class="mt-3 text-white bg-orange-500 border-orange-500 btn hover:bg-orange-600">
                          Купить
                        </button>
                      </div>

                    </div>

                  </div>

                </div>

                <button x-on:click="open()" class="w-40 px-4 py-1 text-blue-600 hover:underline"
                  aria-label="Купить в 1 клик">
                  Купить в 1 клик
                </button>

              </div>

              <div x-show="item.stock == 0" x-transition.opacity>
                <x-modal>
                  <x-slot name="button">
                    <button x-on:click="open()"
                      class=" relative flex items-center justify-center w-40 px-4 py-2.5 space-x-3 font-bold text-white transition ease-in-out transform bg-blue-500 cursor-pointer rounded-lg active:scale-95 hover:bg-blue-600">
                      <span>Заказать</span>
                      <x-tabler-package class="w-6 h-6" />
                    </button>
                  </x-slot>

                  <x-slot name="content">
                    <div class="w-full space-y-6">

                      <div class="pt-4 font-bold leading-tight text-center text-gray-600">Оповестить когда
                        появиться<br>в
                        наличии</div>
                      <div>
                        <div>
                          <label for="emailNotyf" class="block mb-2 text-sm font-bold text-gray-700">
                            Адрес эл. почты
                          </label>
                          <div class="relative text-lg">
                            <div class="absolute top-0 left-0 z-30 pt-4 pl-3 cursor-default">
                              <x-tabler-mail class="w-5 h-5 text-gray-400 stroke-current" />
                            </div>
                            <input x-model="email" id="emailNotyf" type="email" name="emailNotyf" autocomplete="email"
                              inputmode="email"
                              class="w-full px-4 py-3 pl-10 text-sm font-semibold border border-gray-200 bg-gray-50 rounded-2xl focus:outline-none focus:ring focus:bg-white">
                          </div>
                          @error('email')
                            <p class="mt-2 text-xs italic text-red-500">
                              {{ $message }}
                            </p>
                          @enderror
                        </div>
                      </div>
                      <div class="flex justify-center w-full">
                        <button x-on:click="preOrder()"
                          class="relative flex items-center justify-center w-full space-x-3 font-bold text-white transition ease-in-out transform bg-blue-500 rounded-lg cursor-pointer btn active:scale-95 hover:bg-blue-600">
                          <span>Оповестить</span>
                          <div wire:loading wire:target="preOrder" class="absolute top-2 right-2">
                            <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                              fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                              </circle>
                              <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                              </path>
                            </svg>
                          </div>
                        </button>
                      </div>
                    </div>
                  </x-slot>

                </x-modal>
              </div>

            </div>

          </div>

        </div>
      </div>

      <div class="px-4 pt-2 bg-white lg:px-8 lg:rounded-2xl">
        <div class="flex items-center justify-between space-x-6">
          <nav class="flex items-center justify-start gap-4 md:gap-0">
            <h2 x-on:click="tabDescription" data-route="description"
              :class="{ 'text-blue-500 border-blue-500': tab == 1 }"
              class="block py-2 text-xl font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
              Описание
            </h2>
            @if ($product->consist)
              <h2 x-on:click="tabСonsist" data-route="consist" :class="{ 'text-blue-500 border-blue-500': tab == 2 }"
                class="block py-2 text-xl font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                Состав
              </h2>
            @endif
            @if ($product->applying)
              <h2 x-on:click="tabApplying" data-route="applying" :class="{ 'text-blue-500 border-blue-500': tab == 3 }"
                class="block py-2 text-xl font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                Применение
              </h2>
            @endif
          </nav>
          <div>

          </div>
        </div>

        <div x-cloak class="w-full pb-6 prose content max-w-none">
          <div :class="tab == 1 ? 'block' : 'hidden'" class="pt-4 leading-normal" itemprop="description">
            @if ($product->description)
              {!! $product->description !!}
            @else
              Нет описания
            @endif
          </div>

          @if ($product->consist)
            <div :class="tab == 2 ? 'block' : 'hidden'" x-transition.opacity class="pt-4 leading-normal">
              {!! $product->consist !!}
            </div>
          @endif

          @if ($product->applying)
            <div :class="tab == 3 ? 'block' : 'hidden'" x-transition.opacity class="pt-4 leading-normal">
              {!! $product->applying !!}
            </div>
          @endif

        </div>
      </div>

      <div x-cloak id="reviews" class="px-8 py-6 bg-white rounded-2xl">
        @if ($tab === 2 || $tab === 3)
          <noindex>
        @endif
        <div class="flex flex-col">
          <div class="flex items-center justify-start space-x-2 font-semibold">
            <h4 class="text-xl">Отзывы</h4>
            <span class="text-gray-500">
              ({{ $product->reviews_count }})
            </span>
          </div>

          <livewire:site.reviews-com :model="$product" :key="'review-'.$product->id">

        </div>
        @if ($tab === 2 || $tab === 3)
          </noindex>
        @endif
      </div>

      <div wire:ignore class="px-8 py-6 bg-white rounded-2xl">
        <div class="pb-4 text-xl font-semibold">Рекомендуем также</div>
        <div class="flex flex-col items-center justify-between space-y-4 lg:space-y-0 lg:space-x-8 lg:flex-row">
          @forelse ($related as $item)
            <div class="w-full lg:w-2/12">
              <livewire:site.card-products :product="$item" :catalog="$catalog->slug" :category="$category->slug"
                :key="'card-'.$product->id" />
            </div>
          @empty
          @endforelse
        </div>
      </div>

      <script>
        document.addEventListener('alpine:initializing', () => {
          Alpine.data('tabs', () => ({
            tab: {{ $tab }},
            url: window.location.pathname,
            title: '',
            tabUrl: '',
            tabDescription() {
              this.tab = 1;
              this.tabUrl = ''
              this.openTab();
            },

            tabСonsist() {
              this.tab = 2;
              this.tabUrl = '/consist'
              this.openTab();
            },

            tabApplying() {
              this.tab = 3;
              this.tabUrl = '/applying'
              this.openTab();
            },

            openTab() {
              const state = {};
              this.url = '/pet/{{ $catalog->slug }}/{{ $category->slug }}/{{ $product->slug }}' + this
                .tabUrl;
              history.pushState(state, this.title, this.url)
            }
          }))
        });

        function reviews() {
          document.getElementById('reviews').scrollIntoView({
            behavior: 'smooth'
          });
        }
      </script>
      <script>
        document.addEventListener('alpine:initializing', () => {
          Alpine.data('variationsToggle', () => ({
            count: 1,
            byWeight: 1000,
            catalogId: '{{ $catalog->id }}',
            item: {
              id: parseInt('{{ $product->variations[0]->id }}'),
              stock: parseInt('{{ $product->variations[0]->stock }}'),
              unit_value: '{{ $product->variations[0]->unit_value }}',
            },
            openModal: false,
            formatedPhone: null,
            phone: null,
            valid: false,
            orderOneClick: {},
            email: @entangle('email').defer,
            decrement() {
              if (this.count >= 2) {
                this.count--;
              }
            },
            validate() {
              if (this.count > 64) {
                this.count = 64;
              }

              if (this.count === '' || this.count == 0) {
                this.count = 1;
              }
            },
            addToCart() {
              this.item.stock = this.item.stock - this.count;
              if (this.item.stock < 0) {
                this.item.stock = 0
              }
              if (this.item.unit_value == 'на развес') {
                return window.livewire.emit('addToCart', this.item.id, this.count, this.catalogId, this.byWeight);
              }
              return window.livewire.emit('addToCart', this.item.id, this.count, this.catalogId)
            },
            preOrder() {
              window.livewire.emit('preOrder', this.item.id, this.email)
              this.showModal = false;
            },
            open() {
              this.openModal = true
              document.body.classList.add('overflow-hidden', 'pr-4');
            },
            close() {
              this.openModal = false
              document.body.classList.remove('overflow-hidden', 'pr-4');
            },
            validatePhone() {
              if (this.orderOneClick.phone && this.orderOneClick.phone !== null) {
                let formatedPhone = this.orderOneClick.phone.replace(/[^0-9]/g, '')
                this.orderOneClick.phone = formatedPhone.replace(/(\d{3})(\d{3})(\d{2})(\d{2})/, '($1)-$2-$3-$4');
                this.phone = this.orderOneClick.phone.replace(/[^\w\s]/gi, '');

                if (this.phone.length < 10) {
                  this.valid = false;
                } else {
                  this.valid = true;
                }
              }
            },
          }))
        })
      </script>

      @if ($product->media()->exists())
        <script src="{{ mix('js/splide.min.js') }}"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {

            var thumbSlider = new Splide('#thumbSlider', {
              type: 'slide',
              rewind: true,
              height: 300,
              fixedWidth: 56,
              fixedHeight: 56,
              perMove: 1,
              perPage: 4,
              isNavigation: true,
              focus: 'center',
              pagination: false,
              direction: 'ttb',
              cover: true,
              lazyLoad: 'nearby',
              breakpoints: {
                768: {
                  height: 1,
                }
              }
            }).mount();

            var slider = new Splide('#slider', {
              type: 'fade',
              fixedHeight: 320,
              padding: '3em',
              heightRatio: 0.5,
              pagination: false,
              arrows: false,
              cover: false,
              lazyLoad: 'nearby',
            });

            // Set the thumbnails slider as a sync target and then call mount.
            slider.sync(thumbSlider).mount();
          });
        </script>
      @endif
    </article>
  </div>
