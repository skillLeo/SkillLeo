@extends('layouts.onboarding')

@section('title', 'Review & Publish - ProMatch')

@php
    $currentStep = 8;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Review & Publish" />
        <h1 class="form-title">Your profile is ready to shine</h1>
        <p class="form-subtitle">Give it a quick look, toggle visibility, and publish.</p>
    </div>

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
                <input type="checkbox" id="makePublic" checked />
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <form class="form-actions" id="reviewForm" action="{{ route('tenant.onboarding.publish') }}" method="POST">
        @csrf
        <x-ui.button variant="back" href="{{ route('tenant.onboarding.preferences') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back
        </x-ui.button>

        <x-ui.button variant="primary" type="submit" id="publishBtn">
            <span id="btnText">Publish profile</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </x-ui.button>
    </form>
@endsection

@push('styles')
<style>
    .summary-card {
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        padding: 20px;
        background: var(--gray-100);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 16px;
    }

    .profile-header {
        display: grid;
        grid-template-columns: 64px 1fr;
        gap: 14px;
        align-items: center;
        margin-bottom: 12px;
        border-bottom: 1px solid var(--gray-300);
        padding-bottom: 12px;
    }

    .avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-weight: 700;
        color: #fff;
        font-size: 20px;
        background: var(--dark);
    }

    .ph-name {
        font-weight: 700;
        color: var(--gray-900);
    }

    .ph-sub {
        font-size: 13px;
        color: var(--gray-500);
    }

    .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .chip {
        font-size: 12px;
        color: var(--gray-700);
        background: var(--white);
        border: 1px solid var(--gray-300);
        padding: 6px 10px;
        border-radius: 999px;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 8px;
    }

    .stat {
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: 10px;
        padding: 12px;
        text-align: center;
    }

    .stat .n {
        font-weight: 800;
        color: var(--gray-900);
    }

    .stat .l {
        font-size: 12px;
        color: var(--gray-500);
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
    // Load profile data from localStorage and render summary
    document.addEventListener('DOMContentLoaded', function() {
        // This would pull data from your Laravel session/database
        // For now, showing structure
    });
</script>
@endpush