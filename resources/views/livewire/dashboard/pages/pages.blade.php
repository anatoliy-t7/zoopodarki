@section('title')
  Страницы
@endsection
<div>

  <div class="flex items-center justify-between w-full pb-2 space-x-6">

    <h3 class="text-2xl">Страницы</h3>

    @can('create')
      <a id="add" title="Создать новую страницу" class="flex space-x-2 text-white bg-green-500 btn hover:bg-green-700"
        href="{{ route('dashboard.page.edit', ['pageId' => null]) }}">
        <x-tabler-file-plus class="w-6 h-6 text-white" />
        <div>Создать</div>
      </a>
    @endcan

  </div>

  <div>

    <div class="py-4">
      <x-dashboard.table>
        <x-slot name="head">
          <x-dashboard.table.head>
            Id
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Заголовок
          </x-dashboard.table.head>
          <x-dashboard.table.head>
            Статус
          </x-dashboard.table.head>
          <x-dashboard.table.head>
          </x-dashboard.table.head>
        </x-slot>

        <x-slot name="body">

          <x-dashboard.table.row>
            <x-dashboard.table.cell></x-dashboard.table.cell>
            <x-dashboard.table.cell>
              Главная страница
            </x-dashboard.table.cell>
            <x-dashboard.table.cell>

            </x-dashboard.table.cell>
            <x-dashboard.table.cell>
              <div class="opacity-0 group-hover:opacity-100">
                <a class="text-gray-400 hover:text-orange-400 " href="{{ route('dashboard.home.edit') }}">
                  <x-tabler-edit class="w-6 h-6 stroke-current" />
                </a>
              </div>
            </x-dashboard.table.cell>
          </x-dashboard.table.row>

          @forelse($pages as $key => $page)
            <x-dashboard.table.row>

              <x-dashboard.table.cell>
                {{ $page->id }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                {{ $page->title }}
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>
                <div class="flex items-center justify-start">
                  @if ($page->isActive === 1)
                    <div class="flex items-center py-1 space-x-2 text-sm">
                      <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                      <span>Опубликован</span>
                    </div>
                  @else
                    <div class="flex items-center py-1 space-x-2 text-sm ">
                      <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                      <span>Не опубликован</span>
                    </div>
                  @endif
                </div>
              </x-dashboard.table.cell>

              <x-dashboard.table.cell>

                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100">
                  <a class="p-2 text-gray-400 hover:text-orange-400"
                    href="{{ route('site.page', ['slug' => $page->slug]) }}" target="_blank">
                    <x-tabler-external-link class="w-6 h-6 stroke-current" />
                  </a>

                  <a id="add" title="Редактировать" class="p-2 text-gray-400 hover:text-orange-400"
                    href="{{ route('dashboard.page.edit', ['pageId' => $page->id]) }}">
                    <x-tabler-edit class="w-6 h-6 stroke-current" />
                  </a>

                  <x-dashboard.confirm :confirmId="$page->id" wire:key="remove{{ $page->id }}" />
                </div>

              </x-dashboard.table.cell>

            </x-dashboard.table.row>
          @empty
            <x-dashboard.table.row>
              <x-dashboard.table.cell>

              </x-dashboard.table.cell>
              <x-dashboard.table.cell>

              </x-dashboard.table.cell>
              <x-dashboard.table.cell>

              </x-dashboard.table.cell>
              <x-dashboard.table.cell>

              </x-dashboard.table.cell>
            </x-dashboard.table.row>
          @endforelse
        </x-slot>

      </x-dashboard.table>
    </div>


    <div class="py-4">
      {{ $pages->links() }}
    </div>

    <script>
      document.addEventListener("keydown", function(e) {

        if (e.keyCode == 112) {
          e.preventDefault();
          var event = new CustomEvent('new');
          window.dispatchEvent(event);
        }

      }, false);
    </script>


  </div>
</div>
