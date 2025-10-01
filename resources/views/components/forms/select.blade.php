@props([
    'label' => '',
    'name' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'options' => [],
    'selected' => null,
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
        class="form-select {{ $attributes->get('class') }}"
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->except(['class']) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error($name)
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

@once
@push('styles')
<style>
    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        background: var(--white);
        transition: all .2s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--dark);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }

    .form-select.success {
        border-color: var(--success);
    }
</style>
@endpush
@endonce