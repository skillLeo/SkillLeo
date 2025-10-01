@extends('layouts.onboarding')

@section('title', 'Project Details - ProMatch')

@php
    $currentStep = 2;
    $totalSteps = 5;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Project Details" />
        <h1 class="form-title">What are you looking to build?</h1>
        <p class="form-subtitle">Describe your project so we can match you with the right talent</p>
    </div>

    <form id="projectForm" action="{{ route('client.onboarding.project.store') }}" method="POST">
        @csrf

        <x-forms.input 
            name="project_title"
            label="Project Title"
            placeholder="e.g., E-commerce Website Development"
            required
            maxlength="100"
        />

        <x-forms.textarea 
            name="project_description"
            label="Project Description"
            placeholder="Describe what you need built, key features, goals, and any specific requirements..."
            rows="6"
            maxlength="2000"
            :showCounter="true"
            required
        />

        <!-- Project Category -->
        <x-forms.select 
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

        <!-- Skills Needed -->
        <div class="form-group">
            <label class="form-label" for="skillsInput">Skills Required</label>
            <input 
                type="text" 
                class="form-input" 
                id="skillsInput"
                placeholder="Type a skill and press Enter (e.g., React, Node.js)"
                autocomplete="off"
            >
            <p class="input-hint">Add 3-8 key skills needed for this project</p>

            <div class="skills-display" id="skillsDisplay">
                <div class="skills-empty" id="skillsEmpty">No skills added yet</div>
                <div class="skills-list" id="skillsList"></div>
            </div>

            <input type="hidden" name="skills" id="skillsData">
        </div>

        <!-- Project Type -->
        <div class="form-group">
            <label class="form-label">Project Type</label>
            <div class="radio-group">
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

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('client.onboarding.info') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </x-ui.button>

            <x-ui.button variant="primary" type="submit" id="continueBtn" disabled>
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
    .radio-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
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
        padding: 16px 18px;
        border: 2px solid var(--gray-300);
        border-radius: 10px;
        background: var(--white);
        transition: all .2s ease;
    }

    .radio-card:hover .radio-content {
        border-color: var(--dark);
        background: var(--gray-100);
    }

    .radio-card input[type="radio"]:checked + .radio-content {
        border-color: var(--primary);
        background: rgba(0, 97, 255, 0.08);
    }

    .radio-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 4px;
    }

    .radio-desc {
        font-size: 13px;
        color: var(--gray-500);
    }

    .skills-display {
        margin-top: 12px;
        padding: 16px;
        border: 1px dashed var(--gray-300);
        border-radius: 10px;
        background: var(--gray-100);
        min-height: 80px;
    }

    .skills-empty {
        text-align: center;
        color: var(--gray-500);
        font-size: 14px;
    }

    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    @media (max-width: 640px) {
        .radio-group {
            grid-template-columns: 1fr;
        }
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
                    <div class="skill-chip">
                        <span class="skill-name">${escapeHtml(skill)}</span>
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
                const idx = parseInt(e.target.getAttribute('data-idx'));
                skills.splice(idx, 1);
                render();
            }
        });

        render();
    });
</script>
@endpush