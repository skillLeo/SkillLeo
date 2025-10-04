@extends('layouts.onboarding')

@section('title', 'Company Information - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="1"
    title="Tell us about your company"
    subtitle="This helps professionals understand who you are and builds trust"
/>

<form id="clientInfoForm" action="{{ route('client.onboarding.info.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label class="form-label">I am</label>
        <div class="account-toggle">
            <button type="button" class="toggle-btn active" data-type="company">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                </svg>
                A Company
            </button>
            <button type="button" class="toggle-btn" data-type="individual">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                An Individual
            </button>
        </div>
        <input type="hidden" name="account_type" id="accountType" value="company">
    </div>

    <div id="companyFields">
        <x-onboarding.input 
            name="company_name"
            label="Company Name"
            placeholder="Acme Inc."
            required
        />

        <x-onboarding.select 
            name="company_size"
            label="Company Size"
            placeholder="Select company size"
            :options="[
                '1-10' => '1-10 employees',
                '11-50' => '11-50 employees',
                '51-200' => '51-200 employees',
                '201-500' => '201-500 employees',
                '500+' => '500+ employees'
            ]"
        />

        <x-onboarding.input 
            name="industry"
            label="Industry"
            placeholder="e.g., Technology, Healthcare, Finance"
        />
    </div>

    <div id="individualFields" style="display: none;">
        <div class="input-row">
            <x-onboarding.input 
                name="first_name"
                label="First Name"
                placeholder="John"
            />

            <x-onboarding.input 
                name="last_name"
                label="Last Name"
                placeholder="Smith"
            />
        </div>
    </div>

    <x-onboarding.input 
        name="contact_email"
        type="email"
        label="Contact Email"
        placeholder="hiring@company.com"
        required
    />

    <x-onboarding.input 
        name="phone"
        type="tel"
        label="Phone Number (Optional)"
        placeholder="+1 (555) 000-0000"
    />

    <x-onboarding.input 
        name="website"
        type="url"
        label="Website (Optional)"
        placeholder="https://yourcompany.com"
    />

    <x-onboarding.textarea 
        name="about"
        label="About"
        placeholder="Tell professionals about your company, mission, or what you do..."
        rows="4"
        maxlength="500"
        :showCounter="true"
    />

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}" backUrl="#" />
</form>

@endsection

@push('styles')
<style>
.account-toggle {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
}

.toggle-btn {
    padding: var(--space-md) var(--space-lg);
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all var(--transition-base);
    font-family: var(--font-sans);
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    color: var(--text-body);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
}

.toggle-btn:hover {
    border-color: var(--accent);
    background: var(--apc-bg);
}

.toggle-btn.active {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
}

@media (max-width: 640px) {
    .account-toggle {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    const accountTypeInput = document.getElementById('accountType');
    const companyFields = document.getElementById('companyFields');
    const individualFields = document.getElementById('individualFields');

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            toggleBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const type = this.dataset.type;
            accountTypeInput.value = type;

            if (type === 'company') {
                companyFields.style.display = 'block';
                individualFields.style.display = 'none';
            } else {
                companyFields.style.display = 'none';
                individualFields.style.display = 'block';
            }
        });
    });
});
</script>
@endpush