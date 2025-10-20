@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
@extends('tenant.manage.app')

@section('title', 'Manage Portfolio - ' . $user->name)

@section('main')
    @if (session('success'))
        <div class="alert alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1 class="page-title">Portfolio</h1>
            <p class="page-subtitle">Showcase your best work and projects</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('editPortfolioModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Edit Portfolio
            </button>
        </div>
    </div>

    <div class="content-section">
        <div class="portfolio-grid">
            @forelse($owner->portfolios as $portfolio)
                <div class="portfolio-card" data-portfolio-id="{{ $portfolio->id }}">
                    @if($portfolio->image_path)
                        <div class="portfolio-image">
                            <img src="{{ Storage::disk($portfolio->image_disk ?? 'public')->url($portfolio->image_path) }}" 
                                 alt="{{ $portfolio->title }}">
                        </div>
                    @else
                        <div class="portfolio-image-empty">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="14" rx="2"/>
                                <circle cx="8" cy="8" r="2"/>
                                <path d="M21 15l-5-5L5 21"/>
                            </svg>
                        </div>
                    @endif

                    <div class="portfolio-content">
                        <h4 class="portfolio-title">{{ $portfolio->title }}</h4>
                        @if($portfolio->description)
                            <p class="portfolio-description">{{ Str::limit($portfolio->description, 100) }}</p>
                        @endif

                        @php
                            $meta = is_array($portfolio->meta) ? $portfolio->meta : [];
                            $skillIds = $meta['skill_ids'] ?? [];
                            $portfolioSkills = $owner->skills->whereIn('id', $skillIds);
                        @endphp

                        @if($portfolioSkills->count() > 0)
                            <div class="portfolio-skills">
                                @foreach($portfolioSkills->take(3) as $skill)
                                    <span class="skill-badge">{{ $skill->name }}</span>
                                @endforeach
                                @if($portfolioSkills->count() > 3)
                                    <span class="skill-badge-more">+{{ $portfolioSkills->count() - 3 }}</span>
                                @endif
                            </div>
                        @endif

                        @if($portfolio->link_url)
                            <a href="{{ $portfolio->link_url }}" target="_blank" class="portfolio-link">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                    <polyline points="15 3 21 3 21 9"/>
                                    <line x1="10" y1="14" x2="21" y2="3"/>
                                </svg>
                                View Project
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state-full">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <h3>No projects yet</h3>
                    <p>Start building your portfolio by adding your first project</p>
                    <button class="btn btn-primary" onclick="openModal('editPortfolioModal')">Add First Project</button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Include Edit Portfolio Modal --}}
    <x-modals.edits.edit-portfolio 
        :modal-portfolios="$modalPortfolios" 
        :user-skills="$userSkills" 
        :username="$username" 
    />
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">💼 Portfolio Guide</h3>
            <p class="inspector-desc">Tips for showcasing your work</p>
        </div>

        <div class="help-card">
            <h4>Best Practices</h4>
            <ul class="help-list">
                <li>Use high-quality images (1200×900px)</li>
                <li>Write clear, concise descriptions</li>
                <li>Include live demo links</li>
                <li>Showcase your best 6-10 projects</li>
            </ul>
        </div>

        <div class="help-card accent">
            <h4>🎯 Pro Tip</h4>
            <p>Projects with images get 3x more engagement</p>
        </div>
    </div>
@endsection

@push('styles')
<style>
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

.portfolio-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.portfolio-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(var(--accent-rgb), 0.12);
}

.portfolio-image, .portfolio-image-empty {
    width: 100%;
    height: 200px;
    background: var(--apc-bg);
}

.portfolio-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.portfolio-image-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.portfolio-content {
    padding: 20px;
}

.portfolio-title {
    font-size: 17px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 10px 0;
}

.portfolio-description {
    font-size: 14px;
    color: var(--text-body);
    line-height: 1.5;
    margin: 0 0 12px 0;
}

.portfolio-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
}

.skill-badge {
    font-size: 11px;
    padding: 4px 8px;
    background: rgba(var(--accent-rgb), 0.1);
    color: var(--accent);
    border-radius: 4px;
    font-weight: 600;
}

.portfolio-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
}

.empty-state-full {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}
</style>
@endpush

