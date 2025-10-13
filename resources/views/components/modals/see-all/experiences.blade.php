@props(['experiences' => []])

<x-modals.base-modal id="seeAllExperiencesModal" title="Experience" size="lg">
    <div class="modal-content-v2">
        @forelse($experiences as $exp)
            <div class="experience-item-v2">
                <div class="exp-icon-v2">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <rect width="48" height="48" rx="4" fill="var(--hover-bg, #0000000a)"/>
                        <path d="M24 14c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10-4.48-10-10-10zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" fill="currentColor" opacity="0.6"/>
                    </svg>
                </div>
                
                <div class="exp-content-v2">
                    <div class="exp-header-v2">
                        <div>
                            <h3 class="exp-title-v2">{{ $exp['title'] ?? 'Job Title' }}</h3>
                            <p class="exp-company-v2">{{ $exp['company'] ?? 'Company Name' }}</p>
                        </div>
                        @if($exp['current'] ?? false)
                            <span class="exp-badge-v2 current">Current</span>
                        @endif
                    </div>
                    
                    <div class="exp-meta-v2">
                        @if(!empty($exp['period']))
                            <span class="exp-meta-item-v2">{{ $exp['period'] }}</span>
                        @endif
                        @if(!empty($exp['location']))
                            <span class="exp-meta-dot-v2">·</span>
                            <span class="exp-meta-item-v2">{{ $exp['location'] }}</span>
                        @endif
                    </div>
                    
                    @if(!empty($exp['description']))
                        <p class="exp-description-v2">{{ $exp['description'] }}</p>
                    @endif
                    
                    @if(!empty($exp['skills']))
                        <div class="exp-skills-v2">
                            @foreach($exp['skills'] as $skill)
                                <span class="exp-skill-tag-v2">{{ $skill['name'] ?? $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state-v2">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                    <path d="M32 20v24M20 32h24" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.2"/>
                </svg>
                <p class="empty-title-v2">No experience added yet</p>
                <p class="empty-subtitle-v2">Add your work experience to showcase your career</p>
            </div>
        @endforelse
    </div>
</x-modals.base-modal>

<style>
/* Experience Item */
.experience-item-v2 {
    display: flex;
    gap: 12px;
    padding: 24px;
    border-bottom: 1px solid var(--border, #e0e0e0);
}

.experience-item-v2:last-child {
    border-bottom: none;
}

/* Experience Icon */
.exp-icon-v2 {
    flex-shrink: 0;
    color: var(--text-muted, #00000099);
}

/* Experience Content */
.exp-content-v2 {
    flex: 1;
    min-width: 0;
}

.exp-header-v2 {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 4px;
    gap: 12px;
}

.exp-title-v2 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-heading, #000000de);
    margin: 0 0 2px 0;
    line-height: 1.3;
}

.exp-company-v2 {
    font-size: 14px;
    color: var(--text-body, #000000de);
    margin: 0;
    line-height: 1.4;
}

.exp-badge-v2 {
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
}

.exp-badge-v2.current {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

/* Experience Meta */
.exp-meta-v2 {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
}

.exp-meta-item-v2 {
    font-size: 14px;
    color: var(--text-muted, #00000099);
    line-height: 1.4;
}

.exp-meta-dot-v2 {
    color: var(--text-muted, #00000099);
    font-size: 14px;
}

/* Experience Description */
.exp-description-v2 {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-body, #000000de);
    margin: 0 0 12px 0;
}

/* Experience Skills */
.exp-skills-v2 {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.exp-skill-tag-v2 {
    padding: 4px 12px;
    background: var(--hover-bg, #0000000a);
    border-radius: 16px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-body, #000000de);
}

/* Mobile */
@media (max-width: 768px) {
    .experience-item-v2 {
        padding: 16px;
    }

    .exp-icon-v2 svg {
        width: 40px;
        height: 40px;
    }

    .exp-header-v2 {
        flex-direction: column;
        gap: 8px;
    }

    .exp-title-v2 {
        font-size: 15px;
    }

    .exp-company-v2,
    .exp-meta-item-v2,
    .exp-description-v2 {
        font-size: 13px;
    }

    .exp-badge-v2 {
        align-self: flex-start;
    }
}
</style>

<script>
function showAllExperiences() {
    openModal('seeAllExperiencesModal');
}
</script>