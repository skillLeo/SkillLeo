@extends(view: 'layouts.onboarding')
@section('title', 'Portfolio Projects - ProMatch')

@php
    $currentStep = 5;
    $totalSteps = 8;
@endphp

@push('styles')
<style>
    /* Portfolio-specific styles that aren't in app.css */
    .skip-section {
        background: var(--gray-100);
        border: 1px dashed var(--gray-300);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        margin-bottom: 20px;
        color: var(--gray-700);
        font-size: 14px;
    }

    .skip-btn {
        margin-top: 10px;
        background: var(--white);
        border: 1px solid var(--gray-300);
        color: var(--gray-700);
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all .2s ease;
    }

    .skip-btn:hover {
        border-color: var(--dark);
        background: var(--gray-100);
        color: var(--dark);
    }

    /* Project cards specific styles */
    .project-card {
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        padding: 20px;
        background: var(--white);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        position: relative;
        margin-bottom: 14px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        animation: cardAppear .5s ease;
    }

    @keyframes cardAppear {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: none; }
    }

    .display-card {
        background: var(--gray-100);
    }

    .display-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
        border-color: var(--gray-300);
    }

    .edit-card {
        border: 1px solid var(--dark);
    }

    /* Card media/images */
    .card-media {
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 12px;
        border: 1px solid var(--gray-300);
        background: linear-gradient(135deg, rgba(0, 0, 0, .03), rgba(0, 0, 0, .02));
    }

    .card-media .media-wrap {
        width: 100%;
        aspect-ratio: 16 / 9;
        background: var(--gray-100);
        display: block;
    }

    .card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .card-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
    }

    .card-link:hover {
        text-decoration: underline;
    }

    .card-tech {
        font-size: 11px;
        color: var(--gray-500);
        background: var(--white);
        padding: 4px 8px;
        border-radius: 12px;
        border: 1px solid var(--gray-300);
    }

    /* Image upload specific */
    .img-dropzone {
        position: relative;
        border: 2px dashed var(--gray-300);
        border-radius: 12px;
        padding: 14px;
        background: var(--gray-100);
        transition: border-color .2s ease, background .2s ease;
        cursor: pointer;
    }

    .img-dropzone:hover {
        border-color: var(--dark);
        background: #F2F6FF;
    }

    .img-dropzone.dragover {
        border-color: var(--primary);
        background: rgba(0, 97, 255, 0.06);
    }

    .img-dropzone .dz-inner {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 14px;
        align-items: center;
    }

    .img-thumb {
        width: 120px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--gray-300);
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: var(--gray-500);
    }

    .img-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .img-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .img-btn {
        background: var(--white);
        border: 1px solid var(--gray-300);
        color: var(--gray-700);
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all .2s ease;
    }

    .img-btn:hover {
        background: var(--dark);
        color: #fff;
        border-color: var(--dark);
    }

    .img-hint {
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 6px;
    }

    .form-header-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        margin-bottom: 12px;
    }

    /* Toast notification */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--dark);
        color: #fff;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        animation: toastIn .2s ease;
    }

    @keyframes toastIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }
        to {
            opacity: 1;
            transform: none;
        }
    }

    /* Mobile responsiveness for image dropzone */
    @media (max-width: 640px) {
        .img-dropzone .dz-inner {
            grid-template-columns: 1fr;
        }
        
        .img-thumb {
            width: 100%;
            height: 160px;
        }
    }
</style>
@endpush

@section('card-content')
    <div class="form-header">
        <div class="step-badge">Portfolio Projects</div>
        <h1 class="form-title">Showcase your best work</h1>
        <p class="form-subtitle">Add projects that demonstrate your skills and impact.</p>
    </div>

    <form id="portfolioForm" action="{{ route('tenant.onboarding.portfolio.store') }}" method="POST">
        @csrf

        <!-- Skip -->
        <div class="skip-section">
            Portfolio is optional but highly recommended for better visibility.
            <div>
                <button type="button" class="skip-btn" id="skipBtn">Skip for now</button>
            </div>
        </div>

        <!-- Projects -->
        <div class="projects-list" id="projectsList">
            <div class="empty-state" id="emptyState">
                <div class="empty-icon">🎯</div>
                <div class="empty-title">Add your first project</div>
                <div class="empty-subtitle">Showcase work that demonstrates your expertise</div>
            </div>
        </div>

        <!-- Add button -->
        <button type="button" class="add-project-btn" id="addBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Add project
        </button>

        <input type="hidden" name="projects" id="projectsData">

        <!-- AI helper -->
        <div class="ai-helper">
            <strong>AI Project Assistant</strong>
            <div class="ai-actions">
                <button type="button" class="ai-button" id="generateBtn">🔗 Generate from URL</button>
                <button type="button" class="ai-button" id="enhanceBtn">✨ Enhance Description</button>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
            <a href="{{ route('tenant.onboarding.experience') }}" class="btn btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </a>

            <button type="submit" class="btn btn-primary" id="continueBtn" disabled>
                <span id="btnText">Continue</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