@push('styles')
<style>
/* ============================================
   PORTFOLIO PAGE - PROFESSIONAL & POWERFUL
   ============================================ */

/* ============ SORT CONTROLS ============ */
.pf-sort-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    margin-bottom: 24px;
    border-bottom: 1px solid var(--border);
}

.pf-sort-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.pf-sort-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
}

.pf-sort-tabs {
    display: flex;
    gap: 6px;
    background: var(--apc-bg);
    padding: 4px;
    border-radius: 8px;
}

.pf-sort-tab {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: transparent;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-body);
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.pf-sort-tab:hover {
    background: var(--card);
    color: var(--text-heading);
}

.pf-sort-tab.active {
    background: var(--card);
    color: var(--accent);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.pf-sort-hint {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
    font-style: italic;
}

/* ============ PROJECT LIST ============ */
.pf-list {
    display: none;
    flex-direction: column;
    gap: 16px;
}

/* ============ PROJECT PREVIEW CARD ============ */
.pf-preview {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    transition: all 0.25s ease;
    position: relative;
}

.pf-preview:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 16px rgba(var(--accent-rgb), 0.08);
}

.pf-preview.draggable {
    cursor: move;
    padding-left: 52px;
}

.pf-drag-handle {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    cursor: grab;
    transition: all 0.2s ease;
}

.pf-drag-handle:hover {
    color: var(--accent);
}

.pf-drag-handle:active {
    cursor: grabbing;
}

.pf-preview.dragging {
    opacity: 0.5;
    border: 2px dashed var(--accent);
}

.pf-preview-main {
    flex: 1;
    display: flex;
    gap: 16px;
    min-width: 0;
}

.pf-preview-img,
.pf-preview-img-empty {
    flex-shrink: 0;
    width: 120px;
    height: 120px;
    border-radius: 10px;
    overflow: hidden;
    background: var(--apc-bg);
    border: 1px solid var(--border);
}

.pf-preview-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pf-preview-img-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.pf-preview-content {
    flex: 1;
    min-width: 0;
}

.pf-preview-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 10px;
}

.pf-preview-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.pf-preview-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
}

.pf-mini-skill {
    font-size: 11px;
    font-weight: 600;
    color: var(--accent);
    background: rgba(var(--accent-rgb), 0.1);
    padding: 4px 10px;
    border-radius: 6px;
    white-space: nowrap;
}

.pf-preview-desc {
    font-size: 14px;
    color: var(--text-body);
    line-height: 1.6;
    margin: 0 0 12px 0;
}

.pf-preview-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-muted);
    text-decoration: none;
    transition: color 0.2s ease;
}

.pf-preview-link:hover {
    color: var(--accent);
}

.pf-preview-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.pf-action-btn {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
    background: var(--card);
    border-radius: 8px;
    cursor: pointer;
    color: var(--text-muted);
    transition: all 0.2s ease;
}

.pf-action-btn:hover {
    transform: translateY(-2px);
}

.pf-action-btn.edit:hover {
    border-color: var(--accent);
    background: rgba(var(--accent-rgb), 0.08);
    color: var(--accent);
}

.pf-action-btn.delete:hover {
    border-color: #dc2626;
    background: rgba(220, 38, 38, 0.08);
    color: #dc2626;
}

/* ============ EDIT CARD ============ */
.pf-edit-card {
    background: var(--card);
    border: 2px solid var(--accent);
    border-radius: 12px;
    overflow: hidden;
}

.pf-edit-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    background: rgba(var(--accent-rgb), 0.05);
    border-bottom: 1px solid var(--border);
}

.pf-edit-header h4 {
    font-size: 17px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
}

.pf-close-edit {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 6px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-close-edit:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
}

.pf-edit-body {
    padding: 24px;
}

.pf-form-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 24px;
}

.pf-form-col {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ============ FORM FIELDS ============ */
.pf-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.pf-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-heading);
    display: flex;
    align-items: center;
    gap: 4px;
}

.pf-required {
    color: #dc2626;
}

.pf-input,
.pf-textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    background: var(--input-bg);
    color: var(--input-text);
    transition: all 0.2s ease;
}

.pf-input:focus,
.pf-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
}

