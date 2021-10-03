<div x-cloak x-data="toaster" x-on:toaster.window.debounce.700="trigger">
  <div x-show="toastes.length > 0" class="fixed bottom-0 left-0 z-50 flex flex-col max-w-sm p-4 space-y-3 w-80"
    x-transition.duration.300ms>
    <template x-for="(toast, index) in toastes" :key="index">
      <div x-show="toastes.indexOf(toast) >=0" x-transition.duration.300ms>
        <div class="flex items-center justify-between w-full px-4 py-3 text-white rounded-md shadow-lg"
          :class="toast.class ? toast.class : 'bg-green-500' ">
          <div x-text="toast.message" class="leading-snug"></div>
        </div>
      </div>
    </template>

  </div>
  <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toaster', () => ({
          toastes: [],
          flash: null,
          trigger(event = null) {
            if (event == null) return;
            this.flash = event.detail ? event.detail : event;
            let toast = {
              class: this.flash.class,
              message: this.flash.message,
              timeout: this.flash.timeout ? this.flash.timeout : 3000
            }
            this.toastes.push(toast)
            this.autoClose(toast)
          },
          autoClose(toast) {
            setTimeout(() => {
              this.close(toast)
            }, toast.timeout)
          },
          close(toast) {
            this.toastes.splice(this.toastes.indexOf(toast), 1)
          },
          init() {
            if (this.flash) {
              this.trigger(this.flash)
            }
          },
        }))
    })
  </script>
</div>