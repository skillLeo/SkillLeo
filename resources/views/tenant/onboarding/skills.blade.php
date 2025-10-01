@extends('layouts.onboarding')

@section('title', 'Skills & Expertise - ProMatch')

@php
    $currentStep = 3;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Skills & Expertise" />
        <h1 class="form-title">What are your key skills?</h1>
        <p class="form-subtitle">Add your most relevant skills to showcase your expertise to clients</p>
    </div>

    <form id="skillsForm" action="{{ route('tenant.onboarding.skills.store') }}" method="POST">
        @csrf

        <!-- Add Skills -->
        <div class="form-group">
            <label class="form-label" for="skillInput">Add Skills</label>

            <div class="input-row">
                <div class="input-container">
                    <input 
                        type="text" 
                        class="form-input" 
                        id="skillInput"
                        placeholder="Type a skill (e.g. Laravel) and press Enter"
                        autocomplete="off"
                    >
                </div>

                <div class="level-select-wrap">
                    <label class="sr-only" for="levelSelect">Proficiency</label>
                    <select id="levelSelect" class="level-select">
                        <option value="1">Beginner</option>
                        <option value="2">Junior</option>
                        <option value="3" selected>Intermediate</option>
                        <option value="4">Advanced</option>
                        <option value="5">Expert</option>
                    </select>
                </div>
            </div>

            <p class="input-hint">
                Choose a level in the dropdown, press Enter to add. Click the 5 dots on a chip to adjust level (1 = Beginner … 5 = Expert). Use × to remove.
            </p>
        </div>

        <!-- Progress -->
        <div class="skills-progress" aria-live="polite">
            <div class="progress-text">
                Skills added: <strong id="skillCount">0</strong> / 10
            </div>
            <div class="progress-mini">
                <div class="progress-mini__fill" id="progressFill"></div>
            </div>
            <div class="progress-text">Minimum: 3</div>
        </div>

        <!-- Skills Area -->
        <div class="skills-area" id="skillsArea">
            <div class="skills-empty" id="skillsEmpty">Add your first skill to get started.</div>
            <div class="skills-list" id="skillsList" role="list"></div>
        </div>

        <input type="hidden" name="skills" id="skillsData">

        <!-- Tips -->
        <div class="tips">
            <strong>Pro tip:</strong> Focus on 5–8 core skills rather than listing everything. Quality helps you stand out.
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('tenant.onboarding.location') }}">
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
    .input-row {
        display: grid;
        grid-template-columns: 1fr 160px;
        gap: 12px;
        align-items: start;
    }

    .level-select-wrap { position: relative; }
    
    .level-select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font: inherit;
        background: var(--white);
        color: var(--gray-900);
        cursor: pointer;
        transition: box-shadow .2s ease, border-color .2s ease;
    }

    .level-select:focus {
        outline: none;
        border-color: var(--dark);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }

    .input-hint {
        font-size: 13px;
        color: var(--gray-500);
        margin-top: 8px;
    }

    .skills-progress {
        margin: 24px 0;
        padding: 12px 14px;
        background: var(--gray-100);
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .skills-progress .progress-text {
        font-size: 13px;
        color: var(--gray-700);
    }

    .progress-mini {
        flex: 1 1 160px;
        height: 6px;
        background: var(--gray-300);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-mini__fill {
        height: 100%;
        background: var(--dark);
        transition: width .4s ease;
        width: 0%;
    }

    .skills-area {
        border: 1px dashed var(--gray-300);
        border-radius: 12px;
        padding: 24px;
        background: var(--white);
    }

    .skills-empty {
        text-align: center;
        color: var(--gray-500);
        margin: 8px 0 0;
        font-size: 14px;
    }

    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .skill-chip {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border: 1px solid var(--gray-300);
        background: var(--white);
        border-radius: 10px;
        font-size: 13px;
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .skill-chip:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transform: translateY(-1px);
    }

    .skill-name {
        font-weight: 600;
        color: var(--gray-900);
    }

    .level-badge {
        font-size: 12px;
        line-height: 1;
        padding: 6px 8px;
        border-radius: 999px;
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .skill-level {
        display: inline-flex;
        gap: 4px;
        align-items: center;
    }

    .level-dot {
        width: 10px;
        height: 10px;
        appearance: none;
        border-radius: 50%;
        border: 1px solid var(--gray-300);
        background: var(--white);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform .15s ease, background .15s ease, border-color .15s ease;
    }

    .level-dot.active {
        background: var(--dark);
        border-color: var(--dark);
    }

    .level-dot:focus-visible {
        outline: 2px solid var(--dark);
        outline-offset: 2px;
    }

    .level-dot:hover {
        transform: scale(1.12);
    }

    .chip-remove {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 1px solid var(--gray-300);
        background: var(--white);
        color: var(--gray-700);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        font-size: 16px;
        transition: background .2s ease, color .2s ease, transform .15s ease, border-color .2s ease;
    }

    .chip-remove:hover {
        background: #FEE2E2;
        color: #DC2626;
        border-color: #FCA5A5;
        transform: scale(1.05);
    }

    .tips {
        margin-top: 20px;
        padding: 14px;
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 8px;
        color: var(--gray-700);
        font-size: 13px;
    }

    .tips strong {
        color: var(--success);
    }

    .sr-only {
        position: absolute !important;
        height: 1px;
        width: 1px;
        overflow: hidden;
        clip: rect(1px, 1px, 1px, 1px);
        white-space: nowrap;
    }

    @media (max-width: 640px) {
        .input-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Skills management JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const MAX_SKILLS = 10;
        const MIN_SKILLS = 3;
        let skills = [];

        const input = document.getElementById('skillInput');
        const levelSelect = document.getElementById('levelSelect');
        const skillCountEl = document.getElementById('skillCount');
        const progressFillEl = document.getElementById('progressFill');
        const listEl = document.getElementById('skillsList');
        const emptyEl = document.getElementById('skillsEmpty');
        const continueBtn = document.getElementById('continueBtn');
        const skillsDataInput = document.getElementById('skillsData');

        function render() {
            skillCountEl.textContent = skills.length;
            const pct = Math.min((skills.length / MAX_SKILLS) * 100, 100);
            progressFillEl.style.width = pct + '%';
            continueBtn.disabled = skills.length < MIN_SKILLS;

            if (skills.length === 0) {
                emptyEl.style.display = 'block';
                listEl.style.display = 'none';
                return;
            }

            emptyEl.style.display = 'none';
            listEl.style.display = 'flex';
            listEl.innerHTML = skills.map((skill, idx) => `
                <div class="skill-chip" data-idx="${idx}">
                    <span class="skill-name">${escapeHtml(skill.name)}</span>
                    <span class="level-badge">${getLevelLabel(skill.level)}</span>
                    <div class="skill-level">
                        ${[1,2,3,4,5].map(i => `
                            <button type="button" class="level-dot ${i <= skill.level ? 'active' : ''}" data-dot="${i}"></button>
                        `).join('')}
                    </div>
                    <button type="button" class="chip-remove">×</button>
                </div>
            `).join('');

            skillsDataInput.value = JSON.stringify(skills);
        }

        function getLevelLabel(level) {
            const labels = { 1: 'Beginner', 2: 'Junior', 3: 'Intermediate', 4: 'Advanced', 5: 'Expert' };
            return labels[level] || 'Intermediate';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function addSkill(name, level) {
            if (skills.length >= MAX_SKILLS) return;
            const exists = skills.some(s => s.name.toLowerCase() === name.toLowerCase());
            if (!exists) {
                skills.push({ name, level: parseInt(level) });
                render();
            }
        }

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const name = input.value.trim();
                if (name) {
                    addSkill(name, levelSelect.value);
                    input.value = '';
                }
            }
        });

        listEl.addEventListener('click', (e) => {
            const chip = e.target.closest('.skill-chip');
            if (!chip) return;
            const idx = parseInt(chip.dataset.idx);

            if (e.target.classList.contains('chip-remove')) {
                skills.splice(idx, 1);
                render();
            } else if (e.target.classList.contains('level-dot')) {
                const level = parseInt(e.target.dataset.dot);
                skills[idx].level = level;
                render();
            }
        });

        render();
    });
</script>
@endpush