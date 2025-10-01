<section class="hero-mobile">
    <div class="hm-banner">
        <img src="{{ $user->banner ?? 'https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=1400&auto=format&fit=crop' }}" alt="Banner">
        <div class="hm-avatar">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
            @else
                <i class="fa-solid fa-camera u-ic-md" style="margin-bottom: 4px"></i>
            @endif
        </div>
        <span class="hm-generate">
            <i class="fa-solid fa-wand-magic-sparkles" style="margin-right: 6px"></i>
            Generate
        </span>
    </div>

    <div class="hm-body">
        <h2 class="hm-name">
            {{ $user->name }}
            @if($user->open_to_work)
                <span class="hm-otw">Open to work</span>
            @endif
        </h2>

        <div class="hm-title">
            {{ $user->bio ?? 'Professional Developer & Designer' }}
            <a href="#">
                See more
                <i class="fa-solid fa-chevron-down u-ic-xxs" style="margin-left: 4px"></i>
            </a>
        </div>

        <div class="hm-chips">
            @foreach($user->skills ?? [] as $skill)
                <span class="hm-chip">{{ $skill }}</span>
            @endforeach
        </div>

        <div class="hm-loc">
            <i class="fa-solid fa-location-dot u-ic-xs"></i>
            {{ $user->location ?? 'Location not specified' }}
        </div>

        <div class="hm-ctas">
            <button class="hm-btn hm-btn-chat">Let's Chat!</button>
            <button class="hm-btn">Follow</button>
            <button class="hm-kebab" aria-label="More">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <div class="hm-socials">
            @if($user->facebook)
                <a class="hm-social" href="{{ $user->facebook }}"><i class="fa-brands fa-facebook-f"></i></a>
            @endif
            @if($user->instagram)
                <a class="hm-social" href="{{ $user->instagram }}"><i class="fa-brands fa-instagram"></i></a>
            @endif
            @if($user->twitter)
                <a class="hm-social" href="{{ $user->twitter }}"><i class="fa-brands fa-x-twitter"></i></a>
            @endif
            @if($user->linkedin)
                <a class="hm-social" href="{{ $user->linkedin }}"><i class="fa-brands fa-linkedin-in"></i></a>
            @endif
            <a class="hm-cv" href="{{ $user->cv_url ?? '#' }}">
                <i class="fa-solid fa-download"></i> Download CV
            </a>
        </div>

        <div class="hm-sep"></div>
    </div>
</section>