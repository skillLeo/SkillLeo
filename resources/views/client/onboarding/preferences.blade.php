@extends('layouts.onboarding')

@section('title', 'Work Preferences - ProMatch')

@php
    $currentStep = 4;
    $totalSteps = 5;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Work Preferences" />
        <h1 class="form-title">How do you like to work?</h1>
        <p class="form-subtitle">Set expectations for communication and collaboration</p>
    </div>

    <form id="preferencesForm" action="{{ route('client.onboarding.preferences.store') }}" method="POST">
        @csrf

        <!-- Work Style Section -->
        <div class="section">
            <div class="section-title">Work Style Preferences</div>
            <div class="block" style="padding: 0;">
                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">Remote Work</div>
                        <div class="toggle-desc">Open to fully remote professionals</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="remote_ok" checked />
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">Flexible Hours</div>
                        <div class="toggle-desc">Allow professionals to work on their schedule</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="flexible_hours" checked />
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-label">NDA Required</div>
                        <div class="toggle-desc">Require signed non-disclosure agreement</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="nda_required" />
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Communication Section -->
        <div class="section">
            <div class="section-title">Communication</div>
            <div class="block">
                <x-forms.select 
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
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <span>Email</span>
                            </div>
                        </label>
                        <label class="checkbox-card">
                            <input type="checkbox" name="channels[]" value="slack">
                            <div class="checkbox-content">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                <span>Slack</span>
                            </div>
                        </label>
                        <label class="checkbox-card">
                            <input type="checkbox" name="channels[]" value="zoom">
                            <div class="checkbox-content">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M23 7l-7 5 7 5V7z"/>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                                </svg>
                                <span>Video Call</span>
                            </div>
                        </label>
                        <label class="checkbox-card">
                            <input type="checkbox" name="channels[]" value="phone">
                            <div class="checkbox-content">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <span>Phone</span>
                            </div>
                        </label>
                    </div>
                </div>

                <x-forms.select 
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
            </div>
        </div>

        <!-- Team Size -->
        <div class="section">
            <div class="section-title">Team Requirements</div>
            <div class="block">
                <x-forms.select 
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
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('client.onboarding.budget') }}">
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
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 8px;
    }

    .checkbox-card {
        cursor: pointer;
    }

    .checkbox-card input[type="checkbox"] {
        display: none;
    }

    .checkbox-content {
        padding: 14px 16px;
        border: 2px solid var(--gray-300);
        border-radius: 10px;
        background: var(--white);
        transition: all .2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-700);
    }

    .checkbox-content svg {
        flex-shrink: 0;
    }

    .checkbox-card:hover .checkbox-content {
        border-color: var(--dark);
        background: var(--gray-100);
    }

    .checkbox-card input[type="checkbox"]:checked + .checkbox-content {
        border-color: var(--primary);
        background: rgba(0, 97, 255, 0.08);
        color: var(--primary);
    }

    @media (max-width: 640px) {
        .checkbox-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush