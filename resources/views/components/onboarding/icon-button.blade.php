@props([
    'variant' => 'default',
    'type' => 'button',
    'ariaLabel' => 'Button'
])

<button 
    type="{{ $type }}"
    class="icon-btn icon-btn-{{ $variant }}"
    aria-label="{{ $ariaLabel }}"
    {{ $attributes }}
>
    {{ $slot }}
</button>