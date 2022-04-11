<div>
  @auth

  @if (auth()->user()->discount === 0)
  <a class="flex items-center justify-start gap-4 px-4 py-2 bg-orange-100 border border-orange-200 rounded-2xl hover:shadow-lg hover:shadow-orange-200" href="{{ route('site.product', ['catalogslug' => 'promotions-and-gifts', 'categoryslug' => 'diskontnaya-karta', 'productslug' => 'diskontnaya-karta-5']) }}" title="Cкидочная карта 5%">
    <x-tabler-credit-card class="w-8 h-8 text-yellow-500 " />
    <p class="leading-4">Приобрести скидочную карту 5% <br><span class="text-xs font-semibold">действует
        сразу</span>
    </p>

  </a>
  @endif
  @endauth

</div>
