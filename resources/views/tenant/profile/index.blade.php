@php use Illuminate\Support\Str; @endphp

@php
    $LIMITS = [
        'projects' => 2,
        'experiences' => 3,
        'skills' => 4,
        'soft' => 6,
        'reviews' => 2,
    ];

    // Projects
    $totalProjects = count($portfolios ?? []);
    $visibleProjects = collect($portfolios ?? [])->take($LIMITS['projects']);

    // Experiences
    $totalExperiences = count($experiences ?? []);
    $visibleExperiences = collect($experiences ?? [])->take($LIMITS['experiences']);

    // Skills (for the showcase component)
    $totalHardSkills = count($skillsData ?? []);
    $totalSoftSkills = count($user->softSkills ?? []);
    $visibleSkillsData = collect($skillsData ?? [])->take($LIMITS['skills']);
    $visibleSoftSkills = collect($user->softSkills ?? [])->take($LIMITS['soft']);

    // Reviews
    $totalReviews = count($reviews ?? []);
    $visibleReviews = collect($reviews ?? [])->take($LIMITS['reviews']);
@endphp

@extends('layouts.app')

@section('title', $user->name . ' - Professional Portfolio')

@section('content')



    <x-navigation.top-nav :user="$user" />

    <div class="main-container">
        <div class="content-wrapper">
            {{-- Left Sidebar --}}
            <aside class="left-sidebar">
                <x-hero.mobile :user="$user" />
                <x-hero.desktop :user="$user" />

                <x-cards.sidebar-card title="Top Skills" :show-see-all="count($skillNames ?? []) > 5">
                    @php $visibleSkillNames = collect($skillNames ?? [])->take(5); @endphp
                    <div class="tags" style="margin-bottom:8px">
                        @forelse($visibleSkillNames as $name)
                            <span class="tag">{{ $name }}</span>
                        @empty
                            {{-- no tags --}}
                        @endforelse
                    </div>
                </x-cards.sidebar-card>



                <x-cards.sidebar-card title="Soft Skills" :show-see-all="count($user->softSkills ?? []) > 4" see-all-icon="arrow-right">
                    @php $visibleSoft = collect($user->softSkills ?? [])->take(4); @endphp
                    @foreach ($visibleSoft as $skill)
                        <div class="skill-item">
                            <span
                                style="color: {{ $loop->first ? 'var(--accent)' : '#666' }}; width:20px; display:flex; justify-content:center;">
                                <i class="fa-solid fa-{{ $skill['icon'] ?? 'lightbulb' }}"></i>
                            </span>
                            <span>{{ $skill['name'] }}</span>
                        </div>
                    @endforeach
                </x-cards.sidebar-card>


















                <x-cards.sidebar-card title="Languages" :show-see-all="count($user->languages ?? []) > 3">
                    @php $visibleLangs = collect($user->languages ?? [])->take(3); @endphp
                    @foreach ($visibleLangs as $language)
                        <div class="lang-row">
                            <span class="lang-name">{{ $language['name'] }}</span>
                            <span class="lang-level">— {{ $language['level'] }}</span>
                        </div>
                    @endforeach
                </x-cards.sidebar-card>



                {{-- Education --}}
                <x-cards.sidebar-card title="Education" :show-see-all="count($user->education ?? []) > 2" see-all-icon="arrow-right">
                    @php $visibleEdu = collect($user->education ?? [])->take(2); @endphp
                    @foreach ($visibleEdu as $edu)
                        <div class="edu-item">
                            <div class="edu-head">
                                <div class="edu-title">{{ $edu['title'] }}</div>
                                @if ($edu['recent'] ?? false)
                                    <span class="pill">Recent</span>
                                @endif
                            </div>
                            <div class="edu-sub">{{ $edu['institution'] }}</div>
                            <div class="edu-date">{{ $edu['period'] }}</div>
                            <div class="edu-loc">{{ $edu['location'] }}</div>
                        </div>
                    @endforeach
                </x-cards.sidebar-card>

            </aside>

            {{-- Main Content --}}
            <main class="main-content">
                {{-- Hero Banner --}}
                <div class="hero-banner">
                    <div class="hero-logo">
                        <span>&lt;&lt;&lt;</span>
                        <span>{{ $brandName ?? 'Portfolio' }}</span>
                    </div>
                    <button class="ask-leo-btn">
                        <i class="fas fa-sparkles"></i>
                        Ask Leo Ai
                    </button>
                </div>



                <style>
                    /* old header look + counter pill */
                    .cards-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;

                    }

                    .portfolios-title {
                        margin: 0;
                        font-size: var(--fs-h2);
                        font-weight: var(--fw-semibold);
                        color: var(--text-heading);
                    }

                    .projects-btn {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }

                    .pps-count {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        min-width: 28px;
                        height: 28px;
                        padding: 0 8px;
                        border-radius: 14px;
                        background: var(--apc-bg);
                        color: var(--text-muted);
                        font-size: var(--fs-subtle);
                        font-weight: var(--fw-semibold);
                    }


                    /* inline with chips, icon-only */
                    .pps-more-icon {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 32px;
                        height: 32px;
                        margin-left: 6px;
                        flex: 0 0 auto;
                        background: var(--card);
                        color: var(--text-body);
                        border: 1.5px solid var(--border);
                        border-radius: 16px;
                        cursor: pointer;
                        transition: var(--pps-transition);
                    }

                    .pps-more-icon:hover {
                        border-color: var(--accent);
                        color: var(--accent);
                        background: var(--accent-light);
                        transform: translateY(-1px);
                    }

                    .pps-filter-bar {
                        gap: 8px;
                    }

                    .pps-filters {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        overflow-x: auto;
                        scrollbar-width: none;
                    }

                    .pps-filters::-webkit-scrollbar {
                        display: none;
                    }
                </style>


                <section class="pro-portfolio-section">
                    @php
                        $totalProjects = $totalProjects ?? count($sortedPortfolios ?? []);
                        $visibleProjects = $visibleProjects ?? collect($sortedPortfolios ?? [])->take(3);
                        $userSkillsForFilters = $userSkillsForFilters ?? [];
                        $visibleSkills = $visibleSkills ?? array_slice($userSkillsForFilters, 0, 6);
                        $hiddenSkills = $hiddenSkills ?? [];
                    @endphp

                    {{-- Section Header --}}
                    {{-- Projects header (old UX, keeps new functionality) --}}
                    <div class="cards-header">
                        <div class="title-wrap" style="display:flex;align-items:center;gap:10px">
                            <h2 class="portfolios-title">Projects</h2>
                            {{-- keep this so your JS can still update the count --}}
                            <span class="pps-count">{{ $totalProjects }}</span>
                        </div>

                        <div class="projects-btn">
                            {{-- <x-ui.button
            variant="special-outlined"
            shape="rounded"
            color="primary"
            size="sm"
            onclick="openModal('editPortfolioModal')">
            Add Project
        </x-ui.button> --}}

                            <button class="edit-card icon-btn" aria-label="Edit projects"
                                onclick="openModal('editPortfolioModal')">
                                <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                            </button>
                        </div>
                    </div>


                    @if (count($userSkillsForFilters) > 0)
                        <div class="pps-filter-bar">
                            <div class="pps-filters" id="portfolioFilters">
                                <button class="pps-filter-chip active" data-skill="all"
                                    onclick="filterPortfoliosBySkill('all', this)">
                                    <span>All Projects</span>
                                </button>

                                @foreach ($visibleSkills as $skill)
                                    <button class="pps-filter-chip" data-skill="{{ $skill['slug'] }}"
                                        onclick="filterPortfoliosBySkill('{{ $skill['slug'] }}', this)">
                                        <span>{{ $skill['name'] }}</span>
                                    </button>
                                @endforeach

                                {{-- NEW: icon-only "More" placed directly after tabs --}}
                                <button class="pps-more-icon" onclick="openPortfolioOptions()" aria-label="More options"
                                    title="More options">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="1.6"></circle>
                                        <circle cx="12" cy="12" r="1.6"></circle>
                                        <circle cx="19" cy="12" r="1.6"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- No skills in any project --}}
                        <div class="pps-filter-bar">
                            <div class="pps-no-skills">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                <span>Add project skills to enable filtering</span>
                            </div>
                            {{-- keep a small icon on the right for consistency --}}
                            <button class="pps-more-icon" onclick="openPortfolioOptions()" aria-label="More options"
                                title="More options">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="5" cy="12" r="1.6"></circle>
                                    <circle cx="12" cy="12" r="1.6"></circle>
                                    <circle cx="19" cy="12" r="1.6"></circle>
                                </svg>
                            </button>
                        </div>
                    @endif



                    {{-- Projects Grid (shows only first N on index) --}}
                    <div class="pps-grid" id="portfolioGrid">
                        @forelse ($visibleProjects as $p)
                            @php
                                $skillSlugs = $p['skill_slugs'] ?? [];
                                $skillSlugsString = is_array($skillSlugs) ? implode(',', $skillSlugs) : '';
                            @endphp
                            <div class="pps-project-item" data-skills="{{ $skillSlugsString }}">
                                <x-cards.portfolio-card :portfolio="$p" :userSkills="$userSkills" />
                            </div>
                        @empty
                            {{-- empty state --}}
                        @endforelse
                    </div>

                    {{-- 🔒 Hidden store with ALL remaining projects (keeps order) --}}
                    @php
                        $allForStore = collect($sortedPortfolios ?? ($portfolios ?? []))->slice(
                            $LIMITS['projects'] ?? 2,
                        );
                    @endphp

                    <div id="allPortfolioStore" style="display:none">
                        @foreach ($allForStore as $p)
                            @php
                                $skillSlugs = $p['skill_slugs'] ?? [];
                                $skillSlugsString = is_array($skillSlugs) ? implode(',', $skillSlugs) : '';
                            @endphp
                            <div class="pps-project-item" data-skills="{{ $skillSlugsString }}">
                                <x-cards.portfolio-card :portfolio="$p" :userSkills="$userSkills" />
                            </div>
                        @endforeach
                    </div>




                    @if ($totalProjects > $LIMITS['projects'])
                        <x-ui.see-all text="See all Projects" onclick="showAllProjects()" />
                    @endif

                </section>



                <div class="pps-modal-overlay" id="portfolioOptionsModal" onclick="closePortfolioOptions(event)">
                    <div class="pps-modal" onclick="event.stopPropagation()">
                        <div class="pps-modal-header">
                            <h3>Portfolio Options</h3>
                            <button class="pps-modal-close" onclick="closePortfolioOptions()">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <div class="pps-modal-body">
                            @if (count($userSkillsForFilters) > 0)
                                {{-- Filter Skills Section --}}
                                <div class="pps-modal-section">
                                    <div class="pps-modal-section-header">
                                        <h4>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                <polyline points="22 4 12 14.01 9 11.01" />
                                            </svg>
                                            Filter by Skills
                                        </h4>
                                        <span class="pps-info-badge">Select up to 6</span>
                                    </div>
                                    <p class="pps-modal-desc">Choose which skills to display as quick filters</p>

                                    <div class="pps-skills-selected">
                                        <div class="pps-skills-label">
                                            <span>Selected (<span
                                                    id="selectedSkillCount">{{ count($visibleSkills) }}</span>/6)</span>
                                        </div>
                                        <div class="pps-skills-list" id="selectedSkillsList">
                                            @foreach ($visibleSkills as $skill)
                                                <div class="pps-skill-chip selected" data-skill="{{ $skill['slug'] }}">
                                                    <span>{{ $skill['name'] }}</span>
                                                    <button onclick="toggleSkillSelection('{{ $skill['slug'] }}')">
                                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <line x1="18" y1="6" x2="6"
                                                                y2="18"></line>
                                                            <line x1="6" y1="6" x2="18"
                                                                y2="18"></line>
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
                                            @foreach ($hiddenSkills as $skill)
                                                <div class="pps-skill-chip" data-skill="{{ $skill['slug'] }}"
                                                    onclick="toggleSkillSelection('{{ $skill['slug'] }}')">
                                                    <span>{{ $skill['name'] }}</span>
                                                    <button>
                                                        <svg width="14" height="14" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <line x1="12" y1="5" x2="12"
                                                                y2="19"></line>
                                                            <line x1="5" y1="12" x2="19"
                                                                y2="12"></line>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Divider --}}
                            @endif


                        </div>

                        <div class="pps-modal-footer">
                            <button class="pps-btn-secondary" onclick="closePortfolioOptions()">
                                Cancel
                            </button>
                            <button class="pps-btn-primary" onclick="savePortfolioOptions()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>



                {{-- Skills showcase --}}
                <x-sections.skills-showcase :skills="$skillsData" :soft-skills="$user->softSkills" />

                {{-- Experience --}}
                <x-sections.experience :experiences="$experiences" />

                {{-- Reviews --}}
                <x-sections.reviews :reviews="$reviews" />
            </main>

            {{-- Right Sidebar --}}
            <aside class="right-sidebar">
                {{-- AI Profile Creator --}}
                <section class="ai-profile-creator">
                    <div class="ai-creator-header">
                        <h3 class="ai-creator-title">AI Profile Creator</h3>
                        <p class="ai-creator-desc">
                            Upload your CV or describe yourself — AI will build your profile.
                        </p>
                    </div>

                    <div class="ai-creator-content">
                        <div class="upload-section">
                            <label for="cv-upload" class="upload-box">
                                <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                <span class="upload-text">Upload your CV</span>
                            </label>
                            <input type="file" id="cv-upload" accept=".pdf,.doc,.docx" hidden>
                        </div>

                        <div class="or-divider">
                            <span class="or-line"></span>
                            <span class="or-text">OR</span>
                            <span class="or-line"></span>
                        </div>

                        <div class="textarea-section">
                            <div class="textarea-wrapper">
                                <textarea class="describe-textarea" placeholder="Describe yourself here...." rows="3"></textarea>
                            </div>
                        </div>

                        <button class="generate-profile-btn">Generate Profile</button>
                    </div>
                </section>

                {{-- Why Choose Me --}}
                {{-- @if (!empty($user->whyChooseMe)) --}}
                <section class="card pad24">
                    <div class="cards-header">
                        <h2 class="portfolios-title">Why Choose Me?</h2>
                        <button class="edit-card icon-btn" aria-label="Edit card">
                            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                        </button>
                    </div>

                    @php $visibleReasons = collect($user->whyChooseMe ?? [])->take(3); @endphp
                    @foreach ($visibleReasons as $reason)
                        <div class="choose-item">
                            <i class="fas fa-check-square"></i>
                            <span>{{ $reason }}</span>
                        </div>
                    @endforeach

                    @if (count($user->whyChooseMe ?? []) > 3)
                        <x-ui.see-all text="See all Why Choose Me" onclick="showAllWhyChooseMe()" />
                    @endif
                </section>


                <section class="card pad24">
                    <div class="cards-header">
                        <h2 class="portfolios-title">Services</h2>
                        <button class="edit-card icon-btn" aria-label="Edit card">
                            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                        </button>
                    </div>

                    @php $visibleServices = collect($user->services ?? [])->take(3); @endphp
                    @foreach ($visibleServices as $service)
                        <div class="choose-item">
                            <i class="fas fa-check-square"></i>
                            <span>{{ $service }}</span>
                        </div>
                    @endforeach

                    @if (count($user->services ?? []) > 3)
                        <x-ui.see-all text="See all Services" onclick="showAllServices()" />
                    @endif
                </section>


            </aside>
        </div>
    </div>







    <x-modals.edits.edit-profile :user="$user" />
    <x-modals.edits.edit-experience :modal-experiences="$modalExperiences" />
    <x-modals.edits.edit-education :user-educations="$userEducations" :institutions-search-url="route('api.institutions.search')" />
    <x-modals.edits.edit-skills :modal-skills="$modalSkills" :soft-skill-options="$softSkillOptions" :selected-soft="$selectedSoft" />
    <x-modals.edits.edit-portfolio :modal-portfolios="$modalPortfolios" :userSkills="$userSkills" />
    <x-modals.edits.edit-languages :modal-languages="$modalLanguages" />
    <x-modals.edits.edit-services :services="$modalServices" />
    <x-modals.edits.edit-why-choose :reasons="$modalReasons" />



    {{-- See All Modals --}}
    <x-modals.see-all.reviews :reviews="$reviews" />
    <x-modals.see-all.projects :portfolios="$portfolios" :categories="$categories" :userSkills="$userSkills" /> <x-modals.see-all.skills
        :skills="$skillsData" :soft-skills="$user->softSkills" />
    <x-modals.see-all.languages :languages="$user->languages" />
    <x-modals.see-all.educations :education="$user->education" />
    <x-modals.see-all.why-choose :reasons="$user->whyChooseMe" />
    <x-modals.see-all.services :services="$user->services" />
    <x-modals.see-all.soft-skills :soft-skills="$user->softSkills" />

    <x-modals.edits.edit-top-skills :modal-skills="$modalSkills" />
    <x-modals.edits.edit-soft-skills :soft-skill-options="$softSkillOptions" :selected-soft="$selectedSoft" />

    <script>
        (function() {
            'use strict';

            const LIMIT = {{ $LIMITS['projects'] ?? 2 }};
            const allSkills = @json($userSkillsForFilters ?? []);
            const currentSortOrder = @json($sortOrder ?? 'position');

            // DOM References
            const grid = document.getElementById('portfolioGrid');
            let store = document.getElementById('allPortfolioStore');

            // State
            let currentFilter = 'all';
            let allProjects = [];

            console.log('📊 Skills loaded:', allSkills);
            console.log('📊 Current sort order:', currentSortOrder);
            console.log('📦 Display limit:', LIMIT);



            function ensureStore() {
                if (!store) {
                    store = document.createElement('div');
                    store.id = 'allPortfolioStore';
                    store.style.display = 'none';
                    if (grid && grid.parentNode) {
                        grid.parentNode.insertBefore(store, grid.nextSibling);
                    }
                }
            }

            function getAllProjects() {
                // Collect from both grid and store
                const gridItems = Array.from(grid?.querySelectorAll('.pps-project-item') || []);
                const storeItems = Array.from(store?.querySelectorAll('.pps-project-item') || []);

                // Combine and deduplicate
                const allItems = [...gridItems, ...storeItems];
                const seen = new Set();
                return allItems.filter(el => {
                    const id = el.dataset.projectId || el.innerHTML;
                    if (seen.has(id)) return false;
                    seen.add(id);
                    return true;
                });
            }

            function moveAllToStore() {
                if (!store || !grid) return;
                allProjects.forEach(el => {
                    if (el.parentNode !== store) {
                        store.appendChild(el);
                    }
                });
            }

            function filterProjects(skillSlug) {
                const slug = (skillSlug || 'all').trim();

                if (slug === 'all') {
                    return [...allProjects];
                }

                return allProjects.filter(el => {
                    const skills = (el.dataset.skills || '')
                        .split(',')
                        .map(s => s.trim())
                        .filter(Boolean);
                    return skills.includes(slug);
                });
            }

            function renderProjects(projects, limit = LIMIT) {
                if (!grid) return;

                grid.innerHTML = '';

                if (projects.length === 0) {
                    grid.innerHTML = `
                <div class="pps-empty" style="padding:40px;text-align:center;grid-column:1/-1;">
                    <h3>No projects match this filter</h3>
                    <p>Try selecting a different skill.</p>
                </div>
            `;
                    return;
                }

                const toShow = projects.slice(0, limit);
                toShow.forEach(el => {
                    el.style.display = '';
                    grid.appendChild(el);
                });

                // Keep remaining in store
                projects.slice(limit).forEach(el => {
                    if (store) store.appendChild(el);
                });

                console.log(
                    `✅ Rendered ${toShow.length} of ${projects.length} projects for filter: "${currentFilter}"`);
            }

            function updateActiveChip(button) {
                const buttons = document.querySelectorAll('.pps-filter-chip');
                buttons.forEach(btn => btn.classList.remove('active'));
                if (button) {
                    button.classList.add('active');
                }
            }


            window.filterPortfoliosBySkill = function(skillSlug, button) {
                console.log('🔍 Filtering by skill:', skillSlug);

                currentFilter = skillSlug || 'all';
                updateActiveChip(button);

                moveAllToStore();

                const filtered = filterProjects(currentFilter);
                renderProjects(filtered, LIMIT);
            };

            window.openPortfolioOptions = function() {
                const modal = document.getElementById('portfolioOptionsModal');
                if (modal) {
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            };

            window.closePortfolioOptions = function(event) {
                if (event && event.target.classList.contains('pps-modal')) return;

                const modal = document.getElementById('portfolioOptionsModal');
                if (modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            };

            window.toggleSkillSelection = function(skillSlug) {
                const selectedList = document.getElementById('selectedSkillsList');
                const availableList = document.getElementById('availableSkillsList');
                const countEl = document.getElementById('selectedSkillCount');

                if (!selectedList || !availableList) {
                    console.error('Lists not found');
                    return;
                }

                const skillElements = document.querySelectorAll(`[data-skill="${skillSlug}"]`);
                let skillElement = null;

                skillElements.forEach(el => {
                    if (el.classList.contains('pps-skill-chip')) {
                        skillElement = el;
                    }
                });

                if (!skillElement) {
                    console.error('Skill element not found:', skillSlug);
                    return;
                }

                const isSelected = skillElement.classList.contains('selected');
                const currentCount = selectedList.querySelectorAll('.pps-skill-chip').length;

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
                    countEl.textContent = selectedList.querySelectorAll('.pps-skill-chip').length;
                }
            };

            window.savePortfolioOptions = async function() {
                const selectedList = document.getElementById('selectedSkillsList');
                const sortOrder = document.querySelector('input[name="sortOrder"]:checked')?.value ||
                    'position';

                let selectedSkills = [];
                if (selectedList) {
                    selectedSkills = Array.from(selectedList.querySelectorAll('.pps-skill-chip'))
                        .map(el => el.dataset.skill)
                        .filter(s => s);
                }

                console.log('💾 Saving preferences...');
                console.log('Selected skills:', selectedSkills);
                console.log('Sort order:', sortOrder);

                if (allSkills.length > 0 && selectedSkills.length === 0) {
                    alert('Please select at least one skill for filtering');
                    return;
                }

                const saveBtn = document.querySelector('.pps-btn-primary');
                const originalText = saveBtn?.innerHTML;
                if (saveBtn) {
                    saveBtn.disabled = true;
                    saveBtn.innerHTML = 'Saving...';
                }

                try {
                    const response = await fetch('{{ route('tenant.filter-preferences') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content || ''
                        },
                        body: JSON.stringify({
                            visible_skills: selectedSkills,
                            sort_order: sortOrder
                        })
                    });

                    const data = await response.json();
                    console.log('✅ Response:', data);

                    if (data.success) {
                        // Reload page to apply new sorting from backend
                        window.location.reload();
                    } else {
                        alert('Failed to save preferences: ' + (data.message || 'Unknown error'));
                        if (saveBtn) {
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = originalText;
                        }
                    }
                } catch (error) {
                    console.error('❌ Error saving preferences:', error);
                    alert('Failed to save preferences. Please check console for details.');
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalText;
                    }
                }
            };

            window.showAllPortfolioProjects = function() {
                if (typeof openModal === 'function') {
                    const modalEl = document.getElementById('seeAllProjectsModal');
                    if (modalEl) {
                        openModal('seeAllProjectsModal');
                        return;
                    }
                }

                currentFilter = 'all';
                const allBtn = document.querySelector('.pps-filter-chip[data-skill="all"]');
                updateActiveChip(allBtn);

                moveAllToStore();
                const filtered = filterProjects('all');
                renderProjects(filtered, filtered.length);
            };


            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    window.closePortfolioOptions();
                }
            });



            document.addEventListener('DOMContentLoaded', function() {
                console.log('🚀 Initializing portfolio section...');

                if (!grid) {
                    console.error('❌ Portfolio grid not found');
                    return;
                }

                // Setup store
                ensureStore();

                // Collect all projects
                allProjects = getAllProjects();
                console.log('📦 Total projects found:', allProjects.length);

                // Move all to store
                moveAllToStore();

                // Render initial view (respects backend sorting)
                const filtered = filterProjects('all');
                renderProjects(filtered, LIMIT);

                // Set active chip
                const allBtn = document.querySelector('.pps-filter-chip[data-skill="all"]');
                if (allBtn) allBtn.classList.add('active');

                console.log('✅ Portfolio section initialized');
            });

        })();







        // Global modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Close modal on overlay click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                closeModal(e.target.id);
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal-overlay[style*="display: flex"]');
                openModals.forEach(modal => closeModal(modal.id));
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Handle all edit buttons
            document.querySelectorAll('.edit-card').forEach(button => {
                // Skip buttons that already have specific handlers
                if (button.id === 'desktopMenuBtn' || button.classList.contains('edit-profile-btn')) {
                    return;
                }

                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    const section = this.closest('section');
                    const card = this.closest('.card');

                    // ✅ Main content sections
                    if (section && section.classList.contains('hero-merged')) {
                        openModal('editProfileModal');
                    } else if (section && section.classList.contains('experience-section')) {
                        openModal('editExperienceModal');
                    } else if (section && section.classList.contains('portfolios-section')) {
                        openModal('editPortfolioModal');
                    } else if (section && section.classList.contains('skills-showcase')) {
                        // ✅ Skills Showcase - Opens FULL modal with both skills
                        openModal('editSkillsModal');
                    } else if (card) {
                        // ✅ Sidebar cards - Opens SPECIFIC modals
                        const titleEl = card.querySelector('.section-title, .portfolios-title');
                        const title = titleEl ? titleEl.textContent.trim() : '';

                        if (title === 'Top Skills') {
                            // ✅ Sidebar Top Skills - Opens technical skills ONLY
                            openModal('editTopSkillsModal');
                        } else if (title === 'Soft Skills') {
                            // ✅ Sidebar Soft Skills - Opens soft skills ONLY
                            openModal('editSoftSkillsModal');
                        } else if (title === 'Education') {
                            openModal('editEducationModal');
                        } else if (title === 'Language' || title === 'Languages') {
                            openModal('editLanguagesModal');
                        } else if (title === 'Why Choose Me?') {
                            openModal('editWhyChooseModal');
                        } else if (title === 'Services') {
                            openModal('editServicesModal');
                        }
                    }
                });
            });
        });
    </script>
@endsection
