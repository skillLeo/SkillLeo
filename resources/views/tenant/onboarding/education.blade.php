@extends('layouts.onboarding')

@section('title', 'Education - ProMatch')

@section('card-content')

    <x-onboarding.form-header step="6" title="Your education"
        subtitle="Add your academic background and certifications" />

    <form id="educationForm" action="{{ route('tenant.onboarding.education.store') }}" method="POST">
        @csrf

        <div class="education-list" id="educationList">
            <div class="empty-state" id="emptyState">Add your highest degree first</div>
        </div>

        <button type="button" class="add-experience-btn" id="addBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            Add education
        </button>

        <input type="hidden" name="education" id="educationData">

        <x-onboarding.form-footer backUrl="{{ route('tenant.onboarding.portfolio') }}" />
    </form>

@endsection

@push('styles')
     

    <style>
        .education-list {
            margin: 24px 0;
        }

        .education-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            background: var(--card);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
            position: relative;
            margin-bottom: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .education-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
        }

        .edit-card {
            border-color: var(--accent);
        }

        .card-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 8px;
            z-index: 2;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            backdrop-filter: blur(6px);
            padding: 4px;
        }

        .icon-btn {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            background: var(--card);
            border-radius: var(--radius);
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease, transform 0.15s ease, border-color 0.2s ease;
        }

        .icon-btn:hover {
            transform: translateY(-1px);
        }

        .btn-edit:hover {
            background: var(--accent);
            color: var(--btn-text-primary);
            border-color: var(--accent);
        }

        .btn-remove {
            border-radius: 50%;
            color: var(--error);
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .btn-remove:hover {
            background: var(--error);
            color: var(--btn-text-primary);
            border-color: var(--error);
        }

        .card-header {
            padding-right: 96px;
        }

        .card-school {
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            font-size: var(--fs-title);
            margin-bottom: 2px;
        }

        .card-degree {
            font-weight: var(--fw-semibold);
            color: var(--text-body);
            font-size: var(--fs-body);
            margin-bottom: 6px;
        }

        .card-date {
            font-size: var(--fs-subtle);
            color: var(--text-muted);
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

        .add-experience-btn {
            width: 100%;
            padding: 14px 20px;
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
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .add-experience-btn:hover {
            border-color: var(--accent);
            background: var(--accent-light);
            color: var(--accent);
        }

        @media (max-width: 640px) {

            .form-grid,
            .date-grid {
                grid-template-columns: 1fr;
            }

            .card-header {
                padding-right: 76px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            "use strict";

            let education = [];
            let editingId = null;
            let counter = 0;

            const listEl = document.getElementById('educationList');
            const emptyEl = document.getElementById('emptyState');
            const addBtn = document.getElementById('addBtn');
            const continueBtn = document.getElementById('continueBtn');
            const educationDataInput = document.getElementById('educationData');

            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ];
            const currentYear = new Date().getFullYear();
            const years = Array.from({
                length: 50
            }, (_, i) => currentYear - i);

            const esc = (s) => String(s || '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[c]));

            function saveAll() {
                try {
                    localStorage.setItem('onboarding_education', JSON.stringify(education));
                } catch { }
            }

            function loadAll() {
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
                            startMonth: String(x?.startMonth || ''),
                            startYear: String(x?.startYear || ''),
                            endMonth: String(x?.endMonth || ''),
                            endYear: String(x?.endYear || ''),
                            current: !!x?.current
                        }));
                        counter = education.reduce((m, e) => Math.max(m, e.id), counter);
                    }
                } catch { }
            }

            function render() {
                if (education.length === 0) {
                    emptyEl.style.display = 'block';
                    listEl.innerHTML = '';
                    continueBtn.disabled = true;
                    saveAll();
                    return;
                }

                emptyEl.style.display = 'none';
                listEl.innerHTML = education.map(edu =>
                    editingId === edu.id ? renderEdit(edu) : renderDisplay(edu)
                ).join('');

                continueBtn.disabled = !education.some(e => e.school && e.degree);
                educationDataInput.value = JSON.stringify(education);
                saveAll();
            }

            function renderDisplay(edu) {
                const dateRange = formatDateRange(edu);
                return `
                    <div class="education-card" id="edu-${edu.id}">
                        <div class="card-actions">
                            <button type="button" class="icon-btn btn-edit" onclick="editEducation(${edu.id})" aria-label="Edit">✎</button>
                            <button type="button" class="icon-btn btn-remove" onclick="removeEducation(${edu.id})" aria-label="Remove">×</button>
                        </div>
                        <div class="card-header">
                            <div class="card-school">${esc(edu.school || 'School name')}</div>
                            <div class="card-degree">${esc(edu.degree || 'Degree')}${edu.field ? ` in ${esc(edu.field)}` : ''}</div>
                            <div class="card-date">${esc(dateRange)}</div>
                        </div>
                    </div>
                `;
            }

            function renderEdit(edu) {
                return `
                    <div class="education-card edit-card" id="edu-${edu.id}">
                        <div class="form-group">
                            <label class="form-label">School/University <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="University name" value="${esc(edu.school)}"
                                oninput="updateEducation(${edu.id}, 'school', this.value)"/>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Degree <span class="required">*</span></label>
                                <input type="text" class="form-input" placeholder="e.g., Bachelor's" value="${esc(edu.degree)}"
                                    oninput="updateEducation(${edu.id}, 'degree', this.value)"/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Field of study</label>
                                <input type="text" class="form-input" placeholder="e.g., Computer Science" value="${esc(edu.field)}"
                                    oninput="updateEducation(${edu.id}, 'field', this.value)"/>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Start date</label>
                                <div class="date-grid">
                                    <select class="form-select" onchange="updateEducation(${edu.id}, 'startMonth', this.value)">
                                        <option value="">Month</option>
                                        ${months.map((m, i) => `<option value="${i + 1}" ${edu.startMonth == i + 1 ? 'selected' : ''}>${m}</option>`).join('')}
                                    </select>
                                    <select class="form-select" onchange="updateEducation(${edu.id}, 'startYear', this.value)">
                                        <option value="">Year</option>
                                        ${years.map(y => `<option value="${y}" ${edu.startYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">End date</label>
                                <div class="date-grid">
                                    <select class="form-select end-month" ${edu.current ? 'disabled' : ''} onchange="updateEducation(${edu.id}, 'endMonth', this.value)">
                                        <option value="">Month</option>
                                        ${months.map((m, i) => `<option value="${i + 1}" ${edu.endMonth == i + 1 ? 'selected' : ''}>${m}</option>`).join('')}
                                    </select>
                                    <select class="form-select end-year" ${edu.current ? 'disabled' : ''} onchange="updateEducation(${edu.id}, 'endYear', this.value)">
                                        <option value="">Year</option>
                                        ${years.map(y => `<option value="${y}" ${edu.endYear == y ? 'selected' : ''}>${y}</option>`).join('')}
                                    </select>
                                </div>
                                <label class="current-role">
                                    <input type="checkbox" ${edu.current ? 'checked' : ''} onchange="updateEducation(${edu.id}, 'current', this.checked)"/>
                                    Currently studying
                                </label>
                            </div>
                        </div>

                        <div class="form-header-actions">
                            <button type="button" class="cancel-btn" onclick="cancelEdit(${edu.id})">Cancel</button>
                            <button type="button" class="save-btn" onclick="saveEducation(${edu.id})">✓ Save</button>
                        </div>
                    </div>
                `;
            }

            function formatDateRange(edu) {
                const start = edu.startMonth && edu.startYear ? `${months[edu.startMonth - 1]} ${edu.startYear}` : '';
                const end = edu.current ? 'Present' : (edu.endMonth && edu.endYear ?
                    `${months[edu.endMonth - 1]} ${edu.endYear}` : '');
                if (!start) return '';
                return end ? `${start} — ${end}` : start;
            }

            function addEducation() {
                const id = ++counter;
                education.unshift({
                    id,
                    school: '',
                    degree: '',
                    field: '',
                    startMonth: '',
                    startYear: '',
                    endMonth: '',
                    endYear: '',
                    current: false
                });
                editingId = id;
                render();
            }

            window.editEducation = (id) => {
                editingId = id;
                render();
            };

            window.removeEducation = (id) => {
                education = education.filter(e => e.id !== id);
                if (editingId === id) editingId = null;
                render();
            };

            window.cancelEdit = (id) => {
                const e = education.find(x => x.id === id);
                if (e && !e.school && !e.degree) {
                    window.removeEducation(id);
                } else {
                    editingId = null;
                    render();
                }
            };

            window.saveEducation = (id) => {
                const e = education.find(x => x.id === id);
                if (!e || !e.school.trim() || !e.degree.trim()) {
                    alert('Please fill in school and degree.');
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
                    if (e.current) {
                        e.endMonth = '';
                        e.endYear = '';
                    }
                    const card = document.getElementById(`edu-${id}`);
                    if (card) {
                        const endMonthSel = card.querySelector('.end-month');
                        const endYearSel = card.querySelector('.end-year');
                        if (endMonthSel && endYearSel) {
                            endMonthSel.disabled = e.current;
                            endYearSel.disabled = e.current;
                            endMonthSel.value = e.endMonth || '';
                            endYearSel.value = e.endYear || '';
                        }
                    }
                } else {
                    e[field] = value;
                }

                saveAll();
            };

            addBtn.addEventListener('click', addEducation);

            loadAll();
            render();
        });
    </script>
@endpush