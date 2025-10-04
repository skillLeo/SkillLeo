@extends('layouts.onboarding')

@section('title', 'Skills & Expertise - ProMatch')

@section('card-content')

    <x-ui.onboarding.form-header 
        step="3"
        title="Your key skills"
        subtitle="Add 3-10 skills that best represent your expertise"
    />

    <form id="skillsForm" action="{{ route('tenant.onboarding.skills.store') }}" method="POST">
        @csrf

        <!-- Streamlined Skill Input -->
        <div class="skill-input-group">
            <div class="skill-input-wrapper">
                <input 
                    type="text" 
                    class="skill-input" 
                    id="skillInput"
                    placeholder="Type a skill and press Enter"
                    autocomplete="off"
                >
                <select id="levelSelect" class="skill-level-select">
                    <option value="1">Beginner</option>
                    <option value="2">Junior</option>
                    <option value="3" selected>Intermediate</option>
                    <option value="4">Advanced</option>
                    <option value="5">Expert</option>
                </select>
            </div>
            <div class="skill-hint">Press Enter to add • Click dots to adjust level</div>
        </div>

        <!-- Clean Progress Bar -->
        <div class="skill-progress-bar">
            <div class="progress-header">
                <span class="progress-label">Skills added</span>
                <span class="progress-count"><strong id="skillCount">0</strong> / 10</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <!-- Skills Display Area -->
        <div class="skills-display" id="skillsArea">
            <div class="skills-empty" id="skillsEmpty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" class="empty-icon">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p>Start adding your skills</p>
            </div>
            <div class="skills-grid" id="skillsList" role="list"></div>
        </div>

        <input type="hidden" name="skills" id="skillsData">

        <x-ui.onboarding.form-footer
            backUrl="{{ route('tenant.onboarding.location') }}"
        />
    </form>

@endsection

@push('styles')
    <x-ui.onboarding.styles />
    
    <!-- Skills-specific styles -->
    <style>
        /* Skill Input Group */
        .skill-input-group {
            margin-bottom: 24px;
        }

        .skill-input-wrapper {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
        }

        .skill-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid var(--input-border);
            border-radius: var(--radius);
            font-size: var(--fs-body);
            font-family: inherit;
            background: transparent;
            color: var(--input-text);
            transition: all 0.2s ease;
        }

        .skill-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-light);
        }

        .skill-input::placeholder {
            color: var(--input-placeholder);
        }

        .skill-level-select {
            width: 140px;
            padding: 12px 16px;
            border: 1px solid var(--input-border);
            border-radius: var(--radius);
            font-size: var(--fs-body);
            font-family: inherit;
            background: transparent;
            color: var(--input-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 36px;
        }

        .skill-level-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-light);
        }

        .skill-hint {
            font-size: var(--fs-micro);
            color: var(--text-subtle);
        }

        /* Progress Bar */
        .skill-progress-bar {
            margin-bottom: 28px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .progress-label {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
            font-weight: var(--fw-medium);
        }

        .progress-count {
            font-size: var(--fs-subtle);
            color: var(--text-body);
        }

        .progress-count strong {
            color: var(--accent);
            font-weight: var(--fw-bold);
        }

        .progress-track {
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--accent);
            transition: width 0.3s ease;
            width: 0%;
        }

        /* Skills Display */
        .skills-display {
            min-height: 200px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            background: var(--apc-bg);
            margin-bottom: 32px;
        }

        .skills-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 150px;
            color: var(--text-muted);
        }

        .skills-empty .empty-icon {
            margin-bottom: 12px;
            opacity: 0.3;
        }

        .skills-empty p {
            font-size: var(--fs-body);
            margin: 0;
        }

        .skills-grid {
            display: none;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Skill Chip */
        .skill-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border: 1px solid var(--border);
            background: var(--card);
            border-radius: 24px;
            transition: all 0.2s ease;
        }

        .skill-chip:hover {
            border-color: var(--accent);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .skill-name {
            font-size: var(--fs-body);
            font-weight: var(--fw-semibold);
            color: var(--text-heading);
        }

        .skill-badge {
            font-size: var(--fs-micro);
            padding: 3px 8px;
            border-radius: 12px;
            background: var(--accent-light);
            color: var(--accent);
            font-weight: var(--fw-medium);
        }

        /* Level Dots */
        .skill-level {
            display: flex;
            gap: 3px;
            align-items: center;
            padding: 0 4px;
        }

        .level-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--card);
            cursor: pointer;
            transition: all 0.15s ease;
            padding: 0;
        }

        .level-dot.active {
            background: var(--accent);
            border-color: var(--accent);
        }

        .level-dot:hover {
            transform: scale(1.2);
        }

        /* Remove Button */
        .chip-remove {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            transition: all 0.2s ease;
            padding: 0;
        }

        .chip-remove:hover {
            background: var(--error);
            color: var(--btn-text-primary);
            transform: scale(1.1);
        }

        /* Mobile */
        @media (max-width: 768px) {
            .skill-input-wrapper {
                flex-direction: column;
            }

            .skill-level-select {
                width: 100%;
            }

            .skills-display {
                padding: 20px;
            }

            .skill-chip {
                width: 100%;
                justify-content: space-between;
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
                emptyEl.style.display = 'flex';
                listEl.style.display = 'none';
                return;
            }

            emptyEl.style.display = 'none';
            listEl.style.display = 'flex';
            listEl.innerHTML = skills.map((skill, idx) => `
                <div class="skill-chip" data-idx="${idx}">
                    <span class="skill-name">${escapeHtml(skill.name)}</span>
                    <span class="skill-badge">${getLevelLabel(skill.level)}</span>
                    <div class="skill-level">
                        ${[1,2,3,4,5].map(i => `
                            <button type="button" class="level-dot ${i <= skill.level ? 'active' : ''}" data-dot="${i}" aria-label="Level ${i}"></button>
                        `).join('')}
                    </div>
                    <button type="button" class="chip-remove" aria-label="Remove skill">×</button>
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
            if (skills.length >= MAX_SKILLS) {
                input.value = '';
                return;
            }
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