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

    {{-- Search Bar --}}
    <div class="search-container">
        <div class="search-box">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" 
                   id="educationSearchInput" 
                   class="search-input" 
                   placeholder="Search by degree, school, or field of study..."
                   autocomplete="off">
            <button type="button" class="search-clear" id="searchClearBtn" style="display: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="search-results" id="searchResults">
            <span class="results-text">Showing <strong id="visibleCount">{{ $owner->educations->count() }}</strong> of <strong id="totalCount">{{ $owner->educations->count() }}</strong> degrees</span>
        </div>
    </div>

    <div class="content-section">
        <div class="education-grid" id="educationGrid">
            @forelse($owner->educations as $education)
                @php
                    $searchText = strtolower(
                        $education->degree . ' ' . 
                        ($education->field ?? '') . ' ' . 
                        $education->school . ' ' . 
                        ($education->city ?? '') . ' ' . 
                        ($education->country ?? '')
                    );
                @endphp

                <div class="education-card" 
                     data-education-id="{{ $education->id }}"
                     data-search-text="{{ $searchText }}">
                    
                    <div class="education-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                        @if($education->is_current)
                            <span class="status-indicator active"></span>
                        @endif
                    </div>

                    <div class="education-content">
                        <h4 class="education-degree">
                            {{ $education->degree }}
                            @if($education->field)
                                <span class="education-field">in {{ $education->field }}</span>
                            @endif
                        </h4>
                        <p class="education-school">{{ $education->school }}</p>

                        <div class="education-meta">
                            <span class="education-period">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                {{ $education->start_year ?: '—' }} 
                                — 
                                @if($education->is_current)
                                    <span class="current-text">Present</span>
                                @else
                                    {{ $education->end_year ?: '—' }}
                                @endif
                            </span>

                            @if($education->city || $education->country)
                                <span class="education-location">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ collect([$education->city, $education->country])->filter()->join(', ') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state-full">
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

        {{-- No Results Message --}}
        <div class="no-results" id="noResults" style="display: none;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <h3>No education found</h3>
            <p>Try adjusting your search query</p>
            <button type="button" class="btn btn-primary" onclick="clearSearch()">Clear Search</button>
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
            <p>List your most recent education first for better visibility. Include honors or distinctions!</p>
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


/* ============ EDUCATION GRID ============ */
.education-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

.education-card {
    background: var(--card);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.education-card.hidden {
    display: none !important;
}

.education-card:hover {
    border-color: var(--accent);
    box-shadow: 0 8px 24px rgba(var(--accent-rgb), 0.12);
    transform: translateY(-4px);
}

/* ============ EDUCATION ICON ============ */
.education-icon {
    position: relative;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--accent-rgb), 0.1);
    border-radius: 12px;
    margin-bottom: 16px;
    color: var(--accent);
}

.status-indicator {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 14px;
    height: 14px;
    background: #10b981;
    border: 3px solid var(--card);
    border-radius: 50%;
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);
}

.status-indicator.active {
    animation: pulse-green 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse-green {
    0%, 100% {
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);
    }
    50% {
        box-shadow: 0 0 16px rgba(16, 185, 129, 0.6);
    }
}

/* ============ EDUCATION CONTENT ============ */
.education-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.education-degree {
    font-size: 17px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
    line-height: 1.4;
    letter-spacing: -0.01em;
}

.education-field {
    display: block;
    font-size: 15px;
    font-weight: 500;
    color: var(--text-body);
    margin-top: 4px;
}

.education-school {
    font-size: 15px;
    color: var(--accent);
    margin: 0;
    font-weight: 600;
}

/* ============ EDUCATION META ============ */
.education-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 4px;
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

.current-text {
    display: inline-flex;
    padding: 2px 8px;
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* ============ EMPTY & NO RESULTS ============ */
.empty-state-full, .no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}

.empty-state-full svg, .no-results svg {
    color: var(--text-muted);
    opacity: 0.15;
    margin-bottom: 24px;
}

.empty-state-full h3, .no-results h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 12px 0;
}

.empty-state-full p, .no-results p {
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

    .education-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';

    const searchInput = document.getElementById('educationSearchInput');
    const clearBtn = document.getElementById('searchClearBtn');
    const visibleCountEl = document.getElementById('visibleCount');
    const totalCountEl = document.getElementById('totalCount');
    const noResultsEl = document.getElementById('noResults');
    const educationGrid = document.getElementById('educationGrid');

    const allCards = document.querySelectorAll('.education-card[data-search-text]');
    const totalCount = allCards.length;

    function performSearch(query) {
        const searchTerm = query.toLowerCase().trim();
        let visibleCount = 0;

        allCards.forEach(card => {
            const searchText = card.dataset.searchText || '';
            const matches = searchText.includes(searchTerm);
            
            card.classList.toggle('hidden', !matches);
            
            if (matches) {
                visibleCount++;
            }
        });

        visibleCountEl.textContent = visibleCount;

        const hasResults = visibleCount > 0;
        noResultsEl.style.display = hasResults ? 'none' : 'flex';
        educationGrid.style.display = hasResults ? 'grid' : 'none';

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