<div
  class="z-50 px-4 py-3 overflow-hidden border-r-8 rounded-md shadow cursor-pointer pointer-events-auto select-none bg-gray-50 hover:shadow-lg dark:bg-gray-900 dark:hover:bg-gray-800"
  x-bind:class="{
                    'border-blue-500': toast.type === 'info',
                    'border-green-500': toast.type === 'success',
                    'border-yellow-500': toast.type === 'warning',
                    'border-red-500': toast.type === 'danger'
                }">
  <div>
    <div class="pb-1 text-lg font-black tracking-widest text-gray-900 uppercase font-large dark:text-gray-100"
      x-text="toast.title"></div>
    <p class="text-gray-900 dark:text-gray-200" x-html="toast.message"></p>
  </div>
</div>
