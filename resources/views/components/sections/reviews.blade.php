<style>
    
    /* Review Card - Exact Figma Replica */
    .review-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 55px 28px 28px;
        text-align: center;
        position: relative;
        max-width: 400px;
        margin: 0 auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    
    .review-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    /* Avatar - Overlapping Card */
    .reviewer-avatar-large {
        width: 96px;
        height: 96px;
        position: absolute;
        top: -48px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    /* Reviewer Name */
    .reviewer-name {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 4px 0;
        line-height: 1.4;
    }
    
    /* Location */
    .reviewer-location {
        font-size: 14px;
        color: #9ca3af;
        font-style: italic;
        display: block;
        margin-bottom: 5px;
    }
    
    /* Review Content Container */
    .review-content {
        position: relative;
        margin: 0;
    }
    
    /* Review Text with Inline Quotes */
    .review-text-wrapper {
        font-size: 15px;
        line-height: 1.6;
        color: #374151;
        text-align: center;
    }
    
    /* Quotation Marks - Figma Style */
    .quote-mark {
        font-family: Georgia, serif;
        font-size: 32px;
        font-weight: bold;
        color: #1f2937;
        line-height: 0;
        display: inline-block;
        vertical-align: baseline;
        position: relative;
    }
    
    .quote-left {
        margin-right: 2px;
        top: 8px;
    }
    
    .quote-right {
        margin-left: 0px;
        top: 8px;
    }
    
    .review-text {
        display: inline;
    }
    
    /* Review Cards Grid */
    .review-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 400px));
        gap: 80px 32px;
        justify-content: center;
        margin: 70px 0 40px;
    }
    
    /* Dark Mode */
    /* @media (prefers-color-scheme: dark) {
        .review-card {
            background: #1f2937;
            border-color: #374151;
        }
        
        .reviewer-avatar-large {
            border-color: #1f2937;
        }
        
        .reviewer-name,
        .quote-mark {
            color: #f9fafb;
        }
        
        .review-text {
            color: #d1d5db;
        }
    }
     */
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .review-cards {
            grid-template-columns: 1fr;
            gap: 80px 0;
            padding: 0 16px;
        }
    }
    </style>
    
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
                    <img src="{{ $review['avatar'] ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop&crop=face' }}" 
                         alt="{{ $review['name'] ?? 'Sophie Carter' }}" 
                         class="avatar-image" />
                </div>
                <h5 class="reviewer-name">{{ $review['name'] ?? 'Sophie Carter' }}</h5>
                <span class="reviewer-location">{{ $review['location'] ?? 'New York, USA' }}</span>
                <div class="review-content">
                    <div class="review-text-wrapper">
                        <span class="quote-mark quote-left">"</span><span class="review-text">{{ $review['review'] ?? 'Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.' }}</span><span class="quote-mark quote-right">"</span>
                    </div>
                </div>
            </div>
            @endforeach
    
            @if(empty($reviews))
            <div class="review-card">
                <div class="reviewer-avatar-large">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop&crop=face" 
                         alt="James Bennett" 
                         class="avatar-image" />
                </div>
                <h5 class="reviewer-name">James Bennett</h5>
                <span class="reviewer-location">Toronto, Canada</span>
                <div class="review-content">
                    <div class="review-text-wrapper">
                        <span class="quote-mark quote-left">"</span><span class="review-text">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts and visual mockups.</span><span class="quote-mark quote-right">"</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    
        <x-ui.see-all text="See all Reviews" onclick="showAllReviews()" />
    </section>