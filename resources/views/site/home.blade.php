@extends('layouts.app')
@section('content')
  <div class="flex items-center">
    <div class="container py-8 md:mx-auto">
      <div class="space-y-20">


        <div class="space-y-4">
          <div class="flex flex-col items-center justify-between md:flex-row">
            <div class="text-2xl font-semibold">Выгодные предложения</div>
            <div>
              <a href="{{ route('site.discounts') }}" class="flex items-center space-x-1 hover:underline">
                <span>Все предложения</span>
                <x-tabler-chevron-right class="w-5 h-5" />
              </a>
            </div>
          </div>

          <div x-cloak x-data="{tab: 1}" class="px-4 pt-2 bg-white lg:px-8 rounded-2xl">
            <div class="flex items-center justify-between space-x-6">
              <nav class="items-center justify-start flex-none md:flex">
                @if ($discountsCats->isNotEmpty())
                  <h2 x-on:click="tab = 1" data-route="description" :class="{ 'text-blue-500 border-blue-500': tab == 1 }"
                    class="block py-2 text-xl font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Кошки
                  </h2>
                @endif
                @if ($discountsDogs->isNotEmpty())
                  <h2 x-on:click="tab = 2" data-route="consist" :class="{ 'text-blue-500 border-blue-500': tab == 2 }"
                    class="block py-2 text-xl font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Собаки
                  </h2>
                @endif
                @if ($discountsBirds->isNotEmpty())
                  <h2 x-on:click="tab = 3" data-route="applying" :class="{ 'text-blue-500 border-blue-500': tab == 3 }"
                    class="block py-2 text-xl font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Птицы
                  </h2>
                @endif
                @if ($discountsRodents->isNotEmpty())
                  <h2 x-on:click="tab = 4" data-route="applying" :class="{ 'text-blue-500 border-blue-500': tab == 4 }"
                    class="block py-2 text-xl font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Грызуны
                  </h2>
                @endif
              </nav>
            </div>

            <div class="w-full pb-6 ">
              @if ($discountsCats->isNotEmpty())
                <div x-cloak :class="tab == 1 ? 'block' : 'hidden'"
                  class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                  @foreach ($discountsCats as $discountsCat)
                    <div class="w-full max-w-xs">
                      <livewire:site.card-products :product="$discountsCat"
                        :catalog="$discountsCat->categories[0]->catalog->slug"
                        :category="$discountsCat->categories[0]->slug" :wire:key="'product-'.$discountsCat->id" />
                    </div>
                  @endforeach
                </div>
              @endif
              @if ($discountsDogs->isNotEmpty())
                <div x-cloak :class="tab == 2 ? 'block' : 'hidden'"
                  class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                  @foreach ($discountsDogs as $discountsDog)
                    <div class="w-full max-w-xs">
                      <livewire:site.card-products :product="$discountsDog"
                        :catalog="$discountsDog->categories[0]->catalog->slug"
                        :category="$discountsDog->categories[0]->slug" :wire:key="'product-'.$discountsDog->id" />
                    </div>
                  @endforeach
                </div>
              @endif

              @if ($discountsBirds->isNotEmpty())
                <div x-cloak :class="tab == 3 ? 'block' : 'hidden'"
                  class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                  @foreach ($discountsBirds as $discountsBird)
                    <div class="w-full max-w-xs">
                      <livewire:site.card-products :product="$discountsBird"
                        :catalog="$discountsBird->categories[0]->catalog->slug"
                        :category="$discountsBird->categories[0]->slug" :wire:key="'product-'.$discountsBird->id" />
                    </div>
                  @endforeach
                </div>
              @endif
              @if ($discountsRodents->isNotEmpty())
                <div x-cloak :class="tab == 4 ? 'block' : 'hidden'"
                  class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                  @foreach ($discountsRodents as $discountsRodent)
                    <div class="w-full max-w-xs">
                      <livewire:site.card-products :product="$discountsRodent"
                        :catalog="$discountsRodent->categories[0]->catalog->slug"
                        :category="$discountsRodent->categories[0]->slug" :wire:key="'product-'.$discountsRodent->id" />
                    </div>
                  @endforeach
                </div>
              @endif

            </div>
          </div>
        </div>

        <div x-data class="grid gap-12 sm:grid-cols-2 md:grid-cols-4">
          <div @click="$dispatch('auth')"
            class="flex items-center justify-center px-6 py-4 text-lg leading-snug text-center bg-white border border-transparent cursor-pointer rounded-2xl hover:border-gray-200">
            Скидка за первый заказ
          </div>
          <div @click="$dispatch('auth')"
            class="flex items-center justify-center px-6 py-4 text-lg leading-snug text-center bg-white border border-transparent cursor-pointer rounded-2xl hover:border-gray-200">
            Постоянная скидка<br>по карте
          </div>
          <div @click="$dispatch('auth')"
            class="flex items-center justify-center px-6 py-4 text-lg leading-snug text-center bg-white border border-transparent cursor-pointer rounded-2xl hover:border-gray-200">
            Скидка от 5 кг корма
          </div>
          <div @click="$dispatch('auth')"
            class="flex items-center justify-center px-6 py-4 text-lg leading-snug text-center bg-white border border-transparent cursor-pointer rounded-2xl hover:border-gray-200">
            Сумируем скидка
          </div>
        </div>

        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div class="text-2xl font-semibold">Предложение от брендов</div>
            <div>
              <a href="{{ route('site.brands') }}" class="flex items-center space-x-1 hover:underline">
                <span>Все бренды</span>
                <x-tabler-chevron-right class="w-5 h-5" />
              </a>
            </div>
          </div>
          <div class="px-6 py-8 bg-white rounded-2xl">
            <div>
              <div class="flex flex-col items-center justify-start space-x-8 md:flex-row">
                @forelse ($brandsOffer as $brand)
                  <div>
                    <a title="Товары бренда {{ $brand->name }}"
                      href="{{ route('site.brand', ['brandslug' => $brand->slug]) }}" class="p-2 group">
                      @if ($brand->logo)
                        <img src="/brands/{{ $brand->logo }}" alt="Товары бренда {{ $brand->name }}"
                          class="group-hover:text-blue-500">
                      @else
                        <span class="text-xl font-bold group-hover:text-blue-500">{{ $brand->name }}</span>
                      @endif
                    </a>
                  </div>
                @empty
                @endforelse
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="text-2xl font-semibold">Популярные товары</div>

          <div x-cloak x-data="{tab: 1}" class="px-4 py-4 bg-white lg:px-8 rounded-2xl">
            <div class="flex items-center justify-between space-x-6">
              <nav class="items-center justify-start flex-none md:flex">
                @if ($popular1->isNotEmpty())
                  <h2 x-on:click="tab = 1" data-route="description" :class="{ 'text-blue-500 border-blue-500': tab == 1 }"
                    class="block py-2 text-base font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Наполнители для туалета
                  </h2>
                @endif
                @if ($popular2->isNotEmpty())
                  <h2 x-on:click="tab = 2" data-route="consist" :class="{ 'text-blue-500 border-blue-500': tab == 2 }"
                    class="block py-2 text-base font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Одежда для собак
                  </h2>
                @endif
                @if ($popular3->isNotEmpty())
                  <h2 x-on:click="tab = 3" data-route="applying" :class="{ 'text-blue-500 border-blue-500': tab == 3 }"
                    class="block py-2 text-base font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Аквариумы для рыбок
                  </h2>
                @endif
                @if ($popular4->isNotEmpty())
                  <h2 x-on:click="tab = 4" data-route="applying" :class="{ 'text-blue-500 border-blue-500': tab == 4 }"
                    class="block py-2 text-base font-semibold text-gray-600 border-b-2 border-gray-200 cursor-pointer lg:px-6 hover:text-blue-500 focus:outline-none">
                    Кормушки для птиц
                  </h2>
                @endif
              </nav>
              <div>
              </div>
            </div>

            <div class="w-full">
              @if ($popular1->isNotEmpty())
                <div x-cloak :class="tab == 1 ? 'block' : 'hidden'" class="pt-6 space-y-8">
                  <div class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                    @foreach ($popular1 as $popular1Product)
                      <div class="w-full max-w-xs">
                        <livewire:site.card-products :product="$popular1Product"
                          :catalog="$popular1Product->categories[0]->catalog->slug"
                          :category="$popular1Product->categories[0]->slug" :wire:key="'product-'.$popular1Product->id" />
                      </div>
                    @endforeach
                  </div>
                  <div class="flex items-center justify-center w-full pb-4 space-x-1">
                    <a href="{{ route('site.category', ['catalogslug' => $popular1Product->categories[0]->catalog->slug, 'categoryslug' => $popular1Product->categories[0]->slug]) }}"
                      title="Все наполнители для кошачего туалета"
                      class="flex items-center space-x-1 font-semibold hover:underline">
                      <span>Все наполнители для кошачего туалета</span>
                      <x-tabler-chevron-right class="w-5 h-5" />
                    </a>
                  </div>
                </div>
              @endif

              @if ($popular2->isNotEmpty())
                <div x-cloak :class="tab == 2 ? 'block' : 'hidden'" class="pt-6 space-y-8">
                  <div class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                    @foreach ($popular2 as $popular2Product)
                      <div class="w-full max-w-xs">
                        <livewire:site.card-products :product="$popular2Product"
                          :catalog="$popular2Product->categories[0]->catalog->slug"
                          :category="$popular2Product->categories[0]->slug" :wire:key="'product-'.$popular2Product->id" />
                      </div>
                    @endforeach
                  </div>
                  <div class="flex items-center justify-center w-full pb-4 space-x-1">
                    <a href="{{ route('site.category', ['catalogslug' => $popular2Product->categories[0]->catalog->slug, 'categoryslug' => $popular2Product->categories[0]->slug]) }}"
                      title="Вся одежда для собак" class="flex items-center space-x-1 font-semibold hover:underline">
                      <span>Вся одежда для собак</span>
                      <x-tabler-chevron-right class="w-5 h-5" />
                    </a>
                  </div>
                </div>
              @endif

              @if ($popular3->isNotEmpty())
                <div x-cloak :class="tab == 3 ? 'block' : 'hidden'" class="pt-6 space-y-8">
                  <div class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                    @foreach ($popular3 as $popular3Product)
                      <div class="w-full max-w-xs">
                        <livewire:site.card-products :product="$popular3Product"
                          :catalog="$popular3Product->categories[0]->catalog->slug"
                          :category="$popular3Product->categories[0]->slug" :wire:key="'product-'.$popular3Product->id" />
                      </div>
                    @endforeach
                  </div>
                  <div class="flex items-center justify-center w-full pb-4 space-x-1">
                    <a href="{{ route('site.category', ['catalogslug' => $popular3Product->categories[0]->catalog->slug, 'categoryslug' => $popular3Product->categories[0]->slug]) }}"
                      title="Все аквариумы для рыбок" class="flex items-center space-x-1 font-semibold hover:underline">
                      <span>Все аквариумы для рыбок</span>
                      <x-tabler-chevron-right class="w-5 h-5" />
                    </a>
                  </div>
                </div>
              @endif

              @if ($popular4->isNotEmpty())
                <div x-cloak :class="tab == 4 ? 'block' : 'hidden'" class="pt-6 space-y-8">
                  <div class="flex flex-col items-start justify-between gap-4 pt-6 leading-normal md:flex-row">
                    @foreach ($popular4 as $popular4Product)
                      <div class="w-full max-w-xs">
                        <livewire:site.card-products :product="$popular4Product"
                          :catalog="$popular4Product->categories[0]->catalog->slug"
                          :category="$popular4Product->categories[0]->slug" :wire:key="'product-'.$popular4Product->id" />
                      </div>
                    @endforeach
                  </div>
                  <div class="flex items-center justify-center w-full pb-4 space-x-1">
                    <a href="{{ route('site.category', ['catalogslug' => $popular4Product->categories[0]->catalog->slug, 'categoryslug' => $popular4Product->categories[0]->slug]) }}"
                      title="Все кормушки для птиц" class="flex items-center space-x-1 font-semibold hover:underline">
                      <span>Все кормушки для птиц</span>
                      <x-tabler-chevron-right class="w-5 h-5" />
                    </a>
                  </div>
                </div>
              @endif

            </div>
          </div>

        </div>

        <div class="flex flex-col items-center justify-between max-w-screen-lg gap-6 py-12 mx-auto sm:flex-row">
          <div class="flex items-center justify-center space-x-4 text-lg">
            <svg class="w-8 h-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path class="stroke-current" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 14h1l2-2V2H6L3 4" />
              <path class="stroke-current" stroke-linecap=" round" stroke-linejoin="round" stroke-width="1.5"
                d="M2 17c0 2 1 3 3 3h1l2-2 2 2h4l2-2 2 2h1c2 0 3-1 3-3v-3h-3l-1-1v-3l1-1h1l-1-3-2-1h-2v7l-2 2h-1" />
              <path class="stroke-current" stroke-linecap=" round" stroke-linejoin="round" stroke-width="1.5"
                d="M8 22a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm8 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm6-10v2h-3l-1-1v-3l1-1h1l2 3zM2 8h6m-6 3h4m-4 3h2" />
            </svg>
            <span>Бесплатная доставка</span>
          </div>

          <div class="flex items-center justify-center space-x-4 text-lg">
            <x-tabler-shield-check class="w-8 h-8 text-gray-600" />
            <span>Гарантия качества</span>
          </div>

          <div class="flex items-center justify-center space-x-4 text-lg">
            <svg class="w-8 h-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path class="stroke-current" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M13 21h-2c-3-1-9-5-9-12 0-3 2-6 6-6l4 2a6 6 0 0 1 10 4c0 7-6 11-9 12Z" />
            </svg>
            <span>Душевное обслуживание</span>
          </div>
        </div>

        <div class="space-y-4">

          <div class="text-2xl font-semibold text-center">Помоги приюту</div>

          <div class="flex flex-col justify-center space-x-8 md:flex-row">
            <div class="w-full md:w-6/12">
              <a href="#">
                <img itemprop="image" loading="lazy"
                  class="object-contain object-center w-full h-full bg-white rounded-2xl hover:brightness-110"
                  src="/assets/img/dogs-shelter.webp" alt="Приют для собак">
              </a>
            </div>
            {{-- <div class="w-6/12">

                <img itemprop="image" loading="lazy"
                  class="object-contain object-center w-full h-full bg-white rounded-2xl hover:brightness-110"
                  src="/assets/img/cats-shelter.webp" alt="Приют для кошек">

            </div> --}}

          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
