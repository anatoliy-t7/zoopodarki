<div x-data="toggleSearch" @close.window="closeSearch(event)" class="relative">
  <button class="p-1" :class="search ? 'text-blue-600' : ''" @click="openSearch()">
    <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path
        d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z" />
    </svg>
  </button>

  <div x-cloak @click="closeSearch" x-show="search" x-transition.opacity
    class="fixed top-0 bottom-0 left-0 right-0 z-40 w-screen h-screen overflow-hidden bg-gray-700 bg-opacity-50 pointer-events-auto">
  </div>

  <div x-cloak class="fixed z-50 top-6 left-4 right-4" x-show="search" x-transition.opacity>
    <livewire:site.search-com>
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
    document.addEventListener("keydown", function (e) {
            if (e.keyCode == 27) {
                e.preventDefault();
                var event = new CustomEvent('close');
                window.dispatchEvent(event);
            }
        }, false);
  </script>

</div>