/* ===== Portfolio Projects with Image Upload ===== */
(() => {
  "use strict";

  // DOM
  const projectsList = document.getElementById('projectsList');
  const emptyState = document.getElementById('emptyState');
  const addBtn = document.getElementById('addBtn');
  const continueBtn = document.getElementById('continueBtn');
  const btnText = document.getElementById('btnText');
  const generateBtn = document.getElementById('generateBtn');
  const enhanceBtn = document.getElementById('enhanceBtn');
  const skipBtn = document.getElementById('skipBtn');
  const formEl = document.getElementById('portfolioForm');

  // State
  /** @type {{id:number, title:string, description:string, link:string, image?:string}[]} */
  let projects = [];
  let projectIdCounter = 0;
  let editingId = null;

  // Limits
  const TITLE_MAX = 80;
  const DESC_MAX  = 280;
  const FILE_MAX_MB = 6;       // raw upload limit (we still compress)
  const IMG_MAX_W = 1600;      // resize bound
  const IMG_MAX_H = 1200;
  const IMG_QUALITY = 0.86;    // 0..1

  // Utils
  const esc = (s) => String(s || '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  const normURL = (url='') => { const v = String(url).trim(); if (!v) return ''; if (/^https?:\/\//i.test(v)) return v; return 'https://' + v.replace(/^\/+/, ''); };
  const hostnameFrom = (url) => { try { return new URL(normURL(url)).hostname.replace(/^www\./,''); } catch { return ''; } };

  function toast(msg){
    const t = document.createElement('div');
    t.className = 'toast';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateY(-6px)'; setTimeout(()=> t.remove(), 200); }, 2200);
  }

  // Storage
  function saveAll(){
    try {
      const data = projects.filter(p => (p.title || p.description || p.link || p.image));
      localStorage.setItem('onboarding_portfolio', JSON.stringify(data));
      document.getElementById('projectsData').value = JSON.stringify(data);
    } catch {}
  }

  function loadAll(){
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

  // Rendering
  function render(){
    projectsList.innerHTML = '';
    if (projects.length === 0) {
      emptyState.style.display = 'block';
      projectsList.appendChild(emptyState);
      updateContinueState();
      saveAll();
      return;
    }
    emptyState.style.display = 'none';
    projects.forEach(p => {
      const html = (editingId === p.id) ? renderEdit(p) : renderCard(p);
      projectsList.insertAdjacentHTML('beforeend', html);
    });
    updateContinueState();
    saveAll();
  }

  function renderCard(p){
    const link = p.link ? normURL(p.link) : '';
    const hasLink = !!link;
    const hasImg = !!p.image;
    return `
      <div class="project-card display-card" id="proj-${p.id}">
        <div class="card-actions">
          <button type="button" class="card-edit" title="Edit" onclick="editProject(${p.id})" aria-label="Edit project">✎</button>
          <button type="button" class="card-remove" title="Remove" onclick="removeProject(${p.id})" aria-label="Remove project">×</button>
        </div>

        ${hasImg ? `
          <div class="card-media">
            <div class="media-wrap"><img src="${p.image}" alt="${esc(p.title || 'Project image')}" loading="lazy"></div>
          </div>` : ''}

        <div class="card-header">
          <div class="card-title">${esc(p.title) || 'Untitled project'}</div>
        </div>

        ${p.description ? `<div class="card-description">${esc(p.description)}</div>` : ''}

        <div class="card-footer">
          ${hasLink ? `<a class="card-link" href="${link}" target="_blank" rel="noopener">View project →</a>`
                    : `<span class="card-link" style="opacity:.6;pointer-events:none;">No link added</span>`}
          ${hasLink ? `<span class="card-tech">${esc(hostnameFrom(link))}</span>` : `<span></span>`}
        </div>
      </div>
    `;
  }

  function renderEdit(p){
    const hasImg = !!p.image;
    return `
      <div class="project-card edit-card" id="proj-${p.id}">
        <div class="form-header-actions">
          <button type="button" class="save-btn" onclick="saveProject(${p.id})">✓ Save</button>
          <button type="button" class="cancel-btn" onclick="cancelEdit(${p.id})">Cancel</button>
        </div>

        <div class="form-group">
          <label class="form-label">Project title <span class="required">*</span></label>
          <input class="form-input" maxlength="${TITLE_MAX}" data-id="${p.id}" data-field="title"
                 value="${esc(p.title)}" placeholder="e.g., Analytics Dashboard for E-commerce"/>
          <div class="char-count" id="ct-title-${p.id}">${(p.title||'').length}/${TITLE_MAX}</div>
        </div>

        <div class="form-group">
          <label class="form-label">Short description <span class="required">*</span></label>
          <textarea class="form-textarea" maxlength="${DESC_MAX}" data-id="${p.id}" data-field="description"
                    placeholder="What was the goal, what did you build, and what was the impact?">${esc(p.description)}</textarea>
          <div class="char-count" id="ct-desc-${p.id}">${(p.description||'').length}/${DESC_MAX}</div>
        </div>

        <div class="form-group">
          <label class="form-label">Project link (optional)</label>
          <input class="form-input" data-id="${p.id}" data-field="link" value="${esc(p.link)}" placeholder="https://example.com/project"/>
        </div>

        <div class="form-group">
          <label class="form-label">Cover image (optional)</label>
          <div class="img-dropzone" data-id="${p.id}">
            <div class="dz-inner">
              <div class="img-thumb">
                ${hasImg ? `<img src="${p.image}" alt="Preview">` : `No image`}
              </div>
              <div>
                <div class="img-actions">
                  <button type="button" class="img-btn img-upload-btn" data-id="${p.id}">Upload image</button>
                  ${hasImg ? `<button type="button" class="img-btn img-remove-btn" data-id="${p.id}">Remove</button>` : ``}
                </div>
                <div class="img-hint">PNG/JPG/WebP • up to ${FILE_MAX_MB}MB • we'll resize and optimize automatically</div>
              </div>
            </div>
            <input class="img-input" data-id="${p.id}" type="file" accept="image/*" hidden />
          </div>
        </div>
      </div>
    `;
  }

  // CRUD functions
  function addProject(){
    const id = ++projectIdCounter;
    projects.unshift({ id, title:'', description:'', link:'', image:'' });
    editingId = id;
    render();
    setTimeout(() => {
      const first = document.querySelector(`#proj-${id} .form-input[data-field="title"]`);
      if (first) first.focus();
    }, 60);
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
    saveAll();
    render();
  }

  // Update field
  function updateProjectField(id, field, value){
    const p = projects.find(x => x.id === id);
    if (!p) return;
    if (field === 'title')        p.title = value.slice(0, TITLE_MAX);
    else if (field === 'description') p.description = value.slice(0, DESC_MAX);
    else if (field === 'link')    p.link = value.trim();
  }

  // Image processing functions (condensed for brevity)
  async function handlePickedFile(id, file){
    if (!file || !file.type.startsWith('image/')) { toast('Please choose a valid image.'); return; }
    try {
      const dataUrl = await compressImage(file);
      const p = projects.find(x => x.id === id);
      if (p) {
        p.image = dataUrl;
        saveAll();
        render();
        toast('Image added');
      }
    } catch {
      toast('Could not process that image.');
    }
  }

  async function compressImage(file) {
    return new Promise((resolve) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const img = new Image();
        img.onload = () => {
          const canvas = document.createElement('canvas');
          const ratio = Math.min(IMG_MAX_W / img.width, IMG_MAX_H / img.height);
          canvas.width = img.width * ratio;
          canvas.height = img.height * ratio;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
          resolve(canvas.toDataURL('image/jpeg', IMG_QUALITY));
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    });
  }

  // Continue state
  function updateContinueState(){ 
    continueBtn.disabled = (editingId != null); 
  }

  // Event listeners (condensed)
  projectsList.addEventListener('input', (e) => {
    const el = e.target;
    if (!el.dataset?.field) return;
    updateProjectField(Number(el.dataset.id), el.dataset.field, el.value);
    
    // Update character counters
    if (el.dataset.field === 'title') {
      const ct = document.getElementById(`ct-title-${el.dataset.id}`);
      if (ct) ct.textContent = `${el.value.length}/${TITLE_MAX}`;
    }
    if (el.dataset.field === 'description') {
      const ct = document.getElementById(`ct-desc-${el.dataset.id}`);
      if (ct) ct.textContent = `${el.value.length}/${DESC_MAX}`;
    }
    saveAll();
  });

  // Image upload events
  projectsList.addEventListener('click', (e) => {
    if (e.target.closest('.img-upload-btn')) {
      const id = Number(e.target.dataset.id);
      document.querySelector(`.img-input[data-id="${id}"]`).click();
    }
    if (e.target.closest('.img-remove-btn')) {
      const id = Number(e.target.dataset.id);
      const p = projects.find(x => x.id === id);
      if (p) { p.image = ''; saveAll(); render(); }
    }
  });

  projectsList.addEventListener('change', async (e) => {
    if (e.target.matches('.img-input')) {
      const file = e.target.files[0];
      if (file) await handlePickedFile(Number(e.target.dataset.id), file);
      e.target.value = '';
    }
  });

  // AI helpers
  generateBtn.addEventListener('click', () => {
    if (projects.length === 0) addProject();
    toast('URL generation feature coming soon!');
  });

  enhanceBtn.addEventListener('click', () => {
    if (projects.length === 0) addProject();
    toast('Description enhancement coming soon!');
  });

  // Main event listeners
  addBtn.addEventListener('click', addProject);
  skipBtn.addEventListener('click', () => {
    formEl.submit();
  });

  formEl.addEventListener('submit', (e) => {
    e.preventDefault();
    saveAll();
    formEl.submit();
  });

  // Expose global functions
  window.editProject = editProject;
  window.removeProject = removeProject;
  window.saveProject = saveProject;
  window.cancelEdit = cancelEdit;

  // Initialize
  loadAll();
  render();
})();
</script>
@endpush