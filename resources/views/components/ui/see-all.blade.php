@props([
    'text' => 'See all',
    'onclick' => null,
    'class' => '',
])

<div class="see-all-container {{ $class }}" @if ($onclick) onclick="{{ $onclick }}" @endif {{ $attributes }}>
    <a href="javascript:void(0)" class="see-all-link">
        <span class="see-all-text">{{ $text }}</span>
        <i class="fas fa-arrow-right see-all-icon"></i>
    </a>
</div>
