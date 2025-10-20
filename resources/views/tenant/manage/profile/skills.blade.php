@extends('tenant.manage.app')

@section('title', 'Manage Skills - ' . $user->name)

@section('main')
    @if (session('success'))
        <div class="alert alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1 class="page-title">Skills</h1>
            <p class="page-subtitle">Manage your technical and soft skills</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('editSkillsModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Edit Skills
            </button>
        </div>
    </div>

    <div class="content-section">
        {{-- Technical Skills Section --}}
        <div class="skills-section">
            <div class="section-header">
                <h3 class="section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="16 18 22 12 16 6" />
                        <polyline points="8 6 2 12 8 18" />
                    </svg>
                    Technical Skills
                    <span class="skill-count">{{ $skills->count() }}</span>
                </h3>
            </div>

            <div class="skills-grid">
                @forelse($skills as $skill)
                    <div class="skill-chip" data-skill-id="{{ $skill->id }}" data-level="{{ $skill->pivot->level }}">
                        <div class="skill-info">
                            <span class="skill-name">{{ $skill->name }}</span>
                            <span class="skill-level level-{{ $skill->pivot->level }}">
                                @if ($skill->pivot->level == 3)
                                    Expert
                                @elseif($skill->pivot->level == 2)
                                    Proficient
                                @else
                                    Beginner
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-inline">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <polyline points="16 18 22 12 16 6" />
                            <polyline points="8 6 2 12 8 18" />
                        </svg>
                        <p>No technical skills added yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Soft Skills Section --}}
        <div class="skills-section">
            <div class="section-header">
                <h3 class="section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Soft Skills
                    <span class="skill-count">{{ $owner->softSkills->count() }}</span>
                </h3>
            </div>

            <div class="soft-skills-grid">
                @forelse($owner->softSkills as $soft)
                    <div class="soft-skill-card">
                        <div class="soft-skill-icon">
                            <i class="fas fa-{{ $soft->icon ?? 'star' }}"></i>
                        </div>
                        <div class="soft-skill-info">
                            <h4>{{ $soft->name }}</h4>
                            <div class="soft-skill-level">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="level-dot {{ $i <= ($soft->pivot->level ?? 2) ? 'active' : '' }}"></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-inline">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        <p>No soft skills added yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Include Edit Skills Modal --}}
    <x-modals.edits.edit-skills 
        :modal-skills="$modalSkills" 
        :soft-skill-options="$softSkillOptions" 
        :selected-soft="$selectedSoft" 
        :username="$username" 
    />
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">💡 Skills Guide</h3>
        </div>

        <div class="help-card">
            <h4>Skill Levels</h4>
            <ul class="help-list">
                <li><strong>Beginner:</strong> Basic knowledge</li>
                <li><strong>Proficient:</strong> Can work independently</li>
                <li><strong>Expert:</strong> Deep expertise</li>
            </ul>
        </div>

        <div class="help-card accent">
            <h4>🎯 Pro Tip</h4>
            <p>Add 5-8 relevant skills to increase profile visibility by 40%</p>
        </div>
    </div>
@endsection

@push('styles')
<style>

/* ============ ALERTS ============ */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    animation: slideIn 0.3s ease;
}

.alert svg {
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============ PAGE HEADER ============ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 6px 0;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
    font-weight: 400;
}

.page-actions {
    display: flex;
    gap: 8px;
}

/* ============ BUTTONS ============ */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 32px;
    padding: 0 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
}

.btn svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(var(--accent-rgb), 0.3);
}

/* ============ CONTENT SECTION ============ */
.content-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 24px;
}

/* ============ SKILLS SECTION ============ */
.skills-section {
    margin-bottom: 32px;
}

.skills-section:last-child {
    margin-bottom: 0;
}

.section-header {
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
}

.section-title svg {
    color: var(--accent);
}

.skill-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    padding: 0 8px;
    background: var(--apc-bg);
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
}

/* ============ SKILLS GRID ============ */
.skills-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.skill-chip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 14px;
    background: var(--card);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    transition: all 0.2s ease;
}

.skill-chip:hover {
    border-color: var(--accent);
    box-shadow: 0 2px 8px rgba(var(--accent-rgb), 0.1);
    transform: translateY(-1px);
}

.skill-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.skill-name {
    font-weight: 500;
    font-size: 14px;
    color: var(--text-body);
}

.skill-level {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.level-1 {
    background: #fef3c7;
    color: #92400e;
}

.level-2 {
    background: #dbeafe;
    color: #1e40af;
}

.level-3 {
    background: #d1fae5;
    color: #065f46;
}

/* ============ SOFT SKILLS ============ */
.soft-skills-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

.soft-skill-card {
    padding: 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s;
}

.soft-skill-card:hover {
    border-color: rgba(var(--accent-rgb), 0.3);
    transform: translateY(-1px);
}

.soft-skill-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--accent-rgb), 0.1);
    color: var(--accent);
    border-radius: 8px;
    font-size: 18px;
    flex-shrink: 0;
}

.soft-skill-info {
    flex: 1;
}

.soft-skill-info h4 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 6px 0;
    color: var(--text-heading);
}

.soft-skill-level {
    display: flex;
    gap: 4px;
}

.level-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--border);
}

.level-dot.active {
    background: var(--accent);
}

/* ============ EMPTY STATE INLINE ============ */
.empty-state-inline {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    width: 100%;
    text-align: center;
}

.empty-state-inline svg {
    color: var(--text-muted);
    opacity: 0.3;
    margin-bottom: 12px;
}

.empty-state-inline p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

/* ============ HELP CARDS ============ */
.help-card {
    padding: 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 16px;
}

.help-card h4 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 12px 0;
    color: var(--text-heading);
}

.help-card.accent {
    background: linear-gradient(135deg, rgba(var(--accent-rgb), 0.1), rgba(var(--accent-rgb), 0.05));
    border-color: var(--accent);
}

.help-card p {
    margin: 0;
    font-size: 13px;
    color: var(--text-body);
    line-height: 1.5;
}

.help-list {
    margin: 0;
    padding-left: 20px;
}

.help-list li {
    font-size: 13px;
    margin-bottom: 8px;
    color: var(--text-body);
    line-height: 1.5;
}

/* ============ RESPONSIVE ============ */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .skills-grid {
        flex-direction: column;
    }

    .skill-chip {
        width: 100%;
    }

    .soft-skills-grid {
        grid-template-columns: 1fr;
    }
}

/* ============ DARK MODE SUPPORT ============ */
@media (prefers-color-scheme: dark) {
    .level-1 {
        background: rgba(253, 230, 138, 0.2);
        color: #fbbf24;
    }

    .level-2 {
        background: rgba(147, 197, 253, 0.2);
        color: #60a5fa;
    }

    .level-3 {
        background: rgba(167, 243, 208, 0.2);
        color: #34d399;
    }
}
</style>
@endpush