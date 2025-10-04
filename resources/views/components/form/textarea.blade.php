@props([
    'label' => null,
    'name',
    'placeholder' => '',
    'required' => false,
    'maxlength' => null,
    'rows' => 4,
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

    <textarea 
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-textarea"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($required) required @endif
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>

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
    .form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--input-border);
        border-radius: var(--radius);
        font-size: var(--fs-body);
        font-family: inherit;
        background: var(--input-bg);
        color: var(--input-text);
        transition: all 0.2s ease;
        resize: vertical;
        min-height: 80px;
        line-height: var(--lh-relaxed);
    }

    .form-textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }
</style>