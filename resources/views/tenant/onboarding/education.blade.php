@extends('layouts.onboarding')

@section('title', 'Education - ProMatch')

@php
    $currentStep = 6;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="form-header">
        <x-ui.step-badge label="Education" />
        <h1 class="form-title">Academic background</h1>
        <p class="form-subtitle">Add your educational qualifications to build credibility with clients.</p>
    </div>

    <form id="educationForm" action="{{ route('tenant.onboarding.education.store') }}" method="POST">
        @csrf

        <!-- Skip -->
        <div class="skip-section">
            Education is optional but highly recommended for better visibility.
            <div><button type="button" class="skip-btn" id="skipBtn">Skip for now</button></div>
        </div>

        <!-- List -->
        <div class="education-list" id="educationList">
            <div class="empty-state" id="emptyState">
                <div class="empty-icon">📚</div>
                <div class="empty-title">Add your first education</div>
                <div class="empty-subtitle">Start with your highest qualification</div>
            </div>
        </div>

        <!-- Add button -->
        <button type="button" class="add-project-btn" id="addEduBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Add education
        </button>

        <input type="hidden" name="education" id="educationData">

        <!-- Actions -->
        <div class="form-actions">
            <x-ui.button variant="back" href="{{ route('tenant.onboarding.portfolio') }}">
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
    .edu-card {
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        padding: 20px;
        background: var(--white);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        position: relative;
        margin-bottom: 14px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .display-card {
        background: var(--gray-100);
    }

    .display-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
        border-color: var(--gray-300);
    }

    .card-sub {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid var(--gray-300);
        font-size: 14px;
        color: var(--gray-700);
    }

    .card-pill {
        font-size: 11px;
        color: var(--gray-500);
        background: var(--white);
        padding: 4px 8px;
        border-radius: 12px;
        border: 1px solid var(--gray-300);
    }

    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 10px;
    }



    .form-header-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        margin-bottom: 12px;
    }

    .save-btn,
    .cancel-btn {
        padding: 10px 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: transform .15s ease, background .2s ease, color .2s ease, filter .2s ease;
        border: none;
        cursor: pointer;
    }

    .save-btn {
        background: var(--success);
        color: #fff;
    }

    .save-btn:hover {
        filter: brightness(0.95);
        transform: translateY(-1px);
    }

    .cancel-btn {
        background: var(--white);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .cancel-btn:hover {
        background: var(--gray-100);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let educations = [];
        let editingId = null;
        let counter = 0;

        const listEl = document.getElementById('educationList');
        const emptyState = document.getElementById('emptyState');
        const addBtn = document.getElementById('addEduBtn');
        const continueBtn = document.getElementById('continueBtn');
        const skipBtn = document.getElementById('skipBtn');
        const educationDataInput = document.getElementById('educationData');

        function render() {
            listEl.innerHTML = '';
            if (!educations.length) {
                emptyState.style.display = 'block';
                listEl.appendChild(emptyState);
                continueBtn.disabled = true;
                return;
            }

            emptyState.style.display = 'none';
            educations.forEach(e => {
                listEl.insertAdjacentHTML('beforeend', (editingId === e.id) ? renderEdit(e) : renderCard(e));
            });

            continueBtn.disabled = (editingId != null);
            educationDataInput.value = JSON.stringify(educations);
        }

        function renderCard(e) {
            const school = e.school || 'School / University';
            const degree = e.degree || 'Degree';
            const grade = e.grade ? `<span class="card-pill">${escapeHtml(e.grade)}</span>` : '<span></span>';
            return `
                <div class="edu-card display-card" id="edu-${e.id}">
                    <div class="card-actions">
                        <button type="button" class="card-edit" onclick="editEducation(${e.id})">✎</button>
                        <button type="button" class="card-remove" onclick="removeEducation(${e.id})">×</button>
                    </div>
                    <div class="card-header">
                        <div class="card-title">${escapeHtml(school)}</div>
                        <div class="card-sub">${escapeHtml(degree)}</div>
                    </div>
                    <div class="card-footer">
                        <span style="opacity:.6;font-size:12px;">Education entry</span>
                        ${grade}
                    </div>
                </div>
            `;
        }

        function renderEdit(e) {
            return `
                <div class="edu-card edit-card" id="edu-${e.id}">
                    <div class="form-header-actions">
                        <button type="button" class="save-btn" onclick="saveEducation(${e.id})">✓ Save</button>
                        <button type="button" class="cancel-btn" onclick="cancelEdit(${e.id})">Cancel</button>
                    </div>

                    <div class="form-group">
                        <label class="form-label">School / University <span class="required">*</span></label>
                        <input class="form-input" value="${escapeHtml(e.school)}" data-id="${e.id}" data-field="school" placeholder="University of Oxford"/>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Degree <span class="required">*</span></label>
                        <input class="form-input" value="${escapeHtml(e.degree)}" data-id="${e.id}" data-field="degree" placeholder="Bachelor of Computer Science"/>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Grade / Score (optional)</label>
                        <input class="form-input" value="${escapeHtml(e.grade)}" data-id="${e.id}" data-field="grade" placeholder="3.8 GPA, 85%, First Class, etc."/>
                    </div>
                </div>
            `;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        function addEducation() {
            const id = ++counter;
            educations.unshift({ id, school: '', degree: '', grade: '' });
            editingId = id;
            render();
        }

        window.editEducation = (id) => { editingId = id; render(); };
        window.removeEducation = (id) => { educations = educations.filter(x => x.id !== id); if (editingId === id) editingId = null; render(); };
        window.cancelEdit = (id) => { const e = educations.find(x => x.id === id); if (e && !e.school && !e.degree && !e.grade) window.removeEducation(id); else { editingId = null; render(); } };
        window.saveEducation = (id) => { const e = educations.find(x => x.id === id); if (!e || !e.school.trim() || !e.degree.trim()) { alert('Please fill in both School and Degree.'); return; } editingId = null; render(); };

        listEl.addEventListener('input', (ev) => {
            const el = ev.target;
            if (!el.dataset?.field) return;
            const e = educations.find(x => x.id == el.dataset.id);
            if (e) e[el.dataset.field] = el.value;
        });

        addBtn.addEventListener('click', addEducation);
        skipBtn.addEventListener('click', () => window.location.href = '{{ route("tenant.onboarding.preferences") }}');
        render();
    });
</script>
@endpush