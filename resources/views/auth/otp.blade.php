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
        <p class="otp-subtitle">We sent a verification code to<br><strong id="userEmail">user@example.com</strong></p>
    </div>

    <form id="otpForm" action="{{ url('/api/auth/otp/verify') }}" method="POST">
        @csrf
        <input type="hidden" name="email" id="emailValue" value="{{ request('email') }}">

        <div class="otp-inputs">
            @for($i=1;$i<=6;$i++)
            <input type="text" maxlength="1" pattern="[0-9]" class="otp-input" id="otp{{ $i }}" autocomplete="off" inputmode="numeric">
        @endfor </div>

        <input type="hidden" name="otp" id="otpValue">

        <button type="submit" class="btn btn-primary" id="verifyBtn" disabled>
            <span id="btnText">Verify Code</span>
        </button>
    </form>

    <div class="otp-footer">
        <p class="resend-text">
            Didn't receive the code?
            <button type="button" class="resend-btn" id="resendBtn">Resend</button>
        </p>
        <p class="timer-text" id="timerText"></p>
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
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const verifyBtn = document.getElementById('verifyBtn');
    const btnText = document.getElementById('btnText');
    const resendBtn = document.getElementById('resendBtn');
    const timerText = document.getElementById('timerText');
    const otpValue = document.getElementById('otpValue');
    const form = document.getElementById('otpForm');

    let resendTimer;
    let timeLeft = 60;

    // Auto-focus first input
    inputs[0].focus();

    // Handle input
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const value = e.target.value;
            
            // Only allow numbers
            if (value && !/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }

            if (value) {
                input.classList.add('filled');
                
                // Move to next input
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            } else {
                input.classList.remove('filled');
            }

            validateOTP();
        });

        // Handle backspace
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                inputs[index - 1].classList.remove('filled');
                validateOTP();
            }
        });

        // Handle paste
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').trim();
            
            if (/^\d{6}$/.test(pastedData)) {
                pastedData.split('').forEach((char, i) => {
                    if (inputs[i]) {
                        inputs[i].value = char;
                        inputs[i].classList.add('filled');
                    }
                });
                inputs[inputs.length - 1].focus();
                validateOTP();
            }
        });
    });

    function validateOTP() {
        const otp = Array.from(inputs).map(input => input.value).join('');
        otpValue.value = otp;
        verifyBtn.disabled = otp.length !== 6;
    }

    function startResendTimer() {
        timeLeft = 60;
        resendBtn.disabled = true;
        
        resendTimer = setInterval(() => {
            timeLeft--;
            timerText.textContent = `Resend available in ${timeLeft}s`;
            
            if (timeLeft === 0) {
                clearInterval(resendTimer);
                resendBtn.disabled = false;
                timerText.textContent = '';
            }
        }, 1000);
    }

    resendBtn.addEventListener('click', async function() {
        // Call resend API here
        btnText.textContent = 'Sending...';
        
        // Simulate API call
        setTimeout(() => {
            btnText.textContent = 'Verify Code';
            inputs.forEach(input => {
                input.value = '';
                input.classList.remove('filled');
            });
            inputs[0].focus();
            startResendTimer();
        }, 1000);
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (verifyBtn.disabled) return;
        
        btnText.innerHTML = '<span class="loading-spinner"></span>';
        verifyBtn.disabled = true;

        // Simulate API call
        setTimeout(() => {
            // On success
            // window.location.href = '/dashboard';
            
            // On error
            inputs.forEach(input => {
                input.classList.add('error');
                input.value = '';
                input.classList.remove('filled');
            });
            
            setTimeout(() => {
                inputs.forEach(input => input.classList.remove('error'));
                inputs[0].focus();
            }, 300);
            
            btnText.textContent = 'Verify Code';
            verifyBtn.disabled = false;
        }, 1500);
    });

    // Start initial timer
    startResendTimer();

    // Load email from session/localStorage
    const email = localStorage.getItem('user_email') || 'user@example.com';
    document.getElementById('userEmail').textContent = email;
    document.getElementById('emailValue').value = email;
});
</script>
@endpush