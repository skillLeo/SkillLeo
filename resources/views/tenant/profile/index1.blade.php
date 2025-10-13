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

    <style>
        .left-sidebar,
        .main-content,
        .right-sidebar {
            position: relative;
        }
    </style>





    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leftSidebar = document.querySelector('.left-sidebar');
            const mainContent = document.querySelector('.main-content');
            const rightSidebar = document.querySelector('.right-sidebar');

            let leftFixed = false;
            let rightFixed = false;
            let mainFixed = false;

            function handleScroll() {
                const scrollTop = window.pageYOffset;
                const viewportHeight = window.innerHeight;

                // Get positions and heights
                const leftRect = leftSidebar.getBoundingClientRect();
                const mainRect = mainContent.getBoundingClientRect();
                const rightRect = rightSidebar.getBoundingClientRect();

                // RIGHT SIDEBAR - Stop when bottom reaches viewport bottom
                if (!rightFixed && rightRect.bottom <= viewportHeight) {
                    rightSidebar.style.position = 'sticky';
                    rightSidebar.style.top = `${viewportHeight - rightSidebar.offsetHeight}px`;
                    rightSidebar.style.alignSelf = 'flex-start';
                    rightFixed = true;
                } else if (rightFixed && scrollTop < rightSidebar.dataset.stopPoint - 100) {
                    rightSidebar.style.position = 'relative';
                    rightSidebar.style.top = '0';
                    rightFixed = false;
                }

                // LEFT SIDEBAR - Stop when bottom reaches viewport bottom
                if (!leftFixed && leftRect.bottom <= viewportHeight) {
                    leftSidebar.style.position = 'sticky';
                    leftSidebar.style.top = `${viewportHeight - leftSidebar.offsetHeight}px`;
                    leftSidebar.style.alignSelf = 'flex-start';
                    leftFixed = true;
                } else if (leftFixed && scrollTop < leftSidebar.dataset.stopPoint - 100) {
                    leftSidebar.style.position = 'relative';
                    leftSidebar.style.top = '0';
                    leftFixed = false;
                }

                // MAIN CONTENT - Stop when bottom reaches viewport bottom
                if (!mainFixed && mainRect.bottom <= viewportHeight) {
                    mainContent.style.position = 'sticky';
                    mainContent.style.top = `${viewportHeight - mainContent.offsetHeight}px`;
                    mainContent.style.alignSelf = 'flex-start';
                    mainFixed = true;
                } else if (mainFixed && scrollTop < mainContent.dataset.stopPoint - 100) {
                    mainContent.style.position = 'relative';
                    mainContent.style.top = '0';
                    mainFixed = false;
                }

                // Store stop points
                if (rightFixed && !rightSidebar.dataset.stopPoint) {
                    rightSidebar.dataset.stopPoint = scrollTop;
                }
                if (leftFixed && !leftSidebar.dataset.stopPoint) {
                    leftSidebar.dataset.stopPoint = scrollTop;
                }
                if (mainFixed && !mainContent.dataset.stopPoint) {
                    mainContent.dataset.stopPoint = scrollTop;
                }
            }

            window.addEventListener('scroll', handleScroll, {
                passive: true
            });
        });
    </script> --}}

    {{-- Top navigation --}}
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

                {{-- Portfolios (only if user has projects) --}}














































                <section class="portfolios-section">
                    <style>
                        .cards-header button .ui-icon {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .projects-btn {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 2vw;
                        }
                    </style>

                    <div class="cards-header">
                        <h2 class="portfolios-title">Portfolios</h2>
                        <div class="projects-btn">
                            <x-ui.button variant="special-outlined" shape="rounded" color="primary" size="sm">
                                Add Projects
                            </x-ui.button>
                            <button class="edit-card icon-btn" aria-label="Edit card">
                                <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                            </button>
                        </div>
                    </div>

                    {{-- Filter tabs (hide if there’s only “All”) --}}
                    @php
                        $cats = array_values(array_filter($categories ?? []));
                        $showTabs = collect($cats)->reject(fn($c) => strtolower($c) === 'all')->isNotEmpty();
                    @endphp

                    @if ($showTabs)
                        <div class="filter-tabs">
                            @foreach ($cats as $label)
                                @php $slug = Str::slug($label ?: 'All'); @endphp
                                <x-ui.button variant="{{ $loop->first ? 'solid' : 'outlined' }}" shape="rounded"
                                    color="primary_muted" size="sm"
                                    class="filter-tab-btn {{ $loop->first ? 'active' : '' }}"
                                    data-filter="{{ $slug }}"
                                    onclick="filterCategory('{{ $slug }}', this)">
                                    {{ $label }}
                                </x-ui.button>
                            @endforeach
                        </div>
                    @endif

                    <div id="portfolioGrid" class="portfolio-grid">
                        @forelse ($visibleProjects as $p)
                            @php $cat = Str::slug($p['category'] ?? 'All'); @endphp
                            <div class="portfolio-item" data-category="{{ $cat }}">
                                <x-cards.portfolio-card :portfolio="$p" />
                            </div>
                        @empty
                            {{-- optional: show placeholders / empty state --}}
                        @endforelse
                    </div>

                    @if ($totalProjects > $LIMITS['projects'])
                    <x-ui.see-all text="See all Projects" onclick="showAllProjects()" />
                @endif
                

                </section>
<script>
window.showAllProjects = function () {
  openModal('seeAllProjectsModal');
};
</script>

                <script>
                    (function() {
                        const grid = document.getElementById('portfolioGrid');
                        const btns = document.querySelectorAll('.filter-tab-btn');

                        window.filterCategory = function(category, buttonElement) {
                            btns.forEach(btn => {
                                btn.classList.remove('btn-solid', 'active');
                                btn.classList.add('btn-outlined');
                            });
                            if (buttonElement) {
                                buttonElement.classList.remove('btn-outlined');
                                buttonElement.classList.add('btn-solid', 'active');
                            }
                            const items = grid.querySelectorAll('.portfolio-item');
                            const cat = (category || 'all').toLowerCase();
                            items.forEach(el => {
                                const c = (el.dataset.category || 'all').toLowerCase();
                                el.style.display = (cat === 'all' || c === cat) ? '' : 'none';
                            });
                        };

                        window.showAllProjects = function() {
                            const firstBtn = document.querySelector('.filter-tab-btn[data-filter="all"]') || btns[0];
                            if (firstBtn) filterCategory(firstBtn.dataset.filter, firstBtn);
                        };
                    })();
                </script>

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







    <!-- resources/views/tenant/profile/index.blade.php (or wherever) -->
    <x-modals.edits.edit-profile :user="$user" />
    <x-modals.edits.edit-experience :modal-experiences="$modalExperiences" />
    <x-modals.edits.edit-education :user-educations="$userEducations" :institutions-search-url="route('api.institutions.search')" />
    <x-modals.edits.edit-skills :modal-skills="$modalSkills" :soft-skill-options="$softSkillOptions" :selected-soft="$selectedSoft" />
    <x-modals.edits.edit-portfolio :modal-portfolios="$modalPortfolios"     :userSkills="$userSkills"
    />
    <x-modals.edits.edit-languages :modal-languages="$modalLanguages" />
    <x-modals.edits.edit-services :services="$modalServices" />
    <x-modals.edits.edit-why-choose :reasons="$modalReasons" />
    {{-- <x-modals.edits.edit-reviews :modal-reviews="$modalReviews" /> --}}



    {{-- See All Modals --}}
    <x-modals.see-all.reviews :reviews="$reviews" />
    {{-- <x-modals.see-all.experiences :experiences="$experiences" /> --}}
    <x-modals.see-all.projects :portfolios="$portfolios" :categories="$categories" />
    <x-modals.see-all.skills :skills="$skillsData" :soft-skills="$user->softSkills" />
    <x-modals.see-all.languages :languages="$user->languages" />
    <x-modals.see-all.educations :education="$user->education" />
    <x-modals.see-all.why-choose :reasons="$user->whyChooseMe" />
    <x-modals.see-all.services :services="$user->services" />
    <x-modals.see-all.soft-skills :soft-skills="$user->softSkills" />

    <x-modals.edits.edit-top-skills :modal-skills="$modalSkills" />
    <x-modals.edits.edit-soft-skills :soft-skill-options="$softSkillOptions" :selected-soft="$selectedSoft" />

    <script>
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
    </script>

    <script>
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
