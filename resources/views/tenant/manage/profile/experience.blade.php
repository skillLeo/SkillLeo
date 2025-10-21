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

    {{-- Search Bar --}}
    <div class="search-container">
        <div class="search-box">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" 
                   id="experienceSearchInput" 
                   class="search-input" 
                   placeholder="Search by job title, company, or location..."
                   autocomplete="off">
            <button type="button" class="search-clear" id="searchClearBtn" style="display: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="search-results" id="searchResults">
            <span class="results-text">Showing <strong id="visibleCount">{{ $owner->experiences->count() }}</strong> of <strong id="totalCount">{{ $owner->experiences->count() }}</strong> positions</span>
        </div>
    </div>

    <div class="content-section">
        <div class="experience-timeline" id="experienceTimeline">
            @forelse($owner->experiences as $experience)
                @php
                    $searchText = strtolower(
                        $experience->title . ' ' . 
                        $experience->company . ' ' . 
                        ($experience->location_city ?? '') . ' ' . 
                        ($experience->location_country ?? '') . ' ' . 
                        ($experience->description ?? '')
                    );
                @endphp

                <div class="experience-item" 
                     data-experience-id="{{ $experience->id }}"
                     data-search-text="{{ $searchText }}">
                    <div class="experience-marker">
                        @if($experience->is_current)
                            <div class="marker-dot active">
                                <span class="pulse-ring"></span>
                            </div>
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
                                    {{ Carbon::createFromDate($experience->start_year, $experience->start_month, 1)->format('M Y') }}
                                @else
                                    {{ $experience->start_year ?: '—' }}
                                @endif
                                —
                                @if($experience->is_current)
                                    <span class="current-badge">Present</span>
                                @elseif($experience->end_month && $experience->end_year)
                                    {{ Carbon::createFromDate($experience->end_year, $experience->end_month, 1)->format('M Y') }}
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
                            <p class="experience-description">{{ Str::limit($experience->description, 180) }}</p>
                        @endif

                        @if($experience->skills && $experience->skills->count() > 0)
                            <div class="experience-skills">
                                @foreach($experience->skills->take(6) as $skill)
                                    <span class="skill-tag">{{ $skill->name }}</span>
                                @endforeach
                                @if($experience->skills->count() > 6)
                                    <span class="skill-tag-more">+{{ $experience->skills->count() - 6 }}</span>
                                @endif
                            </div>
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

        {{-- No Results Message --}}
        <div class="no-results" id="noResults" style="display: none;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <h3>No experience found</h3>
            <p>Try adjusting your search query</p>
            <button type="button" class="btn btn-primary" onclick="clearSearch()">Clear Search</button>
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
            <p>Profiles with detailed experience get 2x more profile views. Add specific metrics and outcomes!</p>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* ============ ALERTS ============ */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    animation: slideIn 0.3s ease;
}

.alert svg {
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ============ PAGE HEADER ============ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 6px 0;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
    font-weight: 400;
}

.page-actions {
    display: flex;
    gap: 8px;
}

/* ============ BUTTONS ============ */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 32px;
    padding: 0 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    font-family: inherit;
}

.btn svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(var(--accent-rgb), 0.3);
}

/* ============ SEARCH CONTAINER ============ */
.search-container {
    margin-bottom: 24px;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: var(--card);
    border: 2px solid var(--border);
    border-radius: 8px;
    padding: 0 14px;
    transition: all 0.2s ease;
    max-width: 600px;
}

.search-box:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
}

.search-icon {
    flex-shrink: 0;
    color: var(--text-muted);
    transition: color 0.2s ease;
}

.search-box:focus-within .search-icon {
    color: var(--accent);
}

.search-input {
    flex: 1;
    border: none;
    background: none;
    outline: none;
    padding: 12px 12px;
    font-size: 14px;
    color: var(--text-body);
    font-family: inherit;
}

.search-input::placeholder {
    color: var(--text-muted);
    opacity: 0.6;
}

.search-clear {
    display: none;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: var(--apc-bg);
    border: none;
    border-radius: 4px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s ease;
}

.search-clear:hover {
    background: var(--border);
    color: var(--text-heading);
}

.search-results {
    margin-top: 12px;
    padding: 8px 0;
}

.results-text {
    font-size: 13px;
    color: var(--text-muted);
}

.results-text strong {
    color: var(--accent);
    font-weight: 600;
}



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
    background: linear-gradient(to bottom, var(--accent), rgba(var(--accent-rgb), 0.1));
}

.experience-item {
    position: relative;
    margin-bottom: 32px;
    transition: all 0.3s ease;
}

.experience-item:last-child {
    margin-bottom: 0;
}

.experience-item.hidden {
    display: none !important;
}

/* ============ EXPERIENCE MARKER ============ */
.experience-marker {
    position: absolute;
    left: -24px;
    top: 12px;
    z-index: 2;
}

