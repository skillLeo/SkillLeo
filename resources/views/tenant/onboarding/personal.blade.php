@extends('layouts.onboarding')

@section('title', 'Personal Information - ProMatch')

@php
    $currentStep = 1;
    $totalSteps = 8;
@endphp

@push('styles')
<style>
    :root {
        --primary: #0061FF;
        --dark: #000000;
        --white: #FFFFFF;
        --gray-900: #111111;
        --gray-700: #404040;
        --gray-500: #737373;
        --gray-300: #E5E7EB;
        --gray-100: #F9FAFB;
        --error: #EF4444;
        --success: #10B981;
    }

    .form-header {
        margin-bottom: 32px;
    }

    .form-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }

    .form-subtitle {
        font-size: 15px;
        color: var(--gray-500);
    }

    .step-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: var(--gray-100);
        color: var(--gray-700);
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 16px;
    }

    .step-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        background: var(--primary);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    .input-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

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

    .username-group {
        position: relative;
    }

    .username-status {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: var(--gray-500);
    }

    .username-preview {
        margin-top: 8px;
        padding: 10px 14px;
        background: var(--gray-100);
        border-radius: 6px;
        font-size: 13px;
        color: var(--gray-700);
        display: none;
    }

    .username-preview.show {
        display: block;
    }

    .username-url {
        font-weight: 600;
        color: var(--dark);
    }

    .regenerate-btn {
        background: none;
        border: none;
        color: var(--primary);
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        margin-left: 8px;
        text-decoration: underline;
        padding: 0;
    }

    .regenerate-btn:hover {
        color: var(--dark);
    }

    .success-message {
        margin-top: 8px;
        font-size: 13px;
        color: var(--success);
        display: none;
        align-items: center;
        gap: 6px;
    }

    .success-message.show {
        display: inline-flex;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 24px;
        border-top: 1px solid var(--gray-300);
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 15px;
        cursor: pointer;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .2s ease;
        font-family: inherit;
    }

    .btn svg {
        display: block;
    }

    .btn-back {
        background: var(--white);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .btn-back:hover {
        border-color: var(--gray-700);
        background: var(--gray-100);
    }

    .btn-primary {
        background: var(--dark);
        color: var(--white);
        min-width: 120px;
        justify-content: center;
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary:disabled {
        opacity: .3;
        cursor: not-allowed;
    }

    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 640px) {
        .input-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-actions {
            flex-direction: column-reverse;
            gap: 12px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    .sr-only {
        position: absolute !important;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 1px, 1px);
        white-space: nowrap;
        border: 0;
    }
</style>
@endpush

@section('card-content')
    <div class="form-header">
        <div class="step-badge">Personal Information</div>
        <h1 class="form-title" id="personal-title">What should we call you?</h1>
        <p class="form-subtitle">Tell us your name and we'll create your professional profile</p>
    </div>

    <form id="personalForm" action="{{ route('tenant.onboarding.personal.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <div class="input-row">
                <div>
                    <label class="form-label" for="firstName">First Name <span class="required">*</span></label>
                    <input type="text" class="form-input" id="firstName" name="first_name" placeholder="John" required autocomplete="given-name" />
                </div>
                <div>
                    <label class="form-label" for="lastName">Last Name <span class="required">*</span></label>
                    <input type="text" class="form-input" id="lastName" name="last_name" placeholder="Smith" required autocomplete="family-name" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="username">Choose your username</label>
            <div class="username-group">
                <input type="text" class="form-input" id="username" name="username" placeholder="john-smith-2024" autocomplete="username" aria-describedby="usernameHelp" />
                <div class="username-status" id="usernameStatus" aria-live="polite"></div>
            </div>

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

            <p class="sr-only" id="usernameHelp">Only letters, numbers, and hyphens. Minimum 3 characters.</p>
        </div>

        <div class="form-actions">
            <a href="{{ route('tenant.onboarding.welcome') }}" class="btn btn-back" id="backBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </a>

            <button type="submit" class="btn btn-primary" id="continueBtn" disabled>
                <span id="btnText">Continue</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    "use strict";

    // DOM Elements
    const form = document.getElementById('personalForm');
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const username = document.getElementById('username');
    const usernameStatus = document.getElementById('usernameStatus');
    const usernamePreview = document.getElementById('usernamePreview');
    const previewUsername = document.getElementById('previewUsername');
    const usernameSuccess = document.getElementById('usernameSuccess');
    const regenBtn = document.getElementById('regenBtn');
    const continueBtn = document.getElementById('continueBtn');
    const btnText = document.getElementById('btnText');

    // State
    const TAKEN = new Set(['admin', 'test', 'user', 'support', 'team', 'promatch', 'root']);
    let isUsernameOK = false;
    let lastEnableState = false;
    let validateTimer;

    // Utility functions
    const slug = (s) => s
        .toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9- ]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '')
        .slice(0, 30);

    const sample = (arr) => arr[Math.floor(Math.random() * arr.length)];
    const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

    function setButtonEnabled(enabled) {
        continueBtn.disabled = !enabled;
        if (enabled && !lastEnableState) {
            continueBtn.style.transform = 'scale(1.02)';
            setTimeout(() => continueBtn.style.transform = '', 180);
        }
        lastEnableState = enabled;
    }

    function showPreview(u) {
        previewUsername.textContent = u;
        usernamePreview.classList.add('show');
    }

    function hidePreview() {
        usernamePreview.classList.remove('show');
    }

    function markSuccess(ok) {
        isUsernameOK = ok;
        username.classList.toggle('success', ok);
        usernameSuccess.classList.toggle('show', ok);
        usernameStatus.innerHTML = ok ? '<span style="color: var(--success)">✓</span>' : '';
    }

    function validateForm() {
        const ok = Boolean(firstName.value.trim() && lastName.value.trim() && isUsernameOK);
        setButtonEnabled(ok);
        return ok;
    }

    function genFromNames() {
        const f = slug(firstName.value || '');
        const l = slug(lastName.value || '');
        if (!f || !l) return '';

        const v = [
            `${f}-${l}`,
            `${f}${l}`,
            `${f}-${l}-${new Date().getFullYear()}`,
            `${f}-${l}-${rand(100, 9999)}`,
            `${f[0]}-${l}`,
            `${f}-${l[0]}${rand(10, 99)}`
        ].map(slug).filter(Boolean);
        return sample(v);
    }

    function generateUsername() {
        const base = genFromNames();
        if (!base) return;
        username.value = base;
        triggerUsernameCheck(base);
    }

    function triggerUsernameCheck(raw) {
        clearTimeout(validateTimer);
        const u = slug(raw || username.value);
        username.value = u;

        if (!u || u.length < 3) {
            markSuccess(false);
            hidePreview();
            setButtonEnabled(false);
            usernameStatus.textContent = '';
            return;
        }

        usernameStatus.innerHTML = '<span class="loading-spinner" aria-hidden="true"></span>';
        hidePreview();
        markSuccess(false);

        validateTimer = setTimeout(() => {
            const isTaken = TAKEN.has(u);
            if (isTaken) {
                const alt = genFromNames();
                if (alt && alt !== u && !TAKEN.has(alt)) {
                    username.value = alt;
                    showPreview(alt);
                    markSuccess(true);
                } else {
                    usernameStatus.textContent = 'Already taken';
                    markSuccess(false);
                }
            } else {
                showPreview(u);
                markSuccess(true);
            }
            validateForm();
        }, 320);
    }

    // Event Listeners
    firstName.addEventListener('input', () => {
        markSuccess(false);
        if (firstName.value && lastName.value) generateUsername();
        validateForm();
    });

    lastName.addEventListener('input', () => {
        markSuccess(false);
        if (firstName.value && lastName.value) generateUsername();
        validateForm();
    });

    username.addEventListener('input', () => triggerUsernameCheck(username.value));
    username.addEventListener('blur', () => triggerUsernameCheck(username.value));

    regenBtn.addEventListener('click', (e) => {
        e.preventDefault();
        generateUsername();
    });

    [firstName, lastName, username].forEach(el => {
        el.addEventListener('keydown', (ev) => {
            if (ev.key === 'Enter') {
                ev.preventDefault();
                if (validateForm()) form.submit();
            }
        });
    });

    // Form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!validateForm()) return;

        // Save to localStorage
        const payload = {
            firstName: firstName.value.trim(),
            lastName: lastName.value.trim(),
            username: username.value.trim(),
            savedAt: new Date().toISOString()
        };

        try {
            localStorage.setItem('onboarding_personal', JSON.stringify(payload));
        } catch {}

        btnText.innerHTML = '<div class="loading-spinner"></div>';
        setButtonEnabled(false);

        // Submit the form
        setTimeout(() => {
            form.submit();
        }, 500);
    });

    // Initialize
    setTimeout(() => {
        if (!firstName.value) firstName.focus();
    }, 300);

    // Restore from localStorage if available
    try {
        const raw = localStorage.getItem('onboarding_personal');
        if (raw) {
            const data = JSON.parse(raw);
            if (data?.firstName) firstName.value = data.firstName;
            if (data?.lastName) lastName.value = data.lastName;
            if (data?.username) {
                username.value = data.username;
                triggerUsernameCheck(data.username);
            }
        }
    } catch {}
});
</script>
@endpush