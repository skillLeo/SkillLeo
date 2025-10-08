@extends('layouts.onboarding')

@section('title', 'Education - ProMatch')

@section('card-content')

    <x-onboarding.form-header 
        step="6" 
        title="Education" 
        subtitle="Add your academic qualifications"
    />

    <form id="educationForm" action="{{ route('tenant.onboarding.education.store') }}" method="POST">
        @csrf

        <div class="education-container" id="educationContainer">
            <div class="empty-state" id="emptyState">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                <p>No education added yet</p>
                <span>Add your highest qualification first</span>
            </div>
            <div class="education-list" id="educationList"></div>
        </div>

        <button type="button" class="add-btn" id="addBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Education
        </button>

        <input type="hidden" name="education" id="educationData" value="[]">

        <x-onboarding.form-footer backUrl="{{ route('tenant.onboarding.portfolio') }}" />
    </form>

@endsection

@push('styles')
<style>
    /* Container */
    .education-container {
        min-height: 200px;
        margin-bottom: 24px;
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
        color: var(--text-muted);
    }

    /* Education List */
    .education-list {
        display: none;
        flex-direction: column;
        gap: 12px;
    }

    /* Education Card - Display Mode */
    .edu-card {
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 20px;
        background: var(--card);
        transition: all 0.2s ease;
        position: relative;
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
        align-items: flex-start;
        gap: 16px;
    }

    .edu-content {
        flex: 1;
        min-width: 0;
    }

    .edu-school {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .edu-degree {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-body);
        margin-bottom: 8px;
    }

    .edu-date {
        font-size: 13px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .edu-date svg {
        width: 14px;
        height: 14px;
        opacity: 0.6;
    }

    .edu-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
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

    .form-input::placeholder {
        color: var(--input-placeholder);
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

    /* Typeahead Dropdown */
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
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    .btn-save {
        background: var(--accent);
        color: white;
    }

    .btn-save:hover {
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
        margin-bottom: 24px;
        transition: all 0.2s ease;
    }

    .add-btn:hover {
        border-color: var(--accent);
        border-style: solid;
        background: var(--accent-light);
        color: var(--accent);
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

        .action-btn {
            width: 40px;
            height: 40px;
        }

        .empty-state {
            padding: 40px 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    "use strict";

    const INST_API = @json(route('api.institutions.search'));
    
    let education = [];
    let editingId = null;
    let counter = 0;

    const form = document.getElementById('educationForm');
    const container = document.getElementById('educationContainer');
    const listEl = document.getElementById('educationList');
    const emptyEl = document.getElementById('emptyState');
    const addBtn = document.getElementById('addBtn');
    const continueBtn = document.getElementById('continueBtn');
    const btnText = document.getElementById('btnText');
    const educationDataInput = document.getElementById('educationData');

    const currentYear = new Date().getFullYear();
    const years = Array.from({ length: 50 }, (_, i) => currentYear - i);

    const esc = (s) => String(s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    function debounce(fn, ms) { 
        let t; 
        return (...a) => { 
            clearTimeout(t); 
            t = setTimeout(() => fn(...a), ms); 
        }; 
    }

    function saveStorage() {
        try { 
            localStorage.setItem('onboarding_education', JSON.stringify(education)); 
        } catch {}
    }

    function loadStorage() {
        try {
            const raw = localStorage.getItem('onboarding_education');
            if (!raw) return;
            const parsed = JSON.parse(raw);
            if (Array.isArray(parsed)) {
                education = parsed.map(x => ({
                    id: Number(x?.id) || ++counter,
                    school: String(x?.school || ''),
                    degree: String(x?.degree || ''),
                    field: String(x?.field || ''),
                    startYear: String(x?.startYear || ''),
                    endYear: String(x?.endYear || ''),
                    current: !!x?.current,
                    institution_id: x?.institution_id ?? null
                }));
                counter = education.reduce((m, e) => Math.max(m, e.id), counter);
            }
        } catch {}
    }

    function updateContinueButton() {
        const hasValidEducation = education.length > 0 && education.some(e => e.school && e.degree);
        const isEditing = editingId !== null;
        
        if (continueBtn) {
            continueBtn.disabled = !hasValidEducation || isEditing;
        }
    }

    function render() {
        if (education.length === 0) {
            emptyEl.style.display = 'flex';
            listEl.style.display = 'none';
        } else {
            emptyEl.style.display = 'none';
            listEl.style.display = 'flex';
            listEl.innerHTML = education.map(edu =>
                editingId === edu.id ? renderEdit(edu) : renderDisplay(edu)
            ).join('');
            bindTypeaheads();
        }

        // Update hidden input
        educationDataInput.value = JSON.stringify(education);
        
        // Update continue button state
        updateContinueButton();
        
        saveStorage();
    }

    function renderDisplay(edu) {
        const dateRange = formatDate(edu);
        const fieldText = edu.field ? ` in ${esc(edu.field)}` : '';
        
        return `
            <div class="edu-card" id="edu-${edu.id}">
                <div class="edu-header">
                    <div class="edu-content">
                        <div class="edu-school">${esc(edu.school || 'Institution name')}</div>
                        <div class="edu-degree">${esc(edu.degree || 'Degree')}${fieldText}</div>
                        <div class="edu-date">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            ${esc(dateRange)}
                        </div>
                    </div>
                    <div class="edu-actions">
                        <button type="button" class="action-btn" onclick="editEducation(${edu.id})" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button type="button" class="action-btn delete" onclick="removeEducation(${edu.id})" title="Delete">
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

    function renderEdit(edu) {
        return `
            <div class="edu-card editing" id="edu-${edu.id}">
                <div class="edu-form">
                    <div class="form-group">
                        <label class="form-label">Institution <span class="required">*</span></label>
                        <div class="ta-wrap">
                            <input type="text" class="form-input js-inst-input" data-edu-id="${edu.id}"
                                   placeholder="e.g., Harvard University" value="${esc(edu.school)}"/>
                            <div class="ta-menu"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Degree <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="e.g., Bachelor's, Master's" 
                                   value="${esc(edu.degree)}"
                                   oninput="updateEducation(${edu.id}, 'degree', this.value)"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Field of Study</label>
                            <input type="text" class="form-input" placeholder="e.g., Computer Science" 
                                   value="${esc(edu.field)}"
                                   oninput="updateEducation(${edu.id}, 'field', this.value)"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Start Year</label>
                            <select class="form-select" onchange="updateEducation(${edu.id}, 'startYear', this.value)">
                                <option value="">Select year</option>
                                ${years.map(y => `<option value="${y}" ${edu.startYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">End Year</label>
                            <select class="form-select" ${edu.current ? 'disabled' : ''} 
                                    onchange="updateEducation(${edu.id}, 'endYear', this.value)">
                                <option value="">Select year</option>
                                ${years.map(y => `<option value="${y}" ${edu.endYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                            </select>
                            <div class="checkbox-group">
                                <input type="checkbox" id="current-${edu.id}" ${edu.current ? 'checked' : ''} 
                                       onchange="updateEducation(${edu.id}, 'current', this.checked)"/>
                                <label for="current-${edu.id}">Currently studying here</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="form-btn btn-cancel" onclick="cancelEdit(${edu.id})">Cancel</button>
                        <button type="button" class="form-btn btn-save" onclick="saveEducation(${edu.id})">
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

    function formatDate(edu) {
        if (!edu.startYear && !edu.endYear) return 'Dates not specified';
        const start = edu.startYear || '—';
        const end = edu.current ? 'Present' : (edu.endYear || '—');
        return `${start} – ${end}`;
    }

    function addEducation() {
        const id = ++counter;
        education.unshift({
            id,
            school: '',
            degree: '',
            field: '',
            startYear: '',
            endYear: '',
            current: false,
            institution_id: null
        });
        editingId = id;
        render();
    }

    window.editEducation = (id) => { 
        editingId = id; 
        render(); 
    };

    window.removeEducation = (id) => { 
        if (confirm('Remove this education entry?')) {
            education = education.filter(e => e.id !== id); 
            if (editingId === id) editingId = null; 
            render(); 
        }
    };

    window.cancelEdit = (id) => {
        const e = education.find(x => x.id === id);
        if (e && !e.school && !e.degree) { 
            education = education.filter(x => x.id !== id);
        }
        editingId = null; 
        render();
    };

    window.saveEducation = (id) => {
        const e = education.find(x => x.id === id);
        if (!e || !e.school.trim() || !e.degree.trim()) {
            alert('Please enter institution name and degree.');
            return;
        }
        editingId = null;
        render();
    };

    window.updateEducation = (id, field, value) => {
        const e = education.find(x => x.id === id);
        if (!e) return;

        if (field === 'current') {
            e.current = !!value;
            if (e.current) e.endYear = '';
        } else {
            e[field] = value;
        }
        saveStorage();
    };

    // Add button click
    addBtn.addEventListener('click', addEducation);

    // Form submission handler - THIS IS THE KEY FIX!
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if editing
        if (editingId !== null) {
            alert('Please save or cancel the education entry you are editing first.');
            return false;
        }

        // Check if at least one education exists
        if (education.length === 0) {
            alert('Please add at least one education entry.');
            return false;
        }

        // Check all entries are complete
        const incomplete = education.some(edu => !edu.school || !edu.degree);
        if (incomplete) {
            alert('All education entries must have an institution and degree.');
            return false;
        }

        // Update hidden input with final data
        educationDataInput.value = JSON.stringify(education);

        // Show loading state
        if (continueBtn && btnText) {
            continueBtn.disabled = true;
            btnText.innerHTML = '<div class="loading-spinner"></div>';
        }

        // Submit the form
        form.submit();
    });

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
                updateEducation(eduId, 'school', row.name);
                updateEducation(eduId, 'institution_id', row.id);
                menu.style.display = 'none';
            };

            const doSearch = debounce(async (q) => {
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
                updateEducation(eduId, 'school', e.target.value);
                updateEducation(eduId, 'institution_id', null);
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

    // Initialize
    loadStorage();
    render();
});
</script>
@endpush