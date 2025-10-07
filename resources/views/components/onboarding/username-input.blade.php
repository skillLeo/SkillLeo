@props([
    'label' => null,
    'name' => 'username',
    'id' => 'username',
    'placeholder' => '',
    'value' => '',
    'showPreview' => true,
])

@php
    $appUrl = rtrim(config('app.url'), '/');
    $host   = parse_url($appUrl, PHP_URL_HOST) ?: 'promatch.com';
@endphp

<div class="form-group">
    @if ($label)
        <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    @endif

    <div class="username-group">
        <input
            type="text"
            class="form-input"
            id="{{ $id }}"                     {{-- JS expects id="username" --}}
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            autocomplete="username"
            aria-describedby="usernameHelp usernameStatus"
            maxlength="50"
            {{ $attributes }}
        />
        <div class="username-status" id="usernameStatus" aria-live="polite"></div>
    </div>

    @if ($showPreview)
        <div class="username-preview" id="usernamePreview" aria-live="polite">
            Your profile:
            <a
                id="profilePreviewLink"
                data-base="{{ $appUrl }}/"
                href="{{ $appUrl }}/"
                target="_blank"
                rel="noopener"
                class="username-url"
            >
                {{ $host }}/<span id="previewUsername"></span>
            </a>

            <button type="button" class="regenerate-btn" id="regenBtn">regenerate</button>
        </div>

        <div class="success-message" id="usernameSuccess" hidden>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Username is available</span>
        </div>
    @endif

    <p class="sr-only" id="usernameHelp">Only letters, numbers, underscores, and hyphens. Minimum 3 characters.</p>
</div>
