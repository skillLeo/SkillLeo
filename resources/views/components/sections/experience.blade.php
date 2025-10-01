<section class="experience-section">
    <div class="cards-header">
        <h2 class="portfolios-title">Experience</h2>
        <button class="edit-card icon-btn" aria-label="Edit card">
            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
          </button>
          
    </div>

    @foreach($experiences ?? [] as $experience)
        <div class="experience-item">
            <div class="experience-header">
                <h4 class="experience-title">{{ $experience['title'] }}</h4>
                <span class="experience-badge {{ $experience['current'] ?? false ? '' : 'dark' }}">
                    {{ $experience['current'] ?? false ? 'Current' : ($experience['period'] ?? '') }}
                </span>
            </div>
            <p class="experience-desc">{{ $experience['description'] }}</p>
            <div class="experience-meta">
                <span>{{ $experience['period'] }}</span>
                <span>{{ $experience['location'] }}</span>
            </div>
        </div>
    @endforeach

    @if(empty($experiences))
        <div class="experience-item">
            <div class="experience-header">
                <h4 class="experience-title">Full Stack Developer</h4>
                <span class="experience-badge">Current</span>
            </div>
            <p class="experience-desc">
                Lorem ipsum is placeholder, Lorem ipsum is placeholder, Lorem ipsum is placeholder
            </p>
            <div class="experience-meta">
                <span>Jul 2024 - Present</span>
                <span>Sargodha, Pakistan</span>
            </div>
        </div>
    @endif

    <x-ui.see-all text="See all Experiences" onclick="showAllExperiences()" />
</section>