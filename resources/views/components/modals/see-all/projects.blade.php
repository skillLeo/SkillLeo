{{-- resources/views/components/modals/see-all/projects.blade.php --}}
@php
    use Illuminate\Support\Str;
@endphp
@props(['portfolios' => [], 'categories' => [], 'userSkills' => []])

@php
    // Get all unique skill slugs from portfolios
    $allSkillSlugs = collect($portfolios)
        ->pluck('skill_slugs')
        ->flatten()
        ->unique()
        ->filter()
        ->values()
        ->all();
    
    // Filter userSkills to only those used in portfolios
    $allFilterSkills = collect($userSkills)
        ->filter(fn($skill) => in_array($skill['slug'] ?? '', $allSkillSlugs))
        ->values()
        ->all();
    
    // Split into visible and hidden (first 6 visible, rest hidden)
    $visibleModalSkills = array_slice($allFilterSkills, 0, 6);
    $hiddenModalSkills = array_slice($allFilterSkills, 6);
@endphp

<x-modals.base-modal id="seeAllProjectsModal" class="modal-overlay" title="All Projects" size="xl">
    <div class="modal-content-see-all">
        {{-- Filter Section --}}
        @if(count($allFilterSkills) > 0)
            <div class="pps-filter-bar-modal">
                <div class="pps-filters-modal" id="modalPortfolioFilters">
                    <button class="pps-filter-chip-modal active" data-skill="all"
                        onclick="filterModalProjects('all', this)">
                        <span>All Projects</span>
                    </button>

                    @foreach($visibleModalSkills as $skill)
                        <button class="pps-filter-chip-modal" data-skill="{{ $skill['slug'] }}"
                            onclick="filterModalProjects('{{ $skill['slug'] }}', this)">
                            <span>{{ $skill['name'] }}</span>
                        </button>
                    @endforeach

                    {{-- More Options Icon --}}
                    <button class="pps-more-icon-modal" onclick="openModalPortfolioOptions()" 
                            aria-label="More options" title="More filter options">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="5" cy="12" r="1.6"></circle>
                            <circle cx="12" cy="12" r="1.6"></circle>
                            <circle cx="19" cy="12" r="1.6"></circle>
                        </svg>
                    </button>
                </div>
            </div>
        @else
            <div class="pps-filter-bar-modal">
                <div class="pps-no-skills-modal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <span>No skill filters available</span>
                </div>
            </div>
        @endif

        {{-- Projects List (Full Width Horizontal Cards) --}}
        <div class="projects-list-modal" id="modalProjectsGrid">
            @forelse($portfolios as $project)
                @php
                    $skillSlugs = $project['skill_slugs'] ?? [];
                    $skillSlugsString = is_array($skillSlugs) ? implode(',', $skillSlugs) : '';
                @endphp
                <div class="project-horizontal-card" data-skills="{{ $skillSlugsString }}">
                    <x-cards.portfolio-card :portfolio="$project" :userSkills="$userSkills" />
                </div>
            @empty
                <div class="empty-state-modal">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    <p class="empty-title-modal">No projects added yet</p>
                    <p class="empty-subtitle-modal">Showcase your work by adding projects</p>
                </div>
            @endforelse
        </div>

        {{-- No Results State --}}
        <div class="no-results-modal" id="noResultsModal" style="display: none;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                <line x1="11" y1="8" x2="11" y2="14"/>
                <line x1="8" y1="11" x2="14" y2="11"/>
            </svg>
            <p class="empty-title-modal">No projects match this filter</p>
            <p class="empty-subtitle-modal">Try selecting a different skill or view all projects</p>
        </div>
    </div>
</x-modals.base-modal>

