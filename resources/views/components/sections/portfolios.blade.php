{{-- Professional Portfolio Section with Skills-Based Filters (Way 2) --}}
<section class="pro-portfolio-section">
    @php
        use Illuminate\Support\Str;
        $totalProjects = $totalProjects ?? count($sortedPortfolios ?? []);
        $visibleProjects = $visibleProjects ?? collect($sortedPortfolios ?? [])->take(3);
        $userSkillsForFilters = $userSkillsForFilters ?? [];
        $visibleSkills = $visibleSkills ?? array_slice($userSkillsForFilters, 0, 6);
        $hiddenSkills = $hiddenSkills ?? [];
    @endphp

    {{-- Section Header --}}
    <div class="pps-header">
        <div class="pps-header-left">
            <h2 class="pps-title">Projects</h2>
            <span class="pps-count">{{ $totalProjects }}</span>
        </div>
        <div class="pps-header-actions">
            <button class="pps-btn-icon" onclick="openModal('editPortfolioModal')" title="Add project">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
            <button class="pps-btn-icon edit-card" aria-label="Edit projects" onclick="openModal('editPortfolioModal')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Filter Bar (Skills) --}}
    @if(count($userSkillsForFilters) > 0)
        <div class="pps-filter-bar">
            <div class="pps-filters" id="portfolioFilters">
                <button class="pps-filter-chip active" data-skill="all" onclick="filterPortfoliosBySkill('all', this)">
                    <span>All Projects</span>
                </button>
                
                @foreach($visibleSkills as $skill)
                    <button class="pps-filter-chip" data-skill="{{ $skill['slug'] }}" onclick="filterPortfoliosBySkill('{{ $skill['slug'] }}', this)">
                        <span>{{ $skill['name'] }}</span>
                    </button>
                @endforeach
            </div>

            {{-- More Button (Always Visible) --}}
            <button class="pps-more-btn" onclick="openPortfolioOptions()" title="More options">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="1"/>
                    <circle cx="12" cy="5" r="1"/>
                    <circle cx="12" cy="19" r="1"/>
                </svg>
                <span>More</span>
            </button>
        </div>
    @else
        {{-- If user has no skills --}}
        <div class="pps-filter-bar">
            <div class="pps-no-skills">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>Add skills to your profile to enable filtering</span>
            </div>
            
            <button class="pps-more-btn" onclick="openPortfolioOptions()" title="More options">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="1"/>
                    <circle cx="12" cy="5" r="1"/>
                    <circle cx="12" cy="19" r="1"/>
                </svg>
                <span>More</span>
            </button>
        </div>
    @endif

    {{-- Projects Grid --}}
    <div class="pps-grid" id="portfolioGrid">
        @forelse ($visibleProjects as $p)
            @php 
                $meta = is_array($p['meta'] ?? null) ? $p['meta'] : [];
                $skillIds = $meta['skill_ids'] ?? [];
                $skillSlugs = collect($userSkillsForFilters)
                    ->filter(fn($s) => in_array($s['id'], $skillIds))
                    ->pluck('slug')
                    ->implode(',');
            @endphp
            <div class="pps-project-item" data-skills="{{ $skillSlugs }}">
                <x-cards.portfolio-card :portfolio="$p" :userSkills="$userSkillsForFilters" />
            </div>
        @empty
            <div class="pps-empty">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                <h3>No projects yet</h3>
                <p>Showcase your best work by adding your first project</p>
                <button class="pps-empty-btn" onclick="openModal('editPortfolioModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add your first project
                </button>
            </div>
        @endforelse
    </div>

    {{-- See All Button --}}
    @if ($totalProjects > 3)
        <div class="pps-footer">
            <button class="pps-see-all" onclick="showAllPortfolioProjects()">
                <span>Show all {{ $totalProjects }} projects</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    @endif
</section>

