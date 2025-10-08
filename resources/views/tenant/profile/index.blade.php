@extends('layouts.app')

@section('title', $user->name . ' - Professional Portfolio')

@section('content')


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
                        @foreach ($user->topSkills ?? [] as $skill)
                            <span class="tag">{{ $skill }}</span>
                        @endforeach
                    </div>
                </x-cards.sidebar-card>

                <!-- Soft Skills -->
                <x-cards.sidebar-card title="Soft Skills" :show-see-all="true" see-all-icon="arrow-right">
                    @foreach ($user->softSkills ?? [] as $index => $skill)
                        <div class="skill-item">
                            <span
                                style="color: {{ $index === 0 ? 'var(--accent)' : '#666' }}; width: 20px; display: flex; justify-content: center;">
                                <i class="fa-solid fa-{{ $skill['icon'] ?? 'lightbulb' }}"></i>
                            </span>
                            <span>{{ $skill['name'] }}</span>
                        </div>
                    @endforeach
                </x-cards.sidebar-card>

                <!-- Languages -->
                <x-cards.sidebar-card title="Language">
                    @foreach ($user->languages ?? [] as $language)
                        <div class="lang-row">
                            <span class="lang-name">{{ $language['name'] }}</span>
                            <span class="lang-level">— {{ $language['level'] }}</span>
                        </div>
                    @endforeach
                </x-cards.sidebar-card>

                <!-- Education -->
                <x-cards.sidebar-card title="Education" :show-see-all="true" see-all-icon="arrow-right">
                    @foreach ($user->education ?? [] as $edu)
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
                    <div class="ai-creator-header">
                        <h3 class="ai-creator-title">AI Profile Creator</h3>
                        <p class="ai-creator-desc">
                            Upload your CV or describe yourself — AI will build your profile.
                        </p>
                    </div>

                    <div class="ai-creator-content">
                        <!-- Upload Section -->
                        <div class="upload-section">
                            <label for="cv-upload" class="upload-box">
                                <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                <span class="upload-text">Upload your CV</span>
                            </label>
                            <input type="file" id="cv-upload" accept=".pdf,.doc,.docx" hidden>
                        </div>

                        <!-- Divider -->
                        <div class="or-divider">
                            <span class="or-line"></span>
                            <span class="or-text">OR</span>
                            <span class="or-line"></span>
                        </div>

                        <!-- Textarea Section -->
                        <div class="textarea-section">
                            <div class="textarea-wrapper">
                                <textarea class="describe-textarea" placeholder="Describe yourself here...." rows="3"></textarea>
                            </div>
                        </div>

                        <!-- Generate Button -->
                        <button class="generate-profile-btn">
                            Generate Profile
                        </button>
                    </div>
                </section>



                <!-- Why Choose Me -->
                <section class="card pad24">
                    <div class="cards-header">
                        <h2 class="portfolios-title">Why Choose Me?</h2>
                        <button class="edit-card icon-btn" aria-label="Edit card">
                            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                        </button>
                    </div>
                    @foreach ($user->whyChooseMe ?? [] as $reason)
                        <div class="choose-item">
                            <i class="fas fa-check-square"></i>
                            <span>{{ $reason }}</span>
                        </div>
                    @endforeach
                    <x-ui.see-all text="See all Why Choose Me" onclick="showAllWhyChooseMe()" />
                </section>

                <!-- Services -->
                <section class="card pad24">
                    <div class="cards-header">
                        <h2 class="portfolios-title">Services</h2>
                        <button class="edit-card icon-btn" aria-label="Edit card">
                            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
                        </button>
                    </div>
                    @foreach ($user->services ?? [] as $service)
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


        
@endsection


