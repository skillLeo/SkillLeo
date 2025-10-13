
@props(['modalReviews' => []])

<div id="editReviewsModal" class="modal-overlay">
    <div class="modal-container modal-lg">
        <div class="modal-header">
            <h3 class="modal-title">Manage Reviews</h3>
            <button type="button" class="modal-close" onclick="closeModal('editReviewsModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="reviewsForm" method="POST" action="{{ route('tenant.reviews.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="reviews" id="reviewsPayload">

            <div class="modal-body">
                <div id="reviewsList" class="reviews-list"></div>

                <button type="button" class="btn-add-review-item" onclick="addReviewRow()">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Review</span>
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal btn-secondary" onclick="closeModal('editReviewsModal')">
                    Cancel
                </button>
                <button type="submit" class="btn-modal btn-primary">
                    <i class="fas fa-save"></i>
                    Save Reviews
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let reviewsData = @json($modalReviews);

    document.addEventListener('DOMContentLoaded', function() {
        renderReviews();
    });

    function renderReviews() {
        const container = document.getElementById('reviewsList');
        container.innerHTML = '';

        if (reviewsData.length === 0) {
            addReviewRow();
            return;
        }

        reviewsData.forEach((review, index) => {
            container.insertAdjacentHTML('beforeend', createReviewHTML(review, index));
        });
    }

    function createReviewHTML(review = {}, index = 0) {
        const dbId = review.db_id || '';
        const clientName = review.client_name || '';
        const title = review.title || '';
        const location = review.location || '';
        const content = review.content || '';
        const imageUrl = review.image_url || '';

        return `
            <div class="review-card-edit" data-index="${index}">
                <div class="review-card-header">
                    <div class="review-number">
                        <span class="review-badge">${index + 1}</span>
                    </div>
                    <button type="button" class="btn-delete" onclick="removeReview(${index})" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>

                <input type="hidden" name="reviews[${index}][db_id]" value="${dbId}">

                <div class="review-form-content">
                    <div class="review-avatar-section">
                        <div class="avatar-upload-area">
                            <input type="file" 
                                   id="reviewImage${index}" 
                                   accept="image/*"
                                   onchange="handleReviewImageUpload(event, ${index})"
                                   style="display: none;">
                            
                            <div class="avatar-preview" onclick="document.getElementById('reviewImage${index}').click()">
                                ${imageUrl 
                                    ? `<img src="${imageUrl}" id="reviewImagePreview${index}" class="avatar-img">` 
                                    : `<div id="reviewImagePreview${index}" class="avatar-placeholder">
                                         <i class="fas fa-camera"></i>
                                         <span>Upload</span>
                                       </div>`
                                }
                            </div>
                            
                            <input type="hidden" name="reviews[${index}][image]" id="reviewImageData${index}" value="${imageUrl}">
                            <input type="hidden" name="reviews[${index}][image_path]" value="${review.image_path || ''}">
                        </div>
                    </div>

                    <div class="review-fields">
                        <div class="field-group">
                            <label class="field-label">Client Name <span class="required">*</span></label>
                            <input type="text" 
                                   name="reviews[${index}][client_name]" 
                                   value="${clientName}" 
                                   placeholder="e.g., John Smith"
                                   class="field-input"
                                   required>
                        </div>

                        <div class="field-row">
                            <div class="field-group">
                                <label class="field-label">Title/Position</label>
                                <input type="text" 
                                       name="reviews[${index}][title]" 
                                       value="${title}" 
                                       placeholder="e.g., CEO"
                                       class="field-input">
                            </div>
                            <div class="field-group">
                                <label class="field-label">Location</label>
                                <input type="text" 
                                       name="reviews[${index}][location]" 
                                       value="${location}" 
                                       placeholder="e.g., New York, USA"
                                       class="field-input">
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label">Review <span class="required">*</span></label>
                            <textarea name="reviews[${index}][content]" 
                                      rows="4" 
                                      placeholder="Share the client's feedback..."
                                      class="field-textarea"
                                      required>${content}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function addReviewRow() {
        const index = reviewsData.length;
        reviewsData.push({});
        document.getElementById('reviewsList').insertAdjacentHTML('beforeend', createReviewHTML({}, index));
    }

    function removeReview(index) {
        if (reviewsData.length <= 1) {
            alert('At least one review is required.');
            return;
        }
        reviewsData.splice(index, 1);
        renderReviews();
    }

    function handleReviewImageUpload(event, index) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const base64 = e.target.result;
            document.getElementById(`reviewImageData${index}`).value = base64;
            
            const preview = document.getElementById(`reviewImagePreview${index}`);
            preview.outerHTML = `<img src="${base64}" id="reviewImagePreview${index}" class="avatar-img">`;
        };
        reader.readAsDataURL(file);
    }

    document.getElementById('reviewsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const reviews = [];

        reviewsData.forEach((_, index) => {
            const clientName = formData.get(`reviews[${index}][client_name]`)?.trim();
            const content = formData.get(`reviews[${index}][content]`)?.trim();

            if (clientName && content) {
                reviews.push({
                    db_id: formData.get(`reviews[${index}][db_id]`) || '',
                    client_name: clientName,
                    title: formData.get(`reviews[${index}][title]`)?.trim() || '',
                    location: formData.get(`reviews[${index}][location]`)?.trim() || '',
                    content: content,
                    image: formData.get(`reviews[${index}][image]`) || '',
                    image_path: formData.get(`reviews[${index}][image_path]`) || '',
                });
            }
        });

        if (reviews.length === 0) {
            alert('Please add at least one review with name and content.');
            return;
        }

        document.getElementById('reviewsPayload').value = JSON.stringify(reviews);
        this.submit();
    });
</script>

<style>
    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 24px;
    }

    .review-card-edit {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }

    .review-card-edit:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .review-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: var(--apc-bg);
        border-bottom: 1px solid var(--border);
    }

    .review-number {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .review-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: var(--accent);
        color: white;
        border-radius: 50%;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-delete {
        padding: 8px 12px;
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-delete:hover {
        background: #fef2f2;
        border-color: #ef4444;
        color: #ef4444;
    }

    .review-form-content {
        display: flex;
        gap: 20px;
        padding: 20px;
    }

    .review-avatar-section {
        flex-shrink: 0;
    }

    .avatar-upload-area {
        width: 100px;
    }

    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid var(--border);
        transition: border-color 0.2s ease;
    }

    .avatar-preview:hover {
        border-color: var(--accent);
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--apc-bg);
        color: var(--text-muted);
        gap: 4px;
    }

    .avatar-placeholder i {
        font-size: 24px;
    }

    .avatar-placeholder span {
        font-size: 12px;
    }

    .review-fields {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .field-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-heading);
    }

    .required {
        color: #ef4444;
    }

    .field-input,
    .field-textarea {
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        background: var(--card);
        color: var(--text-body);
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .field-input:focus,
    .field-textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
    }

    .field-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn-add-review-item {
        width: 100%;
        padding: 16px;
        background: transparent;
        border: 2px dashed var(--border);
        border-radius: 12px;
        color: var(--accent);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-add-review-item:hover {
        background: rgba(var(--accent-rgb), 0.05);
        border-color: var(--accent);
    }

    .btn-add-review-item i {
        font-size: 18px;
    }

    .btn-modal {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary {
        background: var(--apc-bg);
        color: var(--text-body);
    }

    .btn-secondary:hover {
        background: var(--border);
    }

    @media (max-width: 768px) {
        .review-form-content {
            flex-direction: column;
        }

        .field-row {
            grid-template-columns: 1fr;
        }

        .avatar-upload-area {
            width: 80px;
        }

        .avatar-preview {
            width: 80px;
            height: 80px;
        }
    }
</style>