@extends('layouts.onboarding')

@section('title', 'Work Experience - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"

    step="4"
    title="Your professional journey"
    subtitle="Add roles that highlight your responsibilities and impact"
/>

<form id="experienceForm" action="{{ route('tenant.onboarding.experience.store') }}" method="POST">
    @csrf

    <div class="experience-list" id="experienceList">
        <div class="empty-state" id="emptyState">Start with your most recent role</div>
    </div>

    <button type="button" class="btn-add" id="addBtn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Add experience
    </button>

    <input type="hidden" name="experiences" id="experiencesData">

    <x-onboarding.alert type="success">
        <strong>Pro tip:</strong> Use strong verbs and add metrics (e.g., "Reduced costs by 18%"). Keep 2–4 bullet points per role.
    </x-onboarding.alert>

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}" backUrl="{{ route('tenant.onboarding.skills') }}" />
</form>

@endsection

@push('styles')
<style>
.experience-list { margin: var(--space-lg) 0; }

.experience-card {
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: var(--space-lg);
    background: var(--card);
    position: relative;
    margin-bottom: var(--space-md);
    transition: all var(--transition-base);
}

.experience-card:hover { box-shadow: var(--shadow-sm); }
.edit-card { border-color: var(--accent); background: var(--apc-bg); }

.card-actions {
    position: absolute;
    top: var(--space-md);
    right: var(--space-md);
    display: flex;
    gap: var(--space-sm);
}

.card-company {
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    font-size: var(--fs-title);
    margin-bottom: 2px;
}

.card-title {
    font-weight: var(--fw-semibold);
    color: var(--text-body);
    margin-bottom: var(--space-sm);
}

.card-date {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

.card-description {
    margin-top: var(--space-md);
    padding-top: var(--space-md);
    border-top: 1px solid var(--border);
    color: var(--text-body);
    line-height: var(--lh-relaxed);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
}

.date-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-sm);
}

.current-role {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    margin-top: var(--space-sm);
}

.current-role input {
    width: 16px;
    height: 16px;
    accent-color: var(--accent);
    cursor: pointer;
}

.btn-add {
    width: 100%;
    padding: var(--space-md) var(--space-lg);
    background: var(--card);
    color: var(--text-body);
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
    margin-bottom: var(--space-lg);
    transition: all var(--transition-base);
}

.btn-add:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
}

.form-header-actions {
    display: flex;
    gap: var(--space-sm);
    justify-content: flex-end;
    margin-bottom: var(--space-md);
}

.save-btn, .cancel-btn {
    padding: 8px 16px;
    border-radius: var(--radius);
    font-weight: var(--fw-medium);
    font-size: var(--fs-subtle);
    cursor: pointer;
    transition: all var(--transition-base);
}

.save-btn {
    background: var(--success);
    color: var(--btn-text-primary);
    border: none;
}

.cancel-btn {
    background: var(--card);
    color: var(--text-body);
    border: 1px solid var(--border);
}

