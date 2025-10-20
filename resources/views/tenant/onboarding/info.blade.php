@extends('layouts.onboarding')

@section('title', 'Company Information - ProMatch')

@section('card-content')

    <x-onboarding.form-header 
        step="1"
        totalSteps="5"
        title="Tell us about your company"
        subtitle="This helps professionals understand who you are and builds trust"
    />

    <form id="clientInfoForm" action="#" method="POST">
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
            <x-ui.onboarding.input 
                name="company_name"
                label="Company Name"
                placeholder="Acme Inc."
                required
            />

            <x-ui.onboarding.select 
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

            <x-ui.onboarding.input 
                name="industry"
                label="Industry"
                placeholder="e.g., Technology, Healthcare, Finance"
            />
        </div>

        <!-- Individual Fields (Hidden by default) -->
        <div id="individualFields" style="display: none;">
            <div class="input-row">
                <x-ui.onboarding.input 
                    name="name"
                    label="First Name"
                    placeholder="John"
                />

                <x-ui.onboarding.input 
                    name="last_name"
                    label="Last Name"
                    placeholder="Smith"
                />
            </div>
        </div>

        <!-- Common Fields -->
        <x-ui.onboarding.input 
            name="contact_email"
            type="email"
            label="Contact Email"
            placeholder="hiring@company.com"
            required
        />

        <x-ui.onboarding.input 
            name="phone"
            type="tel"
            label="Phone Number (Optional)"
            placeholder="+1 (555) 000-0000"
        />

        <x-ui.onboarding.input 
            name="website"
            type="url"
            label="Website (Optional)"
            placeholder="https://yourcompany.com"
        />

        <x-ui.onboarding.textarea 
            name="about"
            label="About"
            placeholder="Tell professionals about your company, mission, or what you do..."
            rows="4"
            maxlength="500"
            showCounter
        />

        <x-onboarding.form-footer
            backUrl="#"
            nextLabel="Continue"
        />
    </form>

@endsection

@push('styles')
     
    
    <!-- Client-specific styles -->
    <style>
        .account-toggle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
        }

        .toggle-option {
            padding: 14px 20px;
            background: var(--card);
            border: 2px solid var(--border);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
            color: var(--text-body);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .toggle-option svg {
            flex-shrink: 0;
        }

        .toggle-option:hover {
            border-color: var(--accent);
            background: var(--apc-bg);
        }

        .toggle-option.active {
            border-color: var(--accent);
            background: var(--accent-light);
            color: var(--accent);
        }

        .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 640px) {
            .account-toggle {
                grid-template-columns: 1fr;
            }

            .input-row {
                grid-template-columns: 1fr;
            }
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

        // Load saved data
        loadSavedData();

        toggleOptions.forEach(option => {
            option.addEventListener('click', function() {
                toggleOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const type = this.getAttribute('data-type');
                accountTypeInput.value = type;

                if (type === 'company') {
                    companyFields.style.display = 'block';
                    individualFields.style.display = 'none';
                    
                    // Set company fields as required
                    companyFields.querySelectorAll('input[required], select[required]').forEach(el => el.required = true);
                    individualFields.querySelectorAll('input[required], select[required]').forEach(el => el.required = false);
                } else {
                    companyFields.style.display = 'none';
                    individualFields.style.display = 'block';
                    
                    // Set individual fields as required
                    individualFields.querySelectorAll('input[required], select[required]').forEach(el => el.required = true);
                    companyFields.querySelectorAll('input[required], select[required]').forEach(el => el.required = false);
                }

                saveData();
            });
        });

        // Auto-save on input
        const form = document.getElementById('clientInfoForm');
        form.addEventListener('input', saveData);

        function saveData() {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            try {
                localStorage.setItem('client_onboarding_info', JSON.stringify(data));
            } catch {}
        }

        function loadSavedData() {
            try {
                const saved = localStorage.getItem('client_onboarding_info');
                if (!saved) return;

                const data = JSON.parse(saved);
                
                // Restore account type
                if (data.account_type) {
                    toggleOptions.forEach(opt => {
                        if (opt.getAttribute('data-type') === data.account_type) {
                            opt.click();
                        }
                    });
                }

                // Restore form values
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input && key !== 'account_type') {
                        input.value = data[key];
                    }
                });

                // Update character counter for textarea
                const aboutTextarea = form.querySelector('[name="about"]');
                if (aboutTextarea) {
                    const counter = aboutTextarea.closest('.form-group')?.querySelector('.current-count');
                    if (counter) {
                        counter.textContent = aboutTextarea.value.length;
                    }
                }
            } catch {}
        }
    });
</script>
@endpush