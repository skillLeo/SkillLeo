@extends('layouts.app')

@section('title', $user->name . ' - Professional Portfolio')

@section('content')
<style>
  .card, section {
    border: 1.5px solid var(--border) !important;
    background: var(--card);
    border-radius: var(--radius);
    margin-bottom: var(--mb-sections) !important;
background: var(--card) !important;
    }
</style>
<style>



:root { --sticky-offset: 72px; } /* fallback; JS sets this to nav height */

.content-wrapper {
  align-items: start; /* don't stretch rows */
}

/* --- Sticky for desktop (≥1200px): all three columns --- */
@media (min-width: 1200px) {
  .left-sidebar,
  .main-content,
  .right-sidebar {
    position: sticky;
    top: var(--sticky-offset);
    align-self: start;
    height: max-content; /* natural height for sticky bounds */
  }
}

/* --- Sticky for tablets (768px–1199.98px): only right sidebar --- */
@media (min-width: 768px) and (max-width: 1199.98px) {
  .left-sidebar,
  .main-content {
    position: static; /* disable */
  }

  .right-sidebar {
    position: sticky;
    top: var(--sticky-offset);
    align-self: start;
    height: max-content;
  }
}

/* --- Below 768px: disable all stickiness --- */
@media (max-width: 767.98px) {
  .left-sidebar,
  .main-content,
  .right-sidebar {
    position: static;
  }
}

.ui-edit{
    color: var(--muted) !important;
}




 .fa-graduation-cap{color:var(--ink) !important;}
</style>


<x-navigation.top-nav :user="$user" :brand-name="$brandName" :message-count="$messageCount" />

    <div class="main-container">
        <div class="content-wrapper">
            <!-- Left Sidebar -->
            <aside class="left-sidebar">
                <x-hero.mobile :user="$user" />
                <x-hero.desktop :user="$user" />

                <!-- Top Skills -->
                <x-cards.sidebar-card title="Top Skills" :show-see-all="true">
                    <div class="tags" style="margin-bottom: 8px">
                        @foreach($user->topSkills ?? [] as $skill)
                            <span class="tag">{{ $skill }}</span>
                        @endforeach
                    </div>
                </x-cards.sidebar-card>

                <!-- Soft Skills -->
                <x-cards.sidebar-card title="Soft Skills" :show-see-all="true" see-all-icon="arrow-right">
                    @foreach($user->softSkills ?? [] as $index => $skill)
                        <div class="skill-item">
                            <span style="color: {{ $index === 0 ? 'var(--accent)' : '#666' }}; width: 20px; display: flex; justify-content: center;">
                                <i class="fa-solid fa-{{ $skill['icon'] ?? 'lightbulb' }}"></i>
                            </span>
                            <span>{{ $skill['name'] }}</span>
                        </div>
                    @endforeach
                </x-cards.sidebar-card>

                <!-- Languages -->
                <x-cards.sidebar-card title="Language">
                    @foreach($user->languages ?? [] as $language)
                        <div class="lang-row">
                            <span class="lang-name">{{ $language['name'] }}</span>
                            <span class="lang-level">— {{ $language['level'] }}</span>
                        </div>
                    @endforeach
                </x-cards.sidebar-card>

                <!-- Education -->
                <x-cards.sidebar-card title="Education" :show-see-all="true" see-all-icon="arrow-right">
                    @foreach($user->education ?? [] as $edu)
                        <div class="edu-item">
                            <div class="edu-head">
                                <div class="edu-title">{{ $edu['title'] }}</div>
                                @if($edu['recent'] ?? false)
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

            <!-- Main Content -->
            <main class="main-content">
                <!-- Hero Banner -->
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

                <!-- Portfolios Section -->
                <x-sections.portfolios :portfolios="$portfolios" :categories="$portfolioCategories" />

                <!-- Skills Showcase -->
                <x-sections.skills-showcase :skills="$skillsData" />

                <!-- Experience -->
                <x-sections.experience :experiences="$experiences" />

                <!-- Reviews -->
                <x-sections.reviews :reviews="$reviews" />
            </main>

            <!-- Right Sidebar -->
            <aside class="right-sidebar">
                <!-- AI Profile Creator -->
                <section class="ai-profile-creator">
                    <h3 class="ai-creator-title">AI Profile Creator</h3>
                    <p class="ai-creator-desc">
                        Upload your CV or describe yourself<br>— AI will build your profile.
                    </p>

                    <div class="upload-btn-container">
                        <button class="upload-btn">
                            <i class="fas fa-upload"></i>
                            Upload your CV
                        </button>
                    </div>

                    <div class="or-divider">
                        <span class="or-line"></span>
                        <span class="or-text">OR</span>
                        <span class="or-line"></span>
                    </div>

                    <div class="textarea-container">
                        <textarea class="describe-textarea" placeholder="Describe yourself here...."></textarea>
                    </div>

                    <button class="generate-profile-btn">Generate Profile</button>

                    
                </section>

                <!-- Why Choose Me -->
                <section class="right-sections">
                    <div class="cards-header"  >
                        <h2 class="portfolios-title">Why Choose Me?</h2>
                        <button class="edit-card icon-btn" aria-label="Edit card">
                            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                          </button>
                          
                    </div>                    
                    @foreach($user->whyChooseMe ?? [] as $reason)
                        <div class="choose-item">
                            <i class="fas fa-check-square"></i>
                            <span>{{ $reason }}</span>
                        </div>
                    @endforeach
                    <x-ui.see-all text="See all Why Choose Me" onclick="showAllWhyChooseMe()" />
                </section>
        
        
        
        
        
                <section class="right-sections">
                    <div class="cards-header"  >
                        <h2 class="portfolios-title">Services</h2>
                        <button class="edit-card icon-btn" aria-label="Edit card">
                            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                          </button>
                          
                    </div>                    
                    @foreach($user->whyChooseMe ?? [] as $reason)
                        <div class="choose-item">
                            <i class="fas fa-check-square"></i>
                            <span>{{ $reason }}</span>
                        </div>
                    @endforeach
                    <x-ui.see-all text="See all Why Choose Me" onclick="showAllWhyChooseMe()" />
                </section>

                <!-- Services -->
        
            </aside>
        </div>
    </div>

@endsection

@push('scripts')


@endpush