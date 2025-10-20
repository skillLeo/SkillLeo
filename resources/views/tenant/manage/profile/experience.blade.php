@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Carbon;
@endphp
@extends('tenant.manage.app')

@section('title', 'Manage Experience - ' . $user->name)

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
            <h1 class="page-title">Experience</h1>
            <p class="page-subtitle">Manage your work history and roles</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('editExperienceModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Edit Experience
            </button>
        </div>
    </div>

    <div class="content-section">
        <div class="experience-timeline">
            @forelse($owner->experiences as $experience)
                <div class="experience-item" data-experience-id="{{ $experience->id }}">
                    <div class="experience-marker">
                        @if($experience->is_current)
                            <div class="marker-dot active"></div>
                        @else
                            <div class="marker-dot"></div>
                        @endif
                    </div>
                    <div class="experience-card">
                        <div class="experience-header">
                            <div class="experience-main">
                                <h4 class="experience-title">{{ $experience->title }}</h4>
                                <p class="experience-company">{{ $experience->company }}</p>
                            </div>
                        </div>

                        <div class="experience-meta">
                            <span class="experience-period">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                @if($experience->start_month && $experience->start_year)
                                    {{ \Carbon\Carbon::createFromDate($experience->start_year, $experience->start_month, 1)->format('M Y') }}
                                @else
                                    {{ $experience->start_year ?: '—' }}
                                @endif
                                —
                                @if($experience->is_current)
                                    Present
                                @elseif($experience->end_month && $experience->end_year)
                                    {{ \Carbon\Carbon::createFromDate($experience->end_year, $experience->end_month, 1)->format('M Y') }}
                                @else
                                    {{ $experience->end_year ?: '—' }}
                                @endif
                            </span>
                            @if($experience->location_city || $experience->location_country)
                                <span class="experience-location">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ collect([$experience->location_city, $experience->location_country])->filter()->join(', ') }}
                                </span>
                            @endif
                        </div>

                        @if($experience->description)
                            <p class="experience-description">{{ Str::limit($experience->description, 150) }}</p>
                        @endif

                        @if($experience->skills && $experience->skills->count() > 0)
                            <div class="experience-skills">
                                @foreach($experience->skills->take(5) as $skill)
                                    <span class="skill-tag">{{ $skill->name }}</span>
                                @endforeach
                                @if($experience->skills->count() > 5)
                                    <span class="skill-tag-more">+{{ $experience->skills->count() - 5 }}</span>
                                @endif
                            </div>
                        @endif

                        @if($experience->is_current)
                            <span class="experience-badge">Currently working here</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    <h3>No experience added yet</h3>
                    <p>Start building your professional profile by adding your work experience</p>
                    <button class="btn btn-primary" onclick="openModal('editExperienceModal')">Add First Experience</button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Include Edit Experience Modal --}}
    <x-modals.edits.edit-experience 
        :modal-experiences="$modalExperiences" 
        :username="$username" 
    />
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">💼 Experience Guide</h3>
            <p class="inspector-desc">Tips for adding your experience</p>
        </div>

        <div class="help-card">
            <h4>Writing Tips</h4>
            <ul class="help-list">
                <li>Use action verbs (Led, Built, Managed)</li>
                <li>Quantify achievements with numbers</li>
                <li>Focus on impact and results</li>
                <li>Keep descriptions concise (2-3 lines)</li>
            </ul>
        </div>

        <div class="help-card accent">
            <h4>🎯 Pro Tip</h4>
            <p>Profiles with detailed experience get 2x more profile views</p>
        </div>
    </div>
@endsection
@push('styles')
<style>
/* ============================================
   PROFESSIONAL EXPERIENCE PAGE - TIMELINE
   ============================================ */

/* ============ EXPERIENCE TIMELINE ============ */
.experience-timeline {
    position: relative;
    padding-left: 32px;
}

.experience-timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--accent), transparent);
}

.experience-item {
    position: relative;
    margin-bottom: 28px;
    cursor: pointer;
}

.experience-item:last-child {
    margin-bottom: 0;
}

/* ============ EXPERIENCE MARKER ============ */
.experience-marker {
    position: absolute;
    left: -24px;
    top: 10px;
    z-index: 2;
}

.experience-item:hover .marker-dot {
    border-color: var(--accent);
    transform: scale(1.25);
}

