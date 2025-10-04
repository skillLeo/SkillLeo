@extends('layouts.onboarding')

@section('title', 'Choose Your Path - ProMatch')

@section('card-content')



    <div class="account-type-wrapper">
        <div class="account-type-header">
            <h1 class="account-type-title">Join ProMatch</h1>
            <p class="account-type-subtitle">Choose how you want to get started</p>
        </div>

        <div class="account-type-grid">
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
    </div>
@endsection

@push('styles')
<style>
.account-type-wrapper {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
}

.account-type-header {
    text-align: center;
    margin-bottom: var(--space-2xl);
}

.account-type-title {
    font-size: clamp(28px, 4vw, 36px);
    font-weight: var(--fw-extrabold);
    color: var(--text-heading);
    margin-bottom: var(--space-sm);
    letter-spacing: -0.02em;
}

.account-type-subtitle {
    font-size: var(--fs-h3);
    color: var(--text-muted);
}

.account-type-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-lg);
    margin-bottom: var(--space-xl);
}

.account-type-card {
    background: var(--card);
    border: 2px solid var(--border);
    border-radius: var(--radius);
    padding: var(--space-xl) var(--space-lg);
    transition: all var(--transition-slow);
    position: relative;
    cursor: pointer;
}

.account-type-card:hover {
    border-color: var(--ink);
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

.account-type-card[data-type="freelancer"]:hover {
    border-color: var(--accent);
}

.account-type-icon {
    width: 72px;
    height: 72px;
    background: var(--apc-bg);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--space-lg);
    color: var(--ink);
    transition: all var(--transition-base);
}

.account-type-card:hover .account-type-icon {
    background: var(--ink);
    color: var(--card);
    transform: scale(1.05);
}

.account-type-card[data-type="freelancer"]:hover .account-type-icon {
    background: var(--accent);
}

.account-type-card-title {
    font-size: var(--fs-h2);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    margin-bottom: var(--space-sm);
}

.account-type-card-desc {
    font-size: var(--fs-body);
    color: var(--text-muted);
    line-height: var(--lh-relaxed);
    margin-bottom: var(--space-lg);
}

.account-type-features {
    list-style: none;
    margin: 0 0 var(--space-xl) 0;
    padding: 0;
}

.account-type-features li {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: var(--fs-body);
    color: var(--text-body);
    margin-bottom: var(--space-sm);
}

.account-type-features li svg {
    color: var(--success);
    flex-shrink: 0;
}

.account-type-btn {
    width: 100%;
    padding: 14px 24px;
    background: var(--ink);
    color: var(--card);
    border: none;
    border-radius: var(--radius);
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
    transition: all var(--transition-base);
    font-family: var(--font-sans);
}

.account-type-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.account-type-card[data-type="freelancer"] .account-type-btn {
    background: var(--accent);
}

.account-type-badge {
    position: absolute;
    top: -12px;
    right: 20px;
    background: var(--accent);
    color: var(--btn-text-primary);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: var(--fs-micro);
    font-weight: var(--fw-bold);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(19, 81, 216, 0.3);
}

@media (max-width: 768px) {
    .account-type-grid {
        grid-template-columns: 1fr;
        gap: var(--space-lg);
    }

    .account-type-card {
        padding: var(--space-lg);
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
            if (e.target.closest('.account-type-btn')) return;
            btn.click();
        });
        
        btn.addEventListener('click', function() {
            const redirect = this.getAttribute('data-redirect');
            if (redirect) window.location.href = redirect;
        });
    });
});
</script>
@endpush