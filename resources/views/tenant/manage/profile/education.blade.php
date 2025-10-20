@extends('tenant.manage.app')

@section('title', 'Manage Education - ' . $user->name)

@section('main')
    @if (session('success'))
        <div class="alert alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1 class="page-title">Education</h1>
            <p class="page-subtitle">Manage your academic background</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('editEducationModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Edit Education
            </button>
        </div>
    </div>

    <div class="content-section">
        <div class="education-timeline">
            @forelse($owner->educations as $education)
                <div class="education-item" data-education-id="{{ $education->id }}">
                    <div class="education-marker">
                        @if($education->is_current)
                            <div class="marker-dot active"></div>
                        @else
                            <div class="marker-dot"></div>
                        @endif
                    </div>
                    <div class="education-card">
                        <div class="education-header">
                            <div class="education-main">
                                <h4 class="education-degree">{{ $education->degree }}{{ $education->field ? ' in ' . $education->field : '' }}</h4>
                                <p class="education-school">{{ $education->school }}</p>
                            </div>
                        </div>
                        <div class="education-meta">
                            <span class="education-period">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                {{ $education->start_year ?: '—' }} - {{ $education->is_current ? 'Present' : ($education->end_year ?: '—') }}
                            </span>
                        </div>
                        @if($education->is_current)
                            <span class="education-badge">Currently studying</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    <h3>No education added yet</h3>
                    <p>Add your academic background to complete your profile</p>
                    <button class="btn btn-primary" onclick="openModal('editEducationModal')">Add First Education</button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Include Edit Education Modal --}}
    <x-modals.edits.edit-education 
        :modal-educations="$modalEducations" 
        :user-educations="$userEducations" 
        :institutions-search-url="route('api.institutions.search')" 
        :username="$username" 
    />
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">🎓 Education Guide</h3>
            <p class="inspector-desc">Tips for adding your education</p>
        </div>

        <div class="help-card">
            <h4>What to Include</h4>
            <ul class="help-list">
                <li>Degree type and field of study</li>
                <li>School or institution name</li>
                <li>Start and end dates</li>
                <li>Location (city, country)</li>
            </ul>
        </div>

        <div class="help-card accent">
            <h4>🎯 Pro Tip</h4>
            <p>List your most recent education first for better visibility</p>
        </div>
    </div>
