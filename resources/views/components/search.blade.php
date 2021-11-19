<div x-data="toggleSearch" @close.window="closeSearch(event)" class="relative">
  <button class="p-1" :class="search ? 'text-blue-600' : 'text-gray-600'" @click="openSearch()">
    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <path class="text-gray-600 stroke-current group-hover:text-orange-500 focus:text-orange-500" stroke-linecap="round"
        stroke-linejoin="round" stroke-width="1.5" d="M11.5 21a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19zM22 22l-2-2" />
    </svg>
  </button>

  <div x-cloak @click="closeSearch" x-show="search" x-transition.opacity
    class="fixed top-0 bottom-0 left-0 right-0 z-40 w-screen h-screen overflow-hidden bg-gray-700 bg-opacity-50 pointer-events-auto">
  </div>

  <div x-cloak class="fixed z-50 top-6 left-4 right-4" x-show="search" x-transition.opacity>
    <livewire:site.search.search-com>
  </div>

  <script>
    document.addEventListener('alpine:initializing', () => {
      Alpine.data('toggleSearch', () => ({
        body: document.body,
        search: false,
        openSearch() {
          this.search = true
          this.body.classList.add('overflow-hidden')
        },

        closeSearch() {
          this.search = false
          window.livewire.emit('resetSearch')
          this.body.classList.remove('overflow-hidden');
        },
      }))
    })
  </script>
  <script>
    document.addEventListener("keydown", function(e) {
      if (e.keyCode == 27) {
        e.preventDefault();
        var event = new CustomEvent('close');
        window.dispatchEvent(event);
      }
    }, false);
  </script>

</div>
