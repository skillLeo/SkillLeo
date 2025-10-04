@props([
    'label' => null,
    'name',
    'id' => null,
    'required' => false,
    'placeholder' => 'Select an option',
    'options' => [],
    'selected' => null
])

@php
    $selectId = $id ?? $name;
@endphp

<div class="form-group">
    @if($label)
        <label class="form-label" for="{{ $selectId }}">
            {{ $label }}
            @if($required)
                <span class="required">*</span>
            @endif
        </label>
    @endif
    
    <select
        id="{{ $selectId }}"
        name="{{ $name }}"
        class="form-select"
        @if($required) required @endif
        {{ $attributes }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option 
                value="{{ $value }}"
                @if(old($name, $selected) == $value) selected @endif
            >
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>