{{-- Filter Options Modal (Nested Inside See All) --}}
<div class="pps-modal-overlay-nested" id="portfolioOptionsModalNested" onclick="closeModalPortfolioOptions(event)">
    <div class="pps-modal-nested" onclick="event.stopPropagation()">
        <div class="pps-modal-header-nested">
            <h3>Filter Options</h3>
            <button class="pps-modal-close-nested" onclick="closeModalPortfolioOptions()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="pps-modal-body-nested">
            @if(count($allFilterSkills) > 0)
                <div class="pps-modal-section-nested">
                    <div class="pps-modal-section-header-nested">
                        <h4>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            Select Quick Filters
                        </h4>
                        <span class="pps-info-badge-nested">Up to 6 visible</span>
                    </div>
                    <p class="pps-modal-desc-nested">Choose which skills to display as quick filter buttons</p>

                    <div class="pps-skills-selected-nested">
                        <div class="pps-skills-label-nested">
                            <span>Visible Filters (<span id="selectedSkillCountNested">{{ count($visibleModalSkills) }}</span>/6)</span>
                        </div>
                        <div class="pps-skills-list-nested" id="selectedSkillsListNested">
                            @foreach($visibleModalSkills as $skill)
                                <div class="pps-skill-chip-nested selected" data-skill="{{ $skill['slug'] }}">
                                    <span>{{ $skill['name'] }}</span>
                                    <button onclick="toggleSkillSelectionNested('{{ $skill['slug'] }}')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pps-skills-available-nested">
                        <div class="pps-skills-label-nested">
                            <span>Available Skills</span>
                            <span class="pps-count-badge-nested">{{ count($hiddenModalSkills) }}</span>
                        </div>
                        <div class="pps-skills-list-nested" id="availableSkillsListNested">
                            @foreach($hiddenModalSkills as $skill)
                                <div class="pps-skill-chip-nested" data-skill="{{ $skill['slug'] }}">
                                    <span>{{ $skill['name'] }}</span>
                                    <button onclick="toggleSkillSelectionNested('{{ $skill['slug'] }}')">
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
            @endif
        </div>

        <div class="pps-modal-footer-nested">
            <button class="pps-btn-secondary-nested" onclick="closeModalPortfolioOptions()">
                Close
            </button>
            <button class="pps-btn-primary-nested" onclick="applyModalFilters()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Apply Filters
            </button>
        </div>
    </div>
</div>

<style>
/* ==================== SEE ALL PROJECTS MODAL ==================== */

.modal-content-see-all {
    display: flex;
    flex-direction: column;
    max-height: 80vh;
}

/* Filter Bar */
.pps-filter-bar-modal {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    background: var(--apc-bg);
    flex-shrink: 0;
}

.pps-filters-modal {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow-x: auto;
    scrollbar-width: thin;
}

.pps-filters-modal::-webkit-scrollbar {
    height: 6px;
}

.pps-filters-modal::-webkit-scrollbar-track {
    background: var(--apc-bg);
    border-radius: 3px;
}

.pps-filters-modal::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

.pps-filter-chip-modal {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 16px;
    background: var(--card);
    color: var(--text-body);
    border: 1.5px solid var(--border);
    border-radius: 18px;
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.pps-filter-chip-modal:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
    transform: translateY(-1px);
}

.pps-filter-chip-modal.active {
    background: var(--accent);
    color: var(--text-white);
    border-color: var(--accent);
    box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
}

/* More Icon Button */
.pps-more-icon-modal {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    margin-left: 6px;
    flex-shrink: 0;
    background: var(--card);
    color: var(--text-body);
    border: 1.5px solid var(--border);
    border-radius: 18px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pps-more-icon-modal:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-light);
    transform: translateY(-1px);
}

/* No Skills Message */
.pps-no-skills-modal {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: var(--fs-subtle);
    font-style: italic;
}

.pps-no-skills-modal svg {
    flex-shrink: 0;
}

