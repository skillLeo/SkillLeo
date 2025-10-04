@extends('layouts.onboarding')

@section('title', 'Project Details - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="2"
    title="What are you looking to build?"
    subtitle="Describe your project so we can match you with the right talent"
/>

<form id="projectForm" action="{{ route('client.onboarding.project.store') }}" method="POST">
    @csrf

    <x-onboarding.input 
        name="project_title"
        label="Project Title"
        placeholder="e.g., E-commerce Website Development"
        required
        maxlength="100"
    />

    <x-onboarding.textarea 
        name="project_description"
        label="Project Description"
        placeholder="Describe what you need built, key features, goals, and any specific requirements..."
        rows="6"
        maxlength="2000"
        required
    />

    <x-onboarding.select 
        name="project_category"
        label="Project Category"
        placeholder="Select a category"
        required
        :options="[
            'web-dev' => 'Web Development',
            'mobile-dev' => 'Mobile Development',
            'design' => 'Design (UI/UX)',
            'data-science' => 'Data Science & Analytics',
            'devops' => 'DevOps & Cloud',
            'marketing' => 'Digital Marketing',
            'content' => 'Content Writing',
            'other' => 'Other'
        ]"
    />

    <div class="form-group">
        <label class="form-label">Skills Required</label>
        <input 
            type="text" 
            class="form-input" 
            id="skillsInput"
            placeholder="Type a skill and press Enter (e.g., React, Node.js)"
            autocomplete="off"
        >
        <p class="form-hint">Add 3-8 key skills needed for this project</p>

        <div class="skills-display" id="skillsDisplay">
            <div class="skills-empty" id="skillsEmpty">No skills added yet</div>
            <div class="skills-list" id="skillsList"></div>
        </div>

        <input type="hidden" name="skills" id="skillsData">
    </div>

    <div class="form-group">
        <label class="form-label">Project Type</label>
        <div class="radio-grid">
            <label class="radio-card">
                <input type="radio" name="project_type" value="one-time" checked>
                <div class="radio-content">
                    <div class="radio-title">One-time Project</div>
                    <div class="radio-desc">Fixed scope with defined deliverables</div>
                </div>
            </label>
            <label class="radio-card">
                <input type="radio" name="project_type" value="ongoing">
                <div class="radio-content">
                    <div class="radio-title">Ongoing Work</div>
                    <div class="radio-desc">Long-term engagement or retainer</div>
                </div>
            </label>
        </div>
    </div>

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}" 
        backUrl="{{ route('client.onboarding.info') }}"
        :continueDisabled="true"
    />
</form>

@endsection

@push('styles')
<style>
.radio-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
}

.radio-card {
    position: relative;
    cursor: pointer;
}

.radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio-content {
    padding: var(--space-md) var(--space-lg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--card);
    transition: all var(--transition-base);
}

.radio-card:hover .radio-content {
    border-color: var(--accent);
    background: var(--apc-bg);
}

.radio-card input[type="radio"]:checked + .radio-content {
    border-color: var(--accent);
    background: var(--accent-light);
}

.radio-title {
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin-bottom: 2px;
}

.radio-desc {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

.skills-display {
    margin-top: var(--space-md);
    padding: var(--space-md);
    border: 1px dashed var(--border);
    border-radius: var(--radius);
    background: var(--apc-bg);
    min-height: 80px;
}

.skills-empty {
    text-align: center;
    color: var(--text-muted);
    font-size: var(--fs-subtle);
}

.skills-list {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
}

@media (max-width: 640px) {
    .radio-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const skillsInput = document.getElementById('skillsInput');
    const skillsList = document.getElementById('skillsList');
    const skillsEmpty = document.getElementById('skillsEmpty');
    const skillsData = document.getElementById('skillsData');
    const continueBtn = document.getElementById('continueBtn');
    
    let skills = [];
    const MAX_SKILLS = 12;
    const MIN_SKILLS = 3;

    function render() {
        if (skills.length === 0) {
            skillsEmpty.style.display = 'block';
            skillsList.style.display = 'none';
        } else {
            skillsEmpty.style.display = 'none';
            skillsList.style.display = 'flex';
            skillsList.innerHTML = skills.map((skill, idx) => `
                <div class="chip chip-removable">
                    <span>${escapeHtml(skill)}</span>
                    <button type="button" class="chip-remove" data-idx="${idx}">×</button>
                </div>
            `).join('');
        }
        
        skillsData.value = JSON.stringify(skills);
        continueBtn.disabled = skills.length < MIN_SKILLS;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    skillsInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const skill = skillsInput.value.trim();
            if (skill && skills.length < MAX_SKILLS && !skills.includes(skill)) {
                skills.push(skill);
                skillsInput.value = '';
                render();
            }
        }
    });

    skillsList.addEventListener('click', (e) => {
        if (e.target.classList.contains('chip-remove')) {
            const idx = parseInt(e.target.dataset.idx);
            skills.splice(idx, 1);
            render();
        }
    });

    render();
});
</script>
@endpush