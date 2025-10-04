@props([
    'label' => null,
    'name',
    'required' => false,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'hint' => null
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

    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-select"
        @if($required) required @endif
        {{ $attributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $label)
            <option 
                value="{{ $value }}" 
                @if(old($name, $selected) == $value) selected @endif
            >
                {{ $label }}
            </option>
        @endforeach
    </select>

    @if($hint)
        <div class="form-hint">{{ $hint }}</div>
    @endif

    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>

<style>
    .form-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--input-border);
        border-radius: var(--radius);
        font-size: var(--fs-body);
        font-family: inherit;
        background: var(--input-bg);
        color: var(--input-text);
        cursor: pointer;
        transition: all 0.2s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 36px;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }
</style>