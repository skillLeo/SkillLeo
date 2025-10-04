{{-- Hero Section with Professional Icons --}}
<section class="hero-merged">
    {{-- Default: uses --border bg on hover, bigger icon --}}

    {{-- Default: uses --border bg on hover, bigger icon --}}
    <button class="edit-card icon-btn" aria-label="Edit card">
        <x-ui.icon name="edit" variant="outlined" size="xl" class="color-muted ui-edit" />
    </button>




    <div class="photo-wrap">
        <div class="photo-ring">
            <div class="photo-circle">
                @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                        style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    <x-ui.icon name="user" size="lg" color="secondary" class="mb-2" />
                    Upload your<br>profile photo
                @endif
            </div>
        </div>
    </div>

    <div class="name-row">
        <h2 class="name">{{ $user->name }}</h2>
        {{-- <span class="otw-pill">
    <x-ui.icon name="check" size="xs" color="success" />
    
    Open
    </span> --}}
    </div>

    <div class="stack">
        {{ implode(' · ', $user->skills ?? ['PHP', 'Laravel', 'React']) }}
    </div>

    <div class="loc">
        <x-ui.icon name="location" size="xs" color="secondary" />
        {{ $user->location ?? 'Location not specified' }}
    </div>

    <style>
        .loc {
            font-size: var(--fs-subtle);
            color: var(--muted);
            align-items: center;
            gap: 3px;
            line-height: 1.4;
            /* keeps text readable */
        }

        /* ensure icon is centered visually with text */
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
    </style>
    <div class="hr"></div>

    <div class="about-row">
        <span class="label">About:</span>
        <x-ui.icon name="about" size="xs" color="secondary" class="hover-lift clickable" />
    </div>

    <p class="about-text">
        {{ Str::limit($user->about, 110) }}
        @if (Str::length($user->about) > 110)
            <a href="#" class="see-more-inline">
                See more
                {{-- <x-ui.icon name="arrow-right" size="xs" color="primary" /> --}}
            </a>
        @endif
    </p>

    <div class="hr2"></div>
    <div class="cta">
        <x-ui.button 
        variant="solid" 
        shape="square" 
        color="primary" 
        size="sm" 
        class="btn-chat"
    >
        <x-ui.icon name="message" variant="outlined" size="sm" />
        Chat!
    </x-ui.button>

        <x-ui.button variant="outlined" shape="square" color="primary" size="sm" class="btn-follow">
            <x-ui.icon name="user-plus" size="sm" variant="outlined" />
            Follow
        </x-ui.button>

        <button class="edit-card icon-btn" aria-label="More options"
            style="  position: static !important; /* or relative/fixed/sticky */
">
            <x-ui.icon name="more-vertical" variant="outlined" size="lg" class="color-muted" />
        </button>
    </div>

    <style>
        .cta {
            display: grid;
            grid-template-columns: 40% 40% 10%;
            gap: 8px;
            align-items: center;
            margin-top: 16px;
        }

        .cta .edit-card {
            width: 100%;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .cta .edit-card:hover {
            background: var(--apc-bg);
            border-color: var(--muted);

        }

        .cta .edit-card .ui-icon {
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
    </style>



    <div class="socials">
        @if ($user->facebook)
            <a href="{{ $user->facebook }}" aria-label="Facebook" class="social-link">
                <x-ui.icon name="facebook" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->instagram)
            <a href="{{ $user->instagram }}" aria-label="Instagram" class="social-link">
                <x-ui.icon name="instagram" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->twitter)
            <a href="{{ $user->twitter }}" aria-label="Twitter" class="social-link">
                <x-ui.icon name="twitter" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif
        @if ($user->linkedin)
            <a href="{{ $user->linkedin }}" aria-label="LinkedIn" class="social-link">
                <x-ui.icon name="linkedin" size="sm" color="secondary" class="hover-lift" />
            </a>
        @endif

        <a href="#" class="social-link" aria-label="Download CV">
            <x-ui.icon name="download" size="sm" color="secondary" class="hover-lift" />
        </a>
    </div>
</section>

{{-- <style>
    .otw-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #e8f5e9;
        border: 1px solid #cfe7d1;
        color: #2e7d32;
        padding: 4px 10px;
        border-radius: 16px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }

    .see-more-inline {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 4px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-bold);
        color: var(--accent);
        text-decoration: none;
        border-bottom: 1px dotted transparent;
        transition: color .18s ease, border-color .18s ease, transform .12s ease;
    }

    .see-more-inline:hover,
    .see-more-inline:focus-visible {
        color: #0a58f5;
        border-bottom-color: currentColor;
        outline: none;
    }

    .btn-chat,
    .btn-follow {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex: 1;
        transition: all 0.2s ease;
    }

    .btn-chat:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-follow:hover {
        background-color: rgba(59, 130, 246, 0.05);
        border-color: var(--accent);
        transform: translateY(-1px);
    }

    .about-text .see-more-inline {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 4px;
        font-size: var(--fs-subtle);
        font-weight: var(--fw-bold);
        color: var(--accent);
        text-decoration: none;
        border-bottom: 1px dotted transparent;
        transition: color .18s ease, border-color .18s ease, transform .12s ease;
    }

    .about-text .see-more-inline:hover,
    .about-text .see-more-inline:focus-visible {
        color: #0a58f5;
        border-bottom-color: currentColor;
        outline: none;
    }



    .btn-chat,
    .btn-follow {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex: 1;
        transition: all 0.2s ease;
    }

    .btn-chat:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-follow:hover {
        background-color: rgba(59, 130, 246, 0.05);
        border-color: var(--accent);
        transform: translateY(-1px);
    }



    .otw-pill {
        display: inline-flex;
        align-items: center;
        background: #e8f5e9;
        border: 1px solid #cfe7d1;
        color: #2e7d32;
        padding: 4px 10px;
        border-radius: 16px;
        font-size: var(--fs-micro);
        font-weight: var(--fw-semibold);
        white-space: nowrap;
    }
</style> --}}
