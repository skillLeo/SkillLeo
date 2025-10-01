@extends('layouts.onboarding')

@section('title', 'Budget & Timeline - ProMatch')

@php
    $currentStep = 3;
    $totalSteps = 5;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Budget & Timeline" />
        <h1 class="form-title">Set your project scope</h1>
        <p class="form-subtitle">This helps us match you with professionals within your range</p>
    </div>

    <form id="budgetForm" action="{{ route('client.onboarding.budget.store') }}" method="POST">
        @csrf

        <!-- Budget Section -->
        <div class="section">
            <div class="section-title">Project Budget</div>
            <div class="block">
                <div class="form-group">
                    <label class="form-label">Budget Range</label>
                    <div class="budget-options">
                        <label class="budget-card">
                            <input type="radio" name="budget_range" value="under-5k">
                            <div class="budget-content">
                                <div class="budget-amount">Under $5,000</div>
                                <div class="budget-desc">Small projects</div>
                            </div>
                        </label>
                        <label class="budget-card">
                            <input type="radio" name="budget_range" value="5k-15k" checked>
                            <div class="budget-content">
                                <div class="budget-amount">$5,000 - $15,000</div>
                                <div class="budget-desc">Medium projects</div>
                            </div>
                        </label>
                        <label class="budget-card">
                            <input type="radio" name="budget_range" value="15k-50k">
                            <div class="budget-content">
                                <div class="budget-amount">$15,000 - $50,000</div>
                                <div class="budget-desc">Large projects</div>
                            </div>
                        </label>
                        <label class="budget-card">
                            <input type="radio" name="budget_range" value="50k-plus">
                            <div class="budget-content">
                                <div class="budget-amount">$50,000+</div>
                                <div class="budget-desc">Enterprise</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="custom-budget">
                    <label class="checkbox-row">
                        <input type="checkbox" id="customBudgetToggle">
                        <span>I want to specify exact budget</span>
                    </label>

                    <div class="custom-budget-fields" id="customBudgetFields" style="display: none;">
                        <div class="rate-grid">
                            <x-forms.select 
                                name="currency"
                                label="Currency"
                                :options="[
                                    'USD' => 'USD',
                                    'EUR' => 'EUR',
                                    'GBP' => 'GBP',
                                    'PKR' => 'PKR'
                                ]"
                                selected="USD"
                            />

                            <x-forms.input 
                                name="budget_min"
                                label="Minimum"
                                type="number"
                                placeholder="5000"
                                min="0"
                            />

                            <x-forms.input 
                                name="budget_max"
                                label="Maximum"
                                type="number"
                                placeholder="10000"
                                min="0"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="section">
            <div class="section-title">Project Timeline</div>
            <div class="block">
                <x-forms.select 
                    name="timeline"
                    label="When do you need this completed?"
                    required
                    :options="[
                        'asap' => 'ASAP (within 1 week)',
                        '1-2-weeks' => '1-2 weeks',
                        '2-4-weeks' => '2-4 weeks',
                        '1-2-months' => '1-2 months',
                        '2-3-months' => '2-3 months',
                        '3-plus-months' => '3+ months',
                        'flexible' => 'Flexible timeline'
                    ]"
                />

                <x-forms.select 
                    name="start_date"
                    label="When can work start?"
                    required
                    :options="[
                        'immediately' => 'Immediately',
                        '1-week' => 'Within 1 week',
                        '2-weeks' => 'Within 2 weeks',
                        '1-month' => 'Within 1 month',
                        'flexible' => 'Flexible'
                    ]"
                />
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('client.onboarding.project') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </x-ui.button>

            <x-ui.button variant="primary" type="submit">
                <span>Continue</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-ui.button>
        </div>
    </form>
@endsection

@push('styles')
<style>
    .budget-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 8px;
    }

    .budget-card {
        position: relative;
        cursor: pointer;
    }

    .budget-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .budget-content {
        padding: 18px 16px;
        border: 2px solid var(--gray-300);
        border-radius: 10px;
        background: var(--white);
        transition: all .2s ease;
        text-align: center;
    }

    .budget-card:hover .budget-content {
        border-color: var(--dark);
        transform: translateY(-2px);
    }

    .budget-card input[type="radio"]:checked + .budget-content {
        border-color: var(--primary);
        background: rgba(0, 97, 255, 0.08);
    }

    .budget-amount {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
    }

    .budget-desc {
        font-size: 12px;
        color: var(--gray-500);
    }

    .custom-budget {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--gray-300);
    }

    .checkbox-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: var(--gray-700);
        cursor: pointer;
    }

    .checkbox-row input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    .custom-budget-fields {
        margin-top: 16px;
    }

    @media (max-width: 640px) {
        .budget-options {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('customBudgetToggle').addEventListener('change', function() {
        const fields = document.getElementById('customBudgetFields');
        fields.style.display = this.checked ? 'block' : 'none';
    });
</script>
@endpush