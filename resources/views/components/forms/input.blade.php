@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'autocomplete' => '',
    'maxlength' => null,
])

<div class="form-group">
    @if($label)
        <label class="form-label" for="{{ $name }}">
            {{ $label }}
            @if($required)
                <span class="required">*</span>
            @endif
        </label>
    @endif

    <input 
        type="{{ $type }}"
        class="form-input {{ $attributes->get('class') }}"
        id="{{ $name }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes->except(['class']) }}
    />

    @error($name)
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

@once
@push('styles')
<style>
    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--gray-700);
        margin-bottom: 8px;
    }

    .required {
        color: var(--error);
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        background: var(--white);
        transition: all .2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--dark);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }

    .form-input.success {
        border-color: var(--success);
    }

    .form-error {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        color: var(--error);
    }
</style>
@endpush
@endonce