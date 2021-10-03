<div x-cloak x-data="modal">
  <div x-on:close-modal.window="showModal = false" x-show="showModal" x-transition.opacity
    @keydown.window.escape="showModal = false"
    class="fixed top-0 left-0 z-40 flex items-center justify-center w-screen h-screen bg-gray-500 bg-opacity-50"
    role="dialog" aria-modal="true">
    <div x-on:click.outside="close()" x-show="showModal" x-transition
      class="absolute z-40 flex flex-col w-full max-w-xs px-6 py-5 bg-white shadow-xl rounded-xl">
      <div class="flex items-center justify-between">
        <div></div>
        <button x-on:click="close()" class="link-hover">
          <x-tabler-x class="w-6 h-6 text-gray-500 stroke-current" />
        </button>
      </div>
      {{ $content }}
    </div>
  </div>
  <div>
    {{ $button }}
  </div>
  <script>
    document.addEventListener('alpine:initializing', () => {
      Alpine.data('modal', () => ({
        body: document.body,
        showModal: false,
        open() {
          this.showModal = true
          this.body.classList.add('overflow-hidden', 'pr-4');
        },
        close() {
          this.showModal = false
          this.body.classList.remove('overflow-hidden', 'pr-4');
        },
      }))
    })
  </script>
</div>
