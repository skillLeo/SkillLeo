@props(['modalPortfolios' => []])

<x-modals.edits.base-modal id="editPortfolioModal" title="Portfolio Projects" size="lg">
    <form id="portfolioModalForm" method="POST" action="{{ route('tenant.portfolio.update') }}">
        @csrf
        @method('PUT')

        {{-- Projects List --}}
        <div class="pf-list" id="portfolioList"></div>

        {{-- Empty State --}}
        <div class="pf-empty" id="emptyState">
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="14" rx="2" />
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
            </svg>
            <p>No projects yet</p>
            <span>Add your first project to showcase your work</span>
        </div>

        {{-- Add Button --}}
        <button type="button" class="pf-add-btn" id="addPortfolioBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Project
        </button>

        <input type="hidden" name="portfolios" id="portfolioData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-secondary" onclick="closeModal('editPortfolioModal')">Cancel</button>
        <button type="submit" form="portfolioModalForm" class="btn-modal btn-primary" id="savePortfoliosBtn">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<script>
    const existingPortfolios = @json($modalPortfolios ?? []);

    document.addEventListener('DOMContentLoaded', function() {
        'use strict';

        let portfolios = [];
        let editingId = null;
        let counter = 0;

        const TITLE_MAX = 80;
        const DESC_MAX = 280;
        const IMG_MAX_W = 1200;
        const IMG_MAX_H = 900;
        const IMG_QUALITY = 0.85;

        const el = {
            list: document.getElementById('portfolioList'),
            empty: document.getElementById('emptyState'),
            addBtn: document.getElementById('addPortfolioBtn'),
            saveBtn: document.getElementById('savePortfoliosBtn'),
            dataInput: document.getElementById('portfolioData'),
        };

        const esc = (s) => String(s || '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));
        const normURL = (url = '') => {
            const v = String(url).trim();
            if (!v) return '';
            if (/^https?:\/\//i.test(v)) return v;
            return 'https://' + v.replace(/^\/+/, '');
        };
        const hostnameFrom = (url) => {
            try {
                return new URL(normURL(url)).hostname.replace(/^www\./, '');
            } catch {
                return '';
            }
        };
        const truncate = (str, len) => (str || '').length > len ? str.slice(0, len) + '...' : str;

        function loadExisting() {
            portfolios = existingPortfolios.map((p, i) => {
                // Use image_url for display, but keep image_path for saving
                const displayImage = p.image_url || '';

                return {
                    id: p.id || ++counter,
                    title: String(p.title || ''),
                    description: String(p.description || ''),
                    link: String(p.link || ''),
                    image: displayImage, // For display in UI
                    image_path: p.image_path || '', // For saving to DB
                    image_disk: p.image_disk || 'public',
                    tags: Array.isArray(p.tags) ? p.tags : [],
                    category: String(p.category || ''),
                    db_id: p.id || null // ✅ CRITICAL: This is the database ID
                };
            });
            counter = Math.max(counter, ...portfolios.map(p => p.id));
        }

        function updateSaveButton() {
            const hasValid = portfolios.length > 0 && portfolios.some(p => p.title && p.description);
            const isEditing = editingId !== null;
            if (el.saveBtn) el.saveBtn.disabled = !hasValid || isEditing;
        }

        function render() {
            if (portfolios.length === 0) {
                el.empty.style.display = 'flex';
                el.list.style.display = 'none';
            } else {
                el.empty.style.display = 'none';
                el.list.style.display = 'block';
                el.list.innerHTML = portfolios.map(p =>
                    editingId === p.id ? renderEdit(p) : renderPreview(p)
                ).join('');
            }

            // ✅ CRITICAL FIX: Properly serialize data for submission
            const dataToSave = portfolios.map(p => ({
                db_id: p.db_id, // Database ID for updates
                title: p.title,
                description: p.description,
                link: p.link,
                image: p.image, // Base64 or URL
                image_path: p.image_path, // Original path
                image_disk: p.image_disk,
                tags: p.tags,
                category: p.category
            }));

            el.dataInput.value = JSON.stringify(dataToSave);
            updateSaveButton();
        }

        function renderPreview(p) {
            const link = p.link ? normURL(p.link) : '';
            const tags = Array.isArray(p.tags) ? p.tags.slice(0, 3) : [];
            const host = link ? hostnameFrom(link) : '';

            return `
                <div class="pf-preview" id="port-${p.id}">
                    <div class="pf-preview-main">
                        ${p.image ? `
                            <div class="pf-preview-img">
                                <img src="${p.image}" alt="${esc(p.title)}">
                            </div>
                        ` : `
                            <div class="pf-preview-img-empty">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                            </div>
                        `}
                        
                        <div class="pf-preview-content">
                            <div class="pf-preview-header">
                                <h4 class="pf-preview-title">${esc(p.title) || 'Untitled Project'}</h4>
                                ${tags.length ? `
                                    <div class="pf-preview-tags">
                                        ${tags.map(t => `<span class="pf-mini-tag">${esc(t)}</span>`).join('')}
                                    </div>
                                ` : ''}
                            </div>
                            
                            ${p.description ? `
                                <p class="pf-preview-desc">${esc(truncate(p.description, 100))}</p>
                            ` : ''}
                            
                            ${host ? `
                                <div class="pf-preview-link">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                    </svg>
                                    <span>${esc(host)}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div class="pf-preview-actions">
                        <button type="button" class="pf-action-btn edit" onclick="editPort(${p.id})" title="Edit project">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button type="button" class="pf-action-btn delete" onclick="removePort(${p.id})" title="Delete project">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }

        function renderEdit(p) {
            const tags = Array.isArray(p.tags) ? p.tags : [];

            return `
                <div class="pf-edit-card" id="port-${p.id}">
                    <div class="pf-edit-header">
                        <h4>Edit Project</h4>
                        <button type="button" class="pf-close-edit" onclick="cancelEdit(${p.id})" title="Close">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="pf-edit-body">
                        <div class="pf-form-grid">
                            <div class="pf-form-col">
                                <div class="pf-field">
                                    <label class="pf-label">
                                        Project Title <span class="pf-required">*</span>
                                    </label>
                                    <input type="text" class="pf-input" maxlength="${TITLE_MAX}" 
                                           value="${esc(p.title)}" 
                                           placeholder="Enter project title"
                                           oninput="updatePort(${p.id}, 'title', this.value)"/>
                                    <div class="pf-field-info">
                                        <span class="pf-count" id="ct-title-${p.id}">${(p.title||'').length}/${TITLE_MAX}</span>
                                    </div>
                                </div>
    
                                <div class="pf-field">
                                    <label class="pf-label">
                                        Description <span class="pf-required">*</span>
                                    </label>
                                    <textarea class="pf-textarea" maxlength="${DESC_MAX}" rows="4"
                                              placeholder="Describe your project, technologies used, and key achievements..."
                                              oninput="updatePort(${p.id}, 'description', this.value)">${esc(p.description)}</textarea>
                                    <div class="pf-field-info">
                                        <span class="pf-hint">Be specific and highlight your contributions</span>
                                        <span class="pf-count" id="ct-desc-${p.id}">${(p.description||'').length}/${DESC_MAX}</span>
                                    </div>
                                </div>
    
                                <div class="pf-field-row">
                                    <div class="pf-field">
                                        <label class="pf-label">Category</label>
                                        <select class="pf-select" onchange="updatePort(${p.id}, 'category', this.value)">
                                            <option value="">Select category</option>
                                            <option value="web" ${p.category === 'web' ? 'selected' : ''}>Web Development</option>
                                            <option value="mobile" ${p.category === 'mobile' ? 'selected' : ''}>Mobile App</option>
                                            <option value="design" ${p.category === 'design' ? 'selected' : ''}>UI/UX Design</option>
                                            <option value="other" ${p.category === 'other' ? 'selected' : ''}>Other</option>
                                        </select>
                                    </div>
    
                                    <div class="pf-field">
                                        <label class="pf-label">Project Link</label>
                                        <input type="url" class="pf-input" value="${esc(p.link)}" 
                                               placeholder="https://example.com"
                                               oninput="updatePort(${p.id}, 'link', this.value)"/>
                                    </div>
                                </div>
    
                                <div class="pf-field">
                                    <label class="pf-label">Technologies & Skills</label>
                                    <div class="pf-tag-input-wrap">
                                        <input type="text" class="pf-input" id="tag-input-${p.id}" 
                                               placeholder="Type and press Enter (max 5 tags)" 
                                               onkeydown="if(event.key==='Enter'){event.preventDefault();addTag(${p.id})}"/>
                                    </div>
                                    ${tags.length ? `
                                        <div class="pf-tags-display">
                                            ${tags.map((t, i) => `
                                                <span class="pf-tag-item">
                                                    ${esc(t)}
                                                    <button type="button" class="pf-tag-remove" onclick="removeTag(${p.id}, ${i})" title="Remove">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </button>
                                                </span>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
    
                            <div class="pf-form-col">
                                <div class="pf-field">
                                    <label class="pf-label">Cover Image</label>
                                    <div class="pf-img-upload">
                                        ${p.image ? `
                                            <div class="pf-img-preview">
                                                <img src="${p.image}" alt="Cover">
                                                <div class="pf-img-overlay">
                                                    <button type="button" class="pf-img-change" onclick="document.getElementById('img-input-${p.id}').click()">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                        </svg>
                                                        Change
                                                    </button>
                                                    <button type="button" class="pf-img-remove" onclick="removeImage(${p.id})">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        ` : `
                                            <div class="pf-img-empty" onclick="document.getElementById('img-input-${p.id}').click()">
                                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                                    <path d="M21 15l-5-5L5 21"/>
                                                </svg>
                                                <p>Click to upload</p>
                                                <span>PNG, JPG up to 10MB</span>
                                            </div>
                                        `}
                                        <input type="file" id="img-input-${p.id}" accept="image/*" hidden onchange="handleImage(${p.id}, this)"/>
                                    </div>
                                    <div class="pf-field-info">
                                        <span class="pf-hint">Recommended: 1200x900px or 4:3 ratio</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="pf-edit-footer">
                        <button type="button" class="pf-btn secondary" onclick="cancelEdit(${p.id})">
                            Cancel
                        </button>
                        <button type="button" class="pf-btn primary" onclick="savePort(${p.id})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Save Project
                        </button>
                    </div>
                </div>
            `;
        }

        function addPortfolio() {
            const id = ++counter;
            portfolios.unshift({
                id,
                title: '',
                description: '',
                link: '',
                image: '',
                image_path: '',
                image_disk: 'public',
                tags: [],
                category: '',
                db_id: null // New portfolio has no database ID
            });
            editingId = id;
            render();
        }

        window.editPort = (id) => {
            editingId = id;
            render();
        };
        window.removePort = (id) => {
            if (confirm('Remove this project?')) {
                portfolios = portfolios.filter(p => p.id !== id);
                if (editingId === id) editingId = null;
                render();
            }
        };
        window.cancelEdit = (id) => {
            const p = portfolios.find(x => x.id === id);
            if (p && !p.title && !p.description) portfolios = portfolios.filter(x => x.id !== id);
            editingId = null;
            render();
        };
        window.savePort = (id) => {
            const p = portfolios.find(x => x.id === id);
            if (!p || !p.title.trim() || !p.description.trim()) {
                alert('Title and description are required.');
                return;
            }
            p.link = normURL(p.link);
            editingId = null;
            render();
        };

        window.updatePort = (id, field, value) => {
            const p = portfolios.find(x => x.id === id);
            if (!p) return;
            if (field === 'title') {
                p.title = value.slice(0, TITLE_MAX);
                const ct = document.getElementById(`ct-title-${id}`);
                if (ct) ct.textContent = `${p.title.length}/${TITLE_MAX}`;
            } else if (field === 'description') {
                p.description = value.slice(0, DESC_MAX);
                const ct = document.getElementById(`ct-desc-${id}`);
                if (ct) ct.textContent = `${p.description.length}/${DESC_MAX}`;
            } else if (field === 'link') p.link = value.trim();
            else if (field === 'category') p.category = value;
            updateSaveButton();
        };

        window.addTag = (id) => {
            const p = portfolios.find(x => x.id === id);
            if (!p) return;
            const input = document.getElementById(`tag-input-${id}`);
            const tag = (input?.value || '').trim();
            if (!tag) return;
            if (p.tags.length >= 5) {
                alert('Maximum 5 tags allowed');
                return;
            }
            if (p.tags.includes(tag)) {
                alert('Tag already exists');
                input.value = '';
                return;
            }
            p.tags.push(tag);
            input.value = '';
            render();
        };

        window.removeTag = (id, index) => {
            const p = portfolios.find(x => x.id === id);
            if (!p) return;
            p.tags.splice(index, 1);
            render();
        };

        window.handleImage = async (id, input) => {
            const file = input.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            try {
                const dataUrl = await compressImage(file);
                const p = portfolios.find(x => x.id === id);
                if (p) {
                    p.image = dataUrl; // New base64 image
                    p.image_path = ''; // Clear old path when uploading new image
                    render();
                }
            } catch (e) {
                console.error(e);
            }
            input.value = '';
        };

        window.removeImage = (id) => {
            const p = portfolios.find(x => x.id === id);
            if (p) {
                p.image = '';
                p.image_path = '';
                render();
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

        el.addBtn.addEventListener('click', addPortfolio);
        loadExisting();
        render();
    });
</script>

<style>
    /* ============================================
   PORTFOLIO MODAL - PREMIUM PROFESSIONAL DESIGN
   ============================================ */

    /* List Container */
    .pf-list {
        display: none;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    /* Empty State */
    .pf-empty {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 64px 24px;
        text-align: center;
        color: var(--text-muted);
    }

    .pf-empty svg {
        margin-bottom: 20px;
        opacity: 0.2;
    }

    .pf-empty p {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 6px 0;
    }

    .pf-empty span {
        font-size: var(--fs-body);
        color: var(--text-muted);
    }

    /* ==================== PREVIEW CARD ==================== */
    .pf-preview {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: all 0.2s ease;
    }

    .pf-preview:hover {
        border-color: var(--accent);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    }

    .pf-preview-main {
        flex: 1;
        display: flex;
        gap: 14px;
        min-width: 0;
    }

    .pf-preview-img,
    .pf-preview-img-empty {
        flex-shrink: 0;
        width: 72px;
        height: 72px;
        border-radius: 8px;
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
        gap: 12px;
        margin-bottom: 8px;
    }

    .pf-preview-title {
        font-size: var(--fs-title);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0;
        line-height: var(--lh-tight);
    }

    .pf-preview-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .pf-mini-tag {
        font-size: var(--fs-micro);
        font-weight: var(--fw-medium);
        color: var(--text-body);
        background: var(--apc-bg);
        padding: 2px 8px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .pf-preview-desc {
        font-size: var(--fs-subtle);
        color: var(--text-body);
        line-height: var(--lh-relaxed);
        margin: 0 0 8px 0;
    }

    .pf-preview-link {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    .pf-preview-link svg {
        flex-shrink: 0;
    }

    .pf-preview-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    .pf-action-btn {
        width: 36px;
        height: 36px;
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
        transform: translateY(-1px);
    }

    .pf-action-btn.edit:hover {
        border-color: var(--accent);
        background: var(--accent-light);
        color: var(--accent);
    }

    .pf-action-btn.delete:hover {
        border-color: #dc2626;
        background: rgba(220, 38, 38, 0.08);
        color: #dc2626;
    }

    /* ==================== EDIT CARD ==================== */
    .pf-edit-card {
        background: var(--card);
        border: 2px solid var(--accent);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .pf-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        background: var(--apc-bg);
        border-bottom: 1px solid var(--border);
    }

    .pf-edit-header h4 {
        font-size: var(--fs-h3);
        font-weight: var(--fw-semibold);
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
        background: var(--card);
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

    /* Form Fields */
    .pf-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .pf-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .pf-label {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pf-required {
        color: #dc2626;
        font-size: var(--fs-body);
    }

    .pf-input,
    .pf-select,
    .pf-textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--input-border);
        border-radius: 8px;
        font-size: var(--fs-body);
        font-family: inherit;
        background: var(--input-bg);
        color: var(--input-text);
        transition: all 0.2s ease;
    }

    .pf-input:focus,
    .pf-select:focus,
    .pf-textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .pf-input::placeholder,
    .pf-textarea::placeholder {
        color: var(--input-placeholder);
    }

    .pf-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 20px;
        padding-right: 42px;
        cursor: pointer;
    }

    .pf-textarea {
        resize: vertical;
        line-height: var(--lh-relaxed);
        min-height: 100px;
    }

    .pf-field-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .pf-hint {
        font-size: var(--fs-micro);
        color: var(--text-muted);
    }

    .pf-count {
        font-size: var(--fs-micro);
        font-weight: var(--fw-medium);
        color: var(--text-muted);
        white-space: nowrap;
    }

    /* Tags */
    .pf-tag-input-wrap {
        position: relative;
    }

    .pf-tags-display {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .pf-tag-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        color: var(--text-heading);
        background: var(--apc-bg);
        border: 1px solid var(--border);
        padding: 6px 8px 6px 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .pf-tag-item:hover {
        border-color: var(--accent);
        background: var(--accent-light);
    }

    .pf-tag-remove {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 4px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-tag-remove:hover {
        background: rgba(220, 38, 38, 0.15);
        color: #dc2626;
    }

    /* Image Upload */
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
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        opacity: 0;
        transition: opacity 0.2s ease;
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
        font-size: var(--fs-subtle);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-img-change:hover {
        background: var(--accent);
        color: var(--text-white);
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
        background: var(--accent-light);
    }

    .pf-img-empty svg {
        margin-bottom: 12px;
        color: var(--text-muted);
    }

    .pf-img-empty p {
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        color: var(--text-heading);
        margin: 0 0 4px 0;
    }

    .pf-img-empty span {
        font-size: var(--fs-subtle);
        color: var(--text-muted);
    }

    /* Edit Footer */
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
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .pf-btn.primary {
        background: var(--accent);
        color: var(--text-white);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .pf-btn.primary:hover {
        background: var(--accent-dark);
        box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
        transform: translateY(-1px);
    }

    .pf-btn.secondary {
        background: var(--card);
        color: var(--text-body);
        border: 1.5px solid var(--border);
    }

    .pf-btn.secondary:hover {
        background: var(--apc-bg);
        border-color: var(--text-muted);
    }

    /* Add Button */
    .pf-add-btn {
        width: 100%;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: var(--card);
        color: var(--text-heading);
        border: 2px dashed var(--border);
        border-radius: var(--radius);
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pf-add-btn:hover {
        border-color: var(--accent);
        border-style: solid;
        color: var(--accent);
        background: var(--accent-light);
    }

    /* Modal Footer Buttons */
    .btn-modal {
        height: 44px;
        padding: 0 28px;
        border: none;
        border-radius: 8px;
        font-size: var(--fs-body);
        font-weight: var(--fw-semibold);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modal.btn-primary {
        background: var(--accent);
        color: var(--text-white);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .btn-modal.btn-primary:hover {
        background: var(--accent-dark);
        box-shadow: 0 2px 8px rgba(19, 81, 216, 0.25);
        transform: translateY(-1px);
    }

    .btn-modal.btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-modal.btn-secondary {
        background: transparent;
        border: 1.5px solid var(--border);
        color: var(--text-body);
    }

    .btn-modal.btn-secondary:hover {
        background: var(--apc-bg);
        border-color: var(--text-muted);
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
        .pf-form-grid {
            grid-template-columns: 1fr;
        }

        .pf-field-row {
            grid-template-columns: 1fr;
        }

        .pf-edit-header,
        .pf-edit-body,
        .pf-edit-footer {
            padding: 16px;
        }

        .pf-preview {
            flex-direction: column;
        }

        .pf-preview-main {
            width: 100%;
        }

        .pf-preview-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .pf-edit-footer {
            flex-direction: column-reverse;
        }

        .pf-btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .pf-preview-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .pf-field-info {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>