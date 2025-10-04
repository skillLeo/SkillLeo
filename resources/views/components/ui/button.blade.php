@props([
    'variant' => 'solid', // solid, outlined, special-outlined, special-solid
    'shape' => 'rounded', // rounded, square
    'size' => 'md', // sm, md, lg
    'color' => 'primary', // primary, secondary, success, danger
    'type' => 'button',
    'href' => null,
    'onclick' => null,
])

@php
    $baseClasses = 'btn';
    $variantClasses = match ($variant) {
        'outlined' => 'btn-outlined',
        'special-outlined' => 'btn-special-outlined',
        'special-solid' => 'btn-special-solid',
        default => 'btn-solid',
    };
    $shapeClasses = $shape === 'square' ? 'btn-square' : 'btn-rounded';
    $sizeClasses = match ($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => 'btn-md',
    };
    $colorClasses = 'btn-' . $color;

    $classes = implode(' ', [$baseClasses, $variantClasses, $shapeClasses, $sizeClasses, $colorClasses]);
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $classes }}"
        @if ($onclick) onclick="{{ $onclick }}" @endif {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}"
        @if ($onclick) onclick="{{ $onclick }}" @endif {{ $attributes }}>
        {{ $slot }}
    </button>
@endif


<style>
 
</style>
