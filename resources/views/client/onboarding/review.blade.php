@extends('layouts.onboarding')

@section('title', 'Review & Post Project - ProMatch')

@section('card-content')

<x-onboarding.form-header 

    step="5"
    title="Review your project details"
    subtitle="Make sure everything looks good before posting"
    skipUrl="{{ route('tenant.onboarding.education') }}"

/>

<div class="review-summary">
    <!-- Company Info -->
    <div class="review-section">
        <div class="review-header">
            <h3 class="review-title">Company Information</h3>
            <button type="button" class="edit-btn" onclick="window.location.href='{{ route('client.onboarding.info') }}'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="review-content">
            <div class="review-row">
                <span class="review-label">Company:</span>
                <span class="review-value" id="review-company">Acme Inc.</span>
            </div>
            <div class="review-row">
                <span class="review-label">Industry:</span>
                <span class="review-value" id="review-industry">Technology</span>
            </div>
            <div class="review-row">
                <span class="review-label">Contact:</span>
                <span class="review-value" id="review-email">hiring@acme.com</span>
            </div>
        </div>
    </div>

    <!-- Project Details -->
    <div class="review-section">
        <div class="review-header">
            <h3 class="review-title">Project Details</h3>
            <button type="button" class="edit-btn" onclick="window.location.href='{{ route('client.onboarding.project') }}'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="review-content">
            <div class="review-row">
                <span class="review-label">Title:</span>
                <span class="review-value" id="review-title">E-commerce Website Development</span>
            </div>
            <div class="review-row">
                <span class="review-label">Category:</span>
                <span class="review-value" id="review-category">Web Development</span>
            </div>
            <div class="review-row">
                <span class="review-label">Type:</span>
                <span class="review-value" id="review-type">One-time Project</span>
            </div>
            <div class="review-row full">
                <span class="review-label">Description:</span>
                <p class="review-desc" id="review-description">Build a modern e-commerce platform with payment integration...</p>
            </div>
            <div class="review-row full">
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
        <div class="review-header">
            <h3 class="review-title">Budget & Timeline</h3>
            <button type="button" class="edit-btn" onclick="window.location.href='{{ route('client.onboarding.budget') }}'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="review-stats">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-label">Budget Range</div>
                <div class="stat-value" id="review-budget">$5,000 - $15,000</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏱️</div>
                <div class="stat-label">Timeline</div>
                <div class="stat-value" id="review-timeline">2-4 weeks</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-label">Start Date</div>
                <div class="stat-value" id="review-start">Immediately</div>
            </div>
        </div>
    </div>

    <!-- Preferences -->
    <div class="review-section">
        <div class="review-header">
            <h3 class="review-title">Work Preferences</h3>
            <button type="button" class="edit-btn" onclick="window.location.href='{{ route('client.onboarding.preferences') }}'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="review-badges">
            <span class="badge">✓ Remote Work</span>
            <span class="badge">✓ Flexible Hours</span>
            <span class="badge">Few times per week updates</span>
            <span class="badge">Email & Slack</span>
        </div>
    </div>
</div>

<div class="publish-section">
    <x-onboarding.toggle 
        name="publish_now"
        id="publishNow"
        label="Publish Project"
        description="Make your project visible to professionals immediately"
        checked
    />
</div>

<form action="{{ route('client.onboarding.publish') }}" method="POST">
    @csrf
    <input type="hidden" name="publish_now" id="publishNowInput" value="1">
    
    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}" 
        backUrl="{{ route('client.onboarding.preferences') }}"
        nextLabel="Post Project"
    />
</form>

@endsection

@push('styles')
<style>
.review-summary {
    margin-bottom: var(--space-lg);
}

.review-section {
    background: var(--apc-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: var(--space-lg);
    margin-bottom: var(--space-md);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-md);
    padding-bottom: var(--space-md);
    border-bottom: 1px solid var(--border);
}

.review-title {
    font-size: var(--fs-body);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
}

.edit-btn {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    background: none;
    border: none;
    color: var(--accent);
    font-size: var(--fs-subtle);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius);
    transition: background var(--transition-base);
}

.edit-btn:hover {
    background: var(--accent-light);
}

.review-content {
    display: grid;
    gap: var(--space-sm);
}

.review-row {
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: var(--space-md);
    align-items: start;
}

.review-row.full {
    grid-template-columns: 1fr;
}

.review-label {
    font-size: var(--fs-subtle);
    font-weight: var(--fw-semibold);
    color: var(--text-muted);
}

.review-value {
    font-size: var(--fs-body);
    color: var(--text-heading);
    font-weight: var(--fw-medium);
}

.review-desc {
    font-size: var(--fs-body);
    color: var(--text-body);
    line-height: var(--lh-relaxed);
    margin-top: var(--space-sm);
}

.review-chips {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
    margin-top: var(--space-sm);
}

.review-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-md);
}

.stat-card {
    text-align: center;
    padding: var(--space-md);
    background: var(--card);
    border-radius: var(--radius);
    border: 1px solid var(--border);
}

.stat-icon {
    font-size: 1.75rem;
    margin-bottom: var(--space-sm);
}

.stat-label {
    font-size: var(--fs-micro);
    color: var(--text-muted);
    margin-bottom: var(--space-xs);
}

.stat-value {
    font-size: var(--fs-body);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
}

.review-badges {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
}

.publish-section {
    background: var(--apc-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: var(--space-lg);
    margin-bottom: var(--space-lg);
}

@media (max-width: 768px) {
    .review-row {
        grid-template-columns: 1fr;
    }

    .review-stats {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('publishNow').addEventListener('change', function() {
    document.getElementById('publishNowInput').value = this.checked ? '1' : '0';
});
</script>
@endpush