<div class="text-base text-gray-500 md:text-xs">
  @if ($unit || $value)
    @if ($unit && $unit['name'] === 'гр')

      @if (is_numeric($value))

        {{ kg($value) }}
      @else

        {{ $value }}
      @endif

    @elseif($unit && $value)
      {{ $value }} {{ $unit['name'] }}
    @else
      {{ $value }}
    @endif
  @endif
</div>
