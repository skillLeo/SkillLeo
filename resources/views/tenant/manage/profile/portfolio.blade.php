@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@extends('tenant.manage.app')

@section('title', 'Manage Portfolio - ' . $user->name)

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
            <h1 class="page-title">Portfolio</h1>
            <p class="page-subtitle">Showcase your best work and projects</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('editPortfolioModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Edit Portfolio
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
                   id="portfolioSearchInput" 
                   class="search-input" 
                   placeholder="Search projects..."
                   autocomplete="off">
            <button type="button" class="search-clear" id="searchClearBtn" style="display: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="search-results" id="searchResults">
            <span class="results-text">Showing <strong id="visibleCount">{{ $owner->portfolios->count() }}</strong> of <strong id="totalCount">{{ $owner->portfolios->count() }}</strong> projects</span>
        </div>
    </div>

    <div class="content-section">
        <div class="portfolio-grid" id="portfolioGrid">
            @forelse($owner->portfolios as $portfolio)
                @php
                    $meta = is_array($portfolio->meta) ? $portfolio->meta : [];
                    $skillIds = $meta['skill_ids'] ?? [];
                    $portfolioSkills = $owner->skills->whereIn('id', $skillIds);
                    
                    // Create searchable text
                    $searchText = strtolower(
                        $portfolio->title . ' ' . 
                        $portfolio->description . ' ' . 
                        $portfolioSkills->pluck('name')->join(' ')
                    );
                @endphp

                <div class="portfolio-card" 
                     data-portfolio-id="{{ $portfolio->id }}"
                     data-search-text="{{ $searchText }}">
                    
                    @if($portfolio->image_path)
                        <div class="portfolio-image">
                            <img src="{{ Storage::disk($portfolio->image_disk ?? 'public')->url($portfolio->image_path) }}" 
                                 alt="{{ $portfolio->title }}"
                                 loading="lazy">
                            <div class="portfolio-overlay">
                                <div class="portfolio-actions">
                                    <button class="portfolio-action-btn view" 
                                            onclick="window.open('{{ $portfolio->link_url }}', '_blank')"
                                            title="View Project"
                                            @if(!$portfolio->link_url) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            <polyline points="15 3 21 3 21 9"/>
                                            <line x1="10" y1="14" x2="21" y2="3"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="portfolio-image-empty">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="14" rx="2"/>
                                <circle cx="8" cy="8" r="2"/>
                                <path d="M21 15l-5-5L5 21"/>
                            </svg>
                        </div>
                    @endif

                    <div class="portfolio-content">
                        <h4 class="portfolio-title">{{ $portfolio->title }}</h4>
                        
                        @if($portfolio->description)
                            <p class="portfolio-description">{{ Str::limit($portfolio->description, 120) }}</p>
                        @endif

                        @if($portfolioSkills->count() > 0)
                            <div class="portfolio-skills">
                                @foreach($portfolioSkills->take(4) as $skill)
                                    <span class="skill-badge">{{ $skill->name }}</span>
                                @endforeach
                                @if($portfolioSkills->count() > 4)
                                    <span class="skill-badge-more">+{{ $portfolioSkills->count() - 4 }}</span>
                                @endif
                            </div>
                        @endif

                        @if($portfolio->link_url)
                            <a href="{{ $portfolio->link_url }}" target="_blank" class="portfolio-link">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                    <polyline points="15 3 21 3 21 9"/>
                                    <line x1="10" y1="14" x2="21" y2="3"/>
                                </svg>
                                <span>View Project</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state-full">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <h3>No projects yet</h3>
                    <p>Start building your portfolio by adding your first project</p>
                    <button class="btn btn-primary" onclick="openModal('editPortfolioModal')">Add First Project</button>
                </div>
            @endforelse
        </div>

        {{-- No Results Message --}}
        <div class="no-results" id="noResults" style="display: none;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <h3>No projects found</h3>
            <p>Try adjusting your search query</p>
            <button type="button" class="btn btn-primary" onclick="clearSearch()">Clear Search</button>
        </div>
    </div>

    {{-- Include Edit Portfolio Modal --}}
    <x-modals.edits.edit-portfolio 
        :modal-portfolios="$modalPortfolios" 
        :user-skills="$userSkills" 
        :username="$username" 
    />
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">💼 Portfolio Guide</h3>
            <p class="inspector-desc">Tips for showcasing your work</p>
        </div>

        <div class="help-card">
            <h4>Best Practices</h4>
            <ul class="help-list">
                <li>Use high-quality images (1200×900px)</li>
                <li>Write clear, concise descriptions</li>
                <li>Include live demo links</li>
                <li>Showcase your best 6-10 projects</li>
            </ul>
        </div>

        <div class="help-card accent">
            <h4>🎯 Pro Tip</h4>
            <p>Projects with images get 3x more engagement. Add skill tags to increase discoverability!</p>
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


/* ============ PORTFOLIO GRID ============ */
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

.portfolio-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.portfolio-card.hidden {
    display: none !important;
}

.portfolio-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(var(--accent-rgb), 0.15);
    border-color: rgba(var(--accent-rgb), 0.3);
}

