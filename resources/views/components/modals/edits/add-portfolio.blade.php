{{-- ============================================ --}}
{{-- 1. ADD PORTFOLIO MODAL --}}
{{-- File: resources/views/components/modals/edits/add-portfolio.blade.php --}}
{{-- ============================================ --}}

@props(['userSkills' => [],'username'])

<x-modals.edits.base-modal id="addPortfolioModal" title="Add New Project" size="lg">
    <form id="addPortfolioForm" method="POST" action="{{ route('tenant.portfolio.update',$username) }}">
        @csrf
        @method('PUT')

        <div class="pf-edit-card">
            <div class="pf-edit-body">
                <div class="pf-form-grid">
                    <div class="pf-form-col">
                        <div class="pf-field">
                            <label class="pf-label">Project Title <span class="pf-required">*</span></label>
                            <input id="add-title" type="text" class="pf-input" maxlength="80"
                                   placeholder="Enter project title"
                                   oninput="updateAddField('title', this.value)"/>
                            <div class="pf-field-info">
                                <span class="pf-hint-inline">Keep it crisp—what & impact.</span>
                                <span class="pf-count" id="add-ct-title">0/80</span>
                            </div>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Description <span class="pf-required">*</span></label>
                            <textarea id="add-description" class="pf-textarea" maxlength="280" rows="4"
                                      placeholder="Describe the challenge → solution → outcome..."
                                      oninput="updateAddField('description', this.value)"></textarea>
                            <div class="pf-field-info">
                                <span class="pf-hint">Tip: quantify the outcome (e.g., +32% conversion)</span>
                                <span class="pf-count" id="add-ct-desc">0/280</span>
                            </div>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Project Link</label>
                            <input id="add-link" type="url" class="pf-input"
                                   placeholder="https://example.com"
                                   oninput="updateAddField('link', this.value)"/>
                        </div>

                        <div class="pf-field pf-skills-dropdown">
                            <label class="pf-label">Technologies & Skills</label>
                            <button type="button" class="pf-skills-dropdown-btn" onclick="toggleAddSkillsDropdown()">
                                Choose skills
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="pf-skills-dropdown-menu" id="add-skills-menu" style="display:none;">
                                @foreach($userSkills as $skill)
                                <label class="pf-skill-option">
                                    <input type="checkbox" value="{{ $skill->id }}" onclick="toggleAddSkill({{ $skill->id }})"/>
                                    <span>{{ $skill->name }}</span>
                                </label>
                                @endforeach
                            </div>
                            <div class="pf-skills-selected" id="add-skills-selected">
                                <div class="pf-no-skills-msg">No skills selected</div>
                            </div>
                        </div>
                    </div>

                    <div class="pf-form-col">
                        <div class="pf-field">
                            <label class="pf-label">Cover Image</label>
                            <div class="pf-img-upload">
                                <div class="pf-img-empty" id="add-img-empty" onclick="document.getElementById('add-img-input').click()">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                        <path d="M21 15l-5-5L5 21"/>
                                    </svg>
                                    <p>Click to upload</p><span>PNG, JPG up to 10MB</span>
                                </div>
                                <div class="pf-img-preview" id="add-img-preview" style="display:none;">
                                    <img id="add-img-display" src="" alt="Cover">
                                    <div class="pf-img-overlay">
                                        <button type="button" class="pf-img-change" onclick="document.getElementById('add-img-input').click()">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg> Change
                                        </button>
                                        <button type="button" class="pf-img-remove" onclick="removeAddImage()">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg> Remove
                                        </button>
                                    </div>
                                </div>
                                <input type="file" id="add-img-input" accept="image/*" hidden onchange="handleAddImage(this)"/>
                            </div>
                            <div class="pf-field-info"><span class="pf-hint">Recommended: 1200×900px (4:3)</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="portfolios" id="addPortfolioData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-secondary" onclick="closeAddModal()">Cancel</button>
        <button type="submit" form="addPortfolioForm" class="btn-modal btn-primary" id="saveAddBtn" disabled>
            Add Project
        </button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<script>
