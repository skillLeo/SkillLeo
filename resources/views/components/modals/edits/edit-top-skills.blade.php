@props(['modalSkills' => [],'username'])


<x-modals.edits.base-modal id="editTopSkillsModal" title="Edit Top Skills" size="md">
    <form id="topSkillsForm" method="POST" action="{{route('tenant.skills.update',$username)}}">
        @csrf
        @method('PUT')
        <input type="hidden" name="mode" value="skills">

        {{-- Technical Skills Section --}}
        <div class="modal-section">
            <h3 class="section-title">Technical Skills</h3>
            <p class="section-desc">Showcase your technical expertise</p>

            <div class="skill-input-section">
                <div class="input-row">
                    <div class="input-wrapper">
                        <input type="text" class="skill-input" id="topSkillInput"
                               placeholder="e.g., React, Laravel, Python..." autocomplete="off" maxlength="50">
                    </div>
                    <select id="topLevelSelect" class="level-select">
                        <option value="1">Beginner</option>
                        <option value="2" selected>Proficient</option>
                        <option value="3">Expert</option>
                    </select>
                    <button type="button" class="add-btn" id="addTopSkillBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add
                    </button>
                </div>
                <p class="input-hint">Press Enter or click Add</p>
            </div>

            {{-- Progress Indicator --}}
            <div class="progress-section">
                <div class="progress-info">
                    <span class="progress-text">Added <strong id="topSkillCount">0</strong> skills</span>
                    <span class="progress-status" id="topProgressStatus">Minimum 3 required</span>
                </div>
                <div class="progress-bar"><div class="progress-bar-fill" id="topProgressBar"></div></div>
            </div>

            {{-- Skills Display --}}
            <div class="skills-container" id="topSkillsContainer">
                <div class="empty-state" id="topEmptyState">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                        <path d="M2 17L12 22L22 17"/>
                        <path d="M2 12L12 17L22 12"/>
                    </svg>
                    <p>No skills added yet</p>
                </div>
                <div class="skills-list" id="topSkillsList"></div>
            </div>

            <input type="hidden" name="skills" id="topSkillsData">
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editTopSkillsModal')">Cancel</button>
        <button type="submit" form="topSkillsForm" class="btn-modal btn-save" id="saveTopSkillsBtn">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const topInitialSkills = @json($modalSkills ?? []);
        
        const MAX_SKILLS = 10;
        const MIN_SKILLS = 3;
        const levelLabels = { 1: 'Beginner', 2: 'Proficient', 3: 'Expert' };

        let topSkills = (topInitialSkills || []).map((s, i) => ({
            id: s.id ?? null,
            name: String(s.name || '').trim(),
            level: clampLevel(parseInt(s.level || 2)),
            position: Number.isFinite(s.position) ? s.position : i
        }));

        const topInput = document.getElementById('topSkillInput');
        const topLevelSelect = document.getElementById('topLevelSelect');
        const topAddBtn = document.getElementById('addTopSkillBtn');
        const topSkillCountEl = document.getElementById('topSkillCount');
        const topProgressBar = document.getElementById('topProgressBar');
        const topProgressStatus = document.getElementById('topProgressStatus');
        const topListEl = document.getElementById('topSkillsList');
        const topEmptyState = document.getElementById('topEmptyState');
        const topSkillsDataInput = document.getElementById('topSkillsData');
        const topSaveBtn = document.getElementById('saveTopSkillsBtn');

        function clampLevel(lvl) {
            return Math.max(1, Math.min(3, lvl || 2));
        }

        function escapeHtml(text) {
            const d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        function topRender() {
            topSkillCountEl.textContent = topSkills.length;
            const progress = Math.min((topSkills.length / MAX_SKILLS) * 100, 100);
            topProgressBar.style.width = progress + '%';

            if (topSkills.length < MIN_SKILLS) {
                topProgressStatus.textContent = `Minimum ${MIN_SKILLS} required`;
                topProgressStatus.classList.remove('success');
                topSaveBtn.disabled = true;
            } else {
                topProgressStatus.textContent = 'Looking good!';
                topProgressStatus.classList.add('success');
                topSaveBtn.disabled = false;
            }

            if (topSkills.length === 0) {
                topEmptyState.style.display = 'flex';
                topListEl.style.display = 'none';
            } else {
                topEmptyState.style.display = 'none';
                topListEl.style.display = 'grid';
                topListEl.innerHTML = topSkills.map((skill, idx) => `
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
                            <button type="button" class="remove-btn" data-action="remove" title="Remove">×</button>
                        </div>
                    </div>
                `).join('');
            }

            topSkillsDataInput.value = JSON.stringify(topSkills.map((s, idx) => ({
                id: s.id ?? null,
                name: s.name,
                level: clampLevel(s.level),
                position: idx
            })));
        }

        function addTopSkill() {
            const name = (topInput.value || '').trim();
            if (!name) {
                topInput.focus();
                return;
            }
            if (topSkills.length >= MAX_SKILLS) {
                alert(`Maximum ${MAX_SKILLS} skills allowed`);
                return;
            }
            if (topSkills.some(s => s.name.toLowerCase() === name.toLowerCase())) {
                alert('This skill is already added');
                return;
            }

            const level = clampLevel(parseInt(topLevelSelect.value));
            topSkills.push({ id: null, name, level, position: topSkills.length });
            topInput.value = '';
            topInput.focus();
            topRender();
        }

        function cycleTopLevel(idx) {
            topSkills[idx].level = topSkills[idx].level === 3 ? 1 : topSkills[idx].level + 1;
            topRender();
        }

        function removeTopSkill(idx) {
            topSkills.splice(idx, 1);
            topRender();
        }

        topAddBtn.addEventListener('click', addTopSkill);
        topInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTopSkill();
            }
        });

        topListEl.addEventListener('click', e => {
            const row = e.target.closest('.skill-item');
            if (!row) return;
            const idx = parseInt(row.dataset.idx, 10);
            const btn = e.target.closest('[data-action]');
            if (!btn) return;
            const action = btn.dataset.action;
            if (action === 'cycle') cycleTopLevel(idx);
            if (action === 'remove') removeTopSkill(idx);
        });

        topRender();
    });
</script>