@endsection
    @push('styles')
    <style>
    /* ============================================
       PROFESSIONAL EDUCATION PAGE - TIMELINE STYLE
       ============================================ */
    
    /* ============ EDUCATION TIMELINE ============ */
    .education-timeline {
        position: relative;
        padding-left: 32px;
    }
    
    .education-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--accent), transparent);
    }
    
    .education-item {
        position: relative;
        margin-bottom: 24px;
        cursor: pointer;
    }
    
    .education-item:last-child {
        margin-bottom: 0;
    }
    
    /* ============ EDUCATION MARKER ============ */
    .education-marker {
        position: absolute;
        left: -24px;
        top: 8px;
        z-index: 2;
    }
    
    .marker-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--card);
        border: 3px solid var(--border);
        transition: all 0.25s ease;
    }
    
    .marker-dot.active {
        border-color: var(--accent);
        background: var(--accent);
        box-shadow: 0 0 0 4px rgba(var(--accent-rgb), 0.1);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 4px rgba(var(--accent-rgb), 0.1); }
        50% { box-shadow: 0 0 0 8px rgba(var(--accent-rgb), 0.05); }
    }
    
    .education-item:hover .marker-dot {
        border-color: var(--accent);
        transform: scale(1.2);
    }
    
    /* ============ EDUCATION CARD ============ */
    .education-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    .education-item:hover .education-card {
        border-color: var(--accent);
        box-shadow: 0 4px 16px rgba(var(--accent-rgb), 0.08);
        transform: translateX(4px);
    }
    
    .education-item.selected .education-card {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
    }
    
    /* ============ EDUCATION HEADER ============ */
    .education-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
    }
    
    .education-main {
        flex: 1;
    }
    
    .education-degree {
        font-size: 17px;
        font-weight: 600;
        color: var(--text-heading);
        margin: 0 0 6px 0;
        line-height: 1.4;
        letter-spacing: -0.01em;
    }
    
    .education-school {
        font-size: 15px;
        color: var(--text-body);
        margin: 0;
        font-weight: 500;
    }
    
    /* ============ EDUCATION DELETE ============ */
    .education-delete {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: 1px solid var(--border);
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 6px;
        opacity: 0;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    
    .education-item:hover .education-delete {
        opacity: 1;
    }
    
    .education-delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }
    
    /* ============ EDUCATION META ============ */
    .education-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
    }
    
    .education-period,
    .education-location {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .education-period svg,
    .education-location svg {
        flex-shrink: 0;
        opacity: 0.6;
    }
    
    /* ============ EDUCATION BADGE ============ */
    .education-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(var(--accent-rgb), 0.1);
        color: var(--accent);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 12px;
    }
    
    /* ============ FORM ROW ============ */
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    /* ============ CHECKBOX ============ */
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 14px;
        color: var(--text-body);
    }
    
    .checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--accent);
    }
    
    /* ============ EMPTY STATE ============ */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }
    
    .empty-state svg {
        color: var(--text-muted);
        opacity: 0.15;
        margin-bottom: 24px;
    }
    
    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-heading);
        margin: 0 0 12px 0;
        letter-spacing: -0.01em;
    }
    
    .empty-state p {
        font-size: 15px;
        color: var(--text-muted);
        margin: 0 0 28px 0;
        line-height: 1.5;
    }
    
    /* ============ RESPONSIVE ============ */
    @media (max-width: 768px) {
        .education-timeline {
            padding-left: 28px;
        }
    
        .education-marker {
            left: -20px;
        }
    
        .form-row {
            grid-template-columns: 1fr;
        }
    
        .education-delete {
            opacity: 1;
        }
    
        .education-item:hover .education-card {
            transform: none;
        }
    }
    </style>
    @endpush
    
    @push('scripts')
    @php
        $educationPayload = $owner->educations->map(function ($e) {
            return [
                'db_id' => $e->id,
                'school' => $e->school,
                'degree' => $e->degree,
                'field' => $e->field,
                'startYear' => $e->start_year,
                'endYear' => $e->end_year,
                'current' => (bool)$e->is_current,
                'city' => $e->city,
                'country' => $e->country,
                'position' => $e->position,
            ];
        })->values();
    @endphp
    
    <script>
    let educationArray = @json($educationPayload);
    
    function addNewEducation() {
        document.getElementById('inspectorDefault').style.display = 'none';
        document.getElementById('inspectorForm').style.display = 'block';
        document.getElementById('formTitle').textContent = 'Add Education';
        document.getElementById('educationId').value = '';
        document.getElementById('educationSchool').value = '';
        document.getElementById('educationDegree').value = '';
        document.getElementById('educationField').value = '';
        document.getElementById('educationStartYear').value = '';
        document.getElementById('educationEndYear').value = '';
        document.getElementById('educationCurrent').checked = false;
        document.getElementById('educationCity').value = '';
        document.getElementById('educationCountry').value = '';
        
        document.getElementById('educationSchool').focus();
    }
    
    function selectEducation(id) {
        document.querySelectorAll('.education-item').forEach(item => item.classList.remove('selected'));
        const item = document.querySelector(`[data-education-id="${id}"]`);
        if (item) item.classList.add('selected');
        
        const education = educationArray.find(e => e.db_id === id);
        if (!education) return;
        
        document.getElementById('inspectorDefault').style.display = 'none';
        document.getElementById('inspectorForm').style.display = 'block';
        document.getElementById('formTitle').textContent = 'Edit Education';
        document.getElementById('educationId').value = id;
        document.getElementById('educationSchool').value = education.school;
        document.getElementById('educationDegree').value = education.degree;
        document.getElementById('educationField').value = education.field || '';
        document.getElementById('educationStartYear').value = education.startYear || '';
        document.getElementById('educationEndYear').value = education.endYear || '';
        document.getElementById('educationCurrent').checked = education.current;
        document.getElementById('educationCity').value = education.city || '';
        document.getElementById('educationCountry').value = education.country || '';
    }
    
    function saveEducation() {
        const id = document.getElementById('educationId').value;
        const school = document.getElementById('educationSchool').value.trim();
        const degree = document.getElementById('educationDegree').value.trim();
        const field = document.getElementById('educationField').value.trim();
        const startYear = parseInt(document.getElementById('educationStartYear').value) || null;
        const endYear = parseInt(document.getElementById('educationEndYear').value) || null;
        const current = document.getElementById('educationCurrent').checked;
        const city = document.getElementById('educationCity').value.trim();
        const country = document.getElementById('educationCountry').value.trim();
        
        if (!school || !degree) {
            alert('Please fill in required fields');
            return;
        }
        
        const educationData = {
            school,
            degree,
            field,
            startYear,
            endYear: current ? null : endYear,
            current,
            city,
            country
        };
        
        if (id) {
            const index = educationArray.findIndex(e => e.db_id == id);
            if (index !== -1) {
                educationArray[index] = { ...educationArray[index], ...educationData };
            }
        } else {
            educationArray.push({
                db_id: null,
                ...educationData,
                position: educationArray.length
            });
        }
        
        submitEducation();
    }
    
    function deleteEducation(id, school) {
        if (confirm(`Delete education from ${school}?`)) {
            educationArray = educationArray.filter(e => e.db_id != id);
            submitEducation();
        }
    }
    
    function submitEducation() {
        document.getElementById('educationData').value = JSON.stringify(educationArray);
        document.getElementById('educationUpdateForm').submit();
    }
    
    function closeInspector() {
        document.getElementById('inspectorForm').style.display = 'none';
        document.getElementById('inspectorDefault').style.display = 'block';
        document.querySelectorAll('.education-item').forEach(item => item.classList.remove('selected'));
    }
    
    // Toggle end year when "currently studying" is checked
    document.getElementById('educationCurrent')?.addEventListener('change', (e) => {
        const endYearInput = document.getElementById('educationEndYear');
        if (e.target.checked) {
            endYearInput.value = '';
            endYearInput.disabled = true;
        } else {
            endYearInput.disabled = false;
        }
    });
    
    // Search
    document.getElementById('educationSearch')?.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.education-item').forEach(item => {
            const school = item.querySelector('.education-school').textContent.toLowerCase();
            const degree = item.querySelector('.education-degree').textContent.toLowerCase();
            item.style.display = (school.includes(query) || degree.includes(query)) ? 'block' : 'none';
        });
    });
    
    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.target.matches('input, textarea')) return;
        if (e.key.toLowerCase() === 'a') { e.preventDefault(); addNewEducation(); }
        if (e.key === 'Escape') closeInspector();
    });
    
    function importEducation() { alert('Import feature coming soon!'); }
    </script>
    @endpush

