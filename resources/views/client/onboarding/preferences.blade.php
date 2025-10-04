@extends('layouts.onboarding')

@section('title', 'Work Preferences - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="4"
    title="How do you like to work?"
    subtitle="Set expectations for communication and collaboration"
/>

<form id="preferencesForm" action="{{ route('client.onboarding.preferences.store') }}" method="POST">
    @csrf

    <div class="section-title">Work Style Preferences</div>
    <div class="toggle-container">
        <x-onboarding.toggle 
            name="remote_ok"
            label="Remote Work"
            description="Open to fully remote professionals"
            checked
        />

        <x-onboarding.toggle 
            name="flexible_hours"
            label="Flexible Hours"
            description="Allow professionals to work on their schedule"
            checked
        />

        <x-onboarding.toggle 
            name="nda_required"
            label="NDA Required"
            description="Require signed non-disclosure agreement"
        />
    </div>

    <div class="section-title" style="margin-top: var(--space-xl);">Communication</div>
    
    <x-onboarding.select 
        name="communication_frequency"
        label="Expected Update Frequency"
        required
        :options="[
            'daily' => 'Daily updates',
            'few-times-week' => 'Few times per week',
            'weekly' => 'Weekly updates',
            'bi-weekly' => 'Bi-weekly updates',
            'milestone' => 'At each milestone'
        ]"
        selected="few-times-week"
    />

    <div class="form-group">
        <label class="form-label">Preferred Communication Channels</label>
        <div class="checkbox-grid">
            <label class="checkbox-card">
                <input type="checkbox" name="channels[]" value="email" checked>
                <div class="checkbox-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <span>Email</span>
                </div>
            </label>
            <label class="checkbox-card">
                <input type="checkbox" name="channels[]" value="slack">
                <div class="checkbox-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>Slack</span>
                </div>
            </label>
            <label class="checkbox-card">
                <input type="checkbox" name="channels[]" value="zoom">
                <div class="checkbox-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 7l-7 5 7 5V7z"/>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                    </svg>
                    <span>Video Call</span>
                </div>
            </label>
            <label class="checkbox-card">
                <input type="checkbox" name="channels[]" value="phone">
                <div class="checkbox-content">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <span>Phone</span>
                </div>
            </label>
        </div>
    </div>

    <x-onboarding.select 
        name="timezone"
        label="Your Timezone"
        required
        :options="[
            'PST' => 'Pacific Time (PST)',
            'MST' => 'Mountain Time (MST)',
            'CST' => 'Central Time (CST)',
            'EST' => 'Eastern Time (EST)',
            'GMT' => 'GMT (London)',
            'CET' => 'Central European Time',
            'PKT' => 'Pakistan Time (PKT)',
            'IST' => 'India Time (IST)',
            'Other' => 'Other'
        ]"
    />

    <div class="section-title" style="margin-top: var(--space-xl);">Team Requirements</div>
    
    <x-onboarding.select 
        name="team_size"
        label="How many professionals do you need?"
        required
        :options="[
            '1' => 'Just 1 professional',
            '2-3' => '2-3 professionals',
            '4-5' => '4-5 professionals',
            'team' => 'Full team (6+)',
            'not-sure' => 'Not sure yet'
        ]"
        selected="1"
    />

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}" backUrl="{{ route('client.onboarding.budget') }}" />
</form>

@endsection

@push('styles')
<style>
.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-md);
    margin-top: var(--space-sm);
}

.checkbox-card {
    cursor: pointer;
}

.checkbox-card input[type="checkbox"] {
    display: none;
}

.checkbox-content {
    padding: var(--space-md);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--card);
    transition: all var(--transition-base);
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    color: var(--text-body);
}

.checkbox-card:hover .checkbox-content {
    border-color: var(--accent);
    background: var(--apc-bg);
}

.checkbox-card input[type="checkbox"]:checked + .checkbox-content {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
}

@media (max-width: 640px) {
    .checkbox-grid { grid-template-columns: 1fr; }
}
</style>
@endpush