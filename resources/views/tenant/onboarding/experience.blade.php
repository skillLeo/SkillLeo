@extends('layouts.onboarding')

@section('title', 'Work Experience - ProMatch')

@php
    $currentStep = 4;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Work Experience" />
        <h1 class="form-title">Your professional journey</h1>
        <p class="form-subtitle">Add roles that highlight your responsibilities and impact.</p>
    </div>

    <form id="experienceForm" action="{{ route('tenant.onboarding.experience.store') }}" method="POST">
        @csrf

        <div class="experience-list" id="experienceList">
            <div class="empty-state" id="emptyState">Start with your most recent role.</div>
        </div>

        <button type="button" class="add-experience-btn" id="addBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Add experience
        </button>

        <input type="hidden" name="experiences" id="experiencesData">

        <div class="tips">
            <strong>Pro tip:</strong> Use strong verbs and add metrics (e.g., "Reduced costs by 18%"). Keep 2–4 bullet points per role.
        </div>

        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('tenant.onboarding.skills') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </x-ui.button>

            <x-ui.button variant="primary" type="submit" id="continueBtn" disabled>
                <span id="btnText">Continue</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </x-ui.button>
        </div>
    </form>
@endsection

@push('styles')
<style>
    .experience-list { margin: 24px 0; }

    .empty-state {
        border: 2px dashed var(--gray-300);
        border-radius: 12px;
        padding: 28px;
        text-align: center;
        color: var(--gray-500);
        font-size: 14px;
    }

    .experience-card {
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        padding: 20px;
        background: var(--white);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        position: relative;
        margin-bottom: 14px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .experience-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
    }

    .card-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 8px;
        z-index: 2;
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid var(--gray-300);
        border-radius: 10px;
        backdrop-filter: blur(6px);
        padding: 4px;
    }

    .icon-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--gray-300);
        background: var(--white);
        border-radius: 8px;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, transform .15s ease, border-color .2s ease;
    }

    .icon-btn:hover {
        transform: translateY(-1px);
    }

    .btn-edit:hover {
        background: var(--dark);
        color: #fff;
        border-color: var(--dark);
    }

    .btn-remove {
        border-radius: 50%;
        color: #DC2626;
        background: #FEE2E2;
        border-color: #FCA5A5;
    }

    .btn-remove:hover {
        background: #DC2626;
        color: #fff;
        border-color: #DC2626;
    }

    .card-header {
        padding-right: 96px;
    }

    .card-company {
        font-weight: 700;
        color: var(--gray-900);
        font-size: 16px;
        margin-bottom: 2px;
    }

    .card-title {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 14px;
        margin-bottom: 6px;
    }

    .card-date {
        font-size: 13px;
        color: var(--gray-500);
    }

    .card-description {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--gray-300);
        font-size: 14px;
        color: var(--gray-700);
    }

    .edit-card {
        height: none;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .current-role {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--gray-700);
        margin-top: 8px;
    }

    .current-role input {
        width: 16px;
        height: 16px;
        accent-color: var(--dark);
    }

    .add-experience-btn {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px dashed var(--gray-300);
        background: var(--gray-100);
        color: var(--gray-700);
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all .2s ease;
        margin-top: 12px;
        cursor: pointer;
    }

    .add-experience-btn:hover {
        background: #EEF2FF;
        border-color: var(--dark);
        color: var(--dark);
        transform: translateY(-1px);
    }

    @media (max-width: 640px) {
        .form-grid,
        .date-grid {
            grid-template-columns: 1fr;
        }
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

                    <div class="form-actions" style="padding-top: 8px;">
                        <button type="button" class="btn btn-back" onclick="cancelEdit(${exp.id})">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveExperience(${exp.id})">Save</button>
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