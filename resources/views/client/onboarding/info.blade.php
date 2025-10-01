@extends('layouts.onboarding')

@section('title', 'Company Information - ProMatch')

@php
    $currentStep = 1;
    $totalSteps = 5;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Company Information" />
        <h1 class="form-title">Tell us about your company</h1>
        <p class="form-subtitle">This helps professionals understand who you are and builds trust</p>
    </div>

    <form id="clientInfoForm" action="{{ route('client.onboarding.info.store') }}" method="POST">
        @csrf

        <!-- Account Type Toggle -->
        <div class="form-group">
            <label class="form-label">I am</label>
            <div class="account-toggle">
                <button type="button" class="toggle-option active" data-type="company">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    </svg>
                    A Company
                </button>
                <button type="button" class="toggle-option" data-type="individual">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    An Individual
                </button>
            </div>
            <input type="hidden" name="account_type" id="accountType" value="company">
        </div>

        <!-- Company Fields -->
        <div id="companyFields">
            <x-forms.input 
                name="company_name"
                label="Company Name"
                placeholder="Acme Inc."
                required
            />

            <x-forms.select 
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

            <x-forms.input 
                name="industry"
                label="Industry"
                placeholder="e.g., Technology, Healthcare, Finance"
            />
        </div>

        <!-- Individual Fields (Hidden by default) -->
        <div id="individualFields" style="display: none;">
            <div class="input-row">
                <x-forms.input 
                    name="first_name"
                    label="First Name"
                    placeholder="John"
                />

                <x-forms.input 
                    name="last_name"
                    label="Last Name"
                    placeholder="Smith"
                />
            </div>
        </div>

        <!-- Common Fields -->
        <x-forms.input 
            name="contact_email"
            type="email"
            label="Contact Email"
            placeholder="hiring@company.com"
            required
        />

        <x-forms.input 
            name="phone"
            type="tel"
            label="Phone Number (Optional)"
            placeholder="+1 (555) 000-0000"
        />

        <x-forms.input 
            name="website"
            type="url"
            label="Website (Optional)"
            placeholder="https://yourcompany.com"
        />

        <x-forms.textarea 
            name="about"
            label="About"
            placeholder="Tell professionals about your company, mission, or what you do..."
            rows="4"
            maxlength="500"
            :showCounter="true"
        />

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('tenant.onboarding.account-type') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </x-ui.button>

            <x-ui.button variant="primary" type="submit" id="continueBtn">
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
    .account-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 8px;
    }

    .toggle-option {
        padding: 14px 20px;
        background: var(--white);
        border: 2px solid var(--gray-300);
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s ease;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .toggle-option svg {
        flex-shrink: 0;
    }

    .toggle-option:hover {
        border-color: var(--dark);
        background: var(--gray-100);
    }

    .toggle-option.active {
        border-color: var(--primary);
        background: rgba(0, 97, 255, 0.08);
        color: var(--primary);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleOptions = document.querySelectorAll('.toggle-option');
        const accountTypeInput = document.getElementById('accountType');
        const companyFields = document.getElementById('companyFields');
        const individualFields = document.getElementById('individualFields');

        toggleOptions.forEach(option => {
            option.addEventListener('click', function() {
                toggleOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const type = this.getAttribute('data-type');
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