/* Projects List - Full Width Horizontal Cards */
.projects-list-modal {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.project-horizontal-card {
    width: 100%;
    transition: opacity 0.3s ease;
}

/* Empty States */
.empty-state-modal,
.no-results-modal {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 24px;
    text-align: center;
}

.empty-state-modal svg,
.no-results-modal svg {
    color: var(--text-muted);
    opacity: 0.3;
    margin-bottom: 20px;
}

.empty-title-modal {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 8px 0;
}

.empty-subtitle-modal {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin: 0;
}

/* ==================== NESTED FILTER OPTIONS MODAL ==================== */
.pps-modal-overlay-nested {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100000;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.pps-modal-overlay-nested.active {
    opacity: 1;
    visibility: visible;
}

.pps-modal-nested {
    background: var(--card);
    border-radius: 12px;
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.9);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.pps-modal-overlay-nested.active .pps-modal-nested {
    transform: scale(1);
}

.pps-modal-header-nested {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid var(--border);
}

.pps-modal-header-nested h3 {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.pps-modal-close-nested {
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
    transition: all 0.2s ease;
}

.pps-modal-close-nested:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
    transform: rotate(90deg);
}

.pps-modal-body-nested {
    flex: 1;
    padding: 24px;
    overflow-y: auto;
}

.pps-modal-section-nested {
    margin-bottom: 0;
}

.pps-modal-section-header-nested {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.pps-modal-section-header-nested h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: var(--fs-title);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.pps-modal-section-header-nested svg {
    color: var(--accent);
}

.pps-info-badge-nested {
    font-size: var(--fs-micro);
    font-weight: var(--fw-semibold);
    color: var(--text-muted);
    background: var(--apc-bg);
    padding: 4px 10px;
    border-radius: 12px;
}

.pps-modal-desc-nested {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin: 0 0 16px 0;
    line-height: var(--lh-relaxed);
}

/* Skills Lists */
.pps-skills-selected-nested,
.pps-skills-available-nested {
    margin-bottom: 20px;
}

.pps-skills-label-nested {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
}

.pps-count-badge-nested {
    font-size: var(--fs-subtle);
    font-weight: var(--fw-medium);
    color: var(--text-muted);
    background: var(--apc-bg);
    padding: 2px 8px;
    border-radius: 10px;
}

.pps-skills-list-nested {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 48px;
    padding: 14px;
    background: var(--apc-bg);
    border: 1.5px dashed var(--border);
    border-radius: 10px;
}

.pps-skills-selected-nested .pps-skills-list-nested {
    border-style: solid;
    border-color: var(--accent);
    background: var(--accent-light);
}

.pps-skill-chip-nested {
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
    transition: all 0.2s ease;
    user-select: none;
}

.pps-skill-chip-nested:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    transform: translateY(-1px);
}

.pps-skill-chip-nested.selected {
    background: var(--accent);
    color: var(--text-white);
    border-color: var(--accent);
}

.pps-skill-chip-nested button {
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
    transition: all 0.2s ease;
    padding: 0;
}

.pps-skill-chip-nested:not(.selected) button:hover {
    background: rgba(19, 81, 216, 0.15);
}

.pps-skill-chip-nested.selected button:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Modal Footer */
.pps-modal-footer-nested {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid var(--border);
}

.pps-btn-secondary-nested,
.pps-btn-primary-nested {
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
    transition: all 0.2s ease;
}

.pps-btn-secondary-nested {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-body);
}

.pps-btn-secondary-nested:hover {
    background: var(--apc-bg);
    border-color: var(--text-muted);
}

.pps-btn-primary-nested {
    background: var(--accent);
    color: var(--text-white);
    box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
}

.pps-btn-primary-nested:hover {
    background: var(--accent-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(19, 81, 216, 0.35);
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 768px) {
    .pps-filter-bar-modal {
        padding: 16px;
    }

    .projects-list-modal {
        padding: 16px;
        gap: 12px;
    }

    .pps-filter-chip-modal {
        font-size: var(--fs-subtle);
        height: 32px;
        padding: 0 12px;
    }

    .pps-more-icon-modal {
        width: 32px;
        height: 32px;
    }

    .pps-modal-nested {
        max-height: 90vh;
    }

    .pps-modal-header-nested,
    .pps-modal-body-nested,
    .pps-modal-footer-nested {
        padding: 20px 16px;
    }

    .pps-modal-footer-nested {
        flex-direction: column-reverse;
    }

    .pps-btn-secondary-nested,
    .pps-btn-primary-nested {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .pps-filter-chip-modal {
        font-size: var(--fs-micro);
        height: 30px;
        padding: 0 10px;
    }

    .projects-list-modal {
        padding: 12px;
    }
}
</style>

<script>
function showAllProjects() {
    openModal('seeAllProjectsModal');
    // Reset filter to "All"
    const allBtn = document.querySelector('#modalPortfolioFilters .pps-filter-chip-modal[data-skill="all"]');
    if (allBtn) {
        filterModalProjects('all', allBtn);
    }
}

function filterModalProjects(skillSlug, button) {
    // Update active button
    const buttons = document.querySelectorAll('#modalPortfolioFilters .pps-filter-chip-modal');
    buttons.forEach(btn => btn.classList.remove('active'));
    if (button) {
        button.classList.add('active');
    }
    
    // Filter projects
    const projects = document.querySelectorAll('.project-horizontal-card');
    const slug = (skillSlug || 'all').toLowerCase().trim();
    
    let visibleCount = 0;
    
    projects.forEach(project => {
        const skills = (project.dataset.skills || '')
            .split(',')
            .map(s => s.trim())
            .filter(Boolean);
        
        const shouldShow = slug === 'all' || skills.includes(slug);
        project.style.display = shouldShow ? '' : 'none';
        
        if (shouldShow) visibleCount++;
    });
    
    // Show/hide no results message
    const noResults = document.getElementById('noResultsModal');
    const grid = document.getElementById('modalProjectsGrid');
    
    if (visibleCount === 0 && slug !== 'all') {
        if (noResults) noResults.style.display = 'flex';
        if (grid) grid.style.display = 'none';
    } else {
        if (noResults) noResults.style.display = 'none';
        if (grid) grid.style.display = 'flex';
    }
}

// Open nested filter options modal
function openModalPortfolioOptions() {
    const modal = document.getElementById('portfolioOptionsModalNested');
    if (modal) {
        modal.classList.add('active');
    }
}

// Close nested filter options modal
function closeModalPortfolioOptions(event) {
    if (event && event.target.classList.contains('pps-modal-nested')) return;
    
    const modal = document.getElementById('portfolioOptionsModalNested');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Toggle skill selection in nested modal
function toggleSkillSelectionNested(skillSlug) {
    const selectedList = document.getElementById('selectedSkillsListNested');
    const availableList = document.getElementById('availableSkillsListNested');
    const countEl = document.getElementById('selectedSkillCountNested');

    if (!selectedList || !availableList) {
        console.error('Lists not found');
        return;
    }

    const skillElements = document.querySelectorAll(`[data-skill="${skillSlug}"]`);
    let skillElement = null;

    skillElements.forEach(el => {
        if (el.classList.contains('pps-skill-chip-nested')) {
            skillElement = el;
        }
    });

    if (!skillElement) {
        console.error('Skill element not found:', skillSlug);
        return;
    }

    const isSelected = skillElement.classList.contains('selected');
    const currentCount = selectedList.querySelectorAll('.pps-skill-chip-nested').length;

    if (isSelected) {
        availableList.appendChild(skillElement);
        skillElement.classList.remove('selected');
        skillElement.querySelector('button').innerHTML = `
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        `;
    } else {
        if (currentCount >= 6) {
            alert('You can select maximum 6 skills for quick filtering');
            return;
        }
        selectedList.appendChild(skillElement);
        skillElement.classList.add('selected');
        skillElement.querySelector('button').innerHTML = `
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        `;
    }

    if (countEl) {
        countEl.textContent = selectedList.querySelectorAll('.pps-skill-chip-nested').length;
    }
}

// Apply filters and update the visible filter buttons
function applyModalFilters() {
    const selectedList = document.getElementById('selectedSkillsListNested');
    const filterContainer = document.getElementById('modalPortfolioFilters');
    
    if (!selectedList || !filterContainer) return;
    
    // Get selected skills
    const selectedSkills = Array.from(selectedList.querySelectorAll('.pps-skill-chip-nested'))
        .map(el => ({
            slug: el.dataset.skill,
            name: el.querySelector('span').textContent
        }));
    
    // Rebuild filter buttons
    filterContainer.innerHTML = `
        <button class="pps-filter-chip-modal active" data-skill="all" onclick="filterModalProjects('all', this)">
            <span>All Projects</span>
        </button>
    `;
    
    selectedSkills.forEach(skill => {
        filterContainer.innerHTML += `
            <button class="pps-filter-chip-modal" data-skill="${skill.slug}" onclick="filterModalProjects('${skill.slug}', this)">
                <span>${skill.name}</span>
            </button>
        `;
    });
    
    // Add more options button
    filterContainer.innerHTML += `
        <button class="pps-more-icon-modal" onclick="openModalPortfolioOptions()" aria-label="More options" title="More filter options">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="5" cy="12" r="1.6"></circle>
                <circle cx="12" cy="12" r="1.6"></circle>
                <circle cx="19" cy="12" r="1.6"></circle>
            </svg>
        </button>
    `;
    
    // Reset to "All Projects" filter
    filterModalProjects('all', filterContainer.querySelector('[data-skill="all"]'));
    
    // Close the nested modal
    closeModalPortfolioOptions();
}

// Close nested modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const nestedModal = document.getElementById('portfolioOptionsModalNested');
        if (nestedModal && nestedModal.classList.contains('active')) {
            closeModalPortfolioOptions();
        }
    }
});
</script>