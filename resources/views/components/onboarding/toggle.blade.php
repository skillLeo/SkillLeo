@props([
    'name',
    'label',
    'description' => null,
    'checked' => false
])

<div class="toggle-row">
    <div class="toggle-info">
        <div class="toggle-label">{{ $label }}</div>
        @if($description)
            <div class="toggle-desc">{{ $description }}</div>
        @endif
    </div>
    
    <label class="toggle-switch">
        <input 
            type="checkbox" 
            name="{{ $name }}"
            @if($checked) checked @endif
            {{ $attributes }}
        >
        <span class="toggle-slider"></span>
    </label>
</div>