{{-- Portfolio Options Modal (Filter Skills + Sort) --}}
<div class="pps-modal-overlay" id="portfolioOptionsModal" onclick="closePortfolioOptions(event)">
    <div class="pps-modal" onclick="event.stopPropagation()">
        <div class="pps-modal-header">
            <h3>Portfolio Options</h3>
            <button class="pps-modal-close" onclick="closePortfolioOptions()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="pps-modal-body">
            @if(count($userSkillsForFilters) > 0)
                {{-- Filter Skills Section --}}
                <div class="pps-modal-section">
                    <div class="pps-modal-section-header">
                        <h4>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Filter by Skills
                        </h4>
                        <span class="pps-info-badge">Select up to 6</span>
                    </div>
                    <p class="pps-modal-desc">Choose which skills to display as quick filters</p>
                    
                    <div class="pps-skills-selected">
                        <div class="pps-skills-label">
                            <span>Selected (<span id="selectedSkillCount">{{ count($visibleSkills) }}</span>/6)</span>
                        </div>
                        <div class="pps-skills-list" id="selectedSkillsList">
                            @foreach($visibleSkills as $skill)
                                <div class="pps-skill-chip selected" data-skill="{{ $skill['slug'] }}">
                                    <span>{{ $skill['name'] }}</span>
                                    <button onclick="toggleSkillSelection('{{ $skill['slug'] }}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pps-skills-available">
                        <div class="pps-skills-label">
                            <span>Available Skills</span>
                            <span class="pps-count-badge">{{ count($hiddenSkills) }}</span>
                        </div>
                        <div class="pps-skills-list" id="availableSkillsList">
                            @foreach($hiddenSkills as $skill)
                                <div class="pps-skill-chip" data-skill="{{ $skill['slug'] }}" onclick="toggleSkillSelection('{{ $skill['slug'] }}')">
                                    <span>{{ $skill['name'] }}</span>
                                    <button>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="pps-modal-divider"></div>
            @endif

            {{-- Sort Order Section (Always Visible) --}}
            <div class="pps-modal-section">
                <div class="pps-modal-section-header">
                    <h4>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M7 12h14M11 18h10"/>
                        </svg>
                        Sort Projects
                    </h4>
                </div>
                <p class="pps-modal-desc">Choose how projects are sorted by default</p>
                
                <div class="pps-sort-options">
                    <label class="pps-sort-option {{ ($sortOrder ?? 'position') === 'position' ? 'active' : '' }}">
                        <input type="radio" name="sortOrder" value="position" {{ ($sortOrder ?? 'position') === 'position' ? 'checked' : '' }}>
                        <div class="pps-sort-option-content">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6"/>
                                <line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/>
                                <line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                            <div>
                                <strong>Custom Order</strong>
                                <span>Your manually arranged order</span>
                            </div>
                        </div>
                    </label>

                    <label class="pps-sort-option {{ ($sortOrder ?? '') === 'newest' ? 'active' : '' }}">
                        <input type="radio" name="sortOrder" value="newest" {{ ($sortOrder ?? '') === 'newest' ? 'checked' : '' }}>
                        <div class="pps-sort-option-content">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <div>
                                <strong>Newest First</strong>
                                <span>Most recently added projects</span>
                            </div>
                        </div>
                    </label>

                    <label class="pps-sort-option {{ ($sortOrder ?? '') === 'title' ? 'active' : '' }}">
                        <input type="radio" name="sortOrder" value="title" {{ ($sortOrder ?? '') === 'title' ? 'checked' : '' }}>
                        <div class="pps-sort-option-content">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="21" y1="10" x2="7" y2="10"/>
                                <line x1="21" y1="6" x2="3" y2="6"/>
                                <line x1="21" y1="14" x2="3" y2="14"/>
                                <line x1="21" y1="18" x2="7" y2="18"/>
                            </svg>
                            <div>
                                <strong>Alphabetical</strong>
                                <span>Sort by project title A-Z</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="pps-modal-footer">
            <button class="pps-btn-secondary" onclick="closePortfolioOptions()">
                Cancel
            </button>
            <button class="pps-btn-primary" onclick="savePortfolioOptions()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Save Changes
            </button>
        </div>
    </div>
