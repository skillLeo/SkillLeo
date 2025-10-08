@extends('layouts.onboarding')

@section('title', 'Skills & Expertise - ProMatch')

@section('card-content')

    <x-onboarding.form-header 
        step="3"
        title="Your Skills"
        subtitle="Add 3-10 skills that showcase your expertise"
    />

    <form id="skillsForm" action="{{ route('tenant.onboarding.skills.store') }}" method="POST">
        @csrf

        <!-- Skill Input Section -->
        <div class="skill-input-section">
            <div class="input-row">
                <div class="input-wrapper">
                    <input 
                        type="text" 
                        class="skill-input" 
                        id="skillInput"
                        placeholder="e.g., Web Development, UI/UX Design..."
                        autocomplete="off"
                        maxlength="50"
                    >
                </div>
                <select id="levelSelect" class="level-select">
                    <option value="1">Beginner</option>
                    <option value="2" selected>Proficient</option>
                    <option value="3">Expert</option>
                </select>
                <button type="button" class="add-btn" id="addSkillBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add
                </button>
            </div>
            <p class="input-hint">Press Enter or click Add button</p>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-section">
            <div class="progress-info">
                <span class="progress-text">Added <strong id="skillCount">0</strong> of 10 skills</span>
                <span class="progress-status" id="progressStatus">Minimum 3 required</span>
            </div>
            <div class="progress-bar">
                <div class="progress-bar-fill" id="progressBar"></div>
            </div>
        </div>

        <!-- Skills List -->
        <div class="skills-container" id="skillsContainer">
            <div class="empty-state" id="emptyState">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p>No skills added yet</p>
            </div>
            <div class="skills-list" id="skillsList"></div>
        </div>

        <input type="hidden" name="skills" id="skillsData">

        <x-onboarding.form-footer
            backUrl="{{ route('tenant.onboarding.location') }}"
        />
    </form>

@endsection

