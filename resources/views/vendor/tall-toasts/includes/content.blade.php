<div
  class="z-50 px-4 py-3 overflow-hidden bg-green-500 rounded-md shadow cursor-pointer pointer-events-auto select-none hover:shadow-lg"
  x-bind:class="{
                    'bg-blue-500': toast.type === 'info',
                    'bg-green-500': toast.type === 'success',
                    'bg-yellow-500': toast.type === 'warning',
                    'bg-red-500': toast.type === 'danger'
                }">

  <div class="flex items-center justify-between space-x-5">
    <div class="flex-1">
      <div class="mb-1 text-lg font-black tracking-widest text-white uppercase" x-show="toast.title !== undefined"
        x-html="toast.title"></div>

      <div class="font-semibold leading-snug text-white" x-show="toast.message !== undefined" x-html="toast.message">
      </div>
    </div>
  </div>


</div>