.pf-textarea {
    resize: vertical;
    line-height: 1.6;
    min-height: 120px;
}

.pf-field-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.pf-hint,
.pf-hint-inline {
    font-size: 11px;
    color: var(--text-muted);
}

.pf-count {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
}

/* ============ SKILLS DROPDOWN ============ */
.pf-skills-dropdown {
    position: relative;
}

.pf-skills-dropdown-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 12px 14px;
    background: var(--input-bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    color: var(--input-text);
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-skills-dropdown-btn:hover {
    border-color: var(--accent);
}

.pf-skills-dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    max-height: 240px;
    overflow-y: auto;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
}

.pf-skill-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-skill-option:hover {
    background: var(--apc-bg);
}

.pf-skills-selected {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.pf-skill-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 8px 6px 12px;
    background: rgba(var(--accent-rgb), 0.1);
    border: 1px solid var(--accent);
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    color: var(--accent);
}

.pf-skill-tag button {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    color: currentColor;
    cursor: pointer;
    font-size: 16px;
    line-height: 1;
}

.pf-no-skills-msg {
    padding: 12px 14px;
    background: var(--apc-bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text-muted);
    font-size: 13px;
    text-align: center;
}

/* ============ IMAGE UPLOAD ============ */
.pf-img-upload {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    border-radius: 10px;
    overflow: hidden;
    background: var(--apc-bg);
    border: 2px dashed var(--border);
}

.pf-img-preview {
    width: 100%;
    height: 100%;
    position: relative;
}

.pf-img-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pf-img-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.pf-img-preview:hover .pf-img-overlay {
    opacity: 1;
}

