@extends('layouts.onboarding')

@section('title', 'Choose Your Path - ProMatch')

@php
    $currentStep = 0;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="account-type-wrapper">
        <div class="account-type-header">
            <h1 class="account-type-title">Join ProMatch</h1>
            <p class="account-type-subtitle">Choose how you want to get started</p>
        </div>

        <div class="account-type-grid">
            <!-- Freelancer/Skilled Professional -->
            <div class="account-type-card" data-type="freelancer">
                <div class="account-type-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                
                <h3 class="account-type-card-title">I'm a Professional</h3>
                <p class="account-type-card-desc">Showcase your skills, build your portfolio, and connect with clients seeking your expertise.</p>
                
                <ul class="account-type-features">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Create professional profile
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Showcase projects & skills
                    </li>
                    <li> 
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Get matched with opportunities
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Secure payment protection
                    </li>
                </ul>

                <button type="button" class="account-type-btn" data-redirect="{{ route('tenant.onboarding.welcome') }}">
                    <span>Continue as Professional</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>

                <div class="account-type-badge">Most popular</div>
            </div>

            <!-- Client/Recruiter -->
            <div class="account-type-card" data-type="client">
                <div class="account-type-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                
                <h3 class="account-type-card-title">I'm Hiring</h3>
                <p class="account-type-card-desc">Find and hire top talent for your projects. Access verified professionals ready to deliver results.</p>
                
                <ul class="account-type-features">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        AI-powered talent matching
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Post unlimited projects
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Review verified portfolios
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Milestone-based payments
                    </li>
                </ul>

                <button type="button" class="account-type-btn" data-redirect="{{ route('client.onboarding.info') }}">
                    <span>Continue as Client</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="account-type-footer">
            {{-- <p class="footer-text">Already have an account? <a href="{{ login('auth.register') }}" class="footer-link">Sign in</a></p> --}}
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Override container for this page */
    .container {
        max-width: 1100px !important;
    }

    .form-card {
        padding: 48px 40px;
    }

    .account-type-wrapper {
        width: 100%;
    }

    .account-type-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .account-type-title {
        font-size: 36px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .account-type-subtitle {
        font-size: 16px;
        color: var(--gray-500);
    }

    .account-type-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    .account-type-card {
        background: var(--white);
        border: 2px solid var(--gray-300);
        border-radius: 16px;
        padding: 32px 28px;
        transition: all .3s ease;
        position: relative;
        cursor: pointer;
    }

    .account-type-card:hover {
        border-color: var(--dark);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px);
    }

    .account-type-card[data-type="freelancer"]:hover {
        border-color: var(--primary);
    }

    .account-type-icon {
        width: 72px;
        height: 72px;
        background: var(--gray-100);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        color: var(--dark);
        transition: all .3s ease;
    }

    .account-type-card:hover .account-type-icon {
        background: var(--dark);
        color: var(--white);
        transform: scale(1.05);
    }

    .account-type-card[data-type="freelancer"]:hover .account-type-icon {
        background: var(--primary);
    }

    .account-type-card-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 12px;
    }

    .account-type-card-desc {
        font-size: 14px;
        color: var(--gray-500);
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .account-type-features {
        list-style: none;
        margin: 0 0 28px 0;
        padding: 0;
    }

    .account-type-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: var(--gray-700);
        margin-bottom: 12px;
    }

    .account-type-features li svg {
        color: var(--success);
        flex-shrink: 0;
    }

    .account-type-btn {
        width: 100%;
        padding: 14px 24px;
        background: var(--dark);
        color: var(--white);
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all .3s ease;
        font-family: inherit;
    }

    .account-type-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .account-type-card[data-type="freelancer"] .account-type-btn {
        background: var(--primary);
    }

    .account-type-badge {
        position: absolute;
        top: -12px;
        right: 20px;
        background: var(--primary);
        color: var(--white);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(0, 97, 255, 0.3);
    }

    .account-type-footer {
        text-align: center;
        padding-top: 24px;
        border-top: 1px solid var(--gray-300);
    }

    .footer-text {
        font-size: 14px;
        color: var(--gray-500);
    }

    .footer-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    .footer-link:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .account-type-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .account-type-title {
            font-size: 28px;
        }

        .form-card {
            padding: 32px 24px;
        }

        .account-type-card {
            padding: 28px 24px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.account-type-card');
        
        cards.forEach(card => {
            const btn = card.querySelector('.account-type-btn');
            
            card.addEventListener('click', function(e) {
                if (e.target.closest('.account-type-btn')) {
                    return;
                }
                btn.click();
            });
            
            btn.addEventListener('click', function() {
                const redirect = this.getAttribute('data-redirect');
                if (redirect) {
                    window.location.href = redirect;
                }
            });
        });
    });
</script>
@endpush