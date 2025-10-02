<header class="header">
    <div class="header__inner">
        <a href="#" class="brand" aria-label="ProMatch">
            <picture>
                <source media="(max-width: 768px)" srcset="{{ asset('assets/images/logos/croped_720x200/logo_light.png') }}"/>
                <img class="brand-logo" 
                     src="{{ asset('assets/images/logos/croped_720x200/logo_light.png') }}" 
                     alt="ProMatch"
                     width="720" 
                     height="200"/>
            </picture>
        </a>

        <div class="progress-container" aria-live="polite">
            <span id="stepText">Step {{ $currentStep ?? 0 }} of {{ $totalSteps ?? 8 }}</span>
            <div class="progress-bar" aria-hidden="true">
                <div class="progress-fill" id="headerProgress" style="width: {{ ($currentStep ?? 0) / ($totalSteps ?? 8) * 100 }}%"></div>
            </div>
        </div>
    </div>
</header>

@push('styles')
<style>
.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(
        to bottom,
        rgba(255, 255, 255, 0.95) 0%,
        rgba(255, 255, 255, 0.88) 100%
    );
    backdrop-filter: blur(12px) saturate(180%) brightness(105%);
    -webkit-backdrop-filter: blur(16px) saturate(180%) brightness(105%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow: 
        0 1px 3px rgba(0, 0, 0, 0.05),
        0 4px 12px rgba(0, 0, 0, 0.03);
    z-index: 100;
}

.header__inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 64px;
}

.brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: opacity 0.2s ease;
}

.brand:hover {
    opacity: 0.85;
}

.brand-logo {
    height: 38px;
    width: auto;
    max-width: 180px;
    object-fit: contain;
    display: block;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: var(--gray-500);
    font-weight: 500;
}

.progress-bar {
    width: 120px;
    height: 6px;
    background: var(--gray-300);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--dark);
    transition: width 0.6s ease;
    border-radius: 3px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .header__inner {
        padding: 12px 16px;
        min-height: 56px;
    }
    
    .brand-logo {
        height: 28px;
        max-width: 140px;
    }
    
    .progress-container {
        font-size: 12px;
        gap: 8px;
    }
    
    .progress-bar {
        width: 80px;
        height: 5px;
    }
}

@media (max-width: 480px) {
    #stepText {
        display: none;
    }
    
    .progress-bar {
        width: 100px;
    }
}
</style>
@endpush