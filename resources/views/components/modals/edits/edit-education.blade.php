@props(['modalReviews' => []])

<x-modals.edits.base-modal id="editReviewsModal" title="Client Reviews" size="lg">
    <form id="reviewsModalForm" method="POST" action="{{ route('tenant.reviews.update') }}">
        @csrf
        @method('PUT')

        {{-- Reviews Container --}}
        <div class="reviews-container" id="reviewsContainer">
            <div class="empty-state" id="emptyState">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <p>No reviews added yet</p>
                <span>Add client testimonials to build trust</span>
            </div>
            <div class="reviews-list" id="reviewsList"></div>
        </div>

        {{-- Add Button --}}
        <button type="button" class="add-btn" id="addReviewBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Review
        </button>

        <input type="hidden" name="reviews" id="reviewsData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editReviewsModal')">Cancel</button>
        <button type="submit" form="reviewsModalForm" class="btn-modal btn-save" id="saveReviewsBtn">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<script>
const existingReviews = @json($modalReviews ?? []);

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    let reviews = [];
    let editingId = null;
    let counter = 0;

    const CONTENT_MAX = 500;
    const IMG_MAX_W = 400;
    const IMG_MAX_H = 400;
    const IMG_QUALITY = 0.88;

    const el = {
        container: document.getElementById('reviewsContainer'),
        list: document.getElementById('reviewsList'),
        empty: document.getElementById('emptyState'),
        addBtn: document.getElementById('addReviewBtn'),
        saveBtn: document.getElementById('saveReviewsBtn'),
        dataInput: document.getElementById('reviewsData'),
    };

    const esc = (s) => String(s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    // Load existing data
    function loadExisting() {
        reviews = existingReviews.map((r, i) => ({
            id: r.id || ++counter,
            client_name: String(r.client_name || ''),
            title: String(r.title || ''),
            location: String(r.location || ''),
            content: String(r.content || ''),
            image: r.image_url || r.image_path || r.image || '',
            image_path: r.image_path || '',
            image_disk: r.image_disk || 'public',
            db_id: r.db_id || null
        }));
        counter = Math.max(counter, ...reviews.map(r => r.id));
    }

    function updateSaveButton() {
        const hasValidReview = reviews.length > 0 && reviews.some(r => r.client_name && r.content);
        const isEditing = editingId !== null;
        if (el.saveBtn) {
            el.saveBtn.disabled = !hasValidReview || isEditing;
        }
    }

    function render() {
        if (reviews.length === 0) {
            el.empty.style.display = 'flex';
            el.list.style.display = 'none';
        } else {
            el.empty.style.display = 'none';
            el.list.style.display = 'flex';
            el.list.innerHTML = reviews.map(r =>
                editingId === r.id ? renderEdit(r) : renderDisplay(r)
            ).join('');
        }
        el.dataInput.value = JSON.stringify(reviews);
        updateSaveButton();
    }

    function renderDisplay(r) {
        const hasImg = !!r.image;
        const avatarUrl = hasImg 
            ? r.image 
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(r.client_name || 'Client')}&size=200&background=667eea&color=fff`;

        return `
            <div class="review-card" id="review-${r.id}">
                <div class="review-header">
                    <div class="review-avatar">
                        <img src="${avatarUrl}" alt="${esc(r.client_name)}">
                    </div>
                    
                    <div class="review-content">
                        <div class="review-client-info">
                            <div class="review-name">${esc(r.client_name) || 'Client Name'}</div>
                            ${r.title ? `<div class="review-title">${esc(r.title)}</div>` : ''}
                            ${r.location ? `<div class="review-location">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                ${esc(r.location)}
                            </div>` : ''}
                        </div>
                        
                        <div class="review-text">
                            <span class="quote-left">"</span>
                            ${esc(r.content) || 'Review content...'}
                            <span class="quote-right">"</span>
                        </div>
                    </div>
                    
                    <div class="review-actions">
                        <button type="button" class="action-btn" onclick="editReview(${r.id})" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button type="button" class="action-btn delete" onclick="removeReview(${r.id})" title="Delete">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function renderEdit(r) {
        const hasImg = !!r.image;
        const avatarUrl = hasImg 
            ? r.image 
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(r.client_name || 'Client')}&size=200&background=667eea&color=fff`;

        return `
            <div class="review-card editing" id="review-${r.id}">
                <div class="review-form">
                    <div class="review-form-header">
                        <div class="form-group">
                            <label class="form-label">Client Photo</label>
                            <div class="avatar-upload">
                                <div class="avatar-preview" id="avatar-preview-${r.id}">
                                    <img src="${avatarUrl}" alt="Client">
                                </div>
                                <input type="file" id="avatar-input-${r.id}" accept="image/*" hidden 
                                       onchange="handleAvatarUpload(${r.id}, this)"/>
                                <input type="hidden" id="avatar-path-${r.id}" value="${esc(r.image_path || '')}" />
                                <input type="hidden" id="avatar-disk-${r.id}" value="${esc(r.image_disk || 'public')}" />
                                <div class="avatar-actions">
                                    <button type="button" class="avatar-btn" 
                                            onclick="document.getElementById('avatar-input-${r.id}').click()">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                            <circle cx="12" cy="13" r="4"></circle>
                                        </svg>
                                        ${hasImg ? 'Change' : 'Upload'}
                                    </button>
                                    ${hasImg ? `<button type="button" class="avatar-btn remove" onclick="removeAvatar(${r.id})">Remove</button>` : ''}
                                </div>
                            </div>
                        </div>

                        <div class="form-fields-main">
                            <div class="form-group">
                                <label class="form-label">Client Name <span class="required">*</span></label>
                                <input type="text" class="form-input" value="${esc(r.client_name)}" 
                                       placeholder="e.g., John Smith"
                                       oninput="updateReview(${r.id}, 'client_name', this.value)"/>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Title/Position</label>
                                    <input type="text" class="form-input" value="${esc(r.title)}" 
                                           placeholder="e.g., CEO at Company"
                                           oninput="updateReview(${r.id}, 'title', this.value)"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <input type="text" class="form-input" value="${esc(r.location)}" 
                                           placeholder="e.g., New York, USA"
                                           oninput="updateReview(${r.id}, 'location', this.value)"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Review Content <span class="required">*</span></label>
                        <textarea class="form-textarea" maxlength="${CONTENT_MAX}" rows="5"
                                  placeholder="Share what the client said about your work..."
                                  oninput="updateReview(${r.id}, 'content', this.value)">${esc(r.content)}</textarea>
                        <div class="char-count" id="ct-content-${r.id}">${(r.content||'').length}/${CONTENT_MAX}</div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="form-btn btn-cancel" onclick="cancelEdit(${r.id})">Cancel</button>
                        <button type="button" class="form-btn btn-save-review" onclick="saveReview(${r.id})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function addReview() {
        const id = ++counter;
        reviews.unshift({
            id,
            client_name: '',
            title: '',
            location: '',
            content: '',
            image: '',
            image_path: '',
            image_disk: 'public',
            db_id: null
        });
        editingId = id;
        render();
    }

    window.editReview = (id) => {
        editingId = id;
        render();
    };

    window.removeReview = (id) => {
        if (confirm('Remove this review?')) {
            reviews = reviews.filter(r => r.id !== id);
            if (editingId === id) editingId = null;
            render();
        }
    };

    window.cancelEdit = (id) => {
        const r = reviews.find(x => x.id === id);
        if (r && !r.client_name && !r.content) {
            reviews = reviews.filter(x => x.id !== id);
        }
        editingId = null;
        render();
    };

    window.saveReview = (id) => {
        const r = reviews.find(x => x.id === id);
        if (!r || !r.client_name.trim() || !r.content.trim()) {
            alert('Please fill in client name and review content.');
            return;
        }
        editingId = null;
        render();
    };

    window.updateReview = (id, field, value) => {
        const r = reviews.find(x => x.id === id);
        if (!r) return;

        if (field === 'content') {
            r.content = value.slice(0, CONTENT_MAX);
            const ct = document.getElementById(`ct-content-${id}`);
            if (ct) ct.textContent = `${r.content.length}/${CONTENT_MAX}`;
        } else {
            r[field] = value;
        }
        updateSaveButton();
    };

    // Avatar handling
    window.handleAvatarUpload = async (id, input) => {
        const file = input.files[0];
        if (!file || !file.type.startsWith('image/')) return;

        try {
            const dataUrl = await compressImage(file);
            const r = reviews.find(x => x.id === id);
            if (r) {
                r.image = dataUrl;
                const preview = document.getElementById(`avatar-preview-${id}`);
                if (preview) {
                    preview.innerHTML = `<img src="${dataUrl}" alt="Client">`;
                }
            }
        } catch (e) {
            console.error(e);
        }
        input.value = '';
    };

    window.removeAvatar = (id) => {
        const r = reviews.find(x => x.id === id);
        if (!r) return;
        r.image = '';
        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(r.client_name || 'Client')}&size=200&background=667eea&color=fff`;
        const preview = document.getElementById(`avatar-preview-${id}`);
        if (preview) {
            preview.innerHTML = `<img src="${avatarUrl}" alt="Client">`;
        }
    };

    async function compressImage(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ratio = Math.min(IMG_MAX_W / img.width, IMG_MAX_H / img.height);
                    canvas.width = Math.max(1, Math.round(img.width * ratio));
                    canvas.height = Math.max(1, Math.round(img.height * ratio));
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    resolve(canvas.toDataURL('image/jpeg', IMG_QUALITY));
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    el.addBtn.addEventListener('click', addReview);

    // Initialize
    loadExisting();
    render();
});
</script>

<style>
/* Container */
.reviews-container {
    min-height: 200px;
    margin-bottom: 20px;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    border: 1.5px dashed var(--border);
    border-radius: 12px;
    background: var(--apc-bg);
    color: var(--text-muted);
    text-align: center;
}

.empty-state svg {
    margin-bottom: 16px;
    opacity: 0.2;
}

.empty-state p {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-body);
    margin: 0 0 4px 0;
}

.empty-state span {
    font-size: 13px;
}

/* Reviews List */
.reviews-list {
    display: none;
    flex-direction: column;
    gap: 12px;
}

/* Review Card - Display */
.review-card {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    background: var(--card);
    transition: all 0.2s ease;
}

.review-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.review-card.editing {
    border-color: var(--accent);
    background: var(--apc-bg);
}

.review-header {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 16px;
    align-items: start;
}

.review-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    overflow: hidden;
    background: var(--apc-bg);
    flex-shrink: 0;
}

.review-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.review-content {
    flex: 1;
    min-width: 0;
}

.review-client-info {
    margin-bottom: 12px;
}

.review-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-heading);
    margin-bottom: 4px;
}

.review-title {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 4px;
}

.review-location {
    font-size: 13px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.review-location svg {
    opacity: 0.6;
}

.review-text {
    font-size: 15px;
    line-height: 1.6;
    color: var(--text-body);
    font-style: italic;
    position: relative;
}

.quote-left,
.quote-right {
    font-size: 24px;
    color: var(--accent);
    opacity: 0.3;
    font-style: normal;
}

.quote-left {
    margin-right: 4px;
}

.quote-right {
    margin-left: 4px;
}

.review-actions {
    display: flex;
    gap: 6px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border: 1.5px solid var(--border);
    background: var(--card);
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    color: var(--text-muted);
}

.action-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
    transform: translateY(-2px);
}

.action-btn.delete:hover {
    border-color: #dc2626;
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Edit Form */
.review-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.review-form-header {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 20px;
    align-items: start;
}

.avatar-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.avatar-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--border);
    background: var(--apc-bg);
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-actions {
    display: flex;
    flex-direction: column;
    gap: 6px;
    width: 100%;
}

.avatar-btn {
    padding: 6px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--card);
    color: var(--text-body);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.avatar-btn:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}

.avatar-btn.remove:hover {
    background: #dc2626;
    border-color: #dc2626;
}

.form-fields-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-body);
}

.required {
    color: #dc2626;
}

.form-input,
.form-textarea {
    padding: 10px 14px;
    border: 1.5px solid var(--input-border);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    background: var(--card);
    color: var(--input-text);
    transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    resize: vertical;
    line-height: 1.6;
}

.char-count {
    text-align: right;
    font-size: 12px;
    color: var(--text-muted);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 10px;
    padding-top: 8px;
    margin-top: 8px;
    border-top: 1px solid var(--border);
}

.form-btn {
    flex: 1;
    height: 44px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-cancel {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-body);
}

.btn-cancel:hover {
    border-color: var(--text-muted);
    background: var(--apc-bg);
}

.btn-save-review {
    background: var(--accent);
    color: white;
}

.btn-save-review:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}

/* Add Button */
.add-btn {
    width: 100%;
    height: 52px;
    background: var(--card);
    color: var(--text-body);
    border: 1.5px dashed var(--border);
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 20px;
    transition: all 0.2s ease;
}

.add-btn:hover {
    border-color: var(--accent);
    border-style: solid;
    background: var(--accent-light);
    color: var(--accent);
}

/* Responsive */
@media (max-width: 768px) {
    .review-header {
        grid-template-columns: 1fr;
    }

    .review-avatar {
        margin: 0 auto;
    }

    .review-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .review-form-header {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>