@props(['modalEducations' => []])

<x-modals.edits.base-modal id="editEducationModal" title="Education" size="lg">
    <form id="educationModalForm" method="POST" action="{{ route('tenant.education.update') }}">
        @csrf
        @method('PUT')

        {{-- Education Container --}}
        <div class="edu-container" id="eduContainer">
            <div class="empty-state" id="emptyState">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                <p>No education added yet</p>
                <span>Add your highest qualification first</span>
            </div>
            <div class="edu-list" id="eduList"></div>
        </div>

        {{-- Add Button --}}
        <button type="button" class="add-btn" id="addEduBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Education
        </button>

        <input type="hidden" name="educations" id="educationData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editEducationModal')">Cancel</button>
        <button type="submit" form="educationModalForm" class="btn-modal btn-save" id="saveEducationsBtn">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<script>
// Load existing educations from server
const existingEducations = @json($modalEducations ?? []);
const INST_API = @json(route('api.institutions.search'));

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    let educations = [];
    let editingId = null;
    let counter = 0;

    const currentYear = new Date().getFullYear();
    const years = Array.from({ length: 50 }, (_, i) => currentYear - i);

    const el = {
        container: document.getElementById('eduContainer'),
        list: document.getElementById('eduList'),
        empty: document.getElementById('emptyState'),
        addBtn: document.getElementById('addEduBtn'),
        saveBtn: document.getElementById('saveEducationsBtn'),
        dataInput: document.getElementById('educationData'),
    };

    const esc = t => {
        const d = document.createElement('div');
        d.textContent = t || '';
        return d.innerHTML;
    };

    const deb = (f, d) => {
        let t;
        return (...a) => {
            clearTimeout(t);
            t = setTimeout(() => f(...a), d);
        };
    };

    // Load existing data
    function loadExisting() {
        educations = existingEducations.map((e, i) => ({
            id: e.id || ++counter,
            school: String(e.school || ''),
            institution_id: e.institution_id || null,
            degree: String(e.degree || ''),
            field: String(e.field_of_study || e.field || ''),
            startYear: e.start_year || '',
            endYear: e.end_year || '',
            current: !!e.is_current,
            db_id: e.id || null
        }));
        counter = Math.max(counter, ...educations.map(e => e.id));
    }

    function updateSaveButton() {
        const hasValid = educations.length > 0 && educations.some(e => e.school && e.degree);
        const isEditing = editingId !== null;
        if (el.saveBtn) el.saveBtn.disabled = !hasValid || isEditing;
    }

    function render() {
        if (educations.length === 0) {
            el.empty.style.display = 'flex';
            el.list.style.display = 'none';
        } else {
            el.empty.style.display = 'none';
            el.list.style.display = 'flex';
            el.list.innerHTML = educations.map(e =>
                editingId === e.id ? renderEdit(e) : renderDisplay(e)
            ).join('');
            bindTypeaheads();
        }
        el.dataInput.value = JSON.stringify(educations);
        updateSaveButton();
    }

    function renderDisplay(e) {
        const dr = getDR(e);
        const fieldText = e.field ? ` in ${esc(e.field)}` : '';

        return `
            <div class="edu-card" id="edu-${e.id}">
                <div class="edu-header">
                    <div class="edu-content">
                        <div class="edu-school">${esc(e.school || 'Institution name')}</div>
                        <div class="edu-degree">${esc(e.degree || 'Degree')}${fieldText}</div>
                        ${dr ? `<div class="edu-date">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            ${esc(dr)}
                        </div>` : ''}
                    </div>
                    <div class="edu-actions">
                        <button type="button" class="action-btn" onclick="editEdu(${e.id})" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button type="button" class="action-btn delete" onclick="removeEdu(${e.id})" title="Delete">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function renderEdit(e) {
        return `
            <div class="edu-card editing" id="edu-${e.id}">
                <div class="edu-form">
                    <div class="form-group">
                        <label class="form-label">Institution <span class="required">*</span></label>
                        <div class="ta-wrap">
                            <input type="text" class="form-input js-inst-input" data-edu-id="${e.id}"
                                   placeholder="e.g., Harvard University" value="${esc(e.school)}"/>
                            <div class="ta-menu"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Degree <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="e.g., Bachelor's, Master's"
                                   value="${esc(e.degree)}" oninput="updateEdu(${e.id}, 'degree', this.value)"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Field of Study</label>
                            <input type="text" class="form-input" placeholder="e.g., Computer Science"
                                   value="${esc(e.field)}" oninput="updateEdu(${e.id}, 'field', this.value)"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Start Year</label>
                            <select class="form-select" onchange="updateEdu(${e.id}, 'startYear', this.value)">
                                <option value="">Select year</option>
                                ${years.map(y => `<option value="${y}" ${e.startYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">End Year</label>
                            <select class="form-select" ${e.current ? 'disabled' : ''}
                                    onchange="updateEdu(${e.id}, 'endYear', this.value)">
                                <option value="">Select year</option>
                                ${years.map(y => `<option value="${y}" ${e.endYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                            </select>
                            <div class="checkbox-group">
                                <input type="checkbox" id="current-${e.id}" ${e.current ? 'checked' : ''}
                                       onchange="updateEdu(${e.id}, 'current', this.checked)"/>
                                <label for="current-${e.id}">Currently studying here</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="form-btn btn-cancel" onclick="cancelEdit(${e.id})">Cancel</button>
                        <button type="button" class="form-btn btn-save-edu" onclick="saveEdu(${e.id})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function getDR(e) {
        if (!e.startYear && !e.endYear) return '';
        const s = e.startYear || '—';
        const n = e.current ? 'Present' : (e.endYear || '—');
        return `${s} – ${n}`;
    }

    function addEducation() {
        const id = ++counter;
        educations.unshift({
            id,
            school: '',
            institution_id: null,
            degree: '',
            field: '',
            startYear: '',
            endYear: '',
            current: false,
            db_id: null
        });
        editingId = id;
        render();
    }

    window.editEdu = (id) => {
        editingId = id;
        render();
    };

    window.removeEdu = (id) => {
        if (confirm('Remove this education entry?')) {
            educations = educations.filter(e => e.id !== id);
            if (editingId === id) editingId = null;
            render();
        }
    };

    window.cancelEdit = (id) => {
        const e = educations.find(x => x.id === id);
        if (e && !e.school && !e.degree) {
            educations = educations.filter(x => x.id !== id);
        }
        editingId = null;
        render();
    };

    window.saveEdu = (id) => {
        const e = educations.find(x => x.id === id);
        if (!e || !e.school.trim() || !e.degree.trim()) {
            alert('Please fill institution and degree.');
            return;
        }
        ['school', 'degree', 'field'].forEach(k => {
            if (e[k]) e[k] = String(e[k]).trim();
        });
        editingId = null;
        render();
    };

    window.updateEdu = (id, f, v) => {
        const e = educations.find(x => x.id === id);
        if (!e) return;

        if (f === 'current') {
            e.current = !!v;
            if (e.current) e.endYear = '';
            render();
            return;
        }

        if (['startYear', 'endYear'].includes(f)) {
            e[f] = v ? Number(v) : '';
            render();
            return;
        }

        e[f] = v;
    };

    // Typeahead functionality
    function bindTypeaheads() {
        document.querySelectorAll('.js-inst-input').forEach((input) => {
            if (input.dataset.taBound === '1') return;
            input.dataset.taBound = '1';

            const eduId = Number(input.dataset.eduId);
            const wrap = input.closest('.ta-wrap');
            const menu = wrap.querySelector('.ta-menu');

            let idx = -1;
            let items = [];

            const renderMenu = (list) => {
                items = list || [];
                if (!items.length) {
                    menu.innerHTML = `<div class="ta-empty">No results found</div>`;
                    menu.style.display = 'block';
                    idx = -1;
                    return;
                }
                menu.innerHTML = items.map((x, i) => `
                    <div class="ta-item ${i === idx ? 'active' : ''}" data-index="${i}">
                        <img class="ta-logo" src="${x.logo || ''}" onerror="this.style.visibility='hidden'" alt=""/>
                        <div class="ta-info">
                            <div class="ta-title">${esc(x.name)}</div>
                            <div class="ta-sub">${esc([x.city, x.country].filter(Boolean).join(' • '))}</div>
                        </div>
                    </div>
                `).join('');
                menu.style.display = 'block';
            };

            const choose = (row) => {
                if (!row) return;
                input.value = row.name;
                updateEdu(eduId, 'school', row.name);
                const e = educations.find(x => x.id === eduId);
                if (e) e.institution_id = row.id;
                menu.style.display = 'none';
            };

            const doSearch = deb(async (q) => {
                if (!q || q.length < 2) {
                    menu.style.display = 'none';
                    return;
                }
                try {
                    const url = `${INST_API}?q=${encodeURIComponent(q)}&limit=8`;
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const json = await res.json();
                    renderMenu(json.data || []);
                } catch (e) {
                    console.warn(e);
                    menu.style.display = 'none';
                }
            }, 250);

            input.addEventListener('input', (e) => {
                updateEdu(eduId, 'school', e.target.value);
                const ex = educations.find(x => x.id === eduId);
                if (ex) ex.institution_id = null;
                doSearch(e.target.value.trim());
            });

            input.addEventListener('focus', () => {
                const v = input.value.trim();
                if (v.length >= 2) doSearch(v);
            });

            input.addEventListener('blur', () => {
                setTimeout(() => menu.style.display = 'none', 150);
            });

            input.addEventListener('keydown', (e) => {
                const open = menu.style.display !== 'none';
                if (!open) return;

                if (e.key === 'ArrowDown') {
                    idx = Math.min(idx + 1, items.length - 1);
                    renderMenu(items);
                    e.preventDefault();
                } else if (e.key === 'ArrowUp') {
                    idx = Math.max(idx - 1, 0);
                    renderMenu(items);
                    e.preventDefault();
                } else if (e.key === 'Enter') {
                    if (idx >= 0) {
                        choose(items[idx]);
                        e.preventDefault();
                    }
                } else if (e.key === 'Escape') {
                    menu.style.display = 'none';
                }
            });

            menu.addEventListener('mousedown', (e) => {
                const item = e.target.closest('.ta-item');
                if (item) {
                    const i = Number(item.dataset.index);
                    choose(items[i]);
                }
            });
        });
    }

    el.addBtn.addEventListener('click', addEducation);

    // Initialize
    loadExisting();
    render();
});
</script>

<style>
/* Container */
.edu-container {
    min-height: 200px;
    margin-bottom: 20px;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    border: 1.5px dashed var(--border);
    border-radius: 12px;
    background: var(--apc-bg);
    color: var(--text-muted);
    text-align: center;
}

.empty-state svg {
    margin-bottom: 16px;
    opacity: 0.2;
}

.empty-state p {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-body);
    margin: 0 0 4px 0;
}

