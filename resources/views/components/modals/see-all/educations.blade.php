@props(['education' => []])

<x-modals.base-modal id="seeAllEducationModal" title="Education" size="lg">
    <div class="modal-content-v2">
        @forelse($education as $edu)
            <div class="education-item-v2">
                <div class="edu-icon-v2">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <rect width="48" height="48" rx="4" fill="var(--hover-bg, #0000000a)"/>
                        <path d="M24 14l-10 6v8c0 3.31 6.71 6 10 6s10-2.69 10-6v-8l-10-6zm0 4.5l5.5 3.3-5.5 3.3-5.5-3.3 5.5-3.3zm-6 7.2l6 3.6 6-3.6v3.6c0 1.5-4 3-6 3s-6-1.5-6-3v-3.6z" fill="currentColor" opacity="0.6"/>
                    </svg>
                </div>
                
                <div class="edu-content-v2">
                    <div class="edu-header-v2">
                        <div>
                            <h3 class="edu-degree-v2">{{ $edu['title'] ?? 'Degree' }}</h3>
                            <p class="edu-institution-v2">{{ $edu['institution'] ?? 'Institution' }}</p>
                        </div>
                        @if($edu['recent'] ?? false)
                            <span class="edu-badge-v2">Recent</span>
                        @endif
                    </div>
                    
                    <div class="edu-meta-v2">
                        @if(!empty($edu['period']))
                            <span class="edu-meta-item-v2">{{ $edu['period'] }}</span>
                        @endif
                        @if(!empty($edu['location']))
                            <span class="edu-meta-dot-v2">·</span>
                            <span class="edu-meta-item-v2">{{ $edu['location'] }}</span>
                        @endif
                    </div>
                    
                    @if(!empty($edu['gpa']))
                        <p class="edu-gpa-v2">GPA: {{ $edu['gpa'] }}</p>
                    @endif
                    
                    @if(!empty($edu['description']))
                        <p class="edu-description-v2">{{ $edu['description'] }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state-v2">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                    <path d="M32 20v24M20 32h24" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.2"/>
                </svg>
                <p class="empty-title-v2">No education added yet</p>
                <p class="empty-subtitle-v2">Add your educational background</p>
            </div>
        @endforelse
    </div>
</x-modals.base-modal>

<style>
/* Education Item */
.education-item-v2 {
    display: flex;
    gap: 12px;
    padding: 24px;
    border-bottom: 1px solid var(--border, #e0e0e0);
}

.education-item-v2:last-child {
    border-bottom: none;
}

/* Education Icon */
.edu-icon-v2 {
    flex-shrink: 0;
    color: var(--text-muted, #00000099);
}

/* Education Content */
.edu-content-v2 {
    flex: 1;
    min-width: 0;
}

.edu-header-v2 {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 4px;
    gap: 12px;
}

.edu-degree-v2 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-heading, #000000de);
    margin: 0 0 2px 0;
    line-height: 1.3;
}

.edu-institution-v2 {
    font-size: 14px;
    color: var(--text-body, #000000de);
    margin: 0;
    line-height: 1.4;
}

.edu-badge-v2 {
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    white-space: nowrap;
    flex-shrink: 0;
}

/* Education Meta */
.edu-meta-v2 {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.edu-meta-item-v2 {
    font-size: 14px;
    color: var(--text-muted, #00000099);
    line-height: 1.4;
}

.edu-meta-dot-v2 {
    color: var(--text-muted, #00000099);
    font-size: 14px;
}

.edu-gpa-v2 {
    font-size: 14px;
    color: var(--text-muted, #00000099);
    margin: 0 0 8px 0;
}

.edu-description-v2 {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-body, #000000de);
    margin: 8px 0 0 0;
}

/* Mobile */
@media (max-width: 768px) {
    .education-item-v2 {
        padding: 16px;
    }

    .edu-icon-v2 svg {
        width: 40px;
        height: 40px;
    }

    .edu-header-v2 {
        flex-direction: column;
        gap: 8px;
    }

    .edu-degree-v2 {
        font-size: 15px;
    }

    .edu-institution-v2,
    .edu-meta-item-v2,
    .edu-gpa-v2,
    .edu-description-v2 {
        font-size: 13px;
    }

    .edu-badge-v2 {
        align-self: flex-start;
    }
}
</style>

<script>
function showAllEducation() {
    openModal('seeAllEducationModal');
}
</script>