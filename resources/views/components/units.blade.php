<div>
  @if ($unit)
    <div class="text-xs text-gray-500">
      <span>
        @if ($unit->name === 'гр')
          {{ kg($value) }}
        @else
          {{ $value }} {{ $unit->name }}
        @endif
      </span>
    </div>
  @endif
</div>
