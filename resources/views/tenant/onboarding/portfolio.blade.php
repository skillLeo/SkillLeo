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

    <x-onboarding.alert type="info">
        Optional but recommended for better visibility
        <button type="button" class="btn btn-secondary" id="skipBtn" style="margin-top: var(--space-sm);">Skip for now</button>
    </x-onboarding.alert>

    <div class="projects-list" id="projectsList">
        <div class="empty-state" id="emptyState">
            <div style="font-size: 2rem; margin-bottom: var(--space-sm);">📁</div>
            <div style="font-weight: var(--fw-semibold); margin-bottom: var(--space-xs);">No projects yet</div>
            <div style="color: var(--text-muted); font-size: var(--fs-subtle);">Add your first project to get started</div>
        </div>
    </div>

    <button type="button" class="btn-add" id="addBtn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Add project
    </button>

    <input type="hidden" name="projects" id="projectsData">

    <div style="background: var(--accent-light); border: 1px solid var(--accent); border-radius: var(--radius); padding: var(--space-md); margin-bottom: var(--space-lg);">
        <strong style="color: var(--accent); font-size: var(--fs-subtle); display: block; margin-bottom: var(--space-sm);">AI Assistant</strong>
        <div style="display: flex; gap: var(--space-sm);">
            <button type="button" class="btn btn-secondary" style="flex: 1; font-size: var(--fs-subtle);" id="generateBtn">Generate from URL</button>
            <button type="button" class="btn btn-secondary" style="flex: 1; font-size: var(--fs-subtle);" id="enhanceBtn">Enhance text</button>
        </div>
    </div>

    <x-onboarding.form-footer 
skipUrl="{{ route('tenant.onboarding.education') }}" backUrl="{{ route('tenant.onboarding.experience') }}" />
</form>

@endsection

@push('styles')
<style>
.projects-list { margin: var(--space-lg) 0; }

.project-card {
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: var(--space-lg);
    background: var(--card);
    position: relative;
    margin-bottom: var(--space-md);
    transition: all var(--transition-base);
}

.project-card:hover { box-shadow: var(--shadow-sm); }
.edit-card { border-color: var(--accent); background: var(--apc-bg); }

.card-actions {
    position: absolute;
    top: var(--space-lg);
    right: var(--space-lg);
    display: flex;
    gap: var(--space-sm);
}

.card-edit, .card-remove {
    width: 28px;
    height: 28px;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-base);
}

.card-edit:hover {
    background: var(--accent);
    color: var(--btn-text-primary);
    border-color: var(--accent);
}

.card-remove:hover {
    background: var(--error);
    color: var(--btn-text-primary);
    border-color: var(--error);
}

.card-media {
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: var(--space-md);
    background: var(--apc-bg);
    aspect-ratio: 16 / 9;
}

.card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-title {
    font-size: var(--fs-title);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin-bottom: var(--space-sm);
}

.card-description {
    color: var(--text-body);
    line-height: var(--lh-relaxed);
    margin-bottom: var(--space-md);
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-link {
    color: var(--accent);
    text-decoration: none;
    font-weight: var(--fw-medium);
    font-size: var(--fs-subtle);
    transition: color var(--transition-base);
}

.card-link:hover { color: var(--accent-dark); }

.card-tech {
    font-size: var(--fs-micro);
    color: var(--text-muted);
    background: var(--apc-bg);
    padding: 4px 10px;
    border-radius: 12px;
}

.img-dropzone {
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    padding: var(--space-md);
    background: var(--apc-bg);
    cursor: pointer;
    transition: all var(--transition-base);
}

.img-dropzone:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.dz-inner {
    display: flex;
    gap: var(--space-md);
    align-items: center;
}

.img-thumb {
    width: 100px;
    height: 60px;
    border-radius: var(--radius);
    overflow: hidden;
    background: var(--card);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--fs-micro);
    color: var(--text-muted);
    flex-shrink: 0;
}

.img-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.img-actions {
    display: flex;
    gap: var(--space-sm);
    flex-wrap: wrap;
}

.img-btn {
    background: var(--card);
    border: 1px solid var(--border);
    color: var(--text-body);
    padding: 6px 12px;
    border-radius: var(--radius);
    font-weight: var(--fw-medium);
    font-size: var(--fs-micro);
    cursor: pointer;
    transition: all var(--transition-base);
}

.img-btn:hover {
    background: var(--accent);
    color: var(--btn-text-primary);
    border-color: var(--accent);
}

.img-hint {
    font-size: var(--fs-micro);
    color: var(--text-subtle);
    margin-top: 4px;
}

.btn-add {
    width: 100%;
    padding: var(--space-md) var(--space-lg);
    background: var(--card);
    color: var(--text-body);
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
    margin-bottom: var(--space-lg);
    transition: all var(--transition-base);
}

.btn-add:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    color: var(--accent);
}

.form-header-actions {
    display: flex;
    gap: var(--space-sm);
    justify-content: flex-end;
    margin-bottom: var(--space-md);
}

.save-btn, .cancel-btn {
    padding: 8px 16px;
    border-radius: var(--radius);
    font-weight: var(--fw-medium);
    font-size: var(--fs-subtle);
    cursor: pointer;
    transition: all var(--transition-base);
}

.save-btn {
    background: var(--success);
    color: var(--btn-text-primary);
    border: none;
}

.cancel-btn {
    background: var(--card);
    color: var(--text-body);
    border: 1px solid var(--border);
}

@media (max-width: 768px) {
    .dz-inner { flex-direction: column; }
    .img-thumb { width: 100%; height: 120px; }
}
</style>
@endpush

 
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