.pf-img-change,
.pf-img-remove {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: var(--card);
    color: var(--text-heading);
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-img-change:hover {
    background: var(--accent);
    color: white;
}

.pf-img-remove {
    background: rgba(220, 38, 38, 0.9);
    color: white;
}

.pf-img-remove:hover {
    background: #dc2626;
}

.pf-img-empty {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-img-empty:hover {
    border-color: var(--accent);
    background: rgba(var(--accent-rgb), 0.05);
}

.pf-img-empty svg {
    margin-bottom: 12px;
    color: var(--text-muted);
}

.pf-img-empty p {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.pf-img-empty span {
    font-size: 12px;
    color: var(--text-muted);
}

/* ============ EDIT FOOTER ============ */
.pf-edit-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    background: var(--apc-bg);
    border-top: 1px solid var(--border);
}

.pf-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 44px;
    padding: 0 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-btn.primary {
    background: var(--accent);
    color: white;
}

.pf-btn.primary:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

.pf-btn.secondary {
    background: var(--card);
    color: var(--text-body);
    border: 1px solid var(--border);
}

.pf-btn.secondary:hover {
    background: var(--apc-bg);
}

/* ============ EMPTY STATE ============ */
.pf-empty {
    display: none;
    text-align: center;
    padding: 80px 20px;
}

.pf-empty svg {
    color: var(--text-muted);
    opacity: 0.15;
    margin-bottom: 24px;
}

.pf-empty h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 12px 0;
}

.pf-empty p {
    font-size: 15px;
    color: var(--text-muted);
    margin: 0 0 28px 0;
}

/* ============ RESPONSIVE ============ */
@media (max-width: 768px) {
    .pf-sort-controls {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .pf-sort-left {
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
    }

    .pf-sort-tabs {
        width: 100%;
    }

    .pf-sort-tab {
        flex: 1;
    }

    .pf-form-grid {
        grid-template-columns: 1fr;
    }

    .pf-preview {
        flex-direction: column;
    }

    .pf-preview.draggable {
        padding-left: 20px;
        padding-top: 52px;
    }

    .pf-drag-handle {
        left: 50%;
        top: 16px;
        transform: translateX(-50%);
    }

    .pf-preview-img {
        width: 100%;
        height: 200px;
    }

    .pf-preview-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
@endpush

@push('scripts')
@php

    // Portfolios payload (unchanged)
    $portfoliosPayload = $owner->portfolios->map(function ($p) {
        $meta = is_array($p->meta) ? $p->meta : [];
        return [
            'db_id'      => $p->id,
            'title'      => $p->title,
            'description'=> $p->description,
            'link'       => $p->link_url,
            'image'      => $p->image_path
                              ? ($p->image_url ?? Storage::disk($p->image_disk ?? 'public')->url($p->image_path))
                              : '',
            'image_path' => $p->image_path,
            'image_disk' => $p->image_disk ?? 'public',
            'skill_ids'  => $meta['skill_ids'] ?? [],
            'position'   => $p->position,
            'created_id' => $p->id ?? 0,
        ];
    })->values();

    // 🔒 Build skills payload from whatever is available:
    // prefer $userSkills (controller provided), otherwise $owner->skills
    $skillsSource = collect(isset($userSkills) ? $userSkills : ($owner->skills ?? []));
    $userSkillsPayload = $skillsSource->map(function ($s) {
        // Handle both array and object inputs
        if (is_array($s)) {
            return ['id' => (int)$s['id'], 'name' => (string)$s['name']];
        }
        return ['id' => (int)$s->id, 'name' => (string)$s->name];
    })->values();
@endphp

<script>
(function initPortfolio(){
    'use strict';

    // ======= DATA =======
    let portfolios = @json($portfoliosPayload);
    const userSkills = @json($userSkillsPayload);

    let editingId = null;
    let currentSortMode = 'custom';
    let dragSrcId = null;

    // ids for new not-yet-saved rows (negative to avoid clash)
    let tempIdCounter = -1;

    // Limits
    const TITLE_MAX   = 80;
    const DESC_MAX    = 500;
    const IMG_MAX_W   = 1200;
    const IMG_MAX_H   = 900;
    const IMG_QUALITY = 0.85;

    // ======= ELEMENTS =======
    const el = {
        list:      document.getElementById('portfolioList'),
        empty:     document.getElementById('portfolioEmpty'),
        dataInput: document.getElementById('portfoliosData'),
        sortTabs:  document.querySelectorAll('.pf-sort-tab'),
        hintCustom: document.getElementById('sortHintCustom'),
        hintNewest: document.getElementById('sortHintNewest'),
        sortControls: document.querySelector('.pf-sort-controls'),
    };

    // ======= HELPERS =======
    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const truncate = (str, len) => (str || '').length > len ? str.slice(0, len) + '…' : (str || '');
    const normURL = (url='') => {
        const v = String(url).trim();
        if (!v) return '';
        if (/^https?:\/\//i.test(v)) return v;
        return 'https://' + v.replace(/^\/+/, '');
    };
    const hostnameFrom = (url) => { try { return new URL(normURL(url)).hostname.replace(/^www\./,''); } catch { return ''; } };
    const byId = (id) => portfolios.find(p => String(p.db_id) === String(id));

    // ======= RENDERING =======
    function render() {
        if (portfolios.length === 0) {
            el.empty.style.display = 'flex';
            el.list.style.display  = 'none';
            if (el.sortControls) el.sortControls.style.display = 'none';
            return;
        }
        el.empty.style.display = 'none';
        el.list.style.display  = 'flex';
        if (el.sortControls) el.sortControls.style.display = 'flex';

        // Ensure positions are consistent in custom mode
        if (currentSortMode === 'custom') {
            portfolios.sort((a,b) => (a.position ?? 0) - (b.position ?? 0));
        } else if (currentSortMode === 'newest') {
            portfolios.sort((a,b) => (b.created_id ?? b.db_id ?? 0) - (a.created_id ?? a.db_id ?? 0));
        }

        el.list.innerHTML = portfolios.map(p =>
            editingId === p.db_id ? renderEdit(p) : renderPreview(p)
        ).join('');

        bindEvents();
    }

    function renderPreview(p) {
        const host = p.link ? hostnameFrom(p.link) : '';
        const skillBadges = (p.skill_ids || [])
            .map(id => userSkills.find(s => s.id === id))
            .filter(Boolean)
            .map(s => `<span class="pf-mini-skill">${esc(s.name)}</span>`)
            .join('');

        const dragHandle = currentSortMode === 'custom'
            ? `<div class="pf-drag-handle" title="Drag to reorder">
                   <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                       <circle cx="9" cy="6" r="1"/><circle cx="15" cy="6" r="1"/>
                       <circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/>
                       <circle cx="9" cy="18" r="1"/><circle cx="15" cy="18" r="1"/>
                   </svg>
               </div>`
            : '';

        const imgPart = p.image
            ? `<div class="pf-preview-img"><img src="${esc(p.image)}" alt="${esc(p.title)}"></div>`
            : `<div class="pf-preview-img-empty"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                   <rect x="3" y="3" width="18" height="14" rx="2"/><circle cx="8" cy="8" r="2"/><path d="M21 15l-5-5L5 21"/>
               </svg></div>`;

        return `
        <div class="pf-preview ${currentSortMode === 'custom' ? 'draggable' : ''}" data-id="${esc(p.db_id)}" draggable="${currentSortMode === 'custom'}">
            ${dragHandle}
            <div class="pf-preview-main">
                ${imgPart}
                <div class="pf-preview-content">
                    <div class="pf-preview-header">
                        <div>
                            <h3 class="pf-preview-title">${esc(truncate(p.title, TITLE_MAX))}</h3>
                            <div class="pf-preview-skills">${skillBadges || ''}</div>
                            <p class="pf-preview-desc">${esc(truncate(p.description, 160))}</p>
                            ${p.link ? `<a class="pf-preview-link" href="${esc(normURL(p.link))}" target="_blank" rel="noopener">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 13a5 5 0 0 1 7 0l3 3a5 5 0 0 1-7 7l-3-3"/>
                                    <path d="M14 11a5 5 0 0 1-7 0L4 8a5 5 0 0 1 7-7l3 3"/>
                                </svg>
                                <span>${esc(host)}</span>
                            </a>` : ''}
                        </div>
                        <div class="pf-preview-actions">
                            <button class="pf-action-btn edit" data-action="edit" data-id="${esc(p.db_id)}" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                </svg>
                            </button>
                            <button class="pf-action-btn delete" data-action="delete" data-id="${esc(p.db_id)}" title="Delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    function renderEdit(p) {
        const selectedTags = (p.skill_ids || [])
            .map(id => userSkills.find(s => s.id === id))
            .filter(Boolean)
            .map(s => `<span class="pf-skill-tag" data-id="${s.id}">${esc(s.name)} <button type="button" class="pf-skill-tag-remove" aria-label="Remove skill" data-id="${s.id}">&times;</button></span>`)
            .join('');

        const imgBlock = p.image
            ? `
            <div class="pf-img-preview">
                <img src="${esc(p.image)}" alt="${esc(p.title)}">
                <div class="pf-img-overlay">
                    <button type="button" class="pf-img-change" data-action="img-change" data-id="${esc(p.db_id)}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12l7 7 7-7"/>
                        </svg> Change
                    </button>
                    <button type="button" class="pf-img-remove" data-action="img-remove" data-id="${esc(p.db_id)}">Remove</button>
                </div>
            </div>`
            : `
            <div class="pf-img-empty" data-action="img-change" data-id="${esc(p.db_id)}">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="14" rx="2"/><circle cx="8" cy="8" r="2"/><path d="M21 15l-5-5L5 21"/>
                </svg>
                <p>Add image</p><span>1200×900 recommended</span>
            </div>`;

        return `
        <div class="pf-edit-card" data-id="${esc(p.db_id)}">
            <div class="pf-edit-header">
                <h4>${p.db_id > 0 ? 'Edit Project' : 'New Project'}</h4>
                <button class="pf-close-edit" data-action="cancel-edit" data-id="${esc(p.db_id)}" title="Close">×</button>
            </div>
            <div class="pf-edit-body">
                <div class="pf-form-grid">
                    <div class="pf-form-col">
                        <div class="pf-field">
                            <label class="pf-label">Title <span class="pf-required">*</span></label>
                            <input type="text" class="pf-input" data-field="title" data-id="${esc(p.db_id)}" maxlength="${TITLE_MAX}" value="${esc(p.title || '')}">
                            <div class="pf-field-info">
                                <span class="pf-hint-inline">Keep it concise and specific</span>
                                <span class="pf-count" data-count="title">${(p.title || '').length}/${TITLE_MAX}</span>
                            </div>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Description</label>
                            <textarea class="pf-textarea" data-field="description" data-id="${esc(p.db_id)}" maxlength="${DESC_MAX}">${esc(p.description || '')}</textarea>
                            <div class="pf-field-info">
                                <span class="pf-hint-inline">What did you build? What impact?</span>
                                <span class="pf-count" data-count="description">${(p.description || '').length}/${DESC_MAX}</span>
                            </div>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Link</label>
                            <input type="text" class="pf-input" data-field="link" data-id="${esc(p.db_id)}" placeholder="https://…" value="${esc(p.link || '')}">
                            <span class="pf-hint">Live demo or repo URL</span>
                        </div>

                        <div class="pf-field pf-skills-dropdown">
                            <label class="pf-label">Skills</label>
                            <button type="button" class="pf-skills-dropdown-btn" data-action="skills-toggle" data-id="${esc(p.db_id)}">
                                <span>${(p.skill_ids || []).length ? 'Edit selected skills' : 'Select skills'}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="pf-skills-dropdown-menu" data-menu="${esc(p.db_id)}">
                                ${userSkills.map(s => `
                                    <div class="pf-skill-option" data-action="skill-toggle" data-pid="${esc(p.db_id)}" data-skill="${s.id}">
                                        <input type="checkbox" ${ (p.skill_ids || []).includes(s.id) ? 'checked' : '' }/>
                                        <span>${esc(s.name)}</span>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="pf-skills-selected" data-selected="${esc(p.db_id)}">
                                ${selectedTags || `<div class="pf-no-skills-msg">No skills selected</div>`}
                            </div>
                        </div>
                    </div>

                    <div class="pf-form-col">
                        <div class="pf-field">
                            <label class="pf-label">Project Image</label>
                            <div class="pf-img-upload" data-wrapper="${esc(p.db_id)}">
                                ${imgBlock}
                                <input type="file" accept="image/*" style="display:none" data-file="${esc(p.db_id)}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pf-edit-footer">
                <button class="pf-btn secondary" data-action="cancel-edit" data-id="${esc(p.db_id)}">Cancel</button>
                <button class="pf-btn primary" data-action="save" data-id="${esc(p.db_id)}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    Save
                </button>
            </div>
        </div>`;
    }

    // ======= EVENTS =======
    function bindEvents() {
        // Counts
        el.list.querySelectorAll('[data-field="title"],[data-field="description"]').forEach(inp => {
            inp.addEventListener('input', (e) => {
                const field = e.target.dataset.field;
                const id    = e.target.dataset.id;
                const p     = byId(id);
                if (!p) return;
                p[field] = e.target.value;
                const max = field === 'title' ? TITLE_MAX : DESC_MAX;
                const counter = e.target.closest('.pf-field').querySelector(`[data-count="${field}"]`);
                if (counter) counter.textContent = `${(p[field] || '').length}/${max}`;
            });
        });

        // Link normalize on blur
        el.list.querySelectorAll('[data-field="link"]').forEach(inp => {
            inp.addEventListener('blur', (e) => {
                const id = e.target.dataset.id;
                const p  = byId(id);
                if (!p) return;
                p.link = e.target.value = normURL(e.target.value) || '';
            });
        });

        // Close edit
        el.list.querySelectorAll('[data-action="cancel-edit"]').forEach(btn => {
            btn.addEventListener('click', () => { editingId = null; render(); });
        });

        // Save
        el.list.querySelectorAll('[data-action="save"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const p  = byId(id);
                if (!p) return;

                const errors = [];
                if (!p.title || !p.title.trim()) errors.push('Title is required.');
                if ((p.title || '').length > TITLE_MAX) errors.push(`Title must be ≤ ${TITLE_MAX} chars.`);
                if ((p.description || '').length > DESC_MAX) errors.push(`Description must be ≤ ${DESC_MAX} chars.`);
                if (errors.length) { alert(errors.join('\n')); return; }

                // Give a temporary negative id to new records
                if (!(p.db_id > 0)) {
                    p.db_id = tempIdCounter--;
                }
                editingId = null;
                serializeAndSubmit(false); // just persist to hidden input; server will store on "main Save" if you add one
                render();
            });
        });

        // Preview actions (edit/delete)
        el.list.querySelectorAll('[data-action="edit"]').forEach(btn => {
            btn.addEventListener('click', () => {
                editingId = btn.dataset.id;
                render();
                // Focus title
                const title = el.list.querySelector(`.pf-input[data-field="title"][data-id="${editingId}"]`);
                if (title) title.focus();
            });
        });

        el.list.querySelectorAll('[data-action="delete"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const p  = byId(id);
                if (!p) return;
                if (!confirm(`Delete project "${p.title || 'Untitled'}"?`)) return;
                portfolios = portfolios.filter(x => String(x.db_id) !== String(id));
                editingId = null;
                reindexPositions();
                serializeAndSubmit(false);
                render();
            });
        });

        // Skills dropdown
        el.list.querySelectorAll('[data-action="skills-toggle"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const menu = el.list.querySelector(`[data-menu="${CSS.escape(id)}"]`);
                if (menu) menu.style.display = (menu.style.display === 'block' ? 'none' : 'block');
            });
        });

        // Clicking a skill
        el.list.querySelectorAll('.pf-skill-option').forEach(opt => {
            opt.addEventListener('click', (e) => {
                const pid = opt.dataset.pid;
                const sid = parseInt(opt.dataset.skill, 10);
                const p   = byId(pid);
                if (!p) return;
                p.skill_ids = Array.isArray(p.skill_ids) ? p.skill_ids : [];
                const idx = p.skill_ids.indexOf(sid);
                if (idx >= 0) p.skill_ids.splice(idx, 1);
                else p.skill_ids.push(sid);

                // Reflect checkbox state
                const chk = opt.querySelector('input[type="checkbox"]');
                if (chk) chk.checked = p.skill_ids.includes(sid);

                // Refresh selected tags area
                const selected = el.list.querySelector(`[data-selected="${CSS.escape(pid)}"]`);
                if (selected) {
                    selected.innerHTML = p.skill_ids.length
                        ? p.skill_ids
                            .map(id => userSkills.find(s => s.id === id))
                            .filter(Boolean)
                            .map(s => `<span class="pf-skill-tag" data-id="${s.id}">${esc(s.name)} <button type="button" class="pf-skill-tag-remove" data-id="${s.id}">&times;</button></span>`)
                            .join('')
                        : `<div class="pf-no-skills-msg">No skills selected</div>`;
                }
            });
        });

        // Remove a selected skill tag
        el.list.querySelectorAll('.pf-skill-tag-remove').forEach(btn => {
            btn.addEventListener('click', () => {
                const sid = parseInt(btn.dataset.id, 10);
                const pid = btn.closest('.pf-edit-card')?.dataset.id;
                const p   = byId(pid);
                if (!p) return;
                p.skill_ids = (p.skill_ids || []).filter(x => x !== sid);
                render(); // simpler to rerender to keep menu/checkboxes in sync
            });
        });

        // Image change / remove
        el.list.querySelectorAll('[data-action="img-change"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const fileInput = el.list.querySelector(`input[type="file"][data-file="${CSS.escape(id)}"]`);
                if (fileInput) fileInput.click();
            });
        });

        el.list.querySelectorAll('input[type="file"][data-file]').forEach(inp => {
            inp.addEventListener('change', async (e) => {
                const id = e.target.dataset.file;
                const p  = byId(id);
                if (!p) return;
                const file = e.target.files?.[0];
                if (!file) return;
                try {
                    p.image = await readAndResizeImage(file, IMG_MAX_W, IMG_MAX_H, IMG_QUALITY);
                    render();
                } catch(err) {
                    alert('Could not process image.');
                    console.error(err);
                } finally {
                    e.target.value = '';
                }
            });
        });

        el.list.querySelectorAll('[data-action="img-remove"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const p  = byId(id);
                if (!p) return;
                p.image = '';
                render();
            });
        });

        // Drag & drop (custom mode only)
        if (currentSortMode === 'custom') {
            el.list.querySelectorAll('.pf-preview.draggable').forEach(card => {
                card.addEventListener('dragstart', (e) => {
                    dragSrcId = card.dataset.id;
                    card.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                    // Needed for Firefox
                    e.dataTransfer.setData('text/plain', dragSrcId);
                });
                card.addEventListener('dragend', () => {
                    card.classList.remove('dragging');
                    dragSrcId = null;
                    serializeAndSubmit(false);
                });
                card.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    const over = e.currentTarget;
                    const srcIndex  = portfolios.findIndex(p => String(p.db_id) === String(dragSrcId));
                    const overIndex = portfolios.findIndex(p => String(p.db_id) === String(over.dataset.id));
                    if (srcIndex < 0 || overIndex < 0 || srcIndex === overIndex) return;
                    // Insert src before/after over depending on position
                    const after = isAfter(e.clientY, over);
                    const moved = portfolios.splice(srcIndex, 1)[0];
                    const newIndex = portfolios.findIndex(p => String(p.db_id) === String(over.dataset.id)) + (after ? 1 : 0);
                    portfolios.splice(newIndex, 0, moved);
                    reindexPositions();
                    render(); // rebind events
                });
            });
        }
    }

    function isAfter(mouseY, element) {
        const rect = element.getBoundingClientRect();
        return mouseY > rect.top + rect.height / 2;
    }

    // ======= IMAGE RESIZE =======
    function readAndResizeImage(file, maxW, maxH, quality) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onerror = () => reject(new Error('FileReader error'));
            reader.onload  = () => {
                const img = new Image();
                img.onload = () => {
                    let { width, height } = img;
                    const ratio = Math.min(maxW / width, maxH / height, 1);
                    const canvas = document.createElement('canvas');
                    canvas.width  = Math.round(width  * ratio);
                    canvas.height = Math.round(height * ratio);
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    resolve(canvas.toDataURL('image/jpeg', quality));
                };
                img.onerror = reject;
                img.src = reader.result;
            };
            reader.readAsDataURL(file);
        });
    }

    // ======= POSITIONING / SUBMIT =======
    function reindexPositions() {
        portfolios.forEach((p, i) => { p.position = i; });
    }

    function serializeAndSubmit(submitNow = false) {
        // Ensure positions are consistent
        reindexPositions();
        const payload = portfolios.map(p => ({
            db_id:      p.db_id,
            title:      p.title || '',
            description:p.description || '',
            link:       p.link || '',
            image:      p.image || '',          // client-side new/changed image as dataURL (optional)
            image_path: p.image_path || null,   // keep if needed server-side
            image_disk: p.image_disk || 'public',
            skill_ids:  Array.isArray(p.skill_ids) ? p.skill_ids : [],
            position:   p.position ?? 0,
        }));
        el.dataInput.value = JSON.stringify(payload);
        if (submitNow) {
            document.getElementById('portfolioUpdateForm').submit();
        }
    }

    // ======= PUBLIC ACTIONS =======
    window.addNewProject = function() {
        const newItem = {
            db_id: tempIdCounter--,
            title: '',
            description: '',
            link: '',
            image: '',
            image_path: null,
            image_disk: 'public',
            skill_ids: [],
            position: portfolios.length,
            created_id: Date.now()
        };
        portfolios.push(newItem);
        editingId = newItem.db_id;
        render();
        const title = el.list.querySelector(`.pf-input[data-field="title"][data-id="${editingId}"]`);
        if (title) title.focus();
    };

    window.importProjects = function() {
        alert('Import feature coming soon!');
    };

    window.setSortMode = function(mode) {
        currentSortMode = mode;
        el.sortTabs.forEach(tab => tab.classList.toggle('active', tab.dataset.sort === mode));
        el.hintCustom.style.display = mode === 'custom' ? 'flex' : 'none';
        el.hintNewest.style.display = mode === 'newest' ? 'flex' : 'none';
        render();
    };

    // ======= KEYBOARD SHORTCUTS =======
    document.addEventListener('keydown', (e) => {
        if (e.target.matches('input, textarea')) return;
        if (e.key.toLowerCase() === 'a') { e.preventDefault(); window.addNewProject(); }
        if (e.key === 'Escape' && editingId !== null) { editingId = null; render(); }
    });

    // ======= INIT =======
    // Guarantee positions
    if (!portfolios.every(p => Number.isInteger(p.position))) reindexPositions();
    render();
    // Expose a quick "save all" if you want to trigger from somewhere:
    window.submitAllPortfolios = () => serializeAndSubmit(true);
})();
</script>
@endpush
