@props([
    'label' => null,
    'name',
    'id' => null,
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'value' => '',
    'hint' => null,
    'icon' => null
])

@php
    $inputId = $id ?? $name;
    $hasIcon = !empty($icon);
@endphp

<div class="form-group">
    @if($label)
        <label class="form-label" for="{{ $inputId }}">
            {{ $label }}
            @if($required)
                <span class="required">*</span>
            @endif
        </label>
    @endif
    
    <div class="input-wrapper">
        @if($hasIcon)
            <span class="input-icon">{!! $icon !!}</span>
        @endif
        
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            class="form-input {{ $hasIcon ? 'has-icon' : '' }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            @if($required) required @endif
            {{ $attributes }}
        >
    </div>

    @if($hint)
        <div class="form-hint">{{ $hint }}</div>
    @endif

    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>