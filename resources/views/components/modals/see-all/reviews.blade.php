@props(['reviews' => []])

<x-modals.base-modal id="seeAllReviewsModal" title="Reviews" size="lg">
    <div class="modal-content-v2">
        @forelse($reviews as $review)
            <div class="review-item-v2">
                <div class="review-header-v2">
                    <div class="reviewer-avatar-v2">
                        @if(!empty($review['avatar']))
                            <img src="{{ $review['avatar'] }}" alt="{{ $review['name'] }}">
                        @else
                            <div class="avatar-initials-v2">
                                {{ strtoupper(substr($review['name'] ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="reviewer-info-v2">
                        <h3 class="reviewer-name-v2">{{ $review['name'] ?? 'Anonymous' }}</h3>
                        <p class="reviewer-meta-v2">{{ $review['location'] ?? 'Location not specified' }}</p>
                        @if(!empty($review['date']))
                            <p class="reviewer-date-v2">{{ $review['date'] }}</p>
                        @endif
                    </div>
                    @if(!empty($review['rating']))
                        <div class="review-rating-v2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="star-icon-v2 {{ $i <= $review['rating'] ? 'filled' : '' }}" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M8 .25a.75.75 0 01.673.418l1.882 3.815 4.21.612a.75.75 0 01.416 1.279l-3.046 2.97.719 4.192a.75.75 0 01-1.088.791L8 12.347l-3.766 1.98a.75.75 0 01-1.088-.79l.72-4.194L.818 6.374a.75.75 0 01.416-1.28l4.21-.611L7.327.668A.75.75 0 018 .25z"/>
                                </svg>
                            @endfor
                        </div>
                    @endif
                </div>
                
                <div class="review-body-v2">
                    <p class="review-text-v2">{{ $review['review'] ?? 'No review text provided.' }}</p>
                </div>
                
                @if(!empty($review['project']))
                    <div class="review-footer-v2">
                        <span class="review-project-v2">Project: {{ $review['project'] }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state-v2">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                    <path d="M32 20v24M20 32h24" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.2"/>
                </svg>
                <p class="empty-title-v2">No reviews yet</p>
                <p class="empty-subtitle-v2">Reviews from clients will appear here</p>
            </div>
        @endforelse
    </div>
</x-modals.base-modal>

<style>
/* Modal Content Wrapper */
.modal-content-v2 {
    padding: 0;
}

/* Review Item */
.review-item-v2 {
    padding: 24px;
    border-bottom: 1px solid var(--border, #e0e0e0);
}

.review-item-v2:last-child {
    border-bottom: none;
}

/* Review Header */
.review-header-v2 {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.reviewer-avatar-v2 {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.reviewer-avatar-v2 img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-initials-v2 {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent, #0a66c2);
    color: white;
    font-size: 18px;
    font-weight: 600;
}

.reviewer-info-v2 {
    flex: 1;
    min-width: 0;
}

.reviewer-name-v2 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-heading, #000000de);
    margin: 0 0 2px 0;
    line-height: 1.3;
}

.reviewer-meta-v2,
.reviewer-date-v2 {
    font-size: 14px;
    color: var(--text-muted, #00000099);
    margin: 0;
    line-height: 1.4;
}

/* Rating */
.review-rating-v2 {
    display: flex;
    gap: 2px;
    align-items: center;
}

.star-icon-v2 {
    fill: #d1d5db;
}

.star-icon-v2.filled {
    fill: #fbbf24;
}

/* Review Body */
.review-body-v2 {
    margin-bottom: 12px;
}

.review-text-v2 {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-body, #000000de);
    margin: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/* Review Footer */
.review-footer-v2 {
    padding-top: 12px;
    border-top: 1px solid var(--border, #e0e0e0);
}

.review-project-v2 {
    font-size: 13px;
    color: var(--text-muted, #00000099);
}

/* Empty State */
.empty-state-v2 {
    text-align: center;
    padding: 60px 24px;
}

.empty-state-v2 svg {
    color: var(--text-muted, #00000099);
    margin-bottom: 16px;
}

.empty-title-v2 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-heading, #000000de);
    margin: 0 0 4px 0;
}

.empty-subtitle-v2 {
    font-size: 14px;
    color: var(--text-muted, #00000099);
    margin: 0;
}

/* Mobile */
@media (max-width: 768px) {
    .review-item-v2 {
        padding: 16px;
    }

    .reviewer-avatar-v2 {
        width: 40px;
        height: 40px;
    }

    .avatar-initials-v2 {
        font-size: 16px;
    }

    .reviewer-name-v2 {
        font-size: 15px;
    }

    .reviewer-meta-v2,
    .reviewer-date-v2 {
        font-size: 13px;
    }

    .review-text-v2 {
        font-size: 13px;
    }

    .review-header-v2 {
        flex-wrap: wrap;
    }

    .review-rating-v2 {
        width: 100%;
        margin-top: 8px;
    }
}
</style>

<script>
function showAllReviews() {
    openModal('seeAllReviewsModal');
}
</script>