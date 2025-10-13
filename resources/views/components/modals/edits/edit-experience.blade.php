@props(['modalExperiences' => []])

<x-modals.edits.base-modal id="editExperienceModal" title="Work Experience" size="lg">
    <form id="experienceModalForm" method="POST" action="{{ route('tenant.experience.update') }}">
        @csrf
        @method('PUT')

        {{-- Experience Container --}}
        <div class="exp-container" id="expContainer">
            <div class="empty-state" id="emptyState">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                <p>No experience added yet</p>
                <span>Start with your most recent role</span>
            </div>
            <div class="exp-list" id="expList"></div>
        </div>

        {{-- Add Button --}}
        <button type="button" class="add-btn" id="addExpBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Experience
        </button>

        <input type="hidden" name="experiences" id="experienceData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editExperienceModal')">Cancel</button>
        <button type="submit" form="experienceModalForm" class="btn-modal btn-save" id="saveExperiencesBtn">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<script>
// Load existing experiences from server
const existingExperiences = @json($modalExperiences ?? []);
const API = @json(route('api.companies.search'));

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    let experiences = [];
    let editingId = null;
    let counter = 0;

    const M = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const Y = Array.from({ length: 40 }, (_, i) => new Date().getFullYear() - i);
    const L = { 1: 'Beginner', 2: 'Proficient', 3: 'Expert' };

    const el = {
        container: document.getElementById('expContainer'),
        list: document.getElementById('expList'),
        empty: document.getElementById('emptyState'),
        addBtn: document.getElementById('addExpBtn'),
        saveBtn: document.getElementById('saveExperiencesBtn'),
        dataInput: document.getElementById('experienceData'),
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
        experiences = existingExperiences.map((e, i) => ({
            id: e.id || ++counter,
            company: String(e.company || ''),
            company_id: e.company_id || null,
            title: String(e.title || ''),
            startMonth: e.start_month || '',
            startYear: e.start_year || '',
            endMonth: e.end_month || '',
            endYear: e.end_year || '',
            current: !!e.is_current,
            locationCity: String(e.location_city || ''),
            locationCountry: String(e.location_country || ''),
            description: String(e.description || ''),
            skills: Array.isArray(e.skills) ? e.skills.map(s => ({
                name: String(s.name || ''),
                level: parseInt(s.level) || 2
            })) : [],
            db_id: e.id || null // Database ID
        }));
        counter = Math.max(counter, ...experiences.map(e => e.id));
    }

    function isEndBeforeStart(e) {
        if (!e.startYear || !e.startMonth || !e.endYear || !e.endMonth) return false;
        const s = new Date(Number(e.startYear), Number(e.startMonth) - 1, 1);
        const n = new Date(Number(e.endYear), Number(e.endMonth) - 1, 1);
        return n < s;
    }

    const monthOpts = (selected, disableBefore = 0) =>
        `<option value="">Month</option>` + M.map((m, i) => {
            const v = i + 1;
            const dis = disableBefore && v < disableBefore ? 'disabled' : '';
            return `<option value="${v}" ${String(selected) === String(v) ? 'selected' : ''} ${dis}>${m}</option>`;
        }).join('');

    const yearOpts = (selected, minYear = null) =>
        `<option value="">Year</option>` + Y.map(y =>
            `<option value="${y}" ${String(selected) === String(y) ? 'selected' : ''} ${minYear && y < minYear ? 'disabled' : ''}>${y}</option>`
        ).join('');

    function updateSaveButton() {
        const hasValidExp = experiences.length > 0 && experiences.some(e => e.company && e.title);
        const isEditing = editingId !== null;
        if (el.saveBtn) {
            el.saveBtn.disabled = !hasValidExp || isEditing;
        }
    }

    function render() {
        if (experiences.length === 0) {
            el.empty.style.display = 'flex';
            el.list.style.display = 'none';
        } else {
            el.empty.style.display = 'none';
            el.list.style.display = 'flex';
            el.list.innerHTML = experiences.map(e =>
                editingId === e.id ? renderEdit(e) : renderDisplay(e)
            ).join('');
            bindTypeaheads();
            bindSkills();
        }
        el.dataInput.value = JSON.stringify(experiences);
        updateSaveButton();
    }

    function renderDisplay(e) {
        const dr = getDR(e);
        const loc = [e.locationCity, e.locationCountry].filter(Boolean).join(', ');
        const sk = e.skills?.length ?
            `<div class="skill-list" style="margin-top:16px;">${e.skills.map(s => `<span class="chip l${s.level}">${esc(s.name)} • ${L[s.level]}</span>`).join('')}</div>` :
            '';

        return `
            <div class="exp-card" id="exp-${e.id}">
                <div class="exp-header">
                    <div class="exp-content">
                        <div class="exp-company">${esc(e.company || 'Company name')}</div>
                        <div class="exp-title">${esc(e.title || 'Job title')}</div>
                        <div class="exp-meta">
                            ${dr ? `<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>${esc(dr)}</span>` : ''}
                            ${loc ? `<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>${esc(loc)}</span>` : ''}
                        </div>
                        ${e.description ? `<div class="exp-desc">${esc(e.description)}</div>` : ''}
                        ${sk}
                    </div>
                    <div class="exp-actions">
                        <button type="button" class="action-btn" onclick="editExp(${e.id})" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button type="button" class="action-btn delete" onclick="removeExp(${e.id})" title="Delete">
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
        const endYearMin = e.startYear ? Number(e.startYear) : null;
        const endMonthDisableBefore = (e.startYear && e.endYear && String(e.endYear) === String(e.startYear)) ? Number(e.startMonth) : 0;
        const invalidRange = !e.current && isEndBeforeStart(e);
        const invalidHint = invalidRange ? `<div class="hint" style="color:#ef4444;margin-top:6px;">End date cannot be before start date</div>` : '';

        return `
            <div class="exp-card editing" id="exp-${e.id}">
                <div class="exp-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Company <span class="required">*</span></label>
                            <div class="ta-wrap">
                                <input type="text" class="form-input js-comp-input" data-exp-id="${e.id}"
                                       placeholder="e.g., Google" value="${esc(e.company)}"/>
                                <div class="ta-menu"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Job Title <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="e.g., Senior Developer"
                                   value="${esc(e.title)}" oninput="updateExp(${e.id}, 'title', this.value)"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <div class="date-row">
                                <select class="form-select" onchange="updateExp(${e.id}, 'startMonth', this.value)">
                                    ${monthOpts(e.startMonth)}
                                </select>
                                <select class="form-select" onchange="updateExp(${e.id}, 'startYear', this.value)">
                                    ${yearOpts(e.startYear)}
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <div class="skill-list" style="${e.current ? '' : 'display:none'}; margin-bottom:8px;">
                                <span class="chip l2" title="Displayed on your profile">Present</span>
                            </div>
                            <div class="date-row" style="${e.current ? 'display:none' : ''}">
                                <select class="form-select" onchange="updateExp(${e.id}, 'endMonth', this.value)">
                                    ${monthOpts(e.endMonth, endMonthDisableBefore)}
                                </select>
                                <select class="form-select" onchange="updateExp(${e.id}, 'endYear', this.value)">
                                    ${yearOpts(e.endYear, endYearMin)}
                                </select>
                            </div>
                            ${invalidHint}
                            <div class="checkbox-group">
                                <input type="checkbox" id="current-${e.id}" ${e.current ? 'checked' : ''}
                                       onchange="updateExp(${e.id}, 'current', this.checked)"/>
                                <label for="current-${e.id}">I currently work here</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <div class="date-row">
                                <input type="text" class="form-input" placeholder="City"
                                       value="${esc(e.locationCity)}" oninput="updateExp(${e.id}, 'locationCity', this.value)"/>
                                <input type="text" class="form-input" placeholder="Country"
                                       value="${esc(e.locationCountry)}" oninput="updateExp(${e.id}, 'locationCountry', this.value)"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-textarea" placeholder="Key responsibilities and achievements..."
                                      oninput="updateExp(${e.id}, 'description', this.value)">${esc(e.description)}</textarea>
                        </div>
                    </div>

                    <div class="skills-section">
                        <div class="skills-header">
                            <div class="skills-label">Role-specific skills</div>
                            <div class="skills-hint">Add 3-8 skills • Click ↻ to change level</div>
                        </div>
                        <div class="skill-row">
                            <input type="text" class="form-input" id="si${e.id}" placeholder="e.g., React, Python"/>
                            <select class="form-select" id="sl${e.id}" style="min-width:130px">
                                <option value="1">Beginner</option>
                                <option value="2" selected>Proficient</option>
                                <option value="3">Expert</option>
                            </select>
                            <button type="button" class="skill-add-btn" onclick="addSkill(${e.id})">Add</button>
                        </div>
                        <div class="skill-list" id="skl${e.id}">${(e.skills || []).map((s, i) => renderChip(e.id, i, s)).join('')}</div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="form-btn btn-cancel" onclick="cancelEdit(${e.id})">Cancel</button>
                        <button type="button" class="form-btn btn-save-exp" onclick="saveExp(${e.id})">
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

    function renderChip(id, i, s) {
        return `<span class="chip l${s.level}">${esc(s.name)} • ${L[s.level]}<span class="chip-btns"><button type="button" class="chip-btn" onclick="cycleSkill(${id}, ${i})">↻</button><button type="button" class="chip-btn" onclick="removeSkill(${id}, ${i})">×</button></span></span>`;
    }

    function getDR(e) {
        if (!e.startMonth || !e.startYear) return '';
        const s = `${M[e.startMonth - 1]} ${e.startYear}`;
        const n = e.current ? 'Present' : (e.endMonth && e.endYear ? `${M[e.endMonth - 1]} ${e.endYear}` : '');
        return n ? `${s} — ${n}` : s;
    }

    function addExperience() {
        const id = ++counter;
        experiences.unshift({
            id,
            company: '',
            company_id: null,
            title: '',
            startMonth: '',
            startYear: '',
            endMonth: '',
            endYear: '',
            current: false,
            locationCity: '',
            locationCountry: '',
            description: '',
            skills: [],
            db_id: null
        });
        editingId = id;
        render();
    }

    window.editExp = (id) => {
        editingId = id;
        render();
    };

    window.removeExp = (id) => {
        if (confirm('Remove this experience?')) {
            experiences = experiences.filter(e => e.id !== id);
            if (editingId === id) editingId = null;
            render();
        }
    };

    window.cancelEdit = (id) => {
        const e = experiences.find(x => x.id === id);
        if (e && !e.company && !e.title) {
            experiences = experiences.filter(x => x.id !== id);
        }
        editingId = null;
        render();
    };

    window.saveExp = (id) => {
        const e = experiences.find(x => x.id === id);
        if (!e || !e.company.trim() || !e.title.trim()) {
            alert('Please fill company and job title.');
            return;
        }
        if (!e.current && isEndBeforeStart(e)) {
            alert('Please fix the date range: End date cannot be before start date.');
            return;
        }
        ['company', 'title', 'locationCity', 'locationCountry', 'description'].forEach(k => {
            if (e[k]) e[k] = String(e[k]).trim();
        });
        editingId = null;
        render();
    };

    window.updateExp = (id, f, v) => {
        const e = experiences.find(x => x.id === id);
        if (!e) return;

        if (f === 'current') {
            e.current = !!v;
            if (e.current) {
                e.endMonth = '';
                e.endYear = '';
            }
            render();
            return;
        }

        if (['startMonth', 'startYear', 'endMonth', 'endYear'].includes(f)) {
            e[f] = v ? Number(v) : '';
            if (!e.current && isEndBeforeStart(e)) {
                e.endMonth = '';
                e.endYear = '';
            }
            render();
            return;
        }

        e[f] = v;
    };

    // Skills management
    function bindSkills() {
        experiences.forEach(e => {
            const inp = document.getElementById(`si${e.id}`);
            if (inp && !inp.dataset.b) {
                inp.dataset.b = '1';
                inp.addEventListener('keydown', ev => {
                    if (ev.key === 'Enter') {
                        ev.preventDefault();
                        addSkill(e.id);
                    }
                });
            }
        });
    }

    window.addSkill = (id) => {
        const e = experiences.find(x => x.id === id);
        if (!e) return;
        e.skills = e.skills || [];
        if (e.skills.length >= 8) {
            alert('Max 8 skills');
            return;
        }
        const inp = document.getElementById(`si${id}`);
        const sel = document.getElementById(`sl${id}`);
        const name = (inp?.value || '').trim();
        const level = parseInt(sel?.value || '2', 10);

        if (!name) {
            inp?.focus();
            return;
        }
        if (e.skills.some(s => s.name.toLowerCase() === name.toLowerCase())) {
            alert('Skill already added');
            inp.value = '';
            inp.focus();
            return;
        }
        e.skills.push({ name, level });
        inp.value = '';
        const lst = document.getElementById(`skl${id}`);
        if (lst) lst.innerHTML = e.skills.map((s, i) => renderChip(id, i, s)).join('');
    };

    window.cycleSkill = (id, i) => {
        const e = experiences.find(x => x.id === id);
        if (!e || !e.skills[i]) return;
        e.skills[i].level = e.skills[i].level === 3 ? 1 : e.skills[i].level + 1;
        const lst = document.getElementById(`skl${id}`);
        if (lst) lst.innerHTML = e.skills.map((s, j) => renderChip(id, j, s)).join('');
    };

    window.removeSkill = (id, i) => {
        const e = experiences.find(x => x.id === id);
        if (!e) return;
        e.skills.splice(i, 1);
        const lst = document.getElementById(`skl${id}`);
        if (lst) lst.innerHTML = e.skills.map((s, j) => renderChip(id, j, s)).join('');
    };

    // Typeahead functionality
    function bindTypeaheads() {
        document.querySelectorAll('.js-comp-input').forEach((input) => {
            if (input.dataset.taBound === '1') return;
            input.dataset.taBound = '1';

            const expId = Number(input.dataset.expId);
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
                updateExp(expId, 'company', row.name);
                const e = experiences.find(x => x.id === expId);
                if (e) e.company_id = row.id;
                menu.style.display = 'none';
            };

            const doSearch = deb(async (q) => {
                if (!q || q.length < 2) {
                    menu.style.display = 'none';
                    return;
                }
                try {
                    const url = `${API}?q=${encodeURIComponent(q)}&limit=10`;
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const json = await res.json();
                    renderMenu(json.data || []);
                } catch (e) {
                    console.warn(e);
                    menu.style.display = 'none';
                }
            }, 250);

            input.addEventListener('input', (e) => {
                updateExp(expId, 'company', e.target.value);
                const ex = experiences.find(x => x.id === expId);
                if (ex) ex.company_id = null;
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

    el.addBtn.addEventListener('click', addExperience);

    // Initialize
    loadExisting();
    render();
});
</script>

<style>
/* Container */
.exp-container {
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

/* Experience List */
.exp-list {
    display: none;
    flex-direction: column;
    gap: 12px;
}

/* Experience Card */
.exp-card {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    background: var(--card);
    transition: all 0.2s ease;
}

.exp-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.exp-card.editing {
    border-color: var(--accent);
    background: var(--apc-bg);
}

.exp-header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
}

.exp-content {
    flex: 1;
}

.exp-company {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 4px;
}

.exp-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-body);
    margin-bottom: 8px;
}

.exp-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.exp-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.exp-meta svg {
    width: 14px;
    height: 14px;
    opacity: 0.6;
}

.exp-desc {
    font-size: 14px;
    color: var(--text-body);
    line-height: 1.6;
    white-space: pre-wrap;
    margin-top: 12px;
}

.exp-actions {
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
    border-color: var(--accent);
    color: var(--accent);
    transform: translateY(-2px);
}

.action-btn.delete:hover {
    border-color: #dc2626;
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Edit Form */
.exp-form {
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

.form-textarea {
    min-height: 100px;
    padding: 12px 14px;
    border: 1.5px solid var(--input-border);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    background: var(--card);
    color: var(--input-text);
    resize: vertical;
    line-height: 1.6;
}

.form-textarea:focus {
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

.date-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
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

/* Skills Section */
.skills-section {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.skills-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.skills-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
}

.skills-hint {
    font-size: 12px;
    color: var(--text-muted);
}

.skill-row {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 12px;
    margin-bottom: 12px;
}

.skill-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

/* Chips */
.chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
}

.chip-btns {
    display: inline-flex;
    gap: 4px;
    margin-left: 4px;
}

.chip-btn {
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
    font-size: 14px;
    color: inherit;
    opacity: 0.6;
}

.chip-btn:hover {
    opacity: 1;
}

.chip.l1 {
    background: rgba(234, 179, 8, 0.12);
    color: #a16207;
    border: 1px solid rgba(234, 179, 8, 0.3);
}

.chip.l2 {
    background: rgba(59, 130, 246, 0.12);
    color: #1e40af;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.chip.l3 {
    background: rgba(16, 185, 129, 0.12);
    color: #047857;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.skill-add-btn {
    padding: 0 20px;
    height: 48px;
    border: none;
    border-radius: 8px;
    background: var(--accent);
    color: white;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.skill-add-btn:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
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

.btn-save-exp {
    background: var(--accent);
    color: white;
}

.btn-save-exp:hover {
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
    border: 1.5px dashed var(--border);
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
    border-color: var(--accent);
    border-style: solid;
    background: var(--accent-light);
    color: var(--accent);
}

.hint {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 6px;
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .date-row {
        grid-template-columns: 1fr;
    }

    .skill-row {
        grid-template-columns: 1fr;
    }

    .skill-add-btn {
        width: 100%;
    }

    .exp-header {
        flex-direction: column;
        gap: 12px;
    }

    .exp-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .skills-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}
</style>