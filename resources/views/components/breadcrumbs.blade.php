<div class="flex justify-start px-4 py-1 text-xs font-semibold text-gray-400 xl:px-0" itemscope
  itemtype="https://schema.org/BreadcrumbList">

  <div class="flex items-center justify-between" itemprop="itemListElement" itemscope
    itemtype="https://schema.org/ListItem">

    @if (request()->is('brands/*'))

      <a itemprop="item" class="py-1 pr-1 hover:underline" href="{{ route('site.brands') }}">
        <span itemprop="name">Бренды</span>
      </a>

    @else

      <a itemprop="item" class="py-1 pr-1 hover:underline"
        href="{{ route('site.catalog', ['catalogslug' => $catalog->slug]) }}">
        <span itemprop="name">{{ $catalog->name }}</span>
      </a>

    @endif
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
      <path
        d="M15.54,11.29,9.88,5.64a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l4.95,5L8.46,17a1,1,0,0,0,0,1.41,1,1,0,0,0,.71.3,1,1,0,0,0,.71-.3l5.66-5.65A1,1,0,0,0,15.54,11.29Z" />
    </svg>
  </div>

  @if (request()->is('brands/*'))
    @if ($category)

      <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
        class="flex items-center justify-between">
        <a itemprop="item" class="p-1 hover:underline" href="{{ route('site.brand', $category->slug) }}">
          <span itemprop="name">{{ $category->name }}</span>
        </a>
      </div>

    @endif
  @else

    @if ($category)
      <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
        class="flex items-center justify-between">
        @if ($catalog->slug == 'goods')

          <a itemprop="item" class="p-1 hover:underline"
            href="{{ route('site.category', ['catalogslug' => 'dogs', 'categoryslug' => $category->slug]) }}">
            <span itemprop="name">{{ $category->name }}</span>
          </a>

        @else

          <a itemprop="item" class="p-1 hover:underline"
            href="{{ route('site.category', ['catalogslug' => $catalog->slug, 'categoryslug' => $category->slug]) }}">
            <span itemprop="name">{{ $category->name }}</span>
          </a>

        @endif
      </div>
    @endif
  @endif
</div>
