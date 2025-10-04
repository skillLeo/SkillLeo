
@props([
    'label' => null,
    'name',
    'id' => null,
    'required' => false,
    'placeholder' => '',
    'value' => '',
    'rows' => 4,
    'maxlength' => null,
    'showCounter' => false,
    'hint' => null
])

@php
    $textareaId = $id ?? $name;
@endphp

<div class="form-group">
    @if($label)
        <label class="form-label" for="{{ $textareaId }}">
            {{ $label }}
            @if($required)
                <span class="required">*</span>
            @endif
        </label>
    @endif
    
    <textarea
        id="{{ $textareaId }}"
        name="{{ $name }}"
        class="form-textarea"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>

    <div class="textarea-footer">
        @if($hint)
            <div class="form-hint">{{ $hint }}</div>
        @endif
        
        @if($showCounter && $maxlength)
            <div class="char-count">
                <span class="current-count">0</span>/{{ $maxlength }}
            </div>
        @endif
    </div>

    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>