</div>

<script>
// Portfolio Skills-Based Filtering
(function() {
    'use strict';

    // All user skills for mapping
    const allSkills = @json($userSkillsForFilters ?? []);
    const skillsMap = Object.fromEntries(allSkills.map(s => [s.slug, s]));

    // Filter portfolios by skill
    window.filterPortfoliosBySkill = function(skillSlug, button) {
        const items = document.querySelectorAll('.pps-project-item');
        const buttons = document.querySelectorAll('.pps-filter-chip');
        
        // Update active state
        buttons.forEach(btn => btn.classList.remove('active'));
        if (button) button.classList.add('active');
        
        // Filter items
        items.forEach(item => {
            const itemSkills = item.dataset.skills ? item.dataset.skills.split(',') : [];
            const shouldShow = skillSlug === 'all' || itemSkills.includes(skillSlug);
            item.style.display = shouldShow ? '' : 'none';
        });
    };

    // Open options modal
    window.openPortfolioOptions = function() {
        document.getElementById('portfolioOptionsModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    // Close options modal
    window.closePortfolioOptions = function(event) {
        if (event && event.target.classList.contains('pps-modal')) return;
        document.getElementById('portfolioOptionsModal').classList.remove('active');
        document.body.style.overflow = '';
    };

    // Toggle skill selection
    window.toggleSkillSelection = function(skillSlug) {
        const selectedList = document.getElementById('selectedSkillsList');
        const availableList = document.getElementById('availableSkillsList');
        const countEl = document.getElementById('selectedSkillCount');
        
        if (!selectedList || !availableList) return;
        
        const skillElements = document.querySelectorAll(`[data-skill="${skillSlug}"]`);
        
        let skillElement = null;
        skillElements.forEach(el => {
            if (el.classList.contains('pps-skill-chip')) {
                skillElement = el;
            }
        });

        if (!skillElement) return;

        const isSelected = skillElement.classList.contains('selected');
        const currentCount = selectedList.querySelectorAll('.pps-skill-chip').length;

        if (isSelected) {
            // Move to available
            availableList.appendChild(skillElement);
            skillElement.classList.remove('selected');
            skillElement.querySelector('button').innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            `;
        }

        // Update count
        if (countEl) {
            countEl.textContent = selectedList.querySelectorAll('.pps-skill-chip').length;
        }
    };

    // Save portfolio options
    window.savePortfolioOptions = async function() {
        const selectedList = document.getElementById('selectedSkillsList');
        const sortOrder = document.querySelector('input[name="sortOrder"]:checked')?.value || 'position';

        let selectedSkills = [];
        if (selectedList) {
            selectedSkills = Array.from(selectedList.querySelectorAll('.pps-skill-chip'))
                .map(el => el.dataset.skill);
        }

        // If no skills selected but user has skills, require at least one
        if (allSkills.length > 0 && selectedSkills.length === 0) {
            alert('Please select at least one skill for filtering');
            return;
        }

        try {
            const response = await fetch('{{ route("tenant.filter-preferences") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    visible_skills: selectedSkills,
                    sort_order: sortOrder
                })
            });

            const data = await response.json();

            if (data.success) {
                window.location.reload();
            } else {
                alert('Failed to save preferences. Please try again.');
            }
        } catch (error) {
            console.error('Error saving preferences:', error);
            alert('Failed to save preferences. Please try again.');
        }
    };

    // Show all projects
    window.showAllPortfolioProjects = function() {
        document.querySelectorAll('.pps-project-item').forEach(item => {
            item.style.display = '';
        });
        document.querySelectorAll('.pps-filter-chip').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector('.pps-filter-chip[data-skill="all"]')?.classList.add('active');
    };

    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePortfolioOptions();
        }
    });
})();
</script>

<style>
/* ============================================
   PROFESSIONAL PORTFOLIO SECTION - SKILLS-BASED
   ============================================ */

:root {
    --pps-transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.pro-portfolio-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: var(--mb-sections);
}

/* ========== HEADER ========== */
.pps-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}

.pps-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.pps-title {
    font-size: var(--fs-h2);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.pps-count {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    padding: 0 8px;
    background: var(--apc-bg);
    color: var(--text-muted);
    border-radius: 14px;
    font-size: var(--fs-subtle);
    font-weight: var(--fw-semibold);
}

.pps-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.pps-btn-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--pps-transition);
}

.pps-btn-icon:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
}

/* ========== FILTER BAR ========== */
.pps-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    background: var(--apc-bg);
}

.pps-filters {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    overflow-x: auto;
    scrollbar-width: none;
}

.pps-filters::-webkit-scrollbar {
    display: none;
}

.pps-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 32px;
    padding: 0 14px;
    background: var(--card);
    color: var(--text-body);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    font-size: var(--fs-subtle);
    font-weight: var(--fw-medium);
    white-space: nowrap;
    cursor: pointer;
    transition: var(--pps-transition);
}

.pps-filter-chip:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
    transform: translateY(-1px);
}

.pps-filter-chip.active {
    background: var(--accent);
    color: var(--text-white);
    border-color: var(--accent);
    box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
}

/* More Button (Always Visible) */
.pps-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 32px;
    padding: 0 14px;
    background: var(--card);
    color: var(--text-body);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    font-size: var(--fs-subtle);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: var(--pps-transition);
    white-space: nowrap;
}

.pps-more-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-light);
    transform: translateY(-1px);
}

.pps-more-btn svg {
    flex-shrink: 0;
}

/* No Skills Message */
.pps-no-skills {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: var(--fs-subtle);
    font-style: italic;
}

.pps-no-skills svg {
    flex-shrink: 0;
}

/* ========== PROJECTS GRID ========== */
.pps-grid {
    padding: 8px 0;
}

.pps-project-item {
    transition: opacity 0.3s ease;
}

/* ========== EMPTY STATE ========== */
.pps-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 64px 24px;
    text-align: center;
}

.pps-empty svg {
    margin-bottom: 20px;
    color: var(--text-muted);
    opacity: 0.3;
}

.pps-empty h3 {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 6px 0;
}

.pps-empty p {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin: 0 0 24px 0;
}

.pps-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    padding: 0 20px;
    background: var(--accent);
    color: var(--text-white);
    border: none;
    border-radius: 8px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: var(--pps-transition);
}

.pps-empty-btn:hover {
    background: var(--accent-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(19, 81, 216, 0.3);
}

/* ========== FOOTER ========== */
.pps-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
}

.pps-see-all {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 40px;
    background: transparent;
    color: var(--text-muted);
    border: none;
    border-radius: 8px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: var(--pps-transition);
}

.pps-see-all:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
}

/* ==================== OPTIONS MODAL ==================== */
.pps-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.pps-modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.pps-modal {
    background: var(--card);
    border-radius: 12px;
    max-width: 700px;
    width: 100%;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.9);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.pps-modal-overlay.active .pps-modal {
    transform: scale(1);
}

.pps-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid var(--border);
}

.pps-modal-header h3 {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.pps-modal-close {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--pps-transition);
}

.pps-modal-close:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
    transform: rotate(90deg);
}

.pps-modal-body {
    flex: 1;
    padding: 24px;
    overflow-y: auto;
}

.pps-modal-section {
    margin-bottom: 32px;
}

.pps-modal-section:last-child {
    margin-bottom: 0;
}

.pps-modal-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.pps-modal-section-header h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: var(--fs-title);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.pps-modal-section-header svg {
    color: var(--accent);
}

.pps-info-badge {
    font-size: var(--fs-micro);
    font-weight: var(--fw-semibold);
    color: var(--text-muted);
    background: var(--apc-bg);
    padding: 4px 10px;
    border-radius: 12px;
}

.pps-modal-desc {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin: 0 0 16px 0;
    line-height: var(--lh-relaxed);
}

.pps-modal-divider {
    height: 1px;
    background: var(--border);
    margin: 24px 0;
}

/* Skills Lists */
.pps-skills-selected,
.pps-skills-available {
    margin-bottom: 20px;
}

.pps-skills-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
}

.pps-count-badge {
    font-size: var(--fs-subtle);
    font-weight: var(--fw-medium);
    color: var(--text-muted);
    background: var(--apc-bg);
    padding: 2px 8px;
    border-radius: 10px;
}

.pps-skills-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 48px;
    padding: 14px;
    background: var(--apc-bg);
    border: 1.5px dashed var(--border);
    border-radius: 10px;
}

.pps-skills-selected .pps-skills-list {
    border-style: solid;
    border-color: var(--accent);
    background: var(--accent-light);
}

.pps-skill-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 32px;
    padding: 0 10px 0 14px;
    background: var(--card);
    color: var(--text-body);
    border: 1.5px solid var(--border);
    border-radius: 16px;
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    cursor: pointer;
    transition: var(--pps-transition);
    user-select: none;
}

.pps-skill-chip:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    transform: translateY(-1px);
}

.pps-skill-chip.selected {
    background: var(--accent);
    color: var(--text-white);
    border-color: var(--accent);
}

.pps-skill-chip button {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 50%;
    color: currentColor;
    cursor: pointer;
    transition: var(--pps-transition);
    padding: 0;
}

.pps-skill-chip:not(.selected) button:hover {
    background: rgba(19, 81, 216, 0.15);
}

.pps-skill-chip.selected button:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Sort Options */
.pps-sort-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.pps-sort-option {
    position: relative;
    display: flex;
    align-items: center;
    padding: 16px;
    background: var(--card);
    border: 2px solid var(--border);
    border-radius: 10px;
    cursor: pointer;
    transition: var(--pps-transition);
}

.pps-sort-option:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.pps-sort-option.active {
    border-color: var(--accent);
    background: var(--accent-light);
}

.pps-sort-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.pps-sort-option-content {
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;
}

.pps-sort-option-content svg {
    flex-shrink: 0;
    color: var(--text-muted);
}

.pps-sort-option.active svg {
    color: var(--accent);
}

.pps-sort-option-content > div {
    flex: 1;
}

.pps-sort-option-content strong {
    display: block;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin-bottom: 2px;
}

.pps-sort-option-content span {
    display: block;
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

/* Modal Footer */
.pps-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid var(--border);
}

.pps-btn-secondary,
.pps-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 44px;
    padding: 0 24px;
    border: none;
    border-radius: 8px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: var(--pps-transition);
}

.pps-btn-secondary {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-body);
}

.pps-btn-secondary:hover {
    background: var(--apc-bg);
    border-color: var(--text-muted);
}

.pps-btn-primary {
    background: var(--accent);
    color: var(--text-white);
    box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
}

.pps-btn-primary:hover {
    background: var(--accent-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(19, 81, 216, 0.35);
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 768px) {
    .pps-header {
        padding: 16px;
    }

    .pps-filter-bar {
        flex-wrap: wrap;
        padding: 12px 16px;
    }

    .pps-filters {
        flex: 1 1 100%;
        order: 2;
        margin-top: 8px;
    }

    .pps-more-btn {
        order: 1;
    }

    .pps-modal {
        max-height: 90vh;
        border-radius: 12px 12px 0 0;
    }

    .pps-modal-header,
    .pps-modal-body,
    .pps-modal-footer {
        padding: 20px 16px;
    }

    .pps-modal-footer {
        flex-direction: column-reverse;
    }

    .pps-btn-secondary,
    .pps-btn-primary {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .pps-title {
        font-size: var(--fs-h3);
    }

    .pps-filter-chip,
    .pps-more-btn {
        font-size: var(--fs-micro);
        height: 28px;
        padding: 0 10px;
    }

    .pps-no-skills {
        font-size: var(--fs-micro);
    }
}
</style>    