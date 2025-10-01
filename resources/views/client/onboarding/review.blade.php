@extends('layouts.onboarding')

@section('title', 'Review & Post Project - ProMatch')

@php
    $currentStep = 5;
    $totalSteps = 5;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Review & Post" />
        <h1 class="form-title">Review your project details</h1>
        <p class="form-subtitle">Make sure everything looks good before posting</p>
    </div>

    <!-- Project Summary -->
    <div class="review-summary">
        <!-- Company Info -->
        <div class="review-section">
            <div class="review-section-header">
                <h3 class="review-section-title">Company Information</h3>
                <button type="button" class="edit-link" onclick="window.location.href='{{ route('client.onboarding.info') }}'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </button>
            </div>
            <div class="review-content">
                <div class="review-item">
                    <span class="review-label">Company:</span>
                    <span class="review-value" id="review-company">Acme Inc.</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Industry:</span>
                    <span class="review-value" id="review-industry">Technology</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Contact:</span>
                    <span class="review-value" id="review-email">hiring@acme.com</span>
                </div>
            </div>
        </div>

        <!-- Project Details -->
        <div class="review-section">
            <div class="review-section-header">
                <h3 class="review-section-title">Project Details</h3>
                <button type="button" class="edit-link" onclick="window.location.href='{{ route('client.onboarding.project') }}'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </button>
            </div>
            <div class="review-content">
                <div class="review-item">
                    <span class="review-label">Title:</span>
                    <span class="review-value" id="review-title">E-commerce Website Development</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Category:</span>
                    <span class="review-value" id="review-category">Web Development</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Type:</span>
                    <span class="review-value" id="review-type">One-time Project</span>
                </div>
                <div class="review-item full-width">
                    <span class="review-label">Description:</span>
                    <p class="review-description" id="review-description">
                        Build a modern e-commerce platform with payment integration...
                    </p>
                </div>
                <div class="review-item full-width">
                    <span class="review-label">Skills Required:</span>
                    <div class="review-chips" id="review-skills">
                        <span class="chip">React</span>
                        <span class="chip">Node.js</span>
                        <span class="chip">MongoDB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget & Timeline -->
        <div class="review-section">
            <div class="review-section-header">
                <h3 class="review-section-title">Budget & Timeline</h3>
                <button type="button" class="edit-link" onclick="window.location.href='{{ route('client.onboarding.budget') }}'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </button>
            </div>
            <div class="review-content">
                <div class="review-grid">
                    <div class="review-stat">
                        <div class="stat-icon">💰</div>
                        <div class="stat-label">Budget Range</div>
                        <div class="stat-value" id="review-budget">$5,000 - $15,000</div>
                    </div>
                    <div class="review-stat">
                        <div class="stat-icon">⏱️</div>
                        <div class="stat-label">Timeline</div>
                        <div class="stat-value" id="review-timeline">2-4 weeks</div>
                    </div>
                    <div class="review-stat">
                        <div class="stat-icon">📅</div>
                        <div class="stat-label">Start Date</div>
                        <div class="stat-value" id="review-start">Immediately</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preferences -->
        <div class="review-section">
            <div class="review-section-header">
                <h3 class="review-section-title">Work Preferences</h3>
                <button type="button" class="edit-link" onclick="window.location.href='{{ route('client.onboarding.preferences') }}'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </button>
            </div>
            <div class="review-content">
                <div class="review-preferences">
                    <div class="pref-badge">✓ Remote Work</div>
                    <div class="pref-badge">✓ Flexible Hours</div>
                    <div class="pref-badge">Few times per week updates</div>
                    <div class="pref-badge">Email & Slack</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visibility Toggle -->
    <div class="visibility-section">
        <div class="toggle-row">
            <div class="toggle-info">
                <strong>Publish Project</strong><br/>
                <span class="ph-sub">Make your project visible to professionals immediately</span>
            </div>
            <label class="switch">
                <input type="checkbox" id="publishNow" checked />
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <form class="form-actions" action="{{ route('client.onboarding.publish') }}" method="POST">
        @csrf
        <input type="hidden" name="publish_now" id="publishNowInput" value="1">

        <x-ui.button variant="back" href="{{ route('client.onboarding.preferences') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back
        </x-ui.button>

        <x-ui.button variant="primary" type="submit" id="publishBtn">
            <span id="btnText">Post Project</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </x-ui.button>
    </form>
@endsection

@push('styles')
<style>
    .review-summary {
        margin-bottom: 24px;
    }

    .review-section {
        background: var(--gray-100);
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 16px;
    }

    .review-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--gray-300);
    }

    .review-section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .edit-link {
        display: flex;
        align-items: center;
        gap: 6px;
        background: none;
        border: none;
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 6px;
        transition: background .2s ease;
    }

    .edit-link:hover {
        background: rgba(0, 97, 255, 0.1);
    }

    .review-content {
        display: grid;
        gap: 12px;
    }

    .review-item {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 12px;
        align-items: start;
    }

    .review-item.full-width {
        grid-template-columns: 1fr;
    }

    .review-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-500);
    }

    .review-value {
        font-size: 14px;
        color: var(--gray-900);
        font-weight: 500;
    }

    .review-description {
        font-size: 14px;
        color: var(--gray-700);
        line-height: 1.6;
        margin-top: 8px;
    }

    .review-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .review-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .review-stat {
        text-align: center;
        padding: 16px;
        background: var(--white);
        border-radius: 10px;
        border: 1px solid var(--gray-300);
    }

    .stat-icon {
        font-size: 28px;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--gray-500);
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 15px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .review-preferences {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pref-badge {
        padding: 8px 14px;
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: 20px;
        font-size: 13px;
        color: var(--gray-700);
        font-weight: 500;
    }

    .visibility-section {
        background: var(--gray-100);
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 768px) {
        .review-item {
            grid-template-columns: 1fr;
        }

        .review-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('publishNow').addEventListener('change', function() {
        document.getElementById('publishNowInput').value = this.checked ? '1' : '0';
        const btnText = document.getElementById('btnText');
        btnText.textContent = this.checked ? 'Post Project' : 'Save as Draft';
    });

    // Load summary from session storage (in production, this would come from Laravel session)
    document.addEventListener('DOMContentLoaded', function() {
        // This would typically be populated from your Laravel backend
        console.log('Review page loaded - summary data would be populated here');
    });
</script>
@endpush