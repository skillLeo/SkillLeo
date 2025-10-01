@props([
    'label' => '',
    'name' => '',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'rows' => 4,
    'maxlength' => null,
    'showCounter' => false,
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
        class="form-textarea {{ $attributes->get('class') }}"
        id="{{ $name }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes->except(['class']) }}
    >{{ old($name, $value) }}</textarea>

    @if($showCounter && $maxlength)
        <div class="char-count" id="char-{{ $name }}">
            <span class="current">0</span>/{{ $maxlength }} characters
        </div>
    @endif

    @error($name)
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

@once
@push('styles')
<style>
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        background: var(--white);
        transition: all .2s ease;
        resize: vertical;
        min-height: 96px;
        line-height: 1.6;
    }

    .form-textarea:focus {
        outline: none;
        border-color: var(--dark);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }

    .char-count {
        text-align: right;
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 6px;
    }
</style>
@endpush

@if($showCounter && $maxlength)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('{{ $name }}');
        const counter = document.querySelector('#char-{{ $name }} .current');
        
        if (textarea && counter) {
            textarea.addEventListener('input', function() {
                counter.textContent = this.value.length;
            });
            counter.textContent = textarea.value.length;
        }
    });
</script>
@endpush
@endif
@endonce