.empty-state span {
    font-size: 13px;
}

/* Education List */
.edu-list {
    display: none;
    flex-direction: column;
    gap: 12px;
}

/* Education Card */
.edu-card {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    background: var(--card);
    transition: all 0.2s ease;
}

.edu-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.edu-card.editing {
    border-color: var(--accent);
    background: var(--apc-bg);
}

.edu-header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
}

.edu-content {
    flex: 1;
}

.edu-school {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 4px;
}

.edu-degree {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-body);
    margin-bottom: 8px;
}

.edu-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-muted);
}

.edu-date svg {
    width: 14px;
    height: 14px;
    opacity: 0.6;
}

.edu-actions {
    display: flex;
    gap: 6px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border: 1.5px solid var(--border);
    background: var(--card);
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    color: var(--text-muted);
}

.action-btn:hover {
    color: var(--accent);
    transform: translateY(-2px);
}

.action-btn.delete:hover {
    border-color: #dc2626;
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Edit Form */
.edu-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding-top: 8px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-body);
}

.required {
    color: #dc2626;
    margin-left: 2px;
}

.form-input,
.form-select {
    height: 48px;
    padding: 0 14px;
    border: 1.5px solid var(--input-border);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    background: var(--card);
    color: var(--input-text);
    transition: all 0.2s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 20px;
    padding-right: 40px;
    cursor: pointer;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 0 4px 0;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--accent);
}

