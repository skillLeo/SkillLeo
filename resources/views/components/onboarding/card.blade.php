@props([
    'title' => null,
    'subtitle' => null
])

<div class="card" {{ $attributes }}>
    @if($title || $subtitle)
        <div class="card-header">
            @if($title)
                <h3 class="card-title">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="card-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
</div>