@props([
    'label' => null,
    'name' => 'username',
    'id' => 'username',
    'placeholder' => '',
    'value' => '',
    'showPreview' => true
])

<div class="form-group">
    @if($label)
        <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    @endif
    
    <div class="username-group">
        <input 
            type="text" 
            class="form-input" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}" 
            value="{{ old($name, $value) }}"
            autocomplete="username" 
            aria-describedby="usernameHelp"
            {{ $attributes }}
        />
        <div class="username-status" id="usernameStatus" aria-live="polite"></div>
    </div>

    @if($showPreview)
        <div class="username-preview" id="usernamePreview" aria-live="polite">
            Your profile: <span class="username-url">promatch.com/<span id="previewUsername"></span></span>
            <button type="button" class="regenerate-btn" id="regenBtn">regenerate</button>
        </div>

        <div class="success-message" id="usernameSuccess">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Username is available</span>
        </div>
    @endif

    <p class="sr-only" id="usernameHelp">Only letters, numbers, and hyphens. Minimum 3 characters.</p>
</div>