/* ============ EXPERIENCE CARD ============ */
.experience-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.experience-item:hover .experience-card {
    border-color: var(--accent);
    box-shadow: 0 4px 20px rgba(var(--accent-rgb), 0.1);
    transform: translateX(6px);
}

.experience-item.selected .experience-card {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
}

/* ============ EXPERIENCE HEADER ============ */
.experience-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 14px;
}

.experience-main {
    flex: 1;
}

.experience-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 6px 0;
    line-height: 1.3;
    letter-spacing: -0.02em;
}

.experience-company {
    font-size: 15px;
    color: var(--text-body);
    margin: 0;
    font-weight: 500;
}

/* ============ EXPERIENCE DELETE ============ */
.experience-delete {
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

.experience-item:hover .experience-delete {
    opacity: 1;
}

.experience-delete:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

/* ============ EXPERIENCE META ============ */
.experience-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
    margin-bottom: 14px;
}

.experience-period,
.experience-location {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-muted);
}

.experience-period svg,
.experience-location svg {
    flex-shrink: 0;
    opacity: 0.6;
}

/* ============ EXPERIENCE DESCRIPTION ============ */
.experience-description {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-body);
    margin: 0 0 14px 0;
}

/* ============ EXPERIENCE SKILLS ============ */
.experience-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 14px;
}

.skill-tag {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    background: var(--apc-bg);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-body);
    transition: all 0.2s ease;
}

.skill-tag:hover {
    border-color: var(--accent);
    background: rgba(var(--accent-rgb), 0.05);
}

.skill-tag-more {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    background: rgba(var(--accent-rgb), 0.1);
    border: 1px solid var(--accent);
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    color: var(--accent);
}

/* ============ EXPERIENCE BADGE ============ */
.experience-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(var(--accent-rgb), 0.1);
    color: var(--accent);
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 14px;
}

/* ============ FORM SELECT ============ */
select.form-control {
    cursor: pointer;
}

