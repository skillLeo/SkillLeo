@extends('layouts.onboarding')

@section('title', 'Work Experience - ProMatch')

@section('card-content')

    <x-onboarding.form-header skipUrl="{{ route('tenant.onboarding.education') }}" step="4"
        title="Your professional journey"
        subtitle="Add roles that highlight your responsibilities, location, and role-specific skills" />

    <form id="experienceForm" action="{{ route('tenant.onboarding.experience.store') }}" method="POST">
        @csrf

        <div class="exp-list" id="expList">
            <div class="empty" id="empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                </svg>
                <p>No experience added yet</p>
                <span>Start with your most recent role</span>
            </div>
        </div>

        <button type="button" class="add-btn" id="addBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14" stroke-linecap="round" />
            </svg>
            Add experience
        </button>

        <input type="hidden" name="experiences" id="expData">

        <x-onboarding.form-footer skipUrl="{{ route('tenant.onboarding.education') }}"
            backUrl="{{ route('tenant.onboarding.skills') }}" />
    </form>

@endsection

@push('styles')
    <style>
        /* List */
        .exp-list {
            margin: 32px 0;
        }

        .empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty svg {
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty p {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-body);
            margin: 0 0 4px;
        }

        .empty span {
            font-size: 14px;
        }

        /* Card */
        .exp-card {
            border-radius: 8px;
            margin-bottom: 16px;
            position: relative;
        }

        .exp-card.editing {
        }

        /* Display */
        .card-header {
            padding-right: 80px;
        }

        .company {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-heading);
            margin-bottom: 4px;
        }

        .title {
            font-size: 15px;
            font-weight: 500;
            color: var(--text-body);
            margin-bottom: 12px;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .meta svg {
            width: 14px;
            height: 14px;
            opacity: 0.6;
        }

        .desc {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            color: var(--text-body);
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        /* Actions */
        .actions {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 8px;
        }

        .action {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            background: var(--card);
            border-radius: 6px;
            cursor: pointer;
            color: var(--text-muted);
        }

        .action:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .action.del:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        /* Form */
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .group {
            display: flex;
            flex-direction: column;
        }

        .label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .required {
            color: #ef4444;
        }

        .input,
        .select,
        .textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 6px;
            background: var(--card);
            color: var(--text-body);
            font-size: 14px;
            font-family: inherit;
        }

        .input:focus,
        .select:focus,
        .textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .textarea {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }

        .select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 36px;
            cursor: pointer;
        }

        .select:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: var(--bg);
        }

        .hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            font-size: 14px;
            cursor: pointer;
        }

        .check input {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        /* Skills Section */
        .skills-section {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .skills-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .skills-label {
            font-size: 14px;
            font-weight: 500;
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
            margin-bottom: 16px;
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

        /* Buttons */
        .add-btn {
            width: 100%;
            padding: 14px;
            background: var(--card);
            color: var(--text-body);
            border: 2px dashed var(--border);
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .add-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-row {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }

        .btn-save {
            background: var(--success, #10b981);
            color: white;
        }

        .btn-cancel {
            background: var(--card);
            color: var(--text-body);
            border: 1px solid var(--border);
        }

        .skill-add {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            background: var(--accent);
            color: white;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
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
            border: 1px solid var(--border);
            border-radius: 6px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow: auto;
            display: none;
        }

        .ta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            cursor: pointer;
        }

        .ta-item:hover,
        .ta-item.active {
            background: var(--accent-light, rgba(99, 102, 241, 0.08));
        }

        .ta-logo {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            flex-shrink: 0;
            background: #f3f4f6;
            object-fit: cover;
        }

        .ta-name {
            font-weight: 500;
            color: var(--text-heading);
            font-size: 14px;
        }

        .ta-loc {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .ta-empty {
            padding: 10px 12px;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                padding-right: 70px;
            }

            .actions {
                top: 16px;
                right: 16px;
            }

            .action {
                width: 28px;
                height: 28px;
            }

            .row {
                grid-template-columns: 1fr;
            }

            .date-row {
                grid-template-columns: 1fr;
            }

            .skill-row {
                grid-template-columns: 1fr;
            }

            .skill-add {
                width: 100%;
            }

            .skills-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .btn-row {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            'use strict';
            const API = @json(route('api.companies.search'));
            let exps = [],
                editing = null,
                counter = 0;
            const el = {
                list: document.getElementById('expList'),
                empty: document.getElementById('empty'),
                add: document.getElementById('addBtn'),
                data: document.getElementById('expData'),
                cont: document.getElementById('continueBtn')
            };
            const M = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                'November', 'December'
            ];
            const Y = Array.from({
                length: 40
            }, (_, i) => new Date().getFullYear() - i);
            const L = {
                1: 'Beginner',
                2: 'Proficient',
                3: 'Expert'
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

            // ---- helpers for a more professional end-date UX ----
            const isEndBeforeStart = e => {
                if (!e.startYear || !e.startMonth || !e.endYear || !e.endMonth) return false;
                const s = new Date(Number(e.startYear), Number(e.startMonth) - 1, 1);
                const n = new Date(Number(e.endYear), Number(e.endMonth) - 1, 1);
                return n < s;
            };
            const monthOpts = (selected, disableBefore = 0) =>
                `<option value="">Month</option>` + M.map((m, i) => {
                    const v = i + 1,
                        dis = disableBefore && v < disableBefore ? 'disabled' : '';
                    return `<option value="${v}" ${String(selected) === String(v) ? 'selected' : ''} ${dis}>${m}</option>`;
                }).join('');
            const yearOpts = (selected, minYear = null) =>
                `<option value="">Year</option>` + Y.map(y =>
                    `<option value="${y}" ${String(selected) === String(y) ? 'selected' : ''} ${minYear && y < minYear ? 'disabled' : ''}>${y}</option>`
                ).join('');
            // -----------------------------------------------------

            function render() {
                if (!exps.length) {
                    el.empty.style.display = 'flex';
                    el.list.innerHTML = '';
                    if (el.cont) el.cont.disabled = true;
                    return;
                }
                el.empty.style.display = 'none';
                el.list.innerHTML = exps.map(e => editing === e.id ? renderEdit(e) : renderView(e)).join('');
                if (el.cont) el.cont.disabled = !exps.some(e => e.company && e.title);
                el.data.value = JSON.stringify(exps);
                bindTA();
                bindSkills();
            }

            function renderView(e) {
                const dr = getDR(e),
                    loc = [e.locationCity, e.locationCountry].filter(Boolean).join(', ');
                const sk = e.skills?.length ?
                    `<div class="skill-list" style="margin-top:16px;">${e.skills.map(s => `<span class="chip l${s.level}">${esc(s.name)} • ${L[s.level]}</span>`).join('')}</div>` :
                    '';
                return `<div class="exp-card">
            <div class="actions">
                <button type="button" class="action" onclick="edit(${e.id})" title="Edit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </button>
                <button type="button" class="action del" onclick="del(${e.id})" title="Delete">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="card-header">
                <div class="company">${esc(e.company || 'Company name')}</div>
                <div class="title">${esc(e.title || 'Job title')}</div>
                <div class="meta">
                    ${dr ? `<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>${esc(dr)}</span>` : ''}
                    ${loc ? `<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>${esc(loc)}</span>` : ''}
                </div>
            </div>
            ${e.description ? `<div class="desc">${esc(e.description)}</div>` : ''}${sk}
        </div>`;
            }

            function renderEdit(e) {
                // dynamic disabling rules (end date cannot be before start date)
                const endYearMin = e.startYear ? Number(e.startYear) : null;
                const endMonthDisableBefore = (e.startYear && e.endYear && String(e.endYear) === String(e.startYear)) ?
                    Number(e.startMonth) : 0;

                // Validation message (inline, subtle)
                const invalidRange = !e.current && isEndBeforeStart(e);
                const invalidHint = invalidRange ?
                    `<div class="hint" style="color:#ef4444;margin-top:6px;">End date cannot be before start date</div>` :
                    '';

                return `<div class="exp-card editing">
            <div class="row">
                <div class="group">
                    <label class="label">Company <span class="required">*</span></label>
                    <div class="ta-wrap">
                        <input type="text" class="input comp-input" data-id="${e.id}" placeholder="e.g., Google" value="${esc(e.company)}"/>
                        <div class="ta-menu"></div>
                    </div>
                </div>
                <div class="group">
                    <label class="label">Job title <span class="required">*</span></label>
                    <input type="text" class="input" placeholder="e.g., Senior Developer" value="${esc(e.title)}" oninput="upd(${e.id}, 'title', this.value)"/>
                </div>
            </div>

            <div class="row">
                <div class="group">
                    <label class="label">Start date</label>
                    <div class="date-row">
                        <select class="select" onchange="upd(${e.id}, 'startMonth', this.value)">
                            ${monthOpts(e.startMonth)}
                        </select>
                        <select class="select" onchange="upd(${e.id}, 'startYear', this.value)">
                            ${yearOpts(e.startYear)}
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label class="label">End date</label>

                    <!-- When current is checked show a clean Present pill -->
                    <div class="skill-list" style="${e.current ? '' : 'display:none'}; margin-bottom:8px;">
                        <span class="chip l2" title="Displayed on your profile">Present</span>
                    </div>

                    <!-- Real inputs are hidden while current is checked -->
                    <div class="date-row" style="${e.current ? 'display:none' : ''}">
                        <select class="select" aria-disabled="${e.current ? 'true' : 'false'}" onchange="upd(${e.id}, 'endMonth', this.value)">
                            ${monthOpts(e.endMonth, endMonthDisableBefore)}
                        </select>
                        <select class="select" aria-disabled="${e.current ? 'true' : 'false'}" onchange="upd(${e.id}, 'endYear', this.value)">
                            ${yearOpts(e.endYear, endYearMin)}
                        </select>
                    </div>

                    ${invalidHint}

                    <label class="check"><input type="checkbox" ${e.current ? 'checked' : ''} onchange="upd(${e.id}, 'current', this.checked)"/>I currently work here</label>
                    <div class="hint">Check to display “Present” and hide end date.</div>
                </div>
            </div>

            <div class="row">
                <div class="group">
                    <label class="label">Location</label>
                    <div class="date-row">
                        <input type="text" class="input" placeholder="City" value="${esc(e.locationCity)}" oninput="upd(${e.id}, 'locationCity', this.value)"/>
                        <input type="text" class="input" placeholder="Country" value="${esc(e.locationCountry)}" oninput="upd(${e.id}, 'locationCountry', this.value)"/>
                    </div>
                    <div class="hint">Optional</div>
                </div>
                <div class="group">
                    <label class="label">Description</label>
                    <textarea class="textarea" placeholder="Key responsibilities and achievements..." oninput="upd(${e.id}, 'description', this.value)">${esc(e.description)}</textarea>
                </div>
            </div>

            <div class="skills-section">
                <div class="skills-header">
                    <div class="skills-label">Role-specific skills</div>
                    <div class="skills-hint">Add 3-8 skills • Click ↻ to change level</div>
                </div>
                <div class="skill-row">
                    <input type="text" class="input" id="si${e.id}" placeholder="e.g., React, Python"/>
                    <select class="select" id="sl${e.id}" style="min-width:130px">
                        <option value="1">Beginner</option><option value="2" selected>Proficient</option><option value="3">Expert</option>
                    </select>
                    <button type="button" class="skill-add" onclick="addSk(${e.id})">Add</button>
                </div>
                <div class="skill-list" id="skl${e.id}">${(e.skills || []).map((s, i) => renderChip(e.id, i, s)).join('')}</div>
            </div>

            <div class="btn-row">
                <button type="button" class="btn btn-cancel" onclick="cancel(${e.id})">Cancel</button>
                <button type="button" class="btn btn-save" onclick="save(${e.id})">Save</button>
            </div>
        </div>`;
            }

            function renderChip(id, i, s) {
                return `<span class="chip l${s.level}">${esc(s.name)} • ${L[s.level]}<span class="chip-btns"><button type="button" class="chip-btn" onclick="cycleSk(${id}, ${i})">↻</button><button type="button" class="chip-btn" onclick="rmSk(${id}, ${i})">×</button></span></span>`;
            }

            function getDR(e) {
                if (!e.startMonth || !e.startYear) return '';
                const s = `${M[e.startMonth-1]} ${e.startYear}`;
                const n = e.current ? 'Present' : (e.endMonth && e.endYear ? `${M[e.endMonth-1]} ${e.endYear}` : '');
                return n ? `${s} — ${n}` : s;
            }

            window.edit = id => {
                editing = id;
                render();
            };
            window.del = id => {
                if (confirm('Delete this experience?')) {
                    exps = exps.filter(e => e.id !== id);
                    if (editing === id) editing = null;
                    render();
                }
            };
            window.cancel = id => {
                const e = exps.find(x => x.id === id);
                if (e && !e.company && !e.title) exps = exps.filter(x => x.id !== id);
                editing = null;
                render();
            };
            window.save = id => {
                const e = exps.find(x => x.id === id);
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
                editing = null;
                render();
            };

            window.upd = (id, f, v) => {
                const e = exps.find(x => x.id === id);
                if (!e) return;
                if (f === 'current') {
                    e.current = !!v;
                    if (e.current) {
                        e.endMonth = '';
                        e.endYear = '';
                    }
                    // if toggled off, focus end month for quick entry (after re-render)
                    const focusAfter = !e.current;
                    render();
                    if (focusAfter) {
                        const sel = document.querySelector(
                            `.exp-card.editing select[onchange^="upd(${id}, 'endMonth'"]`);
                        sel && sel.focus();
                    }
                    return;
                }
                // normalize numeric selects
                if (['startMonth', 'startYear', 'endMonth', 'endYear'].includes(f)) {
                    e[f] = v ? Number(v) : '';
                    // if start change makes end invalid, clear end
                    if (!e.current && isEndBeforeStart(e)) {
                        e.endMonth = '';
                        e.endYear = '';
                    }
                    render(); // re-render to refresh disabled options in selects
                    return;
                }
                e[f] = v;
                el.data.value = JSON.stringify(exps);
            };

            function bindSkills() {
                exps.forEach(e => {
                    const inp = document.getElementById(`si${e.id}`);
                    if (inp && !inp.dataset.b) {
                        inp.dataset.b = '1';
                        inp.addEventListener('keydown', ev => {
                            if (ev.key === 'Enter') {
                                ev.preventDefault();
                                addSk(e.id);
                            }
                        });
                    }
                });
            }

            window.addSk = id => {
                const e = exps.find(x => x.id === id);
                if (!e) return;
                e.skills = e.skills || [];
                if (e.skills.length >= 8) {
                    alert('Max 8 skills');
                    return;
                }
                const inp = document.getElementById(`si${id}`),
                    sel = document.getElementById(`sl${id}`);
                const n = (inp?.value || '').trim(),
                    lv = parseInt(sel?.value || '2', 10);
                if (!n) {
                    inp?.focus();
                    return;
                }
                if (e.skills.some(s => s.name.toLowerCase() === n.toLowerCase())) {
                    alert('Skill already added');
                    inp.value = '';
                    inp.focus();
                    return;
                }
                e.skills.push({
                    name: n,
                    level: lv
                });
                inp.value = '';
                const lst = document.getElementById(`skl${id}`);
                if (lst) lst.innerHTML = e.skills.map((s, i) => renderChip(id, i, s)).join('');
                el.data.value = JSON.stringify(exps);
            };

            window.cycleSk = (id, i) => {
                const e = exps.find(x => x.id === id);
                if (!e || !e.skills[i]) return;
                e.skills[i].level = e.skills[i].level === 3 ? 1 : e.skills[i].level + 1;
                const lst = document.getElementById(`skl${id}`);
                if (lst) lst.innerHTML = e.skills.map((s, j) => renderChip(id, j, s)).join('');
                el.data.value = JSON.stringify(exps);
            };
            window.rmSk = (id, i) => {
                const e = exps.find(x => x.id === id);
                if (!e) return;
                e.skills.splice(i, 1);
                const lst = document.getElementById(`skl${id}`);
                if (lst) lst.innerHTML = e.skills.map((s, j) => renderChip(id, j, s)).join('');
                el.data.value = JSON.stringify(exps);
            };

            function bindTA() {
                document.querySelectorAll('.comp-input').forEach(inp => {
                    if (inp.dataset.b) return;
                    inp.dataset.b = '1';
                    const id = Number(inp.dataset.id),
                        wr = inp.closest('.ta-wrap'),
                        mn = wr.querySelector('.ta-menu');
                    let idx = -1,
                        its = [];
                    const rend = ls => {
                        its = ls || [];
                        if (!its.length) {
                            mn.innerHTML = '<div class="ta-empty">No results</div>';
                            mn.style.display = 'block';
                            return;
                        }
                        mn.innerHTML = its.map((x, i) => `<div class="ta-item ${i===idx ? 'active' : ''}" data-i="${i}">
                    <img class="ta-logo" src="${x.logo || ''}" onerror="this.style.visibility='hidden'"/>
                    <div><div class="ta-name">${esc(x.name)}</div>${x.city || x.country ? `<div class="ta-loc">${esc([x.city, x.country].filter(Boolean).join(' • '))}</div>` : ''}</div>
                </div>`).join('');
                        mn.style.display = 'block';
                        mn.querySelectorAll('.ta-item').forEach(el => el.addEventListener('mousedown',
                        e => {
                            e.preventDefault();
                            cho(its[Number(el.dataset.i)]);
                        }));
                    };
                    const cho = r => {
                        if (!r) return;
                        inp.value = r.name;
                        upd(id, 'company', r.name);
                        const e = exps.find(x => x.id === id);
                        if (e) e.company_id = r.id;
                        mn.style.display = 'none';
                        el.data.value = JSON.stringify(exps);
                    };
                    const srch = deb(async q => {
                        if (!q || q.length < 2) {
                            mn.style.display = 'none';
                            return;
                        }
                        try {
                            const res = await fetch(`${API}?q=${encodeURIComponent(q)}&limit=10`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            if (!res.ok) throw new Error();
                            const j = await res.json();
                            rend(j.data || []);
                        } catch {
                            mn.style.display = 'none';
                        }
                    }, 250);
                    inp.addEventListener('input', e => {
                        upd(id, 'company', e.target.value);
                        const ex = exps.find(x => x.id === id);
                        if (ex) ex.company_id = null;
                        srch(e.target.value.trim());
                    });
                    inp.addEventListener('focus', () => {
                        const v = inp.value.trim();
                        if (v.length >= 2) srch(v);
                    });
                    inp.addEventListener('blur', () => setTimeout(() => mn.style.display = 'none', 150));
                    inp.addEventListener('keydown', e => {
                        if (mn.style.display === 'none') return;
                        if (e.key === 'ArrowDown') {
                            idx = Math.min(idx + 1, its.length - 1);
                            rend(its);
                            e.preventDefault();
                        } else if (e.key === 'ArrowUp') {
                            idx = Math.max(idx - 1, 0);
                            rend(its);
                            e.preventDefault();
                        } else if (e.key === 'Enter') {
                            if (idx >= 0) {
                                cho(its[idx]);
                                e.preventDefault();
                            }
                        } else if (e.key === 'Escape') mn.style.display = 'none';
                    });
                });
            }

            el.add.addEventListener('click', () => {
                const id = ++counter;
                exps.unshift({
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
                    skills: []
                });
                editing = id;
                render();
            });

            render();
        })();
    </script>
@endpush
