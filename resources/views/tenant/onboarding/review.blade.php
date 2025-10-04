@extends('layouts.onboarding')

@section('title', 'Review & Publish - ProMatch')

@section('card-content')

    <x-ui.onboarding.form-header 
        step="8"
        title="Your profile is ready to shine"
        subtitle="Give it a quick look, toggle visibility, and publish."
    />

    <!-- Profile summary -->
    <div class="summary-card" id="profileSummary">
        <div class="profile-header">
            <div class="avatar" id="profileAvatar">U</div>
            <div>
                <div class="ph-name" id="profileName">Your Name</div>
                <div class="ph-sub">
                    <span id="profileLocation">Your Location</span> ·
                    <span id="profileUrl">promatch.com/username</span>
                </div>
            </div>
        </div>

        <div class="chips" id="skillsChips"></div>

        <div class="stats">
            <div class="stat">
                <div class="n" id="skillsCount">0</div>
                <div class="l">Skills</div>
            </div>
            <div class="stat">
                <div class="n" id="experienceCount">0</div>
                <div class="l">Experience</div>
            </div>
            <div class="stat">
                <div class="n" id="projectsCount">0</div>
                <div class="l">Projects</div>
            </div>
        </div>
    </div>

    <!-- Visibility -->
    <div class="summary-card">
        <div class="toggle-row">
            <div class="toggle-info">
                <strong>Make profile public</strong><br/>
                <span class="ph-sub">Your profile will be visible to clients and searchable.</span>
            </div>
            <label class="switch">
                <input type="checkbox" id="makePublic" name="is_public" checked />
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <form id="reviewForm" action="{{ route('tenant.onboarding.publish') }}" method="POST">
        @csrf
        <input type="hidden" name="is_public" id="isPublicInput" value="1">
        
        <x-ui.onboarding.form-footer
            backUrl="{{ route('tenant.onboarding.preferences') }}"
            nextLabel="Publish profile"
            id="publishBtn"
        />
    </form>

@endsection

@push('styles')
    <x-ui.onboarding.styles />
    
    <style>
        .summary-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            background: var(--apc-bg);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 16px;
        }

        .profile-header {
            display: grid;
            grid-template-columns: 64px 1fr;
            gap: 14px;
            align-items: center;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
        }

        .avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-weight: var(--fw-bold);
            color: var(--btn-text-primary);
            font-size: var(--fs-h2);
            background: var(--accent);
        }

        .ph-name {
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            font-size: var(--fs-title);
        }

        .ph-sub {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .chip {
            font-size: var(--fs-micro);
            color: var(--text-body);
            background: var(--card);
            border: 1px solid var(--border);
            padding: 6px 10px;
            border-radius: 999px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 12px;
            text-align: center;
        }

        .stat .n {
            font-weight: var(--fw-extrabold);
            color: var(--text-heading);
            font-size: var(--fs-h3);
        }

        .stat .l {
            font-size: var(--fs-micro);
            color: var(--text-muted);
            margin-top: 2px;
        }

        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .toggle-row:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .toggle-info {
            flex: 1;
        }

        .toggle-info strong {
            font-size: var(--fs-body);
            font-weight: var(--fw-bold);
            color: var(--text-heading);
        }

        .switch {
            position: relative;
            width: 48px;
            height: 26px;
            flex-shrink: 0;
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
            background: var(--border);
            transition: 0.25s;
            border-radius: 999px;
        }

        .slider:before {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            left: 3px;
            top: 3px;
            background: var(--card);
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
            transition: 0.25s;
        }

        .switch input:checked + .slider {
            background: var(--accent);
        }

        .switch input:checked + .slider:before {
            transform: translateX(22px);
        }

        @media (max-width: 640px) {
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const makePublicCheckbox = document.getElementById('makePublic');
        const isPublicInput = document.getElementById('isPublicInput');
        
        // Load profile data from localStorage
        const personal = JSON.parse(localStorage.getItem('onboarding_personal') || '{}');
        const location = JSON.parse(localStorage.getItem('onboarding_location') || '{}');
        const skills = JSON.parse(localStorage.getItem('onboarding_skills') || '[]');
        const experience = JSON.parse(localStorage.getItem('onboarding_experience') || '[]');
        const portfolio = JSON.parse(localStorage.getItem('onboarding_portfolio') || '[]');

        // Update profile summary
        const firstName = personal.firstName || 'Your';
        const lastName = personal.lastName || 'Name';
        const username = personal.username || 'username';
        
        document.getElementById('profileName').textContent = `${firstName} ${lastName}`;
        document.getElementById('profileAvatar').textContent = firstName.charAt(0).toUpperCase();
        document.getElementById('profileUrl').textContent = `promatch.com/${username}`;
        
        if (location.cityName && location.countryName) {
            document.getElementById('profileLocation').textContent = `${location.cityName}, ${location.countryName}`;
        }

        // Display skills chips
        const skillsChipsEl = document.getElementById('skillsChips');
        if (skills.length > 0) {
            skillsChipsEl.innerHTML = skills.slice(0, 5).map(skill => 
                `<div class="chip">${escapeHtml(skill.name)}</div>`
            ).join('');
        }

        // Update stats
        document.getElementById('skillsCount').textContent = skills.length;
        document.getElementById('experienceCount').textContent = experience.length;
        document.getElementById('projectsCount').textContent = portfolio.length;

        // Handle visibility toggle
        makePublicCheckbox.addEventListener('change', function() {
            isPublicInput.value = this.checked ? '1' : '0';
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }
    });
</script>
@endpush