<div wire:ignore>
  <textarea {{ $attributes->whereStartsWith('wire:model') }} id="editor{{ $index }}"
    wire:key="editor{{ $index }}" x-data x-init="
      CKEDITOR.replace('editor{{ $index }}', {
        filebrowserUploadUrl: '{{ route('dashboard.upload', ['_token' => csrf_token()]) }}',
        filebrowserUploadMethod: 'form',
        language: 'ru',
        image2_alignClasses: ['image-left', 'image-center', 'image-right'],
        image2_altRequired: true,
      });
      CKEDITOR.instances.editor{{ $index }}.on('change', function() {
        $dispatch('input', this.getData());
      });
      ">
    {{ $content }}
  </textarea>
  @once
    @push('header-js')
      <script src="/js/ckeditor/ckeditor.js"></script>
    @endpush
  @endonce
</div>
