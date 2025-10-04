@props([
    'variant' => 'default'
])

<span class="badge badge-{{ $variant }}" {{ $attributes }}>
    {{ $slot }}
</span>