<div>
  @if ($unit)
    <div class="text-base text-gray-500 md:text-xs">
      <span>
        @if ($unit->name === 'гр')
          {{ kg($value) }}
        @elseif($value)
          {{ $value }} {{ $unit->name }}
        @endif
      </span>
    </div>
  @endif
</div>
