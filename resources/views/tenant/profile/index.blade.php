@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('title', $user->name . ' - Professional Portfolio')

@section('content')
    {{-- Top navigation --}}
    <x-navigation.top-nav :user="$user" :brand-name="$brandName" :message-count="$messageCount" />

    <div class="main-container">
        <div class="content-wrapper">
            {{-- Left Sidebar --}}
            <aside class="left-sidebar">
                <x-hero.mobile  :user="$user" />
                <x-hero.desktop :user="$user" />

                {{-- Top Skills --}}
                    <x-cards.sidebar-card title="Top Skills" :show-see-all="true">
                        <div class="tags" style="margin-bottom:8px">
                            @foreach ($user->topSkills as $skill)
                                <span class="tag">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </x-cards.sidebar-card>

                {{-- Soft Skills --}}
                    <x-cards.sidebar-card title="Soft Skills" :show-see-all="true" see-all-icon="arrow-right">
                        @foreach ($user->softSkills as $index => $skill)
                            <div class="skill-item">
                                <span style="color: {{ $index === 0 ? 'var(--accent)' : '#666' }}; width:20px; display:flex; justify-content:center;">
                                    <i class="fa-solid fa-{{ $skill['icon'] ?? 'lightbulb' }}"></i>
                                </span>
                                <span>{{ $skill['name'] }}</span>
                            </div>
                        @endforeach
                    </x-cards.sidebar-card>

                {{-- Languages --}}
                    <x-cards.sidebar-card title="Language">
                        @foreach ($user->languages as $language)
                            <div class="lang-row">
                                <span class="lang-name">{{ $language['name'] }}</span>
                                <span class="lang-level">— {{ $language['level'] }}</span>
                            </div>
                        @endforeach
                    </x-cards.sidebar-card>

                {{-- Education --}}
                    <x-cards.sidebar-card title="Education" :show-see-all="true" see-all-icon="arrow-right">
                        @foreach ($user->education as $edu)
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
                            .cards-header button .ui-icon { display:flex; align-items:center; justify-content:center; }
                            .projects-btn { display:flex; align-items:center; justify-content:space-between; gap:2vw; }
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

                        @if($showTabs)
                            <div class="filter-tabs">
                                @foreach($cats as $label)
                                    @php $slug = Str::slug($label ?: 'All'); @endphp
                                    <x-ui.button
                                        variant="{{ $loop->first ? 'solid' : 'outlined' }}"
                                        shape="rounded"
                                        color="primary_muted"
                                        size="sm"
                                        class="filter-tab-btn {{ $loop->first ? 'active' : '' }}"
                                        data-filter="{{ $slug }}"
                                        onclick="filterCategory('{{ $slug }}', this)">
                                        {{ $label }}
                                    </x-ui.button>
                                @endforeach
                            </div>
                        @endif

                        <div id="portfolioGrid" class="portfolio-grid">
                            @foreach($portfolios as $p)
                                @php $cat = Str::slug($p['category'] ?? 'All'); @endphp
                                <div class="portfolio-item" data-category="{{ $cat }}">
                                    <x-cards.portfolio-card :portfolio="$p" />
                                </div>
                            @endforeach
                        </div>

                        <x-ui.see-all text="See all Projects" onclick="showAllProjects()" />
                    </section>

                    <script>
                        (function () {
                            const grid = document.getElementById('portfolioGrid');
                            const btns = document.querySelectorAll('.filter-tab-btn');

                            window.filterCategory = function (category, buttonElement) {
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

                            window.showAllProjects = function () {
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
                {{-- @if(!empty($user->whyChooseMe)) --}}
                    <section class="card pad24">
                        <div class="cards-header">
                            <h2 class="portfolios-title">Why Choose Me?</h2>
                            <button class="edit-card icon-btn" aria-label="Edit card">
                                <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                            </button>
                        </div>
                        @foreach ($user->whyChooseMe as $reason)
                            <div class="choose-item">
                                <i class="fas fa-check-square"></i>
                                <span>{{ $reason }}</span>
                            </div>
                        @endforeach
                        <x-ui.see-all text="See all Why Choose Me" onclick="showAllWhyChooseMe()" />
                    </section>
                {{-- @endif --}}

                {{-- Services --}}
                    <section class="card pad24">
                        <div class="cards-header">
                            <h2 class="portfolios-title">Services</h2>
                            <button class="edit-card icon-btn" aria-label="Edit card">
                                <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                            </button>
                        </div>
                        @foreach ($user->services as $service)
                            <div class="choose-item">
                                <i class="fas fa-check-square"></i>
                                <span>{{ $service }}</span>
                            </div>
                        @endforeach
                        <x-ui.see-all text="See all Services" onclick="showAllServices()" />
                    </section>
            </aside>
        </div>
    </div>







    {{-- At the bottom of your profile page, before @endsection --}}

    <x-modals.edit-profile :user="$user" />
    <x-modals.edit-experience />
    <x-modals.edit-education />
    <x-modals.edit-skills />
    <x-modals.edit-portfolio />
    <x-modals.edit-languages />
    <x-modals.edit-services />
    <x-modals.edit-why-choose />
    
    {{-- Modal triggers script --}}
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
                
                // Determine which modal to open based on section/card class or title
                if (section && section.classList.contains('hero-merged')) {
                    openModal('editProfileModal');
                } else if (section && section.classList.contains('experience-section')) {
                    openModal('editExperienceModal');
                } else if (section && section.classList.contains('portfolios-section')) {
                    openModal('editPortfolioModal');
                } else if (section && section.classList.contains('skills-showcase')) {
                    openModal('editSkillsModal');
                } else if (card) {
                    // Check by title text for sidebar cards
                    const titleEl = card.querySelector('.section-title, .portfolios-title');
                    const title = titleEl ? titleEl.textContent.trim() : '';
                    
                    if (title === 'Top Skills') {
                        openModal('editSkillsModal');
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
