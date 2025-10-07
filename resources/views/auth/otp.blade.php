@extends('layouts.onboarding')

@section('title', 'Verify Your Account - ProMatch')


@section('card-content')
<div class="otp-wrapper">
    <div class="otp-header">
        <div class="otp-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        <h1 class="otp-title">Check your email</h1>
        <p class="otp-subtitle">
            We sent a verification code to<br>
            <strong id="userEmail">{{ request('email') ?? session('verify_email') }}</strong>
        </p>
    </div>

    {{-- ✅ Post to WEB route so session is present --}}
    <form id="otpForm" action="{{ route('auth.otp.verify') }}" method="POST" autocomplete="off">
        @csrf

        <div class="otp-inputs">
            @for ($i = 1; $i <= 6; $i++)
                <input type="text" maxlength="1" pattern="[0-9]"
                       class="otp-input" id="otp{{ $i }}" inputmode="numeric" autocomplete="one-time-code">
            @endfor
        </div>

        {{-- ✅ Server expects "code" --}}
        <input type="hidden" name="code" id="otpValue">

        <button type="submit" class="btn btn-primary" id="verifyBtn" disabled>
            <span id="btnText">Verify Code</span>
        </button>

        @error('code')
            <p class="text-red-600 mt-3">{{ $message }}</p>
        @enderror
        @if (session('status'))
            <p class="text-green-600 mt-3">{{ session('status') }}</p>
        @endif
    </form>

    <div class="otp-footer">
        <form id="resendForm" action="{{ route('auth.otp.resend') }}" method="POST">
            @csrf
            <p class="resend-text">
                Didn't receive the code?
                <button type="submit" class="resend-btn" id="resendBtn" disabled>Resend</button>
            </p>
            <p class="timer-text" id="timerText"></p>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.otp-wrapper {
    max-width: 440px;
    margin: 0 auto;
    text-align: center;
}

.otp-header {
    margin-bottom: var(--space-2xl);
}

.otp-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto var(--space-lg);
    background: var(--accent-light);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
}

.otp-title {
    font-size: var(--fs-h1);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    margin-bottom: var(--space-sm);
}

.otp-subtitle {
    font-size: var(--fs-body);
    color: var(--text-muted);
    line-height: var(--lh-relaxed);
}

.otp-subtitle strong {
    color: var(--text-heading);
    font-weight: var(--fw-semibold);
}

.otp-inputs {
    display: flex;
    gap: var(--space-sm);
    justify-content: center;
    margin-bottom: var(--space-xl);
}

.otp-input {
    width: 56px;
    height: 56px;
    text-align: center;
    font-size: var(--fs-h2);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    background: var(--card);
    border: 2px solid var(--border);
    border-radius: var(--radius);
    transition: all var(--transition-base);
}

.otp-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-light);
}

.otp-input.filled {
    border-color: var(--accent);
    background: var(--accent-light);
}

.otp-input.error {
    border-color: var(--error);
    animation: shake 0.3s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-8px); }
    75% { transform: translateX(8px); }
}

.btn-primary {
    width: 100%;
    margin-bottom: var(--space-lg);
}

.otp-footer {
    padding-top: var(--space-lg);
    border-top: 1px solid var(--border);
}

.resend-text {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin-bottom: var(--space-xs);
}

.resend-btn {
    background: none;
    border: none;
    color: var(--accent);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    text-decoration: underline;
    font-size: var(--fs-body);
    padding: 0;
    transition: color var(--transition-base);
}

.resend-btn:hover {
    color: var(--accent-dark);
}

.resend-btn:disabled {
    color: var(--text-disabled);
    cursor: not-allowed;
    text-decoration: none;
}

.timer-text {
    font-size: var(--fs-subtle);
    color: var(--text-subtle);
}

@media (max-width: 640px) {
    .otp-inputs {
        gap: var(--space-xs);
    }

    .otp-input {
        width: 48px;
        height: 48px;
        font-size: var(--fs-h3);
    }
}
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs   = document.querySelectorAll('.otp-input');
    const verifyBtn = document.getElementById('verifyBtn');
    const otpValue  = document.getElementById('otpValue');
    const timerText = document.getElementById('timerText');
    const resendBtn = document.getElementById('resendBtn');

    // enable web one-time-code autofill on supported browsers
    inputs[0].setAttribute('autocomplete','one-time-code');

    inputs[0].focus();

    function updateHiddenAndButton() {
        const code = Array.from(inputs).map(i => i.value).join('');
        otpValue.value = code;
        verifyBtn.disabled = (code.length !== 6);
    }

    inputs.forEach((input, idx) => {
        input.addEventListener('input', (e) => {
            const v = e.target.value.replace(/\D/g,'').slice(0,1);
            e.target.value = v;
            if (v && idx < inputs.length - 1) inputs[idx+1].focus();
            updateHiddenAndButton();
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && idx > 0) {
                inputs[idx-1].focus(); inputs[idx-1].value = '';
                updateHiddenAndButton();
            }
        });
        input.addEventListener('paste', (e) => {
            const t = (e.clipboardData || window.clipboardData).getData('text');
            if (/^\d{6}$/.test(t)) {
                e.preventDefault();
                t.split('').forEach((ch,i)=>{ if(inputs[i]) inputs[i].value = ch; });
                inputs[inputs.length - 1].focus();
                updateHiddenAndButton();
            }
        });
    });

    // simple resend timer
    let timeLeft = 60;
    function tick() {
        timeLeft--;
        timerText.textContent = timeLeft > 0
            ? `Resend available in ${timeLeft}s`
            : '';
        resendBtn.disabled = timeLeft > 0;
        if (timeLeft > 0) setTimeout(tick, 1000);
    }
    tick(); // start

    // IMPORTANT: let the form actually submit (no e.preventDefault()).
});
</script>
@endpush