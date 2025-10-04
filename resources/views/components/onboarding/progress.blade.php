@props([
    'label' => 'Progress',
    'current' => 0,
    'max' => 100,
    'showCount' => true
])

@php
    $percentage = $max > 0 ? ($current / $max) * 100 : 0;
@endphp

<div class="progress-container">
    <div class="progress-header">
        <span class="progress-label">{{ $label }}</span>
        @if($showCount)
            <span class="progress-count">
                <strong>{{ $current }}</strong> / {{ $max }}
            </span>
        @endif
    </div>
    <div class="progress-track">
        <div class="progress-bar-fill" style="width: {{ $percentage }}%"></div>
    </div>
</div>