(function initAddPortfolioModal(){
    'use strict';

    const userSkillsData = @json($userSkills ?? []);
    const TITLE_MAX = 80;
    const DESC_MAX = 280;
    const IMG_MAX_W = 1200;
    const IMG_MAX_H = 900;
    const IMG_QUALITY = 0.85;

    let newProject = {
        title: '',
        description: '',
        link: '',
        image: '',
        skill_ids: []
    };

    const esc = (s) => String(s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    window.updateAddField = function(field, value) {
        if (field === 'title') {
            newProject.title = String(value).slice(0, TITLE_MAX);
            document.getElementById('add-ct-title').textContent = `${newProject.title.length}/${TITLE_MAX}`;
        } else if (field === 'description') {
            newProject.description = String(value).slice(0, DESC_MAX);
            document.getElementById('add-ct-desc').textContent = `${newProject.description.length}/${DESC_MAX}`;
        } else if (field === 'link') {
            newProject.link = String(value).trim();
        }
        updateAddButton();
    };

    window.toggleAddSkillsDropdown = function() {
        const menu = document.getElementById('add-skills-menu');
        menu.style.display = (menu.style.display === 'none' || !menu.style.display) ? 'block' : 'none';
    };

    window.toggleAddSkill = function(skillId) {
        const idx = newProject.skill_ids.indexOf(skillId);
        if (idx > -1) {
            newProject.skill_ids.splice(idx, 1);
        } else {
            newProject.skill_ids.push(skillId);
        }
        renderAddSkills();
    };

    function renderAddSkills() {
        const container = document.getElementById('add-skills-selected');
        const selectedSkills = newProject.skill_ids
            .map(id => userSkillsData.find(s => s.id === id))
            .filter(Boolean);

        if (selectedSkills.length === 0) {
            container.innerHTML = '<div class="pf-no-skills-msg">No skills selected</div>';
        } else {
            container.innerHTML = selectedSkills.map(s => `
                <span class="pf-skill-tag">${esc(s.name)}
                    <button type="button" onclick="toggleAddSkill(${s.id})">×</button>
                </span>
            `).join('');
        }
    }

    window.handleAddImage = async function(input) {
        const file = input.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        
        try {
            const dataUrl = await compressImage(file);
            newProject.image = dataUrl;
            
            document.getElementById('add-img-display').src = dataUrl;
            document.getElementById('add-img-empty').style.display = 'none';
            document.getElementById('add-img-preview').style.display = 'block';
        } catch (e) {
            console.error(e);
        }
        input.value = '';
    };

    window.removeAddImage = function() {
        newProject.image = '';
        document.getElementById('add-img-empty').style.display = 'flex';
        document.getElementById('add-img-preview').style.display = 'none';
    };

    async function compressImage(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ratio = Math.min(IMG_MAX_W / img.width, IMG_MAX_H / img.height);
                    canvas.width = Math.max(1, Math.round(img.width * ratio));
                    canvas.height = Math.max(1, Math.round(img.height * ratio));
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    resolve(canvas.toDataURL('image/jpeg', IMG_QUALITY));
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    function updateAddButton() {
        const isValid = newProject.title.trim() && newProject.description.trim();
        document.getElementById('saveAddBtn').disabled = !isValid;
    }

    window.closeAddModal = function() {
        newProject = { title: '', description: '', link: '', image: '', skill_ids: [] };
        document.getElementById('add-title').value = '';
        document.getElementById('add-description').value = '';
        document.getElementById('add-link').value = '';
        document.getElementById('add-ct-title').textContent = '0/80';
        document.getElementById('add-ct-desc').textContent = '0/280';
        document.getElementById('add-img-empty').style.display = 'flex';
        document.getElementById('add-img-preview').style.display = 'none';
        document.querySelectorAll('#add-skills-menu input[type="checkbox"]').forEach(cb => cb.checked = false);
        renderAddSkills();
        updateAddButton();
        closeModal('addPortfolioModal');
    };

    document.getElementById('addPortfolioForm').addEventListener('submit', function(e) {
        if (!newProject.title.trim() || !newProject.description.trim()) {
            e.preventDefault();
            alert('Title and description are required.');
            return;
        }

        const dataToSave = [{
            db_id: null,
            title: newProject.title,
            description: newProject.description,
            link: newProject.link,
            image: newProject.image,
            image_path: '',
            image_disk: 'public',
            skill_ids: newProject.skill_ids
        }];

        document.getElementById('addPortfolioData').value = JSON.stringify(dataToSave);
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.pf-skills-dropdown') || !e.target.closest('#addPortfolioModal')) {
            const menu = document.getElementById('add-skills-menu');
            if (menu) menu.style.display = 'none';
        }
    });
})();
</script>

<style>
    /* ============================================
       PORTFOLIO MODAL - WITH DRAG & DROP SORTING
       ============================================ */

    /* Sort Controls */
    .pf-sort-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        margin-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .pf-sort-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pf-sort-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
    }

    .pf-sort-tabs {
        display: flex;
        gap: 6px;
        background: var(--apc-bg);
        padding: 4px;
        border-radius: 8px;
    }

    .pf-sort-tab {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: transparent;
        border: none;
        border-radius: 6px;
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-body);
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .pf-sort-tab:hover {
        background: var(--card);
        color: var(--text-heading);
    }

    .pf-sort-tab.active {
        background: var(--card);
        color: var(--accent);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .pf-sort-tab svg {
        flex-shrink: 0;
    }

    .pf-sort-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
        font-style: italic;
    }

    .pf-sort-hint svg {
        flex-shrink: 0;
    }

    /* List Container */
    .pf-list {
        display: none;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    /* Empty State */
    .pf-empty {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 64px 24px;
        text-align: center;
        color: var(--text-muted);
    }

    .pf-empty svg {
        margin-bottom: 20px;
        opacity: 0.2;
    }

    .pf-empty p {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 6px 0;
    }

    .pf-empty span {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    /* ==================== PREVIEW CARD WITH DRAG HANDLE ==================== */
    .pf-preview {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: all 0.2s ease;
        position: relative;
    }

    .pf-preview:hover {
        border-color: var(--accent);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    }

    /* Drag Handle */
    .pf-preview.draggable {
        cursor: move;
        padding-left: 48px;
    }

    .pf-drag-handle {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        cursor: grab;
        transition: all 0.2s ease;
    }

    .pf-drag-handle:hover {
        color: var(--accent);
    }

    .pf-drag-handle:active {
        cursor: grabbing;
    }

    /* Dragging State */
    .pf-preview.dragging {
        opacity: 0.5;
        border: 2px dashed var(--accent);
    }

    .pf-preview-main {
        flex: 1;
        display: flex;
        gap: 14px;
        min-width: 0;
    }

    .pf-preview-img,
    .pf-preview-img-empty {
        flex-shrink: 0;
        width: 72px;
        height: 72px;
        border-radius: 8px;
        overflow: hidden;
        background: var(--apc-bg);
        border: 1px solid var(--border);
    }

    .pf-preview-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-preview-img-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
    }

    .pf-preview-content {
        flex: 1;
        min-width: 0;
    }

    .pf-preview-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
    }

    .pf-preview-title {
        font-size: var(--fs-title);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
        line-height: var(--lh-tight);
    }

    .pf-preview-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .pf-mini-skill {
        font-size: var(--fs-micro);
        font-weight: var(--fw-medium);
        color: var(--text-body);
        background: var(--apc-bg);
        padding: 2px 8px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .pf-preview-desc {
        font-size: var(--fs-subtle);
        color: var(--text-body);
        line-height: var(--lh-relaxed);
        margin: 0 0 8px 0;
    }

    .pf-preview-link {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .pf-preview-link svg {
        flex-shrink: 0;
    }

    .pf-preview-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    .pf-action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        background: var(--card);
        border-radius: 8px;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .pf-action-btn:hover {
        transform: translateY(-1px);
    }

    .pf-action-btn.edit:hover {
        border-color: var(--accent);
        background: var(--accent-light);
        color: var(--accent);
    }

    .pf-action-btn.delete:hover {
        border-color: #dc2626;
        background: rgba(220, 38, 38, 0.08);
        color: #dc2626;
    }

    /* ==================== EDIT CARD ==================== */
    .pf-edit-card {
        background: var(--card);
        border: 2px solid var(--accent);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .pf-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        background: var(--apc-bg);
        border-bottom: 1px solid var(--border);
    }

    .pf-edit-header h4 {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
    }

    .pf-close-edit {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 6px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-close-edit:hover {
        background: var(--card);
        color: var(--text-heading);
    }

    .pf-edit-body {
        padding: 24px;
    }

    .pf-form-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
    }

    .pf-form-col {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Form Fields */
    .pf-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .pf-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pf-hint-inline {
        font-size: var(--fs-micro);
        font-weight: var(--fw-normal);
        color: var(--text-muted);
    }

    .pf-required {
        color: #dc2626;
        font-size: var(--fs-body);
    }

    .pf-input,
    .pf-select,
    .pf-textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--input-border);
        border-radius: 8px;
        font-size: var(--fs-body);
        font-family: inherit;
        background: var(--input-bg);
        color: var(--input-text);
        transition: all 0.2s ease;
    }

    .pf-input:focus,
    .pf-select:focus,
    .pf-textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .pf-input::placeholder,
    .pf-textarea::placeholder {
        color: var(--input-placeholder);
    }

    .pf-textarea {
        resize: vertical;
        line-height: var(--lh-relaxed);
        min-height: 100px;
    }

    .pf-field-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .pf-hint {
        font-size: var(--fs-micro);
        color: var(--text-muted);
    }

    .pf-count {
        font-size: var(--fs-micro);
        font-weight: var(--fw-medium);
        color: var(--text-muted);
        white-space: nowrap;
    }

    /* Skills Dropdown */
    .pf-skills-dropdown {
        position: relative;
    }

    .pf-skills-dropdown-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 12px 14px;
        background: var(--input-bg);
        border: 1.5px solid var(--input-border);
        border-radius: 8px;
        font-size: var(--fs-body);
        color: var(--input-text);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-skills-dropdown-btn:hover {
        border-color: var(--accent);
    }

    .pf-skills-dropdown-menu {
        position: static;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        max-height: 240px;
        overflow-y: auto;
        background: var(--card);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .pf-skill-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-skill-option:hover {
        background: var(--apc-bg);
    }

    .pf-skill-option input[type="checkbox"] {
        cursor: pointer;
    }

    .pf-skills-selected {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .pf-skill-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 8px 6px 12px;
        background: var(--accent-light);
        border: 1px solid var(--accent);
        border-radius: 6px;
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--accent);
    }

    .pf-skill-tag button {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 4px;
        color: currentColor;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-skill-tag button:hover {
        background: rgba(220, 38, 38, 0.15);
        color: #dc2626;
    }

    .pf-no-skills-msg {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 14px;
        background: var(--apc-bg);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        color: var(--text-muted);
        font-size: var(--fs-body);
    }

    /* Image Upload */
    .pf-img-upload {
        position: relative;
        width: 100%;
        aspect-ratio: 4/3;
        border-radius: 10px;
        overflow: hidden;
        background: var(--apc-bg);
        border: 2px dashed var(--border);
    }

    .pf-img-preview {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .pf-img-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-img-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .pf-img-preview:hover .pf-img-overlay {
        opacity: 1;
    }

    .pf-img-change,
    .pf-img-remove {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: var(--card);
        color: var(--text-heading);
        border: none;
        border-radius: 8px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-img-change:hover {
        background: var(--accent);
        color: var(--text-white);
    }

    .pf-img-remove {
        background: rgba(220, 38, 38, 0.9);
        color: white;
    }

    .pf-img-remove:hover {
        background: #dc2626;
    }

    .pf-img-empty {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-img-empty:hover {
        border-color: var(--accent);
        background: var(--accent-light);
    }

    .pf-img-empty svg {
        margin-bottom: 12px;
        color: var(--text-muted);
    }

    .pf-img-empty p {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .pf-img-empty span {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Edit Footer */
    .pf-edit-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 24px;
        background: var(--apc-bg);
        border-top: 1px solid var(--border);
    }

    .pf-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 44px;
        padding: 0 24px;
        border: none;
        border-radius: 8px;
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .pf-btn.primary {
        background: var(--accent);
        color: var(--text-white);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .pf-btn.primary:hover {
        background: var(--accent-dark);
        box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
        transform: translateY(-1px);
    }

    .pf-btn.secondary {
        background: var(--card);
        color: var(--text-body);
        border: 1.5px solid var(--border);
    }

    .pf-btn.secondary:hover {
        background: var(--apc-bg);
        border-color: var(--text-muted);
    }

    /* Add Button */
    .pf-add-btn {
        width: 100%;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: var(--card);
        color: var(--text-heading);
        border: 2px dashed var(--border);
        border-radius: var(--radius);
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-add-btn:hover {
        border-color: var(--accent);
        border-style: solid;
        color: var(--accent);
        background: var(--accent-light);
    }

    /* Modal Footer Buttons */
    .btn-modal {
        height: 44px;
        padding: 0 28px;
        border: none;
        border-radius: 8px;
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modal.btn-primary {
        background: var(--accent);
        color: var(--text-white);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .btn-modal.btn-primary:hover {
        background: var(--accent-dark);
        box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
        transform: translateY(-1px);
    }

    .btn-modal.btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-modal.btn-secondary {
        background: transparent;
        border: 1.5px solid var(--border);
        color: var(--text-body);
    }

    .btn-modal.btn-secondary:hover {
        background: var(--apc-bg);
        border-color: var(--text-muted);
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
        .pf-sort-controls {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .pf-sort-left {
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
        }

        .pf-sort-tabs {
            width: 100%;
        }

        .pf-sort-tab {
            flex: 1;
            justify-content: center;
        }

        .pf-form-grid {
            grid-template-columns: 1fr;
        }

        .pf-edit-header,
        .pf-edit-body,
        .pf-edit-footer {
            padding: 16px;
        }

        .pf-preview {
            flex-direction: column;
        }

        .pf-preview.draggable {
            padding-left: 16px;
            padding-top: 48px;
        }

        .pf-drag-handle {
            left: 50%;
            top: 16px;
            transform: translateX(-50%);
        }

        .pf-preview-main {
            width: 100%;
        }

        .pf-preview-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .pf-edit-footer {
            flex-direction: column-reverse;
        }

        .pf-btn {
            width: 100%;
        }
    }
</style>