@push('styles')
<style>
    /* Input Section */
    .skill-input-section {
        margin-bottom: 28px;
    }

    .input-row {
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 12px;
        margin-bottom: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .skill-input {
        width: 100%;
        height: 48px;
        padding: 0 16px;
        border: 1.5px solid var(--input-border);
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        background: var(--card);
        color: var(--input-text);
        transition: all 0.2s ease;
    }

    .skill-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .skill-input::placeholder {
        color: var(--input-placeholder);
    }

    .level-select {
        height: 48px;
        min-width: 140px;
        padding: 0 40px 0 16px;
        border: 1.5px solid var(--input-border);
        border-radius: 8px;
        font-size: 15px;
        font-family: inherit;
        background: var(--card);
        color: var(--input-text);
        cursor: pointer;
        transition: all 0.2s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 20px;
    }

    .level-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .add-btn {
        height: 48px;
        padding: 0 24px;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .add-btn:hover {
        background: var(--accent-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }

    .add-btn:active {
        transform: translateY(0);
    }

    .add-btn svg {
        flex-shrink: 0;
    }

    .input-hint {
        font-size: 13px;
        color: var(--text-subtle);
        margin: 0;
    }

    /* Progress Section */
    .progress-section {
        margin-bottom: 28px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .progress-text {
        font-size: 14px;
        color: var(--text-body);
        font-weight: 500;
    }

    .progress-text strong {
        color: var(--accent);
        font-weight: 700;
    }

    .progress-status {
        font-size: 13px;
        color: var(--text-muted);
    }

    .progress-status.success {
        color: var(--success);
    }

    .progress-bar {
        height: 6px;
        background: var(--border);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent) 0%, #3b82f6 100%);
        transition: width 0.4s ease;
        width: 0%;
    }

    /* Skills Container */
    .skills-container {
        min-height: 240px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        background: var(--apc-bg);
        margin-bottom: 32px;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 192px;
        color: var(--text-muted);
    }

    .empty-state svg {
        margin-bottom: 16px;
        opacity: 0.2;
    }

    .empty-state p {
        font-size: 15px;
        margin: 0;
    }

    .skills-list {
        display: none;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
    }

    /* Skill Item */
    .skill-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border: 1.5px solid var(--border);
        background: var(--card);
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .skill-item:hover {
        border-color: var(--accent);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .skill-content {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .skill-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-heading);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .skill-level-badge {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .skill-level-badge.level-1 {
        background: rgba(234, 179, 8, 0.15);
        color: #ca8a04;
    }

    .skill-level-badge.level-2 {
        background: rgba(59, 130, 246, 0.15);
        color: #2563eb;
    }

    .skill-level-badge.level-3 {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }

    .skill-actions {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-left: 8px;
    }

    .skill-level-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
        padding: 0;
    }

    .skill-level-btn:hover {
        background: var(--apc-bg);
        color: var(--accent);
    }

    .remove-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 20px;
        line-height: 1;
        padding: 0;
    }

    .remove-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .input-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .level-select {
            min-width: 0;
        }

        .add-btn {
            width: 100%;
            justify-content: center;
        }

        .skills-list {
            grid-template-columns: 1fr;
        }

        .skill-item {
            padding: 12px 14px;
        }

        .skill-name {
            font-size: 14px;
        }

        .skills-container {
            padding: 20px 16px;
        }
    }

    /* Tablet */
    @media (min-width: 769px) and (max-width: 1024px) {
        .skills-list {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const MAX_SKILLS = 10;
        const MIN_SKILLS = 3;
        let skills = [];

        // DOM Elements
        const input = document.getElementById('skillInput');
        const levelSelect = document.getElementById('levelSelect');
        const addBtn = document.getElementById('addSkillBtn');
        const skillCountEl = document.getElementById('skillCount');
        const progressBar = document.getElementById('progressBar');
        const progressStatus = document.getElementById('progressStatus');
        const listEl = document.getElementById('skillsList');
        const emptyState = document.getElementById('emptyState');
        const continueBtn = document.getElementById('continueBtn');
        const skillsDataInput = document.getElementById('skillsData');

        // Level Labels
        const levelLabels = {
            1: 'Beginner',
            2: 'Proficient',
            3: 'Expert'
        };

        // Render Skills
        function render() {
            skillCountEl.textContent = skills.length;
            const progress = Math.min((skills.length / MAX_SKILLS) * 100, 100);
            progressBar.style.width = progress + '%';

            // Update status
            if (skills.length < MIN_SKILLS) {
                progressStatus.textContent = `Minimum ${MIN_SKILLS} required`;
                progressStatus.classList.remove('success');
                continueBtn.disabled = true;
            } else {
                progressStatus.textContent = 'Looking good!';
                progressStatus.classList.add('success');
                continueBtn.disabled = false;
            }

            // Show/hide empty state
            if (skills.length === 0) {
                emptyState.style.display = 'flex';
                listEl.style.display = 'none';
                return;
            }

            emptyState.style.display = 'none';
            listEl.style.display = 'grid';

            // Render skill items
            listEl.innerHTML = skills.map((skill, idx) => `
                <div class="skill-item" data-idx="${idx}">
                    <div class="skill-content">
                        <span class="skill-name">${escapeHtml(skill.name)}</span>
                        <span class="skill-level-badge level-${skill.level}">${levelLabels[skill.level]}</span>
                    </div>
                    <div class="skill-actions">
                        <button type="button" class="skill-level-btn" data-action="cycle" title="Change level">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                        </button>
                        <button type="button" class="remove-btn" data-action="remove" title="Remove skill">×</button>
                    </div>
                </div>
            `).join('');

            // Update hidden input
            skillsDataInput.value = JSON.stringify(skills);
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Add Skill
        function addSkill() {
            const name = input.value.trim();
            if (!name) {
                input.focus();
                return;
            }

            if (skills.length >= MAX_SKILLS) {
                alert(`Maximum ${MAX_SKILLS} skills allowed`);
                return;
            }

            // Check for duplicates
            const exists = skills.some(s => s.name.toLowerCase() === name.toLowerCase());
            if (exists) {
                alert('This skill has already been added');
                input.focus();
                return;
            }

            const level = parseInt(levelSelect.value);
            skills.push({ name, level });
            input.value = '';
            input.focus();
            render();
        }

        // Cycle Skill Level
        function cycleLevel(idx) {
            const currentLevel = skills[idx].level;
            skills[idx].level = currentLevel === 3 ? 1 : currentLevel + 1;
            render();
        }

        // Remove Skill
        function removeSkill(idx) {
            skills.splice(idx, 1);
            render();
        }

        // Event Listeners
        addBtn.addEventListener('click', addSkill);

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });

        listEl.addEventListener('click', (e) => {
            const item = e.target.closest('.skill-item');
            if (!item) return;

            const idx = parseInt(item.dataset.idx);
            const btn = e.target.closest('[data-action]');
            
            if (!btn) return;

            const action = btn.dataset.action;
            if (action === 'cycle') {
                cycleLevel(idx);
            } else if (action === 'remove') {
                removeSkill(idx);
            }
        });

        // Initial render
        render();
    });
</script>
@endpush