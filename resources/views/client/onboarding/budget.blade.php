@extends('layouts.onboarding')

@section('title', 'Budget & Timeline - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="3"
    title="Set your project scope"
    subtitle="This helps us match you with professionals within your range"
/>

<form id="budgetForm" action="{{ route('client.onboarding.budget.store') }}" method="POST">
    @csrf

    <div class="section-title">Project Budget</div>
    <div class="form-group">
        <label class="form-label">Budget Range</label>
        <div class="budget-grid">
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
        <label class="checkbox-label">
            <input type="checkbox" id="customBudgetToggle">
            <span>I want to specify exact budget</span>
        </label>

        <div class="custom-budget-fields" id="customBudgetFields" style="display: none;">
            <div class="rate-grid">
                <x-onboarding.select 
                    name="currency"
                    label="Currency"
                    :options="['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP', 'PKR' => 'PKR']"
                    selected="USD"
                />

                <x-onboarding.input 
                    name="budget_min"
                    label="Minimum"
                    type="number"
                    placeholder="5000"
                    min="0"
                />

                <x-onboarding.input 
                    name="budget_max"
                    label="Maximum"
                    type="number"
                    placeholder="10000"
                    min="0"
                />
            </div>
        </div>
    </div>

    <div class="section-title" style="margin-top: var(--space-xl);">Project Timeline</div>
    
    <x-onboarding.select 
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

    <x-onboarding.select 
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

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}" backUrl="{{ route('client.onboarding.project') }}" />
</form>

@endsection

@push('styles')
<style>
.budget-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-md);
    margin-top: var(--space-sm);
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
    padding: var(--space-lg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--card);
    transition: all var(--transition-base);
    text-align: center;
}

.budget-card:hover .budget-content {
    border-color: var(--accent);
    background: var(--apc-bg);
}

.budget-card input[type="radio"]:checked + .budget-content {
    border-color: var(--accent);
    background: var(--accent-light);
}

.budget-amount {
    font-size: var(--fs-body);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    margin-bottom: 2px;
}

.budget-desc {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

.custom-budget {
    margin-top: var(--space-lg);
    padding-top: var(--space-lg);
    border-top: 1px solid var(--border);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: var(--fs-body);
    color: var(--text-body);
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
    cursor: pointer;
}

.custom-budget-fields {
    margin-top: var(--space-md);
}

.rate-grid {
    display: grid;
    grid-template-columns: 120px 1fr 1fr;
    gap: var(--space-md);
    align-items: end;
}

@media (max-width: 640px) {
    .budget-grid { grid-template-columns: 1fr; }
    .rate-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('customBudgetToggle').addEventListener('change', function() {
    document.getElementById('customBudgetFields').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush