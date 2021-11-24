<div
  class="z-50 px-4 py-3 overflow-hidden border-r-8 rounded-md shadow cursor-pointer pointer-events-auto select-none bg-gray-50 hover:shadow-lg dark:bg-gray-900 dark:hover:bg-gray-800"
  x-bind:class="{
                    'border-blue-500': toast.type === 'info',
                    'border-green-500': toast.type === 'success',
                    'border-yellow-500': toast.type === 'warning',
                    'border-red-500': toast.type === 'danger'
                }">

  <div class="flex items-center justify-between space-x-5">
    <div class="flex-1 mr-2">
      <div class="mb-1 text-lg font-black tracking-widest text-gray-900 uppercase font-large dark:text-gray-100"
        x-show="toast.title !== undefined" x-html="toast.title"></div>

      <div class="text-gray-900 dark:text-gray-200" x-show="toast.message !== undefined" x-html="toast.message"></div>
    </div>
  </div>


</div>
