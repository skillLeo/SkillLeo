<section class="reviews-section">
    @php
        $totalReviews   = count($reviews ?? []);
        $visibleReviews = collect($reviews ?? [])->take(2);
    @endphp

    <div class="cards-header">
        <h2 class="portfolios-title">Reviews</h2>
        <button class="edit-card icon-btn" aria-label="More options">
            <x-ui.icon name="more-vertical" variant="outlined" size="xl" class="color-muted ui-edit" />
        </button>
    </div>

    <div class="review-cards">
        @forelse($visibleReviews as $review)
            <div class="review-card">
                <div class="reviewer-avatar-large">
                    <img src="{{ $review['avatar'] ?? '#' }}"
                         alt="{{ $review['name'] ?? 'Reviewer' }}"
                         class="avatar-image" />
                </div>
                <h5 class="reviewer-name">{{ $review['name'] ?? 'Reviewer' }}</h5>
                <span class="reviewer-location">{{ $review['location'] ?? '' }}</span>
                <div class="review-content">
                    <div class="review-text-wrapper" style="font-size: var(--fs-body) !important;">
                        <span class="quote-mark quote-left">"</span>
                        <span class="review-text">{{ $review['review'] ?? '' }}</span>
                        <span class="quote-mark quote-right">"</span>
                    </div>
                </div>
            </div>
        @empty
            {{-- fallback card --}}
            <div class="review-card">
                <div class="reviewer-avatar-large">
                    <img src="#" alt="Reviewer" class="avatar-image" />
                </div>
                <h5 class="reviewer-name">Sample Reviewer</h5>
                <span class="reviewer-location">Location</span>
                <div class="review-content">
                    <div class="review-text-wrapper" style="font-size: var(--fs-body) !important;">
                        <span class="quote-mark quote-left">"</span>
                        <span class="review-text">Sample review content goes here.</span>
                        <span class="quote-mark quote-right">"</span>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($totalReviews > 2)
        <x-ui.see-all text="See all Reviews" onclick="showAllReviews()" />
    @endif
</section>

<script>
    // Open the "See All Reviews" modal
    window.showAllReviews = function () {
        openModal('seeAllReviewsModal');
    };
</script>
