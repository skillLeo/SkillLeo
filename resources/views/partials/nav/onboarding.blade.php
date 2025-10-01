<header class="header">
    <div class="header__inner">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ asset('logos/rm-bg/icon1.png') }}"/>
            <img class="logo" src="{{ asset('logos/rm-bg/logo1.png') }}" alt="ProMatch"/>
        </picture>

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
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--gray-300);
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

    .logo {
        height: 28px;
        width: auto;
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
    }
</style>
@endpush