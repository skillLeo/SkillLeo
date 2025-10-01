@props(['label'])

<div class="step-badge">{{ $label }}</div>

@once
@push('styles')
<style>
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

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }
</style>
@endpush
@endonce