<div x-data="{ share: false, url: '{{ $url }}', copy: false,  copyToggle() { this.copy = !this.copy} }"
  class="relative">
  <!--googleoff: all-->
  <!--noindex-->
  <button x-on:click="share = !share" title="Поделиться ссылкой" class="link-hover"
    :class="share ? 'text-orange-500' : 'text-gray-500'">
    <x-tabler-share class="w-6 h-6 stroke-current hover:text-orange-500" />
  </button>

  <div x-cloak x-on:click.outside="share = false" x-show="share" x-transition
    class="absolute z-30 flex flex-col items-start justify-between w-auto text-sm text-gray-500 shadow-lg -right-4 top-8 rounded-xl bg-gray-50 ">

    <a href="https://vk.com/share.php?url={{ $url }}" target="_blank" rel="noreferrer"
      class="flex items-center justify-start w-full px-4 pt-3 pb-2 space-x-3 rounded-t-xl hover:bg-gray-100"
      title="Вконтакте">
      <svg class="bg-blue-600 rounded-md fill-current w-7 h-7 " viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M12.785 16.241s.288-.032.436-.194c.136-.148.132-.427.132-.427s-.02-1.304.576-1.496c.588-.19 1.341 1.26 2.14 1.818.605.422 1.064.33 1.064.33l2.137-.03s1.117-.071.587-.964c-.043-.073-.308-.661-1.588-1.87-1.34-1.264-1.16-1.059.453-3.246.983-1.332 1.376-2.145 1.253-2.493-.117-.332-.84-.244-.84-.244l-2.406.015s-.178-.025-.31.056c-.13.079-.212.262-.212.262s-.382 1.03-.89 1.907c-1.07 1.85-1.499 1.948-1.674 1.832-.407-.267-.305-1.075-.305-1.648 0-1.793.267-2.54-.521-2.733-.262-.065-.454-.107-1.123-.114-.858-.009-1.585.003-1.996.208-.274.136-.485.44-.356.457.159.022.519.099.71.363.246.341.237 1.107.237 1.107s.142 2.11-.33 2.371c-.325.18-.77-.187-1.725-1.865-.489-.859-.859-1.81-.859-1.81s-.07-.176-.198-.272c-.154-.115-.37-.151-.37-.151l-2.286.015s-.343.01-.469.161C3.94 7.721 4.043 8 4.043 8s1.79 4.258 3.817 6.403c1.858 1.967 3.968 1.838 3.968 1.838h.957z"
          fill="#FFF" fill-rule="evenodd" />
      </svg>
      <span>Вконтакте</span>
    </a>

    <a href="https://connect.ok.ru/offer?url={{ $url }}" target="_blank" rel="noreferrer"
      class="flex items-center justify-start w-full px-4 py-2 space-x-3 hover:bg-gray-100" title="Одноклассники">
      <svg class="bg-orange-400 rounded-md fill-current w-7 h-7 " viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <g fill="#FFF" fill-rule="evenodd">
          <path
            d="M11.674 6.536a1.69 1.69 0 0 0-1.688 1.688c0 .93.757 1.687 1.688 1.687a1.69 1.69 0 0 0 1.688-1.687 1.69 1.69 0 0 0-1.688-1.688zm0 5.763a4.08 4.08 0 0 1-4.076-4.075 4.08 4.08 0 0 1 4.076-4.077 4.08 4.08 0 0 1 4.077 4.077 4.08 4.08 0 0 1-4.077 4.075zM10.025 15.624a7.633 7.633 0 0 1-2.367-.98 1.194 1.194 0 0 1 1.272-2.022 5.175 5.175 0 0 0 5.489 0 1.194 1.194 0 1 1 1.272 2.022 7.647 7.647 0 0 1-2.367.98l2.279 2.28a1.194 1.194 0 0 1-1.69 1.688l-2.238-2.24-2.24 2.24a1.193 1.193 0 1 1-1.689-1.689l2.279-2.279" />
        </g>
      </svg>
      <span>Одноклассники</span>
    </a>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noreferrer"
      class="flex items-center justify-start w-full px-4 py-2 space-x-3 hover:bg-gray-100" title="Facebook">
      <svg class="bg-blue-600 rounded-md fill-current w-7 h-7 " viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M13.423 20v-7.298h2.464l.369-2.845h-2.832V8.042c0-.824.23-1.385 1.417-1.385h1.515V4.111A20.255 20.255 0
        0 0 14.148 4c-2.183 0-3.678 1.326-3.678 3.76v2.097H8v2.845h2.47V20h2.953z" fill="#FFF" fill-rule="evenodd" />
      </svg>
      <span>Facebook</span>
    </a>

    <button aria-label="Скопировать ссылку" x-on:click="navigator.clipboard.writeText(url); copyToggle()"
      class="flex items-center justify-start w-full px-4 pt-1 pb-3 space-x-3 rounded-b-xl hover:bg-gray-100"
      title="Скопировать ссылку">
      <span :class="copy ? 'animate-spin-once' : ''">
        <x-tabler-link class="text-gray-600 stroke-current w-7 h-7 " />
      </span>
      <div class="leading-tight text-left">Скопировать ссылку</div>
    </button>

  </div>
  <!--/noindex-->
  <!--googleon: all-->
</div>
