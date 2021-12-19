 <div x-data="editor{{ $key }}" class="space-y-6">
    <div x-text="content"></div>
   <div wire:ignore class="space-y-6">
     <textarea x-ref="content{{ $key }}" class="editor">{!! $content !!}</textarea>
   </div>
   <script>
     document.addEventListener('alpine:init', () => {
       Alpine.data('editor{{ $key }}', () => ({
         content: @entangle($attributes->wire('model')),
         editor: [],
         init() {
           var that=this;
           this.editor = window.Editor.create(this.$refs.content{{ $key }}, {});
           this.editor.setContents(this.content);
           this.editor.onChange = function (contents, core) {that.content = contents}
         },
         addBlock() {
           this.init();
           window.livewire.emit('addBlock')
         },
         setContent(contents) {
           this.content = contents
         }
       }))
     })
   </script>

  </div>