.checkbox-group label {
    font-size: 14px;
    color: var(--text-body);
    cursor: pointer;
    user-select: none;
}

/* Typeahead */
.ta-wrap {
    position: relative;
}

.ta-menu {
    position: absolute;
    z-index: 50;
    left: 0;
    right: 0;
    top: calc(100% + 4px);
    background: var(--card);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    max-height: 280px;
    overflow-y: auto;
    display: none;
}

.ta-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    cursor: pointer;
    transition: background 0.15s ease;
}

.ta-item:hover,
.ta-item.active {
    background: var(--accent-light);
}

.ta-logo {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: #f3f4f6;
    flex-shrink: 0;
    object-fit: cover;
}

.ta-info {
    flex: 1;
    min-width: 0;
}

.ta-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 2px;
}

.ta-sub {
    font-size: 12px;
    color: var(--text-muted);
}

.ta-empty {
    padding: 16px;
    text-align: center;
    color: var(--text-muted);
    font-size: 14px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 10px;
    padding-top: 8px;
}

.form-btn {
    flex: 1;
    height: 44px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-cancel {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-body);
}

.btn-cancel:hover {
    border-color: var(--text-muted);
    background: var(--apc-bg);
}

.btn-save-edu {
    background: var(--accent);
    color: white;
}

.btn-save-edu:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}

/* Add Button */
.add-btn {
    width: 100%;
    height: 52px;
    padding: 0 24px;
    background: var(--card);
    color: var(--text-body);
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 20px;
    transition: all 0.2s ease;
}

.add-btn:hover {
    background: var(--accent-light);
    color: var(--card);
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .edu-header {
        flex-direction: column;
        gap: 12px;
    }

    .edu-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>