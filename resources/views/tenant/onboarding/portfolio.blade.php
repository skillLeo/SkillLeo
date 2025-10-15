@extends('layouts.onboarding')

@section('title', 'Portfolio Projects - ProMatch')

@section('card-content')

<x-onboarding.form-header 
    skipUrl="{{ route('tenant.onboarding.education') }}"
    step="5"
    title="Showcase your work"
    subtitle="Add 2-4 projects that best demonstrate your expertise"
/>

<form id="portfolioForm" action="{{ route('tenant.onboarding.portfolio.store') }}" method="POST">
    @csrf

    {{-- Projects List --}}
    <div class="pf-onboard-list" id="projectsList"></div>

    {{-- Empty State --}}
    <div class="pf-onboard-empty" id="emptyState">
        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="7" width="20" height="14" rx="2"/>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
        </svg>
        <p>No projects yet</p>
        <span>Add your first project to showcase your work</span>
    </div>

    {{-- Add Button --}}
    <button type="button" class="pf-onboard-add-btn" id="addBtn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add Project
    </button>

    <input type="hidden" name="projects" id="projectsData">

    <x-onboarding.form-footer 
        skipUrl="{{ route('tenant.onboarding.education') }}" 
        backUrl="{{ route('tenant.onboarding.experience') }}" 
    />
</form>

@endsection

@push('styles')
<style>
 
.pf-onboard-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: var(--space-lg) 0;
}

/* Empty State */
.pf-onboard-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    text-align: center;
    color: var(--text-muted);
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    background: var(--apc-bg);
}

.pf-onboard-empty svg {
    margin-bottom: 16px;
    opacity: 0.2;
}