.marker-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--card);
    border: 3px solid var(--border);
    transition: all 0.3s ease;
    position: relative;
}

.marker-dot.active {
    border-color: var(--accent);
    background: var(--accent);
    box-shadow: 0 0 0 4px rgba(var(--accent-rgb), 0.15);
}

.pulse-ring {
    position: absolute;
    inset: -6px;
    border: 2px solid var(--accent);
    border-radius: 50%;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.4);
        opacity: 0;
    }
}

.experience-item:hover .marker-dot {
    border-color: var(--accent);
    transform: scale(1.2);
}

/* ============ EXPERIENCE CARD ============ */
.experience-card {
    background: var(--card);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.experience-item:hover .experience-card {
    border-color: var(--accent);
    box-shadow: 0 8px 24px rgba(var(--accent-rgb), 0.12);
    transform: translateX(8px);
}

/* ============ EXPERIENCE HEADER ============ */
.experience-header {
    margin-bottom: 14px;
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
    color: var(--accent);
    margin: 0;
    font-weight: 600;
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

.current-badge {
    display: inline-flex;
    padding: 2px 8px;
    background: rgba(var(--accent-rgb), 0.15);
    color: var(--accent);
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* ============ EXPERIENCE DESCRIPTION ============ */
.experience-description {
    font-size: 14px;
    line-height: 1.7;
    color: var(--text-body);
    margin: 0 0 14px 0;
}

/* ============ EXPERIENCE SKILLS ============ */
.experience-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 16px;
}

.skill-tag {
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    background: var(--apc-bg);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-body);
    transition: all 0.2s ease;
}

.skill-tag:hover {
    border-color: var(--accent);
    background: rgba(var(--accent-rgb), 0.08);
    color: var(--accent);
}

.skill-tag-more {
    display: inline-flex;
    align-items: center;
    padding: 5px 10px;
    background: rgba(var(--accent-rgb), 0.1);
    border: 1px solid var(--accent);
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    color: var(--accent);
}

/* ============ EMPTY & NO RESULTS ============ */
.empty-state, .no-results {
    text-align: center;
    padding: 80px 20px;
}

.empty-state svg, .no-results svg {
    color: var(--text-muted);
    opacity: 0.15;
    margin-bottom: 24px;
}

.empty-state h3, .no-results h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 12px 0;
}

.empty-state p, .no-results p {
    font-size: 15px;
    color: var(--text-muted);
    margin: 0 0 28px 0;
}

/* ============ HELP CARDS ============ */
.help-card {
    padding: 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 16px;
}

.help-card h4 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 12px 0;
    color: var(--text-heading);
}

.help-card.accent {
    background: linear-gradient(135deg, rgba(var(--accent-rgb), 0.1), rgba(var(--accent-rgb), 0.05));
    border-color: var(--accent);
}

.help-card p {
    margin: 0;
    font-size: 13px;
    color: var(--text-body);
    line-height: 1.5;
}

.help-list {
    margin: 0;
    padding-left: 20px;
}

.help-list li {
    font-size: 13px;
    margin-bottom: 8px;
    color: var(--text-body);
    line-height: 1.5;
}

/* ============ RESPONSIVE ============ */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .search-box {
        max-width: 100%;
    }

    .experience-timeline {
        padding-left: 28px;
    }

    .experience-marker {
        left: -20px;
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
<script>
(function() {
    'use strict';

    const searchInput = document.getElementById('experienceSearchInput');
    const clearBtn = document.getElementById('searchClearBtn');
    const visibleCountEl = document.getElementById('visibleCount');
    const totalCountEl = document.getElementById('totalCount');
    const noResultsEl = document.getElementById('noResults');
    const timeline = document.getElementById('experienceTimeline');

    const allItems = document.querySelectorAll('.experience-item[data-search-text]');
    const totalCount = allItems.length;

    function performSearch(query) {
        const searchTerm = query.toLowerCase().trim();
        let visibleCount = 0;

        allItems.forEach(item => {
            const searchText = item.dataset.searchText || '';
            const matches = searchText.includes(searchTerm);
            
            item.classList.toggle('hidden', !matches);
            
            if (matches) {
                visibleCount++;
            }
        });

        visibleCountEl.textContent = visibleCount;

        const hasResults = visibleCount > 0;
        noResultsEl.style.display = hasResults ? 'none' : 'flex';
        timeline.style.display = hasResults ? 'block' : 'none';

        clearBtn.style.display = searchTerm ? 'flex' : 'none';
    }

    window.clearSearch = function() {
        searchInput.value = '';
        performSearch('');
        searchInput.focus();
    };

    searchInput.addEventListener('input', (e) => {
        performSearch(e.target.value);
    });

    clearBtn.addEventListener('click', () => {
        clearSearch();
    });

    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }

        if (e.key === 'Escape' && document.activeElement === searchInput) {
            clearSearch();
        }
    });

    totalCountEl.textContent = totalCount;
})();
</script>
@endpush