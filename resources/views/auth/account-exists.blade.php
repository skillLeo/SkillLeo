@extends('marketing.layouts.app')
@section('title', 'Account already exists')

@section('content')
<style>
:root {
  --primary: #2563eb;
  --primary-dark: #1e40af;
  --primary-light: #dbeafe;
  --success: #10b981;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-500: #6b7280;
  --gray-600: #4b5563;
  --gray-700: #374151;
  --gray-900: #111827;
  --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05);
  --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
  --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
  --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
}

.account-exists-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
  padding: 24px;
  position: relative;
  overflow: hidden;
}

.account-exists-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
              radial-gradient(circle at 70% 80%, rgba(255,255,255,0.08) 0%, transparent 50%);
  pointer-events: none;
}

.account-exists-card {
  position: relative;
  max-width: 480px;
  width: 100%;
  background: #ffffff;
  border-radius: 24px;
  box-shadow: var(--shadow-xl), 0 0 0 1px rgba(0,0,0,0.05);
  padding: 48px 40px;
  animation: slideUp 0.4s ease-out;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.account-exists-logo {
  display: flex;
  justify-content: center;
  margin-bottom: 32px;
}

.account-exists-logo img {
  height: 48px;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.account-exists-title {
  text-align: center;
  font-size: 32px;
  font-weight: 700;
  color: var(--gray-900);
  margin: 0 0 12px;
  letter-spacing: -0.02em;
  line-height: 1.2;
}

.account-exists-sub {
  text-align: center;
  color: var(--gray-600);
  font-size: 16px;
  margin-bottom: 28px;
  line-height: 1.6;
}

.locked-email {
  display: flex;
  gap: 14px;
  align-items: center;
  background: linear-gradient(135deg, var(--gray-50) 0%, #fff 100%);
  border: 2px solid var(--gray-200);
  padding: 18px 20px;
  border-radius: 16px;
  font-weight: 600;
  color: var(--gray-900);
  justify-content: center;
  transition: all 0.2s ease;
  box-shadow: var(--shadow-sm);
}

.locked-email svg {
  width: 24px;
  height: 24px;
  color: var(--primary);
  flex-shrink: 0;
}

.help-text {
  color: var(--gray-600);
  font-size: 14px;
  margin-top: 16px;
  text-align: center;
  line-height: 1.6;
}

.pass-field {
  position: relative;
  margin-bottom: 20px;
}

.pass-field input {
  width: 100%;
  border: 2px solid var(--gray-300);
  border-radius: 14px;
  padding: 14px 50px 14px 16px;
  font-size: 15px;
  font-weight: 500;
  color: var(--gray-900);
  transition: all 0.2s ease;
  background: #fff;
}

.pass-field input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px var(--primary-light);
}

.pass-field input::placeholder {
  color: var(--gray-400);
  font-weight: 400;
}

.pass-toggle {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  border: 0;
  background: transparent;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--gray-500);
  cursor: pointer;
  border-radius: 10px;
  transition: all 0.2s ease;
}

.pass-toggle:hover {
  background: var(--gray-100);
  color: var(--gray-700);
}

.pass-toggle:focus {
  outline: none;
  box-shadow: 0 0 0 3px var(--primary-light);
}

.pass-toggle svg {
  width: 20px;
  height: 20px;
  stroke-width: 2;
}

.btn-primary {
  width: 100%;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
  color: #fff;
  border: 0;
  border-radius: 14px;
  padding: 16px 24px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: var(--shadow-md), 0 0 0 1px rgba(37,99,235,0.2);
  position: relative;
  overflow: hidden;
}

.btn-primary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s ease;
}

.btn-primary:hover::before {
  left: 100%;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg), 0 0 0 1px rgba(37,99,235,0.2);
}

.btn-primary:active {
  transform: translateY(0);
}

.btn-ghost {
  width: 100%;
  background: #fff;
  border: 2px solid var(--gray-300);
  color: var(--gray-700);
  border-radius: 14px;
  padding: 14px 24px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  display: inline-block;
  text-align: center;
}

.btn-ghost:hover {
  background: var(--gray-50);
  border-color: var(--gray-400);
  transform: translateY(-1px);
  box-shadow: var(--shadow-sm);
}

.provider-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
  margin-top: 20px;
}

