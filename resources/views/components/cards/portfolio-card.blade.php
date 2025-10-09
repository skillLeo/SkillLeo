{{-- resources/views/components/cards/portfolio-card.blade.php --}}
@php
    // Normalize input (array or object)
    $p        = is_array($portfolio ?? null) ? $portfolio : (array) ($portfolio ?? []);
    $title    = trim($p['title'] ?? '') ?: 'Untitled project';
    $desc     = trim($p['description'] ?? '') ?: 'No description provided.';
    $image    = $p['image'] ?? null;              // expected to be a URL (can be null)
    $link     = trim($p['link'] ?? '') ?: null;   // expected to be a URL (can be null)
    $tags     = $p['tags'] ?? [];                 // array of strings
    $category = $p['category'] ?? null;

    // Derive nice hostname for display
    $host = null;
    if ($link) {
        try {
            $parts = parse_url($link);
            $host  = isset($parts['host']) ? preg_replace('/^www\./', '', $parts['host']) : null;
        } catch (\Throwable $e) { $host = null; }
    }
@endphp

<div class="portfolio-card" @if($category) data-category="{{ \Illuminate\Support\Str::slug($category) }}" @endif>
    <div class="portfolio-media">
        @if($image)
            @if($link)
                <a href="{{ $link }}" target="_blank" rel="noopener" aria-label="Open {{ $title }}">
                    <img src="{{ $image }}" alt="{{ $title }}" class="portfolio-image">
                </a>
            @else
                <img src="{{ $image }}" alt="{{ $title }}" class="portfolio-image">
            @endif
        @else
            {{-- graceful placeholder --}}
            <div class="portfolio-image placeholder" aria-hidden="true">
                <svg viewBox="0 0 24 24" role="img" aria-label="placeholder">
                    <path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14"
                          fill="none" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M3 19l5.5-6 3.5 4 2.5-3L21 19" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="8" cy="8" r="1.5" fill="currentColor"/>
                </svg>
            </div>
        @endif
    </div>

    <div class="portfolio-content">
        <h3 class="portfolio-title">
            @if($link)
                <a href="{{ $link }}" target="_blank" rel="noopener">{{ $title }}</a>
            @else
                {{ $title }}
            @endif
        </h3>

        {{-- Tags / technologies (chips) --}}
        @if(!empty($tags))
            <div class="portfolio-tags">
                @foreach($tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        <p class="portfolio-desc">{{ $desc }}</p>

        {{-- Link row with icon + hostname (if link exists) --}}
        @if($link)
            <div class="portfolio-link">
                <x-ui.icon name="link" variant="outlined" size="sm" class="color-accent" />
                <a href="{{ $link }}" target="_blank" rel="noopener">
                    {{ $host ?: $link }}
                </a>
            </div>
        @endif

        {{-- Action --}}
        <div class="portfolio-actions">
            @if($link)
                <x-ui.button
                    variant="solid"
                    shape="square"
                    color="primary"
                    size="md"
                    onclick="window.open('{{ $link }}','_blank','noopener')"
                    class="w-full mt-3"
                    aria-label="View {{ $title }}">
                    <x-ui.icon name="eye" variant="outlined" size="sm" />
                    View Details
                </x-ui.button>
            @else
                <x-ui.button
                    variant="outlined"
                    shape="square"
                    color="primary_muted"
                    size="md"
                    class="w-full mt-3"
                    disabled
                    title="No link provided">
                    <x-ui.icon name="eye" variant="outlined" size="sm" />
                    View Details
                </x-ui.button>
            @endif
        </div>
    </div>
</div>

{{-- Optional minimal styles the card expects; keep or move to your CSS bundle --}}
<style>
    .portfolio-card { border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; background:var(--card); display:flex; flex-direction:column; }
    .portfolio-media { aspect-ratio:16/9; background:var(--apc-bg); }
    .portfolio-image { width:100%; height:100%; object-fit:cover; display:block; }
    .portfolio-image.placeholder { display:grid; place-items:center; color:var(--text-subtle); }
    .portfolio-image.placeholder svg { width:48px; height:48px; opacity:.8; }
    .portfolio-content { padding:16px; }
    .portfolio-title { font-weight:var(--fw-semibold); color:var(--text-heading); margin:0 0 8px; font-size:var(--fs-title); }
    .portfolio-title a { color:inherit; text-decoration:none; }
    .portfolio-title a:hover { text-decoration:underline; }
    .portfolio-tags { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:8px; }
    .portfolio-tags .tag { font-size:var(--fs-micro); color:var(--text-body); background:var(--apc-bg); border:1px solid var(--border); border-radius:999px; padding:4px 10px; }
    .portfolio-desc { color:var(--text-body); margin:0 0 10px; line-height:var(--lh-relaxed); }
    .portfolio-link { display:flex; align-items:center; gap:6px; font-size:var(--fs-subtle); margin-bottom:8px; }
    .portfolio-actions { margin-top:4px; }
</style>
