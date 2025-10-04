@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
    $classes = 'btn btn-' . $variant;
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if(!$href) type="{{ $type }}" @endif
    @if($disabled) disabled @endif
    class="{{ $classes }}"
    {{ $attributes }}
>
    @if($icon && $iconPosition === 'left')
        {!! $icon !!}
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        {!! $icon !!}
    @endif
</{{ $tag }}>