.provider-btn {
  display: flex;
  gap: 12px;
  align-items: center;
  justify-content: center;
  border: 2px solid var(--gray-300);
  border-radius: 14px;
  padding: 14px 20px;
  font-weight: 600;
  font-size: 15px;
  background: #fff;
  color: var(--gray-700);
  text-decoration: none;
  transition: all 0.2s ease;
  box-shadow: var(--shadow-sm);
}

.provider-btn:hover {
  background: var(--gray-50);
  border-color: var(--gray-400);
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.provider-btn svg {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

.hr-or {
  display: flex;
  align-items: center;
  gap: 16px;
  margin: 28px 0;
  color: var(--gray-500);
  font-size: 14px;
  font-weight: 500;
}

.hr-or::before,
.hr-or::after {
  content: "";
  height: 1px;
  background: var(--gray-300);
  flex: 1;
}

.small-link {
  color: var(--primary);
  font-weight: 600;
  text-decoration: none;
  transition: color 0.2s ease;
}

.small-link:hover {
  color: var(--primary-dark);
  text-decoration: underline;
}

.forgot-link-wrapper {
  text-align: center;
  margin-top: 14px;
}

@media (max-width: 480px) {
  .account-exists-card {
    padding: 36px 28px;
  }
  
  .account-exists-title {
    font-size: 26px;
  }
}
</style>

<div class="account-exists-wrapper">
  <div class="account-exists-card">

    <div class="account-exists-logo">
      <img src="{{ asset('assets/images/logos/croped/logo_light.png') }}" alt="SkillLeo">
    </div>

    <h1 class="account-exists-title">Account already exists</h1>
    <p class="account-exists-sub">
      We found an account using this email address
    </p>

    <div class="locked-email" aria-live="polite">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="5" width="18" height="14" rx="2"/>
        <path d="m3 7 9 6 9-6"/>
      </svg>
      <strong>{{ $maskedEmail }}</strong>
    </div>

    <div class="help-text">Would you like to sign in or use a different email to create a new account?</div>

    {{-- Path A: Sign in to existing account --}}
    @if($hasPassword)
      {{-- Email/password account → ask only for password --}}
      <form method="POST" action="{{ route('login.submit') }}" autocomplete="off" style="margin-top:24px">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="website" value="">

        <div class="pass-field">
          <input 
            type="password" 
            name="password" 
            id="password" 
            placeholder="Enter your password" 
            required 
            autocomplete="current-password"
            aria-label="Password"
          >
          <button type="button" class="pass-toggle" id="passToggle" aria-label="Show password">
            <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>

        <button class="btn-primary" type="submit">Sign in</button>
        
        <div class="forgot-link-wrapper">
          <a class="small-link" href="{{ url('/forgot-password') }}">Forgot password?</a>
        </div>
      </form>
    @else
      {{-- SSO-only account → show provider buttons --}}
{{-- SSO-only account → show only the exact provider(s) we detected --}}
<div class="provider-grid">
    @php
      $map = [
        'google'  => ['/auth/google/redirect',  'Google',  '<path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>' ],
        'linkedin'=> ['/auth/linkedin/redirect','LinkedIn','<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" fill="#0A66C2"/>' ],
        'github'  => ['/auth/github/redirect',  'GitHub',  '<path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" fill="#181717"/>' ],
      ];
    @endphp
  
    @forelse($providers as $p)
      @if(isset($map[$p]))
        @php [$href,$label,$svg] = $map[$p]; @endphp
        <a class="provider-btn" href="{{ $href }}">
          <svg viewBox="0 0 24 24" fill="none">{!! $svg !!}</svg>
          Continue with {{ $label }}
        </a>
      @endif
    @empty
      <div class="help-text">
        We couldn’t verify a linked provider for this email. Please
        <a class="small-link" href="{{ route('register') }}">use a different email</a>
        or contact support.
      </div>
    @endforelse
  </div>
  
  
    @endif

    <div class="hr-or"><span>or</span></div>

    {{-- Path B: Use a different email --}}
    <a href="{{ route('register') }}" class="btn-ghost">Use a different email</a>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const input  = document.getElementById('password');
  const toggle = document.getElementById('passToggle');
  
  if (input && toggle) {
    const eye = toggle.querySelector('.icon-eye');
    
    toggle.addEventListener('click', () => {
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      toggle.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
      eye.style.opacity = isPassword ? '0.5' : '1';
      
      // Keep focus and cursor position
      const len = input.value.length;
      input.focus();
      input.setSelectionRange(len, len);
    });
  }
});
</script>
@endsection