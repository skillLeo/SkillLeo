@extends('layouts.onboarding')

@section('title', 'Personal Information - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="1"
    title="What should we call you?"
    subtitle="Tell us your name and we'll create your professional profile"
/>

<form id="personalForm" action="{{ route('tenant.onboarding.personal.store') }}" method="POST">
    @csrf

    <div class="input-row">
        <x-onboarding.input
            label="First Name"
            name="first_name"
            id="firstName"
            placeholder="John"
            required
            autocomplete="given-name"
        />

        <x-onboarding.input
            label="Last Name"
            name="last_name"
            id="lastName"
            placeholder="Smith"
            required
            autocomplete="family-name"
        />
    </div>

    <x-onboarding.username-input
        label="Choose your username"
        placeholder="john-smith-2024"
    />

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.location') }}"
        backUrl="{{ route('tenant.onboarding.welcome') }}"
    />
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    const TAKEN = new Set(['admin', 'test', 'user', 'support', 'team', 'promatch', 'root']);
    let isUsernameOK = false;
    let validateTimer;

    const slug = (s) => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9- ]/g, '').trim().replace(/\s+/g, '-')
        .replace(/-+/g, '-').replace(/^-|-$/g, '').slice(0, 30);

    const sample = (arr) => arr[Math.floor(Math.random() * arr.length)];
    const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

    function validateForm() {
        const ok = firstName.value.trim() && lastName.value.trim() && isUsernameOK;
        continueBtn.disabled = !ok;
        return ok;
    }

    function genFromNames() {
        const f = slug(firstName.value || '');
        const l = slug(lastName.value || '');
        if (!f || !l) return '';
        const options = [
            `${f}-${l}`,
            `${f}${l}`,
            `${f}-${l}-${new Date().getFullYear()}`,
            `${f}-${l}-${rand(100, 9999)}`,
            `${f[0]}-${l}`
        ].map(slug).filter(Boolean);
        return sample(options);
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
            isUsernameOK = false;
            username.classList.remove('success');
            usernameSuccess.classList.remove('show');
            usernamePreview.classList.remove('show');
            usernameStatus.textContent = '';
            validateForm();
            return;
        }

        usernameStatus.innerHTML = '<span class="loading-spinner"></span>';
        usernamePreview.classList.remove('show');
        username.classList.remove('success');
        usernameSuccess.classList.remove('show');

        validateTimer = setTimeout(() => {
            const isTaken = TAKEN.has(u);
            if (isTaken) {
                const alt = genFromNames();
                if (alt && alt !== u && !TAKEN.has(alt)) {
                    username.value = alt;
                    previewUsername.textContent = alt;
                    usernamePreview.classList.add('show');
                    username.classList.add('success');
                    usernameSuccess.classList.add('show');
                    isUsernameOK = true;
                    usernameStatus.innerHTML = '<span style="color: var(--success)">✓</span>';
                } else {
                    usernameStatus.textContent = 'Already taken';
                    isUsernameOK = false;
                }
            } else {
                previewUsername.textContent = u;
                usernamePreview.classList.add('show');
                username.classList.add('success');
                usernameSuccess.classList.add('show');
                isUsernameOK = true;
                usernameStatus.innerHTML = '<span style="color: var(--success)">✓</span>';
            }
            validateForm();
        }, 320);
    }

    firstName.addEventListener('input', () => {
        isUsernameOK = false;
        username.classList.remove('success');
        usernameSuccess.classList.remove('show');
        if (firstName.value && lastName.value) generateUsername();
        validateForm();
    });

    lastName.addEventListener('input', () => {
        isUsernameOK = false;
        username.classList.remove('success');
        usernameSuccess.classList.remove('show');
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
        el.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (validateForm()) form.submit();
            }
        });
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!validateForm()) return;

        try {
            localStorage.setItem('onboarding_personal', JSON.stringify({
                firstName: firstName.value.trim(),
                lastName: lastName.value.trim(),
                username: username.value.trim(),
                savedAt: new Date().toISOString()
            }));
        } catch {}

        btnText.innerHTML = '<span class="loading-spinner"></span>';
        continueBtn.disabled = true;

        setTimeout(() => form.submit(), 500);
    });

    setTimeout(() => {
        if (!firstName.value) firstName.focus();
    }, 300);

    try {
        const saved = JSON.parse(localStorage.getItem('onboarding_personal') || '{}');
        if (saved.firstName) firstName.value = saved.firstName;
        if (saved.lastName) lastName.value = saved.lastName;
        if (saved.username) {
            username.value = saved.username;
            triggerUsernameCheck(saved.username);
        }
    } catch {}
});
</script>
@endpush