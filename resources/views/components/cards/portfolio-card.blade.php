{{-- resources/views/components/cards/portfolio-card.blade.php --}}
@php
use Illuminate\Support\Str;

$p = is_array($portfolio ?? null) ? $portfolio : (array) ($portfolio ?? []);
$userSkills = $userSkills ?? [];

$title = trim($p['title'] ?? '') ?: 'Untitled project';
$desc = trim($p['description'] ?? '') ?: '';
$image = $p['image'] ?? null;
$link = trim($p['link'] ?? '') ?: null;

// ✅ Get skill slugs directly from portfolio data
$skillSlugs = $p['skill_slugs'] ?? [];

// Get skill IDs from meta (fallback)
$meta = is_array($p['meta'] ?? null) ? $p['meta'] : [];
$skillIds = $meta['skill_ids'] ?? [];

// ✅ Map skill IDs to names for display
$skills = collect($userSkills)
    ->filter(fn($s) => in_array($s['id'] ?? $s->id, $skillIds))
    ->map(fn($s) => $s['name'] ?? $s->name)
    ->take(3)
    ->all();

// ✅ Convert slugs array to comma-separated string for data attribute
$skillSlugsString = is_array($skillSlugs) ? implode(',', $skillSlugs) : '';

$host = null;
if ($link) {
    try {
        $parts = parse_url($link);
        $host = isset($parts['host']) ? preg_replace('/^www\./', '', $parts['host']) : null;
    } catch (\Throwable $e) { $host = null; }
}

$hasImage = !empty($image);
$cardId = 'pf-' . uniqid();
@endphp

{{-- ✅ FIXED: data-skills now contains comma-separated slugs --}}
<article class="linkedin-project-card" data-id="{{ $cardId }}" data-skills="{{ $skillSlugsString }}">
    
    {{-- Project Header --}}
    <div class="lpc-header">
        <h3 class="lpc-title">
            @if($link)
                <a href="{{ $link }}" target="_blank" rel="noopener">{{ $title }}</a>
            @else
                {{ $title }}
            @endif
        </h3>
    </div>

    {{-- Project Description --}}
    <div class="lpc-description" id="desc-{{ $cardId }}">
        @if($desc)
            <p class="lpc-desc-text">{{ Str::limit($desc, 180) }}</p>
            @if(strlen($desc) > 180)
                <button class="lpc-see-more" onclick="toggleDescription('{{ $cardId }}')">
                    ...see more
                </button>
            @endif
        @endif
        
        <div class="lpc-desc-full" style="display: none;">
            <p class="lpc-desc-text">{{ $desc }}</p>
            @if(strlen($desc) > 180)
                <button class="lpc-see-less" onclick="toggleDescription('{{ $cardId }}')">
                    ...see less
                </button>
            @endif
        </div>
    </div>

    {{-- Project Image --}}
    @if($hasImage)
        <div class="lpc-media" onclick="openImageModal('{{ $cardId }}')">
            <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
            <div class="lpc-media-overlay">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </div>
        </div>
    @endif

    {{-- Project Footer (Link + Skills) --}}
    <div class="lpc-footer">
        @if($link)
            <a href="{{ $link }}" target="_blank" rel="noopener" class="lpc-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
                <span>{{ $host ?: $link }}</span>
            </a>
        @endif

        @if(!empty($skills))
            <div class="lpc-skills">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <span>{{ implode(', ', $skills) }}{{ count($skillIds) > 3 ? ' +' . (count($skillIds) - 3) . ' more' : '' }}</span>
            </div>
        @endif
    </div>
</article>

{{-- Image Modal --}}
@if($hasImage)
    <div class="lpc-modal" id="modal-{{ $cardId }}" onclick="closeImageModal('{{ $cardId }}')">
        <div class="lpc-modal-content" onclick="event.stopPropagation()">
            <button class="lpc-modal-close" onclick="closeImageModal('{{ $cardId }}')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <img src="{{ $image }}" alt="{{ $title }}">
        </div>
    </div>
@endif

<script>
function toggleDescription(id) {
    const card = document.querySelector(`[data-id="${id}"]`);
    if (!card) return;
    
    const shortDesc = card.querySelector('.lpc-description > .lpc-desc-text');
    const seeMore = card.querySelector('.lpc-see-more');
    const fullDesc = card.querySelector('.lpc-desc-full');
    
    if (fullDesc.style.display === 'none') {
        if (shortDesc) shortDesc.style.display = 'none';
        if (seeMore) seeMore.style.display = 'none';
        fullDesc.style.display = 'block';
    } else {
        if (shortDesc) shortDesc.style.display = 'block';
        if (seeMore) seeMore.style.display = 'inline';
        fullDesc.style.display = 'none';
    }
}

function openImageModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeImageModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.lpc-modal').forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = '';
    }
});
</script>

<style>
 

.linkedin-project-card {
    width: 100%;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 8px;
    transition: box-shadow 0.2s ease;
}

.linkedin-project-card:hover {
    /* box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.08); */
}

/* Header */
.lpc-header {
    padding: 0 0 12px 0;
}

.lpc-title {
    font-size: var(--fs-title);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
    line-height: var(--lh-tight);
}

.lpc-title a {
    color: inherit;
    text-decoration: none;
}

.lpc-title a:hover {
    text-decoration: underline;
    color: var(--accent);
}

/* Description */
.lpc-description {
    padding: 0 0 12px 0;
}

.lpc-desc-text {
    font-size: var(--fs-body);
    color: var(--text-body);
    line-height: var(--lh-relaxed);
    margin: 0 0 4px 0;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.lpc-see-more,
.lpc-see-less {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    padding: 0;
    margin: 0;
}

.lpc-see-more:hover,
.lpc-see-less:hover {
    color: var(--accent);
}

/* Media */
.lpc-media {
    position: relative;
    margin: 0 0 16px 0;
    width: 100%;
    max-width: 150px;
    aspect-ratio: 16/9;
    background: var(--apc-bg);
    cursor: pointer;
    border-radius: var(--radius);
    overflow: hidden;
    border:1px solid var(--border);
}

.lpc-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.lpc-media:hover img {
    transform: scale(1.05);
}

.lpc-media-overlay {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.lpc-media:hover .lpc-media-overlay {
    opacity: 1;
}

/* Footer (Link + Skills) */
.lpc-footer {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding: 12px 0 0 0;
    border-top: 1px solid var(--border);
}

.lpc-link {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--accent);
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    text-decoration: none;
    transition: all 0.2s ease;
}

.lpc-link:hover {
    color: var(--accent-dark);
}

.lpc-link svg {
    flex-shrink: 0;
}

.lpc-skills {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-body);
    font-size: var(--fs-body);
    line-height: var(--lh-normal);
}

.lpc-skills svg {
    flex-shrink: 0;
    color: var(--text-muted);
}

/* Modal */
.lpc-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999999;
    padding: 20px;
}

.lpc-modal-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
}

.lpc-modal-content img {
    max-width: 100%;
    max-height: 90vh;
    border-radius: 8px;
    object-fit: contain;
}

.lpc-modal-close {
    position: absolute;
    top: -50px;
    right: 0;
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.lpc-modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

/* Responsive */
@media (max-width: 640px) {
    .lpc-header {
        padding: 0 0 10px 0;
    }
    
    .lpc-media {
        max-width: 100%;
    }
    
    .lpc-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .lpc-modal-close {
        top: 10px;
        right: 10px;
    }
}
</style>