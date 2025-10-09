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
    <style>
        /* ========== MOBILE STATUS BADGE ========== */
        .hm-avatar {
            position: relative;
        }
        
        .hm-status-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 3px solid var(--card, #fff);
            background: #9ca3af;
            z-index: 10;
            transform: translate(-4px, -4px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .hm-status-badge.online {
            background: #10b981;
        }
        
        .hm-status-badge .pulse-ring {
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            border: 3px solid #10b981;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            opacity: 0;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 0;
                transform: scale(1);
            }
            50% {
                opacity: 0.5;
                transform: scale(1.3);
            }
        }
        
        /* Mobile Status Text Below Name */
        .hm-online-status {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: -4px;
            margin-bottom: 8px;
            padding: 0 4px;
        }
        
        .hm-online-status .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            flex-shrink: 0;
            animation: pulse-dot 2s ease-in-out infinite;
        }
        
        @keyframes pulse-dot {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.15);
            }
        }
        
        .hm-online-status .status-text {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted, #6b7280);
            line-height: 1;
        }
        
        .hm-online-status .status-text.online {
            color: #10b981;
            font-weight: 600;
        }
        
        
        /* ========== DESKTOP STATUS BADGE ========== */
        .photo-circle {
            position: relative;
        }
        
        .desktop-status-badge {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 4px solid var(--card, #fff);
            background: #9ca3af;
            z-index: 10;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.18);
            transition: all 0.3s ease;
        }
        
        .desktop-status-badge.online {
            background: #10b981;
        }
        
        .desktop-status-badge .pulse-ring {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 4px solid #10b981;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            opacity: 0;
        }
        
        /* Desktop Status Text Below Avatar */
        .desktop-status-text {
            margin-top: 12px;
            text-align: center;
        }
        
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted, #6b7280);
            padding: 4px 12px;
            border-radius: 12px;
            background: var(--apc-bg, #f3f4f6);
            transition: all 0.2s ease;
        }
        
        .status-indicator.online {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
            font-weight: 600;
        }
        
        .status-indicator.online .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse-dot 2s ease-in-out infinite;
        }
        
        .status-indicator.offline {
            color: var(--text-muted, #6b7280);
        }
        
        /* Hover Effects */
        .photo-circle:hover .desktop-status-badge {
            transform: scale(1.1);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }
        
        .hm-avatar:hover .hm-status-badge {
            transform: translate(-4px, -4px) scale(1.1);
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
        }
        
        
        /* ========== EXISTING STYLES ========== */
        .loc {
            font-size: var(--fs-subtle);
            color: var(--muted);
            align-items: center;
            gap: 3px;
            line-height: 1.4;
        }
        
        .loc .ui-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1px !important;
        }
        
        .about-row span {
            padding: 0 !important;
        }
        
        .about-row .ui-icon {
            padding: 0 !important;
            margin-bottom: 10px !important;
        }
        
        .cta {
            display: grid;
            grid-template-columns: 40% 40% 10%;
            gap: 8px;
            align-items: center;
            margin-top: 16px;
        }
        
        .cta .menu-kebab {
            width: 100%;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.18s ease;
            border: none;
        }
        
        .cta .menu-kebab:hover {
            background: var(--apc-bg);
        }
        
        .cta .menu-kebab .ui-icon {
            width: 18px !important;
            height: 18px !important;
        }
        
        .cta .btn-chat,
        .cta .btn-follow {
            width: 100%;
        }
        
        @media (max-width: 768px) {
            .cta {
                grid-template-columns: 1fr 1fr 60px;
                gap: 6px;
            }
        }
        
        /* Dropdown Styles */
        .hm-dropdown {
            display: none;
            position: fixed;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            min-width: 240px;
            z-index: 999999;
            animation: slideDown 0.2s ease;
            overflow: hidden;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hm-dropdown.active {
            display: block;
        }
        
        .hm-dropdown-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: transparent;
            border: none;
            color: var(--text-body);
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            cursor: pointer;
            transition: background 0.2s ease;
            text-align: left;
            font-family: inherit;
        }
        
        .hm-dropdown-item:hover {
            background: var(--apc-bg);
        }
        
        .hm-dropdown-item i {
            width: 18px;
            font-size: 16px;
            color: var(--text-muted);
            flex-shrink: 0;
        }
        
        .hm-dropdown-item.danger {
            color: var(--error);
        }
        
        .hm-dropdown-item.danger i {
            color: var(--error);
        }
        
        .hm-dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 4px 0;
        }
        
        .desktop-dropdown {
            display: none;
            position: fixed;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            min-width: 220px;
            max-width: 240px;
            z-index: 999999;
            animation: slideDown 0.2s ease;
            overflow: hidden;
        }
        
        .desktop-dropdown.active {
            display: block;
        }
        
        .desktop-dropdown-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: transparent;
            border: none;
            color: var(--text-body);
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            cursor: pointer;
            transition: background 0.2s ease;
            text-align: left;
            font-family: inherit;
        }
        
        .desktop-dropdown-item:hover {
            background: var(--apc-bg);
        }
        
        .desktop-dropdown-item .ui-icon {
            flex-shrink: 0;
        }
        
        .desktop-dropdown-item.danger {
            color: var(--error);
        }
        
        .desktop-dropdown-item.danger .ui-icon {
            color: var(--error);
        }
        
        .desktop-dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 4px 0;
        }
        </style>
        
@endsection
