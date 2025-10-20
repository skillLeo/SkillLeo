@props(['user' => null,'username'])

<x-modals.edits.base-modal id="editSkillsModal" title="Edit Skills" size="lg">
    <form id="skillsForm" method="POST" action="{{route('tenant.skills.update',$username)}}">
        @csrf
        @method('PUT')
        <input type="hidden" name="mode" value="both">

        {{-- Technical Skills Section --}}
        <div class="modal-section">
            <h3 class="section-title">Technical Skills</h3>

            <div class="skill-input-section">
                <div class="input-row">
                    <div class="input-wrapper">
                        <input type="text" class="skill-input" id="skillInput"
                               placeholder="e.g., React, Laravel, Python, Figma..." autocomplete="off" maxlength="50">
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
                <p class="input-hint">Press Enter or click Add • 3-10 skills recommended</p>
            </div>

            {{-- Progress Indicator --}}
            <div class="progress-section">
                <div class="progress-info">
                    <span class="progress-text">Added <strong id="skillCount">0</strong> of 10 skills</span>
                    <span class="progress-status" id="progressStatus">Minimum 3 required</span>
                </div>
                <div class="progress-bar"><div class="progress-bar-fill" id="progressBar"></div></div>
            </div>

            {{-- Skills Display --}}
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
        </div>

        {{-- Soft Skills Section --}}
        <div class="modal-section">
            <h3 class="section-title">Soft Skills</h3>
            <p class="section-desc">Select skills that describe how you work (max 6)</p>

            <div class="soft-skills-grid">
                @foreach($softSkillOptions as $skill)
                <label class="soft-skill-card">
                        <input type="checkbox"
                               name="soft_skills[]"
                               value="{{ $skill['value'] }}"
                               class="soft-skill-checkbox">
                        <span class="soft-skill-content">
                            <i class="fa-solid fa-{{ $skill['icon'] ?? 'sparkles' }}"></i>
                            <span class="soft-skill-label">{{ $skill['label'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>

            <div class="soft-skills-counter">
                <span id="softSkillCount">0</span> / 6 selected
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editSkillsModal')">Cancel</button>
        <button type="submit" form="skillsForm" class="btn-modal btn-save" id="saveSkillsBtn">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<style>
    /* Modal Section */
    .modal-section {
        margin-bottom: 32px;
        padding-bottom: 32px;
        border-bottom: 1px solid var(--border);
    }

    .modal-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 6px;
    }

    .section-desc {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 16px;
    }

    /* Skill Input Section */
    .skill-input-section {
        margin-bottom: 20px;
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
        background: var(--input-bg);
        color: var(--input-text);
        transition: all 0.2s ease;
    }

    .skill-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .level-select {
        height: 48px;
        min-width: 140px;
        padding: 0 40px 0 16px;
        border: 1.5px solid var(--input-border);
        border-radius: 8px;
        font-size: 15px;
        background: var(--input-bg);
        color: var(--input-text);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 20px;
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
    }

    .input-hint {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
    }

    /* Progress Section */
    .progress-section {
        margin-bottom: 20px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
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
        color: #10b981;
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
        min-height: 200px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        background: var(--apc-bg);
    }

    .empty-state {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 160px;
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
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 12px;
    }

    /* Skill Item */
    .skill-item {
        display: flex;
        align-items: center;
        padding: 12px 14px;
        background: var(--card);
        border-radius: 8px;
        transition: all 0.2s ease;
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
        gap: 4px;
        margin-left: 8px;
    }

    .skill-level-btn,
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
    }

    .skill-level-btn:hover {
        background: var(--apc-bg);
        color: var(--accent);
    }

    .remove-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    /* Soft Skills Grid */
    .soft-skills-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }

    .soft-skill-card {
        position: relative;
        display: flex;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .soft-skill-checkbox {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .soft-skill-content {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 14px;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .soft-skill-content i {
        font-size: 16px;
        color: var(--text-muted);
        flex-shrink: 0;
    }

    .soft-skill-label {
        font-size: 14px;
        color: var(--text-body);
        font-weight: 500;
    }

 

/* Checked state detail colors */
.soft-skill-checkbox:checked + .soft-skill-content i {
    color: var(--accent);
}
.soft-skill-checkbox:checked + .soft-skill-content .soft-skill-label {
    color: var(--accent);
}



    .soft-skills-counter {
        text-align: right;
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .input-row {
            grid-template-columns: 1fr;
        }

        .soft-skills-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .skills-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .soft-skills-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ======== BOOTSTRAP DATA FROM PHP ========
        window.initialSkills = @json($modalSkills ?? []);
        const preselectedSoft = @json($selectedSoft ?? []);

        // ======== CONSTANTS ========
        const MAX_SKILLS = 10;
        const MIN_SKILLS = 3;
        const MAX_SOFT = 6;
        const levelLabels = {
            1: 'Beginner',
            2: 'Proficient',
            3: 'Expert'
        };

        // ======== STATE ========
        let skills = (window.initialSkills || []).map((s, i) => ({
            id: s.id ?? null,
            name: String(s.name || '').trim(),
            level: clampLevel(parseInt(s.level || 2)),
            position: Number.isFinite(s.position) ? s.position : i
        }));

        // ======== ELEMENTS ========
        const input = document.getElementById('skillInput');
        const levelSelect = document.getElementById('levelSelect');
        const addBtn = document.getElementById('addSkillBtn');
        const skillCountEl = document.getElementById('skillCount');
        const progressBar = document.getElementById('progressBar');
        const progressStatus = document.getElementById('progressStatus');
        const listEl = document.getElementById('skillsList');
        const emptyState = document.getElementById('emptyState');
        const skillsDataInput = document.getElementById('skillsData');
        const saveBtn = document.getElementById('saveSkillsBtn');
        
        // Soft skills elements - specific to this modal only
        const softSkillCountEl = document.getElementById('softSkillCount');
        const softCheckboxes = document.querySelectorAll('#editSkillsModal .soft-skill-checkbox');

        // ======== HELPERS ========
        function clampLevel(lvl) {
            return Math.max(1, Math.min(3, lvl || 2));
        }

        function escapeHtml(text) {
            const d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        function serializePayload() {
            return skills.map((s, idx) => ({
                id: s.id ?? null,
                name: s.name,
                level: clampLevel(s.level),
                position: idx
            }));
        }

        // ======== RENDER ========
        function render() {
            // Update progress
            skillCountEl.textContent = skills.length;
            const progress = Math.min((skills.length / MAX_SKILLS) * 100, 100);
            progressBar.style.width = progress + '%';

            // Update status
            if (skills.length < MIN_SKILLS) {
                progressStatus.textContent = `Minimum ${MIN_SKILLS} required`;
                progressStatus.classList.remove('success');
                saveBtn.disabled = true;
            } else {
                progressStatus.textContent = 'Looking good!';
                progressStatus.classList.add('success');
                saveBtn.disabled = false;
            }

            // Toggle empty state vs list
            if (skills.length === 0) {
                emptyState.style.display = 'flex';
                listEl.style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                listEl.style.display = 'grid';

                listEl.innerHTML = skills.map((skill, idx) => `
                    <div class="skill-item" data-idx="${idx}" data-id="${skill.id ?? ''}">
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
            }

            // Update hidden payload
            skillsDataInput.value = JSON.stringify(serializePayload());
        }

        // ======== ACTIONS ========
        function addSkill() {
            const name = (input.value || '').trim();
            if (!name) {
                input.focus();
                return;
            }
            if (skills.length >= MAX_SKILLS) {
                alert(`Maximum ${MAX_SKILLS} skills allowed`);
                return;
            }
            if (skills.some(s => s.name.toLowerCase() === name.toLowerCase())) {
                alert('This skill is already added');
                return;
            }

            const level = clampLevel(parseInt(levelSelect.value));
            skills.push({
                id: null,
                name,
                level,
                position: skills.length
            });
            input.value = '';
            input.focus();
            render();
        }

        function cycleLevel(idx) {
            skills[idx].level = skills[idx].level === 3 ? 1 : skills[idx].level + 1;
            render();
        }

        function removeSkill(idx) {
            skills.splice(idx, 1);
            render();
        }

        // ======== EVENTS ========
        addBtn.addEventListener('click', addSkill);
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });

        listEl.addEventListener('click', e => {
            const row = e.target.closest('.skill-item');
            if (!row) return;
            const idx = parseInt(row.dataset.idx, 10);
            const btn = e.target.closest('[data-action]');
            if (!btn) return;
            const action = btn.dataset.action;
            if (action === 'cycle') cycleLevel(idx);
            if (action === 'remove') removeSkill(idx);
        });

        // ======== SOFT SKILLS ========
        function updateSoftCount() {
            const count = Array.from(softCheckboxes).filter(cb => cb.checked).length;
            softSkillCountEl.textContent = count;
            
            softCheckboxes.forEach(cb => {
                if (!cb.checked && count >= MAX_SOFT) {
                    cb.disabled = true;
                    cb.closest('.soft-skill-card').style.opacity = '0.5';
                } else {
                    cb.disabled = false;
                    cb.closest('.soft-skill-card').style.opacity = '1';
                }
            });
        }

        // Apply preselected soft skills
        softCheckboxes.forEach(cb => {
            if (preselectedSoft.includes(cb.value)) {
                cb.checked = true;
            }
            cb.addEventListener('change', updateSoftCount);
        });
        
        updateSoftCount();

        // ======== INITIAL RENDER ========
        render();
    });
</script>