@media (max-width: 640px) {
    .form-grid, .date-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

 
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let experiences = [];
        let editingId = null;
        let counter = 0;

        const listEl = document.getElementById('experienceList');
        const emptyEl = document.getElementById('emptyState');
        const addBtn = document.getElementById('addBtn');
        const continueBtn = document.getElementById('continueBtn');
        const experiencesDataInput = document.getElementById('experiencesData');

        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const currentYear = new Date().getFullYear();
        const years = Array.from({length: 40}, (_, i) => currentYear - i);

        function render() {
            if (experiences.length === 0) {
                emptyEl.style.display = 'block';
                listEl.innerHTML = '';
                continueBtn.disabled = true;
                return;
            }

            emptyEl.style.display = 'none';
            listEl.innerHTML = experiences.map(exp => 
                editingId === exp.id ? renderEdit(exp) : renderDisplay(exp)
            ).join('');
            
            continueBtn.disabled = !experiences.some(e => e.company && e.title);
            experiencesDataInput.value = JSON.stringify(experiences);
        }

        function renderDisplay(exp) {
            const dateRange = formatDateRange(exp);
            return `
                <div class="experience-card" id="exp-${exp.id}">
                    <div class="card-actions">
                        <button type="button" class="icon-btn btn-edit" onclick="editExperience(${exp.id})">✎</button>
                        <button type="button" class="icon-btn btn-remove" onclick="removeExperience(${exp.id})">×</button>
                    </div>
                    <div class="card-header">
                        <div class="card-company">${escapeHtml(exp.company || 'Company name')}</div>
                        <div class="card-title">${escapeHtml(exp.title || 'Job title')}</div>
                        <div class="card-date">${escapeHtml(dateRange)}</div>
                    </div>
                    ${exp.description ? `<div class="card-description">${escapeHtml(exp.description)}</div>` : ''}
                </div>
            `;
        }

        function renderEdit(exp) {
            return `
                <div class="experience-card edit-card" id="exp-${exp.id}">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Company <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="Company name" value="${escapeHtml(exp.company)}"
                                oninput="updateExperience(${exp.id}, 'company', this.value)"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Job title <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="Your role" value="${escapeHtml(exp.title)}"
                                oninput="updateExperience(${exp.id}, 'title', this.value)"/>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Start date</label>
                            <div class="date-grid">
                                <select class="form-select" onchange="updateExperience(${exp.id}, 'startMonth', this.value)">
                                    <option value="">Month</option>
                                    ${months.map((m, i) => `<option value="${i+1}" ${exp.startMonth == i+1 ? 'selected' : ''}>${m}</option>`).join('')}
                                </select>
                                <select class="form-select" onchange="updateExperience(${exp.id}, 'startYear', this.value)">
                                    <option value="">Year</option>
                                    ${years.map(y => `<option value="${y}" ${exp.startYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">End date</label>
                            <div class="date-grid">
                                <select class="form-select end-month" ${exp.current ? 'disabled' : ''} onchange="updateExperience(${exp.id}, 'endMonth', this.value)">
                                    <option value="">Month</option>
                                    ${months.map((m, i) => `<option value="${i+1}" ${exp.endMonth == i+1 ? 'selected' : ''}>${m}</option>`).join('')}
                                </select>
                                <select class="form-select end-year" ${exp.current ? 'disabled' : ''} onchange="updateExperience(${exp.id}, 'endYear', this.value)">
                                    <option value="">Year</option>
                                    ${years.map(y => `<option value="${y}" ${exp.endYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                                </select>
                            </div>
                            <label class="current-role">
                                <input type="checkbox" ${exp.current ? 'checked' : ''} onchange="updateExperience(${exp.id}, 'current', this.checked)"/>
                                I currently work here
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description (optional)</label>
                        <textarea class="form-textarea" placeholder="Briefly describe responsibilities and achievements…"
                            oninput="updateExperience(${exp.id}, 'description', this.value)">${escapeHtml(exp.description)}</textarea>
                    </div>

                    <div class="form-header-actions">
                        <button type="button" class="cancel-btn" onclick="cancelEdit(${exp.id})">Cancel</button>
                        <button type="button" class="save-btn" onclick="saveExperience(${exp.id})">✓ Save</button>
                    </div>
                </div>
            `;
        }

        function formatDateRange(exp) {
            const start = exp.startMonth && exp.startYear ? `${months[exp.startMonth-1]} ${exp.startYear}` : '';
            const end = exp.current ? 'Present' : (exp.endMonth && exp.endYear ? `${months[exp.endMonth-1]} ${exp.endYear}` : '');
            if (!start) return '';
            return end ? `${start} — ${end}` : start;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        function addExperience() {
            const id = ++counter;
            experiences.unshift({ id, company: '', title: '', startMonth: '', startYear: '', endMonth: '', endYear: '', current: false, description: '' });
            editingId = id;
            render();
        }

        window.editExperience = (id) => { editingId = id; render(); };
        window.removeExperience = (id) => { experiences = experiences.filter(e => e.id !== id); if (editingId === id) editingId = null; render(); };
        window.cancelEdit = (id) => { const e = experiences.find(x => x.id === id); if (e && !e.company && !e.title) { window.removeExperience(id); } else { editingId = null; render(); } };
        window.saveExperience = (id) => { const e = experiences.find(x => x.id === id); if (!e || !e.company.trim() || !e.title.trim()) { alert('Please fill in company and job title.'); return; } editingId = null; render(); };
        window.updateExperience = (id, field, value) => { const e = experiences.find(x => x.id === id); if (!e) return; if (field === 'current') { e.current = !!value; if (e.current) { e.endMonth = ''; e.endYear = ''; } } else { e[field] = value; } };

        addBtn.addEventListener('click', addExperience);
        render();
    });
</script>
@endpush

 