.pf-onboard-empty p {
    font-size: var(--fs-title);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.pf-onboard-empty span {
    font-size: var(--fs-body);
    color: var(--text-muted);
}

/* ==================== PREVIEW CARD ==================== */
.pf-onboard-preview {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    transition: all 0.2s ease;
}

.pf-onboard-preview:hover {
    border-color: var(--accent);
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

.pf-onboard-preview-main {
    flex: 1;
    display: flex;
    gap: 14px;
    min-width: 0;
}

.pf-onboard-preview-img,
.pf-onboard-preview-img-empty {
    flex-shrink: 0;
    width: 100px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: var(--apc-bg);
    border: 1px solid var(--border);
}

.pf-onboard-preview-img img {
    width: 100%;
    height: 100%;
    object-fit: fill;
}

.pf-onboard-preview-img-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.pf-onboard-preview-content {
    flex: 1;
    min-width: 0;
}

.pf-onboard-preview-title {
    font-size: var(--fs-title);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 6px 0;
    line-height: var(--lh-tight);
}

.pf-onboard-preview-desc {
    font-size: var(--fs-subtle);
    color: var(--text-body);
    line-height: var(--lh-relaxed);
    margin: 0 0 8px 0;
}

.pf-onboard-preview-link {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

.pf-onboard-preview-link svg {
    flex-shrink: 0;
}

.pf-onboard-preview-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.pf-onboard-action-btn {
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

.pf-onboard-action-btn:hover {
    transform: translateY(-1px);
}

.pf-onboard-action-btn.edit:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
}

.pf-onboard-action-btn.delete:hover {
    border-color: #dc2626;
    background: rgba(220, 38, 38, 0.08);
    color: #dc2626;
}

/* ==================== EDIT CARD ==================== */
.pf-onboard-edit-card {
    background: var(--card);
    border: 2px solid var(--accent);
    border-radius: var(--radius);
    overflow: hidden;
}

.pf-onboard-edit-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: var(--apc-bg);
    border-bottom: 1px solid var(--border);
}

.pf-onboard-edit-header h4 {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.pf-onboard-close-edit {
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

.pf-onboard-close-edit:hover {
    background: var(--card);
    color: var(--text-heading);
}

.pf-onboard-edit-body {
    padding: 20px;
}

.pf-onboard-form-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 20px;
}

.pf-onboard-form-col {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Form Fields */
.pf-onboard-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.pf-onboard-label {
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
}

.pf-onboard-required {
    color: #dc2626;
}

.pf-onboard-input,
.pf-onboard-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid var(--input-border);
    border-radius: 8px;
    font-size: var(--fs-body);
    font-family: inherit;
    background: var(--input-bg);
    color: var(--input-text);
    transition: all 0.2s ease;
}

.pf-onboard-input:focus,
.pf-onboard-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-light);
}

.pf-onboard-input::placeholder,
.pf-onboard-textarea::placeholder {
    color: var(--input-placeholder);
}

.pf-onboard-textarea {
    resize: vertical;
    line-height: var(--lh-relaxed);
    min-height: 80px;
}

.pf-onboard-count {
    text-align: right;
    font-size: var(--fs-micro);
    color: var(--text-muted);
}

/* Image Upload */
.pf-onboard-img-upload {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    border-radius: 8px;
    overflow: hidden;
    background: var(--apc-bg);
    border: 2px dashed var(--border);
}

.pf-onboard-img-preview {
    width: 100%;
    height: 100%;
    position: relative;
}

.pf-onboard-img-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pf-onboard-img-overlay {
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

.pf-onboard-img-preview:hover .pf-onboard-img-overlay {
    opacity: 1;
}

.pf-onboard-img-change,
.pf-onboard-img-remove {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: var(--card);
    color: var(--text-heading);
    border: none;
    border-radius: 6px;
    font-size: var(--fs-subtle);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-onboard-img-change:hover {
    background: var(--accent);
    color: var(--text-white);
}

.pf-onboard-img-remove {
    background: rgba(220, 38, 38, 0.9);
    color: white;
}

.pf-onboard-img-remove:hover {
    background: #dc2626;
}

.pf-onboard-img-empty {
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

.pf-onboard-img-empty:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.pf-onboard-img-empty svg {
    margin-bottom: 10px;
    color: var(--text-muted);
}

.pf-onboard-img-empty p {
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.pf-onboard-img-empty span {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

/* Edit Footer */
.pf-onboard-edit-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 20px;
    background: var(--apc-bg);
    border-top: 1px solid var(--border);
}

.pf-onboard-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 40px;
    padding: 0 20px;
    border: none;
    border-radius: 8px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
}

.pf-onboard-btn.primary {
    background: var(--accent);
    color: var(--text-white);
}

.pf-onboard-btn.primary:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

.pf-onboard-btn.secondary {
    background: var(--card);
    color: var(--text-body);
    border: 1.5px solid var(--border);
}

.pf-onboard-btn.secondary:hover {
    background: var(--apc-bg);
}

/* Add Button */
.pf-onboard-add-btn {
    width: 100%;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: var(--card);
    color: var(--text-heading);
    border: 2px dashed var(--border);
    border-radius: var(--radius);
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: var(--space-lg);
}

.pf-onboard-add-btn:hover {
    border-color: var(--accent);
    border-style: solid;
    color: var(--accent);
    background: var(--accent-light);
}

/* Responsive */
@media (max-width: 768px) {
    .pf-onboard-form-grid {
        grid-template-columns: 1fr;
    }
    
    .pf-onboard-edit-header,
    .pf-onboard-edit-body,
    .pf-onboard-edit-footer {
        padding: 14px;
    }
    
    .pf-onboard-preview {
        flex-direction: column;
    }
    
    .pf-onboard-preview-main {
        width: 100%;
    }
    
    .pf-onboard-preview-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .pf-onboard-edit-footer {
        flex-direction: column-reverse;
    }
    
    .pf-onboard-btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
@verbatim
<script>
(() => {
  "use strict";

  const projectsList   = document.getElementById('projectsList');
  const emptyState     = document.getElementById('emptyState');
  const addBtn         = document.getElementById('addBtn');
  const continueBtn    = document.getElementById('continueBtn');
  const formEl         = document.getElementById('portfolioForm');
  const projectsDataEl = document.getElementById('projectsData');

  let projects = [];
  let projectIdCounter = 0;
  let editingId = null;

  const MIN_PROJECTS = 2;
  const TITLE_MAX = 80;
  const DESC_MAX  = 280;
  const IMG_MAX_W = 1200;
  const IMG_MAX_H = 900;
  const IMG_QUALITY = 0.85;

  const esc = (s) => String(s || '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  const normURL = (url='') => { const v = String(url).trim(); if (!v) return ''; if (/^https?:\/\//i.test(v)) return v; return 'https://' + v.replace(/^\/+/, ''); };
  const hostnameFrom = (url) => { try { return new URL(normURL(url)).hostname.replace(/^www\./,''); } catch { return ''; } };
  const truncate = (str, len) => (str || '').length > len ? str.slice(0, len) + '...' : str;

  function packAndStore(){
    const data = projects.filter(p => (p.title || p.description || p.link || p.image));
    try { localStorage.setItem('onboarding_portfolio', JSON.stringify(data)); } catch {}
    projectsDataEl.value = JSON.stringify(data);
  }

  function loadFromStorage(){
    try {
      const raw = localStorage.getItem('onboarding_portfolio');
      if (!raw) return;
      const arr = JSON.parse(raw);
      if (Array.isArray(arr)) {
        projects = arr.map(p => ({
          id: Number(p?.id) || ++projectIdCounter,
          title: String(p?.title || ''),
          description: String(p?.description || ''),
          link: String(p?.link || ''),
          image: p?.image || ''
        }));
        projectIdCounter = projects.reduce((m,p)=> Math.max(m, p.id), projectIdCounter);
      }
    } catch {}
  }

  function validProjectsCount(){
    return projects.filter(p => p.title.trim() && p.description.trim()).length;
  }

  function updateContinueState(){
    if (!continueBtn) return;
    continueBtn.disabled = validProjectsCount() < MIN_PROJECTS;
  }

  function render(){
    projectsList.innerHTML = '';
    if (projects.length === 0) {
      emptyState.style.display = 'flex';
      projectsList.appendChild(emptyState);
    } else {
      emptyState.style.display = 'none';
      projects.forEach(p => {
        const html = (editingId === p.id) ? renderEdit(p) : renderPreview(p);
        projectsList.insertAdjacentHTML('beforeend', html);
      });
    }
    updateContinueState();
    packAndStore();
  }

  function renderPreview(p){
    const link = p.link ? normURL(p.link) : '';
    const host = link ? hostnameFrom(link) : '';

    return `
      <div class="pf-onboard-preview" id="proj-${p.id}">
        <div class="pf-onboard-preview-main">
          ${p.image ? `
            <div class="pf-onboard-preview-img">
              <img src="${p.image}" alt="${esc(p.title)}">
            </div>
          ` : `
            <div class="pf-onboard-preview-img-empty">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <path d="M21 15l-5-5L5 21"/>
              </svg>
            </div>
          `}
          
          <div class="pf-onboard-preview-content">
            <h4 class="pf-onboard-preview-title">${esc(p.title) || 'Untitled Project'}</h4>
            ${p.description ? `<p class="pf-onboard-preview-desc">${esc(truncate(p.description, 100))}</p>` : ''}
            ${host ? `
              <div class="pf-onboard-preview-link">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                  <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
                <span>${esc(host)}</span>
              </div>
            ` : ''}
          </div>
        </div>
        
        <div class="pf-onboard-preview-actions">
          <button type="button" class="pf-onboard-action-btn edit" onclick="editProject(${p.id})">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
          </button>
          <button type="button" class="pf-onboard-action-btn delete" onclick="removeProject(${p.id})">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
          </button>
        </div>
      </div>
    `;
  }

  function renderEdit(p){
    return `
      <div class="pf-onboard-edit-card" id="proj-${p.id}">
        <div class="pf-onboard-edit-header">
          <h4>${p.title ? 'Edit Project' : 'New Project'}</h4>
          <button type="button" class="pf-onboard-close-edit" onclick="cancelEdit(${p.id})">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
        
        <div class="pf-onboard-edit-body">
          <div class="pf-onboard-form-grid">
            <div class="pf-onboard-form-col">
              <div class="pf-onboard-field">
                <label class="pf-onboard-label">Project Title <span class="pf-onboard-required">*</span></label>
                <input type="text" class="pf-onboard-input" maxlength="${TITLE_MAX}" 
                       value="${esc(p.title)}" data-id="${p.id}" data-field="title"
                       placeholder="Enter project title"/>
                <div class="pf-onboard-count" id="ct-title-${p.id}">${(p.title||'').length}/${TITLE_MAX}</div>
              </div>

              <div class="pf-onboard-field">
                <label class="pf-onboard-label">Description <span class="pf-onboard-required">*</span></label>
                <textarea class="pf-onboard-textarea" maxlength="${DESC_MAX}" rows="4"
                          data-id="${p.id}" data-field="description"
                          placeholder="Describe your project, technologies used, and key achievements...">${esc(p.description)}</textarea>
                <div class="pf-onboard-count" id="ct-desc-${p.id}">${(p.description||'').length}/${DESC_MAX}</div>
              </div>

              <div class="pf-onboard-field">
                <label class="pf-onboard-label">Project Link (Optional)</label>
                <input type="url" class="pf-onboard-input" value="${esc(p.link)}" 
                       data-id="${p.id}" data-field="link"
                       placeholder="https://example.com"/>
              </div>
            </div>

            <div class="pf-onboard-form-col">
              <div class="pf-onboard-field">
                <label class="pf-onboard-label">Cover Image (Optional)</label>
                <div class="pf-onboard-img-upload">
                  ${p.image ? `
                    <div class="pf-onboard-img-preview">
                      <img src="${p.image}" alt="Cover">
                      <div class="pf-onboard-img-overlay">
                        <button type="button" class="pf-onboard-img-change" onclick="document.getElementById('img-input-${p.id}').click()">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                          </svg>
                          Change
                        </button>
                        <button type="button" class="pf-onboard-img-remove" onclick="removeImage(${p.id})">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          </svg>
                          Remove
                        </button>
                      </div>
                    </div>
                  ` : `
                    <div class="pf-onboard-img-empty" onclick="document.getElementById('img-input-${p.id}').click()">
                      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
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
              </div>
            </div>
          </div>
        </div>

        <div class="pf-onboard-edit-footer">
          <button type="button" class="pf-onboard-btn secondary" onclick="cancelEdit(${p.id})">Cancel</button>
          <button type="button" class="pf-onboard-btn primary" onclick="saveProject(${p.id})">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Save Project
          </button>
        </div>
      </div>
    `;
  }

  function addProject(){
    const id = ++projectIdCounter;
    projects.unshift({ id, title:'', description:'', link:'', image:'' });
    editingId = id;
    render();
  }

  function editProject(id){ editingId = id; render(); }

  function removeProject(id){
    projects = projects.filter(p => p.id !== id);
    if (editingId === id) editingId = null;
    render();
  }

  function cancelEdit(id){
    const p = projects.find(x => x.id === id);
    if (p && !p.title && !p.description && !p.link && !p.image) { removeProject(id); }
    else { editingId = null; render(); }
  }

  function saveProject(id){
    const p = projects.find(x => x.id === id);
    if (!p) return;
    if (!p.title.trim() || !p.description.trim()){
      alert('Please fill in both title and description.');
      return;
    }
    p.link = normURL(p.link);
    editingId = null;
    packAndStore();
    render();
  }

  function updateProjectField(id, field, value){
    const p = projects.find(x => x.id === id);
    if (!p) return;
    if (field === 'title') p.title = value.slice(0, TITLE_MAX);
    else if (field === 'description') p.description = value.slice(0, DESC_MAX);
    else if (field === 'link') p.link = value.trim();
    packAndStore();
    updateContinueState();
  }

  // ✅ FIXED: Expose handleImage globally
  window.handleImage = async function(id, input) {
    const file = input.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    try {
      const dataUrl = await compressImage(file);
      const p = projects.find(x => x.id === id);
      if (p) { 
        p.image = dataUrl; 
        packAndStore(); 
        render(); 
      }
    } catch (e) { 
      console.error(e); 
    }
    input.value = '';
  };

  // ✅ FIXED: Expose removeImage globally
  window.removeImage = function(id) {
    const p = projects.find(x => x.id === id);
    if (p) { 
      p.image = ''; 
      packAndStore(); 
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

  // Events
  projectsList.addEventListener('input', (e) => {
    const el = e.target;
    if (!el.dataset || !el.dataset.field) return;
    updateProjectField(Number(el.dataset.id), el.dataset.field, el.value);

    if (el.dataset.field === 'title') {
      const ct = document.getElementById(`ct-title-${el.dataset.id}`);
      if (ct) ct.textContent = `${el.value.length}/${TITLE_MAX}`;
    }
    if (el.dataset.field === 'description') {
      const ct = document.getElementById(`ct-desc-${el.dataset.id}`);
      if (ct) ct.textContent = `${el.value.length}/${DESC_MAX}`;
    }
  });

  addBtn.addEventListener('click', addProject);

  if (continueBtn) {
    continueBtn.addEventListener('click', () => {
      if (continueBtn.disabled) return;
      if (formEl.requestSubmit) formEl.requestSubmit();
      else formEl.submit();
    });
  }

  formEl.addEventListener('submit', (e) => {
    if (validProjectsCount() < MIN_PROJECTS) {
      e.preventDefault();
      alert(`Please add at least ${MIN_PROJECTS} projects (title + description).`);
      return;
    }
    packAndStore();
  });

  // Boot
  loadFromStorage();
  render();

  // Expose for inline handlers
  window.editProject   = editProject;
  window.removeProject = removeProject;
  window.saveProject   = saveProject;
  window.cancelEdit    = cancelEdit;
})();
</script>
@endverbatim
@endpush