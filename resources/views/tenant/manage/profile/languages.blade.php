@extends('tenant.manage.app')

@section('title', 'Manage Languages - ' . $user->name)

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
            <h1 class="page-title">Languages</h1>
            <p class="page-subtitle">Manage your language proficiency</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('editLanguagesModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Edit Languages
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
                   id="languageSearchInput" 
                   class="search-input" 
                   placeholder="Search languages..."
                   autocomplete="off">
            <button type="button" class="search-clear" id="searchClearBtn" style="display: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="search-results" id="searchResults">
            <span class="results-text">Showing <strong id="visibleCount">{{ $owner->languages->count() }}</strong> of <strong id="totalCount">{{ $owner->languages->count() }}</strong> languages</span>
        </div>
    </div>

    <div class="content-section">
        <div class="languages-grid" id="languagesGrid">
            @forelse($owner->languages as $language)
                @php
                    $level = optional($language->pivot)->level ?? ($language->level ?? 0);
                    $level = max(0, min(4, (int) $level));
                    $percent = ($level / 4) * 100;
                    
                    $levelText = match($level) {
                        4 => 'Native or Bilingual',
                        3 => 'Professional Working',
                        2 => 'Limited Working',
                        1 => 'Elementary',
                        default => 'Beginner'
                    };
                    
                    $searchText = strtolower($language->name . ' ' . $levelText);
                @endphp
        
                <div class="language-card" 
                     data-language-id="{{ $language->id }}"
                     data-search-text="{{ $searchText }}">
                    
                    <div class="language-header">
                        <div class="language-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                        </div>
                        <h4 class="language-name">{{ $language->name }}</h4>
                    </div>

                    <div class="language-level-badge level-{{ $level }}">
                        {{ $levelText }}
                    </div>

                    <div class="language-proficiency">
                        <div class="proficiency-bar">
                            <div class="proficiency-fill level-{{ $level }}" style="width: {{ $percent }}%"></div>
                        </div>

                        <div class="proficiency-dots">
                            @for($i = 1; $i <= 4; $i++)
                                <span class="dot {{ $i <= $level ? 'active level-' . $level : '' }}"></span>
                            @endfor
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state-full">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    <h3>No languages added yet</h3>
                    <p>Add languages you speak to showcase your communication skills</p>
                    <button class="btn btn-primary" onclick="openModal('editLanguagesModal')">Add First Language</button>
                </div>
            @endforelse
        </div>

        {{-- No Results Message --}}
        <div class="no-results" id="noResults" style="display: none;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <h3>No languages found</h3>
            <p>Try adjusting your search query</p>
            <button type="button" class="btn btn-primary" onclick="clearSearch()">Clear Search</button>
        </div>
    </div>

    {{-- Include Edit Languages Modal --}}
    <x-modals.edits.edit-languages 
        :modal-languages="$modalLanguages" 
        :username="$username" 
    />
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">🌍 Language Guide</h3>
            <p class="inspector-desc">Understanding proficiency levels</p>
        </div>

        <div class="help-card">
            <h4>Proficiency Levels</h4>
            <ul class="help-list">
                <li><strong>Elementary:</strong> Basic words and phrases</li>
                <li><strong>Limited Working:</strong> Simple conversations</li>
                <li><strong>Professional Working:</strong> Business fluency</li>
                <li><strong>Native/Bilingual:</strong> Full proficiency</li>
            </ul>
        </div>

        <div class="help-card accent">
            <h4>🎯 Pro Tip</h4>
            <p>Being multilingual increases job opportunities by 30%. Highlight your strongest languages!</p>
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
    align-items:align-items: center;
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
    max-width: 500px;
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


/* ============ LANGUAGES GRID ============ */
.languages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.language-card {
    background: var(--card);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.language-card.hidden {
    display: none !important;
}

.language-card:hover {
    border-color: var(--accent);
    box-shadow: 0 8px 24px rgba(var(--accent-rgb), 0.12);
    transform: translateY(-4px);
}

/* ============ LANGUAGE HEADER ============ */
.language-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.language-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--accent-rgb), 0.1);
    border-radius: 10px;
    color: var(--accent);
    flex-shrink: 0;
}

.language-name {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
    letter-spacing: -0.01em;
}

/* ============ LANGUAGE LEVEL BADGE ============ */
.language-level-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 16px;
    display: inline-block;
}

.language-level-badge.level-1 {
    background: #fef3c7;
    color: #92400e;
}

.language-level-badge.level-2 {
    background: #dbeafe;
    color: #1e40af;
}

.language-level-badge.level-3 {
    background: #d1fae5;
    color: #065f46;
}

.language-level-badge.level-4 {
    background: #e0e7ff;
    color: #4338ca;
}

/* ============ LANGUAGE PROFICIENCY ============ */
.language-proficiency {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.proficiency-bar {
    height: 8px;
    background: var(--apc-bg);
    border-radius: 4px;
    overflow: hidden;
}

.proficiency-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.proficiency-fill.level-1 {
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
}

.proficiency-fill.level-2 {
    background: linear-gradient(90deg, #60a5fa, #3b82f6);
}

.proficiency-fill.level-3 {
    background: linear-gradient(90deg, #34d399, #10b981);
}

.proficiency-fill.level-4 {
    background: linear-gradient(90deg, #818cf8, #6366f1);
}

.proficiency-dots {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.proficiency-dots .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--border);
    transition: all 0.3s ease;
}

.proficiency-dots .dot.active.level-1 {
    background: #fbbf24;
    box-shadow: 0 0 8px rgba(251, 191, 36, 0.4);
}

.proficiency-dots .dot.active.level-2 {
    background: #60a5fa;
    box-shadow: 0 0 8px rgba(96, 165, 250, 0.4);
}

.proficiency-dots .dot.active.level-3 {
    background: #34d399;
    box-shadow: 0 0 8px rgba(52, 211, 153, 0.4);
}

.proficiency-dots .dot.active.level-4 {
    background: #818cf8;
    box-shadow: 0 0 8px rgba(129, 140, 248, 0.4);
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

    .languages-grid {
        grid-template-columns: 1fr;
    }
}

/* ============ DARK MODE ============ */
@media (prefers-color-scheme: dark) {
    .language-level-badge.level-1 { 
        background: rgba(251, 191, 36, 0.2); 
        color: #fbbf24; 
    }
    .language-level-badge.level-2 { 
        background: rgba(96, 165, 250, 0.2); 
        color: #60a5fa; 
    }
    .language-level-badge.level-3 { 
        background: rgba(52, 211, 153, 0.2); 
        color: #34d399; 
    }
    .language-level-badge.level-4 { 
        background: rgba(129, 140, 248, 0.2); 
        color: #818cf8; 
    }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';

    const searchInput = document.getElementById('languageSearchInput');
    const clearBtn = document.getElementById('searchClearBtn');
    const visibleCountEl = document.getElementById('visibleCount');
    const totalCountEl = document.getElementById('totalCount');
    const noResultsEl = document.getElementById('noResults');
    const languagesGrid = document.getElementById('languagesGrid');

    const allCards = document.querySelectorAll('.language-card[data-search-text]');
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
        languagesGrid.style.display = hasResults ? 'grid' : 'none';

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