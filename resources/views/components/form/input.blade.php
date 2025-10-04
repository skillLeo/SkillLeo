@props([
    'label' => null,
    'name',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'maxlength' => null,
    'value' => '',
    'hint' => null,
    'showCharCount' => false
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
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-input"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($required) required @endif
        {{ $attributes }}
    />

    @if($showCharCount && $maxlength)
        <div class="char-count" id="char-{{ $name }}">
            <span class="current">0</span> / {{ $maxlength }}
        </div>
    @endif

    @if($hint)
        <div class="form-hint">{{ $hint }}</div>
    @endif

    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>

<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-body);
        margin-bottom: 6px;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--input-border);
        border-radius: var(--radius);
        font-size: var(--fs-body);
        font-family: inherit;
        background: var(--input-bg);
        color: var(--input-text);
        transition: all 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .form-input::placeholder {
        color: var(--input-placeholder);
    }

    .char-count {
        text-align: right;
        font-size: var(--fs-micro);
        color: var(--text-subtle);
        margin-top: 4px;
    }

    .form-hint {
        font-size: var(--fs-micro);
        color: var(--text-subtle);
        margin-top: 4px;
    }

    .form-error {
        font-size: var(--fs-micro);
        color: var(--error);
        margin-top: 4px;
    }

    .required {
        color: var(--error);
    }
</style>