/* ============ RESPONSIVE ============ */
@media (max-width: 768px) {
    .experience-timeline {
        padding-left: 28px;
    }

    .experience-marker {
        left: -20px;
    }

    .experience-delete {
        opacity: 1;
    }

    .experience-item:hover .experience-card {
        transform: none;
    }

    .experience-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>
@endpush

@push('scripts')
@php
    $experiencePayload = $owner->experiences->map(function ($e) {
        return [
            'db_id' => $e->id,
            'company' => $e->company,
            'company_id' => $e->company_id,
            'title' => $e->title,
            'startMonth' => $e->start_month,
            'startYear' => $e->start_year,
            'endMonth' => $e->end_month,
            'endYear' => $e->end_year,
            'current' => (bool)$e->is_current,
            'locationCity' => $e->location_city,
            'locationCountry' => $e->location_country,
            'description' => $e->description,
            'skills' => $e->skills->map(fn($s) => ['name' => $s->name, 'level' => $s->level ?? 2])->values(),
            'position' => $e->position,
        ];
    })->values();
@endphp

<script>
let experienceArray = @json($experiencePayload);

function addNewExperience() {
    document.getElementById('inspectorDefault').style.display = 'none';
    document.getElementById('inspectorForm').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Add Experience';
    document.getElementById('experienceId').value = '';
    document.getElementById('experienceTitle').value = '';
    document.getElementById('experienceCompany').value = '';
    document.getElementById('experienceStartMonth').value = '';
    document.getElementById('experienceStartYear').value = '';
    document.getElementById('experienceEndMonth').value = '';
    document.getElementById('experienceEndYear').value = '';
    document.getElementById('experienceCurrent').checked = false;
    document.getElementById('experienceCity').value = '';
    document.getElementById('experienceCountry').value = '';
    document.getElementById('experienceDescription').value = '';
    document.getElementById('endDateFields').style.display = 'grid';
    
    updateCharCount();
    document.getElementById('experienceTitle').focus();
}

function selectExperience(id) {
    document.querySelectorAll('.experience-item').forEach(item => item.classList.remove('selected'));
    const item = document.querySelector(`[data-experience-id="${id}"]`);
    if (item) item.classList.add('selected');
    
    const experience = experienceArray.find(e => e.db_id === id);
    if (!experience) return;
    
    document.getElementById('inspectorDefault').style.display = 'none';
    document.getElementById('inspectorForm').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Edit Experience';
    document.getElementById('experienceId').value = id;
    document.getElementById('experienceTitle').value = experience.title;
    document.getElementById('experienceCompany').value = experience.company;
    document.getElementById('experienceStartMonth').value = experience.startMonth || '';
    document.getElementById('experienceStartYear').value = experience.startYear || '';
    document.getElementById('experienceEndMonth').value = experience.endMonth || '';
    document.getElementById('experienceEndYear').value = experience.endYear || '';
    document.getElementById('experienceCurrent').checked = experience.current;
    document.getElementById('experienceCity').value = experience.locationCity || '';
    document.getElementById('experienceCountry').value = experience.locationCountry || '';
    document.getElementById('experienceDescription').value = experience.description || '';
    
    document.getElementById('endDateFields').style.display = experience.current ? 'none' : 'grid';
    
    updateCharCount();
}

function saveExperience() {
    const id = document.getElementById('experienceId').value;
    const title = document.getElementById('experienceTitle').value.trim();
    const company = document.getElementById('experienceCompany').value.trim();
    const startMonth = parseInt(document.getElementById('experienceStartMonth').value) || null;
    const startYear = parseInt(document.getElementById('experienceStartYear').value) || null;
    const endMonth = parseInt(document.getElementById('experienceEndMonth').value) || null;
    const endYear = parseInt(document.getElementById('experienceEndYear').value) || null;
    const current = document.getElementById('experienceCurrent').checked;
    const locationCity = document.getElementById('experienceCity').value.trim();
    const locationCountry = document.getElementById('experienceCountry').value.trim();
    const description = document.getElementById('experienceDescription').value.trim();
    
    if (!title || !company) {
        alert('Please fill in required fields');
        return;
    }
    
    const experienceData = {
        title,
        company,
        company_id: null,
        startMonth,
        startYear,
        endMonth: current ? null : endMonth,
        endYear: current ? null : endYear,
        current,
        locationCity,
        locationCountry,
        description,
        skills: []
    };
    
    if (id) {
        const index = experienceArray.findIndex(e => e.db_id == id);
        if (index !== -1) {
            experienceArray[index] = { ...experienceArray[index], ...experienceData };
        }
    } else {
        experienceArray.push({
            db_id: null,
            ...experienceData,
            position: experienceArray.length
        });
    }
    
    submitExperience();
}

function deleteExperience(id, title) {
    if (confirm(`Delete experience as ${title}?`)) {
        experienceArray = experienceArray.filter(e => e.db_id != id);
        submitExperience();
    }
}

function submitExperience() {
    document.getElementById('experienceData').value = JSON.stringify(experienceArray);
    document.getElementById('experienceUpdateForm').submit();
}

function closeInspector() {
    document.getElementById('inspectorForm').style.display = 'none';
    document.getElementById('inspectorDefault').style.display = 'block';
    document.querySelectorAll('.experience-item').forEach(item => item.classList.remove('selected'));
}

// Toggle end date fields
document.getElementById('experienceCurrent')?.addEventListener('change', (e) => {
    const endDateFields = document.getElementById('endDateFields');
    if (e.target.checked) {
        endDateFields.style.display = 'none';
        document.getElementById('experienceEndMonth').value = '';
        document.getElementById('experienceEndYear').value = '';
    } else {
        endDateFields.style.display = 'grid';
    }
});

// Character counter
function updateCharCount() {
    const desc = document.getElementById('experienceDescription');
    const count = document.getElementById('descCount');
    if (desc && count) {
        count.textContent = desc.value.length;
    }
}

document.getElementById('experienceDescription')?.addEventListener('input', updateCharCount);

// Search
document.getElementById('experienceSearch')?.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase();
    document.querySelectorAll('.experience-item').forEach(item => {
        const title = item.querySelector('.experience-title').textContent.toLowerCase();
        const company = item.querySelector('.experience-company').textContent.toLowerCase();
        item.style.display = (title.includes(query) || company.includes(query)) ? 'block' : 'none';
    });
});

// Keyboard Shortcuts
document.addEventListener('keydown', (e) => {
    if (e.target.matches('input, textarea, select')) return;
    if (e.key.toLowerCase() === 'a') { e.preventDefault(); addNewExperience(); }
    if (e.key === 'Escape') closeInspector();
});

function importExperience() { alert('Import feature coming soon!'); }
</script>
@endpush