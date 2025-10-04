
    
    <section class="reviews-section">
        <div class="cards-header">
            <h2 class="portfolios-title">Reviews</h2>
            <button class="edit-card icon-btn" aria-label="More options">
                <x-ui.icon name="more-vertical" variant="outlined" size="xl" class="color-muted ui-edit" />
            </button>
        </div>
    
        <div class="review-cards">
            @foreach($reviews ?? [] as $review)
            <div class="review-card">
                <div class="reviewer-avatar-large">
                    <img src="#" 
                         alt="{{ $review['name'] ?? 'Sophie Carter' }}" 
                         class="avatar-image" />
                </div>
                <h5 class="reviewer-name">{{ $review['name'] ?? 'Sophie Carter' }}</h5>
                <span class="reviewer-location">{{ $review['location'] ?? 'New York, USA' }}</span>
                <div class="review-content">
                    <div class="review-text-wrapper" style="font-size: var(--fs-body) !important;">
                        <span class="quote-mark quote-left">"</span><span class="review-text">{{ $review['review'] ?? 'Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.' }}</span><span class="quote-mark quote-right">"</span>
                    </div>
                </div>
            </div>
            @endforeach
    
            @if(empty($reviews))
            <div class="review-card">
                <div class="reviewer-avatar-large">
                    <img src="#" 
                         alt="James Bennett" 
                         class="avatar-image" />
                </div>
                <h5 class="reviewer-name">James Bennett</h5>
                <span class="reviewer-location">Toronto, Canada</span>
                <div class="review-content">
                    <div class="review-text-wrapper" style="font-size: var(--fs-body) !important;">
                        <span class="quote-mark quote-left">"</span><span class="review-text">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.</span><span class="quote-mark quote-right">"</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    
        <x-ui.see-all text="See all Reviews" onclick="showAllReviews()" />
    </section>