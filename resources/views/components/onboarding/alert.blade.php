@props([
    'type' => 'info'
])

<div class="alert alert-{{ $type }}" role="alert" {{ $attributes }}>
    {{ $slot }}
</div>