/* ============ PORTFOLIO IMAGE ============ */
.portfolio-image {
    position: relative;
    width: 100%;
    height: 220px;
    background: var(--apc-bg);
    overflow: hidden;
}

.portfolio-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.portfolio-card:hover .portfolio-image img {
    transform: scale(1.05);
}

.portfolio-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.7));
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    padding: 16px;
}

.portfolio-card:hover .portfolio-overlay {
    opacity: 1;
}

.portfolio-actions {
    display: flex;
    gap: 8px;
}

.portfolio-action-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 8px;
    color: var(--text-heading);
    cursor: pointer;
    transition: all 0.2s ease;
}

.portfolio-action-btn:hover {
    background: var(--accent);
    color: white;
    transform: scale(1.1);
}

.portfolio-image-empty {
    width: 100%;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--apc-bg);
    color: var(--text-muted);
}

/* ============ PORTFOLIO CONTENT ============ */
.portfolio-content {
    padding: 20px;
}

.portfolio-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 10px 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.portfolio-description {
    font-size: 14px;
    color: var(--text-body);
    line-height: 1.6;
    margin: 0 0 14px 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.portfolio-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
}

.skill-badge {
    font-size: 11px;
    padding: 4px 10px;
    background: rgba(var(--accent-rgb), 0.1);
    color: var(--accent);
    border-radius: 4px;
    font-weight: 600;
    border: 1px solid rgba(var(--accent-rgb), 0.2);
}

.skill-badge-more {
    font-size: 11px;
    padding: 4px 10px;
    background: var(--apc-bg);
    color: var(--text-muted);
    border-radius: 4px;
    font-weight: 600;
    border: 1px solid var(--border);
}

.portfolio-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.portfolio-link:hover {
    gap: 8px;
}

.portfolio-link span {
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s ease;
}

.portfolio-link:hover span {
    border-bottom-color: var(--accent);
}

/* ============ EMPTY STATE ============ */
.empty-state-full {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}

.empty-state-full svg {
    color: var(--text-muted);
    opacity: 0.15;
    margin-bottom: 24px;
}

.empty-state-full h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 12px 0;
}

.empty-state-full p {
    font-size: 15px;
    color: var(--text-muted);
    margin: 0 0 28px 0;
}

/* ============ NO RESULTS STATE ============ */
.no-results {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
}

.no-results svg {
    color: var(--text-muted);
    opacity: 0.2;
    margin-bottom: 20px;
}

.no-results h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 8px 0;
}

.no-results p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0 0 24px 0;
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

    .portfolio-grid {
        grid-template-columns: 1fr;
    }
}

/* ============ DARK MODE ============ */
@media (prefers-color-scheme: dark) {
    .portfolio-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.85));
    }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';

    // Elements
    const searchInput = document.getElementById('portfolioSearchInput');
    const clearBtn = document.getElementById('searchClearBtn');
    const visibleCountEl = document.getElementById('visibleCount');
    const totalCountEl = document.getElementById('totalCount');
    const noResultsEl = document.getElementById('noResults');
    const portfolioGrid = document.getElementById('portfolioGrid');

    // All portfolio cards
    const allCards = document.querySelectorAll('.portfolio-card[data-search-text]');
    const totalCount = allCards.length;

    // Search functionality
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

        // Update counts
        visibleCountEl.textContent = visibleCount;

        // Show/hide no results message
        const hasResults = visibleCount > 0;
        noResultsEl.style.display = hasResults ? 'none' : 'flex';
        portfolioGrid.style.display = hasResults ? 'grid' : 'none';

        // Show/hide clear button
        clearBtn.style.display = searchTerm ? 'flex' : 'none';
    }

    // Clear search
    window.clearSearch = function() {
        searchInput.value = '';
        performSearch('');
        searchInput.focus();
    };

    // Event listeners
    searchInput.addEventListener('input', (e) => {
        performSearch(e.target.value);
    });

    clearBtn.addEventListener('click', () => {
        clearSearch();
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }

        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            clearSearch();
        }
    });

    // Initialize
    totalCountEl.textContent = totalCount;
})();
</script>
@endpush