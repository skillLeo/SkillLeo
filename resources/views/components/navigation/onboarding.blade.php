<header class="onboarding-header">
    <div class="header-content">
        <a href="#" class="header-logo" aria-label="ProMatch Home">
            <img src="{{ asset('assets/images/logos/croped/logo_light.png') }}" 
                 alt="ProMatch" 
                 class="logo-light" 
                 height="32">
            <img src="{{ asset('assets/images/logos/croped/logo_dark.png') }}" 
                 alt="ProMatch" 
                 class="logo-dark" 
                 height="32">
        </a>
        
        <div class="header-progress">
            <span class="progress-text" id="stepText" aria-live="polite">Step 1 of 8</span>
            <div class="progress-bar" role="progressbar" aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-fill" id="headerProgress" style="width: 12.5%;"></div>
            </div>
        </div>

        <button type="button" class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
            <svg class="theme-icon sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"/>
                <line x1="12" y1="1" x2="12" y2="3"/>
                <line x1="12" y1="21" x2="12" y2="23"/>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                <line x1="1" y1="12" x2="3" y2="12"/>
                <line x1="21" y1="12" x2="23" y2="12"/>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
            <svg class="theme-icon moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>
    </div>
</header>


@push('styles')
<style>
.onboarding-header {
    position: fixed;
            top: 0;
            width: 100%;
            background: var(--card) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1000;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}


 
</style>
@endpush