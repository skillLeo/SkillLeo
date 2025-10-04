@extends('layouts.onboarding')

@section('title', 'Work Preferences - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="7"
    title="Set your work preferences"
    subtitle="Help clients understand how you work and what you're looking for"
/>

<form id="preferencesForm" action="{{ route('tenant.onboarding.preferences.store') }}" method="POST">
    @csrf

    <div class="section-title">Your Rate</div>
    <div class="rate-card">
        <div class="rate-grid">
            <x-onboarding.select
                name="currency"
                label="Currency"
                :options="['PKR' => 'PKR', 'USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP', 'AED' => 'AED', 'INR' => 'INR']"
                selected="PKR"
            />

            <x-onboarding.input
                name="rate"
                label="Amount"
                type="number"
                placeholder="5000"
                min="0"
                step="1"
            />

            <x-onboarding.select
                name="unit"
                label="Per"
                :options="['/hour' => 'Hour', '/day' => 'Day', '/project' => 'Project']"
                selected="/hour"
            />
        </div>

        <div style="background: var(--accent-light); border: 1px solid var(--accent); border-radius: var(--radius); padding: var(--space-md); margin-top: var(--space-md);">
            <strong style="color: var(--accent); font-size: var(--fs-subtle); display: block; margin-bottom: var(--space-sm);">AI Rate Assistant</strong>
            <button type="button" class="btn btn-secondary" id="rateBtn" style="font-size: var(--fs-subtle);">💡 Suggest My Rate</button>
        </div>
    </div>

    <div class="section-title">Availability</div>
    <div class="rate-card">
        <x-onboarding.select
            name="availability"
            label="When can you start?"
            :options="['now' => 'Available now', '1week' => 'Within 1 week', '2weeks' => 'Within 2 weeks', '1month' => 'Within 1 month']"
            selected="now"
        />

        <x-onboarding.select
            name="hours_per_week"
            label="Hours per week"
            :options="['part-time' => 'Part-time (10-20 hrs)', 'full-time' => 'Full-time (30-40 hrs)', 'flexible' => 'Flexible']"
            selected="full-time"
        />
    </div>

    <div class="section-title">Preferences</div>
    <div class="toggle-container">
        <x-onboarding.toggle 
            name="remote_work"
            label="Remote Work"
            description="I prefer working remotely"
            checked
        />

        <x-onboarding.toggle 
            name="open_to_work"
            label="Open to Work"
            description="Show availability on profile"
            checked
        />

        <x-onboarding.toggle 
            name="long_term"
            label="Long-term Projects"
            description="Interested in ongoing work"
        />
    </div>

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}"
        backUrl="{{ route('tenant.onboarding.education') }}"
        nextLabel="Complete Setup"
    />
</form>

@endsection

@push('styles')
<style>
.section-title {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin-bottom: var(--space-md);
    margin-top: var(--space-xl);
}

.section-title:first-of-type { margin-top: 0; }

.rate-card {
    background: var(--apc-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: var(--space-lg);
    margin-bottom: var(--space-lg);
}

.rate-grid {
    display: grid;
    grid-template-columns: 120px 1fr 140px;
    gap: var(--space-md);
    align-items: end;
}

.toggle-container {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}

@media (max-width: 640px) {
    .rate-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('rateBtn').addEventListener('click', function() {
    const rates = { PKR: 2500, USD: 45, EUR: 40, GBP: 35, AED: 150, INR: 1200 };
    const currency = document.querySelector('[name="currency"]').value;
    document.querySelector('[name="rate"]').value = rates[currency] || 2500;
});
</script>
@endpush