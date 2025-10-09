<section class="experience-section">
    <div class="cards-header">
        <h2 class="portfolios-title">Experience</h2>
        <button class="edit-card icon-btn" aria-label="Edit card">
            <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
        </button>
    </div>

    @foreach(($experiences ?? []) as $experience)
        @php
            $title   = $experience['title'] ?? '';
            $desc    = $experience['description'] ?? '';
            $period  = $experience['period'] ?? ($experience['date'] ?? '');   // ✅ fallback
            $current = (bool) ($experience['current'] ?? false);
            $loc     = $experience['location'] ?? '';
        @endphp

        <div class="experience-item">
            <div class="experience-header">
                <h4 class="experience-title">{{ $title }}</h4>
                <span class="experience-badge {{ $current ? '' : 'dark' }}">
                    {{ $current ? 'Current' : $period }}
                </span>
            </div>
            @if($desc !== '')
                <p class="experience-desc">{{ $desc }}</p>
            @endif
            <div class="experience-meta">
                @if($period !== '')
                    <span>{{ $period }}</span>
                @endif
                @if($loc !== '')
                    <span>{{ $loc }}</span>
                @endif
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
                <span>Jul 2024 — Present</span>
                <span>Sargodha, Pakistan</span>
            </div>
        </div>
    @endif

    <x-ui.see-all text="See all Experiences" onclick="showAllExperiences()" />
</section>
