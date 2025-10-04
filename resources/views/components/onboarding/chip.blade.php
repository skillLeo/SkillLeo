@props([
    'removable' => false,
    'removeId' => null
])

<div class="chip {{ $removable ? 'chip-removable' : '' }}" {{ $attributes }}>
    {{ $slot }}
    
    @if($removable)
        <button 
            type="button" 
            class="chip-remove"
            @if($removeId) data-id="{{ $removeId }}" @endif
            aria-label="Remove"
        >×</button>
    @endif
</div>