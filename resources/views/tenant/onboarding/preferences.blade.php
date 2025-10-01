@extends('layouts.onboarding')

@section('title', 'Work Preferences - ProMatch')

@php
    $currentStep = 7;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Work Preferences" />
        <h1 class="form-title">Set your work preferences</h1>
        <p class="form-subtitle">Help clients understand how you work and what you're looking for.</p>
    </div>

    <form id="preferencesForm" action="{{ route('tenant.onboarding.preferences.store') }}" method="POST">
        @csrf

        <!-- Rate -->
        <div class="section">
            <div class="section-title">Your Rate</div>
            <div class="block">
                <div class="rate-grid">
                    <x-forms.select 
                        name="currency"
                        label="Currency"
                        :options="[
                            'PKR' => 'PKR',
                            'USD' => 'USD',
                            'EUR' => 'EUR',
                            'GBP' => 'GBP',
                            'AED' => 'AED',
                            'INR' => 'INR'
                        ]"
                        selected="PKR"
                    />

                    <x-forms.input 
                        name="rate"
                        label="Amount"
                        type="number"
                        placeholder="5000"
                        min="0"
                        step="1"
                    />

                    <x-forms.select 
                        name="unit"
                        label="Per"
                        :options="[
                            '/hour' => 'Hour',
                            '/day' => 'Day',
                            '/project' => 'Project'
                        ]"
                        selected="/hour"
                    />
                </div>

                <!-- AI helper -->
                <div class="ai-helper">
                    <strong>AI Rate Assistant</strong>
                    <div class="ai-actions">
                        <button type="button" class="ai-button" id="rateBtn">💡 Suggest My Rate</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Availability -->
        <div class="section">
            <div class="section-title">Availability</div>
            <div class="block">
                <x-forms.select 
                    name="availability"
                    label="When can you start?"
                    :options="[
                        'now' => 'Available now',
                        '1week' => 'Within 1 week',
                        '2weeks' => 'Within 2 weeks',
                        '1month' => 'Within 1 month'
                    ]"
                    selected="now"
                />

                <x-forms.select 
                    name="hours_per_week"
                    label="Hours per week"
                    :options="[
                        'part-time' => 'Part-time (10-20 hrs)',
                        'full-time' => 'Full-time (30-40 hrs)',
                        'flexible' => 'Flexible'
                    ]"
                    selected="full-time"
                />
            </div>
        </div>

        <!-- Preferences toggles -->
        <div class="section">
            <div class="section-title">Preferences</div>
            <div class="block" style="padding: 0;">
                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">Remote Work</div>
                        <div class="toggle-desc">I prefer working remotely</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="remote_work" checked />
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">Open to Work</div>
                        <div class="toggle-desc">Show availability on profile</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="open_to_work" checked />
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">Long-term Projects</div>
                        <div class="toggle-desc">Interested in ongoing work</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="long_term" />
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('tenant.onboarding.education') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </x-ui.button>

            <x-ui.button variant="primary" type="submit" id="continueBtn">
                <span id="btnText">Complete Setup</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-ui.button>
        </div>
    </form>
@endsection

@push('styles')
<style>
    .section {
        margin: 24px 0 8px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 10px;
    }

    .block {
        background: var(--gray-100);
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 14px;
    }

    .rate-grid {
        display: grid;
        grid-template-columns: 120px 1fr 140px;
        gap: 12px;
        align-items: end;
    }

    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: var(--white);
        border-radius: 10px;
        border: 1px solid var(--gray-300);
        transition: border-color .2s ease, background .2s ease;
    }

    .toggle-row:hover {
        border-color: var(--dark);
        background: #F2F6FF;
    }

    .toggle-info {
        flex: 1;
    }

    .toggle-label {
        font-size: 15px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .toggle-desc {
        font-size: 13px;
        color: var(--gray-500);
    }

    .switch {
        position: relative;
        width: 48px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: var(--gray-300);
        transition: .25s;
        border-radius: 999px;
    }

    .slider:before {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        left: 3px;
        top: 3px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .15);
        transition: .25s;
    }

    .switch input:checked + .slider {
        background: var(--dark);
    }

    .switch input:checked + .slider:before {
        transform: translateX(22px);
    }

    @media (max-width: 640px) {
        .rate-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('rateBtn').addEventListener('click', function() {
        // Simulate AI rate suggestion
        const rates = { PKR: 2500, USD: 45, EUR: 40, GBP: 35, AED: 150, INR: 1200 };
        const currency = document.querySelector('[name="currency"]').value;
        document.querySelector('[name="rate"]').value = rates[currency] || 2500;
    });
</script>
@endpush