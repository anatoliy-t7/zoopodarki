<div x-cloak x-data="toaster()" class="fixed bottom-0 left-0 z-50 max-w-sm p-4 space-y-3 w-80"
  @toast.window="add($event.detail)" style="pointer-events:none">
  <template x-for="notice of notices" :key="notice.id">
    <div x-show="visible.includes(notice)" x-transition.opacity.duration.300ms @click="remove(notice.id)"
      class="w-full px-4 py-3 leading-tight text-white rounded-md shadow-lg cursor-pointer"
      :class="{
				'bg-green-500': notice.type === 'success',
				'bg-blue-500': notice.type === 'info',
				'bg-orange-500': notice.type === 'warning',
				'bg-red-500': notice.type === 'error',
        'bg-green-500': notice.type  === undefined,
			 }"
      style="pointer-events:all" x-text="notice.text">
    </div>
  </template>
</div>
