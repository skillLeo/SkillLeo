@props(['modalPortfolios' => [], 'userSkills' => [],'username'])

<x-modals.base-modal id="managePortfoliosModal" title="Portfolio Projects" size="lg">
  <form id="managePortfoliosForm" method="POST" action="{{ route('tenant.portfolio.update',$username) }}">
    @csrf
    @method('PUT')

    {{-- Sort controls (unchanged) --}}
    <div class="pf-sort-controls">
      <div class="pf-sort-left">
        <span class="pf-sort-label">Sort by:</span>
        <div class="pf-sort-tabs">
          <button type="button" class="pf-sort-tab active" data-sort="custom" onclick="pfSetSortMode('custom')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
              <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            Custom Order
          </button>
          <button type="button" class="pf-sort-tab" data-sort="newest" onclick="pfSetSortMode('newest')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Newest First
          </button>
        </div>
      </div>
      <div class="pf-sort-hint" id="pfSortHintCustom">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 5v14M5 12l7 7 7-7"/>
        </svg>
        <span>Drag to reorder projects</span>
      </div>
      <div class="pf-sort-hint" id="pfSortHintNewest" style="display:none;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>Sorted by creation date</span>
      </div>
    </div>

    {{-- List + empty --}}
    <div class="pf-list" id="pfManageList"></div>
    <div class="pf-empty" id="pfManageEmpty">
      <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <rect x="2" y="7" width="20" height="14" rx="2"/>
        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
      </svg>
      <p>No projects yet</p>
      <span>Add your first project using the “Add Project” button on the card.</span>
    </div>

    <input type="hidden" name="portfolios" id="pfManageData">
  </form>

  <x-slot:footer>
    <button type="button" class="btn-modal btn-secondary" onclick="closeModal('managePortfoliosModal')">Cancel</button>
    <button type="submit" form="managePortfoliosForm" class="btn-modal btn-primary" id="pfManageSaveBtn">Save Changes</button>
  </x-slot:footer>
</x-modals.base-modal>

{{-- JS (trimmed to only manage/edit/sort; no "add new" entry) --}}
<script>
(function managePortfolios(){
  'use strict';

  const existing = @json($modalPortfolios ?? []);
  const skills   = @json($userSkills ?? []);
  let items = [];
  let editingId = null;
  let sortMode = 'custom';
  let dragged = null;

  const el = {
    list: document.getElementById('pfManageList'),
    empty: document.getElementById('pfManageEmpty'),
    saveBtn: document.getElementById('pfManageSaveBtn'),
    data: document.getElementById('pfManageData'),
    hintC: document.getElementById('pfSortHintCustom'),
    hintN: document.getElementById('pfSortHintNewest'),
  };

  window.pfSetSortMode = function(mode){
    sortMode = mode;
    document.querySelectorAll('.pf-sort-tab').forEach(t => t.classList.toggle('active', t.dataset.sort === mode));
    el.hintC.style.display = mode === 'custom' ? 'flex' : 'none';
    el.hintN.style.display = mode === 'newest' ? 'flex' : 'none';
    if (mode === 'newest') items.sort((a,b)=>(b.db_id||0)-(a.db_id||0));
    render();
  };

  function load(){
    items = existing.map((p,i)=>({
      id: p.id || i+1,
      db_id: p.id || null,
      title: p.title || '',
      description: p.description || '',
      link: p.link || '',
      image: p.image_url || '',
      image_path: p.image_path || '',
      image_disk: p.image_disk || 'public',
      skill_ids: Array.isArray(p.skill_ids) ? p.skill_ids : [],
      position: p.position ?? i
    }));
  }

  function saveState(){
    el.data.value = JSON.stringify(items.map(p => ({
      db_id:p.db_id, title:p.title, description:p.description, link:p.link,
      image:p.image, image_path:p.image_path, image_disk:p.image_disk, skill_ids:p.skill_ids
    })));
    el.saveBtn.disabled = items.length === 0 || editingId !== null;
  }

  function previewCard(p){
    const host = (()=>{ try{ return (new URL(p.link.startsWith('http')?p.link:`https://${p.link}`)).hostname.replace(/^www\./,''); }catch{return ''} })();
    const skillNames=(p.skill_ids||[]).map(id=>skills.find(s=>s.id===id)).filter(Boolean).map(s=>s.name).slice(0,3);
    const drag = sortMode==='custom' && editingId===null;
    return `
      <div class="pf-preview ${drag?'draggable':''}" id="mport-${p.id}">
        ${drag?`<div class="pf-drag-handle" title="Drag"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></div>`:''}
        <div class="pf-preview-main">
          ${p.image?`<div class="pf-preview-img"><img src="${p.image}" alt=""></div>`:`<div class="pf-preview-img-empty"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div>`}
          <div class="pf-preview-content">
            <div class="pf-preview-header">
              <h4 class="pf-preview-title">${(p.title||'Untitled Project')}</h4>
              ${skillNames.length?`<div class="pf-preview-skills">${skillNames.map(n=>`<span class="pf-mini-skill">${n}</span>`).join('')}</div>`:''}
            </div>
            ${p.description?`<p class="pf-preview-desc">${p.description.length>100?p.description.slice(0,100)+'…':p.description}</p>`:''}
            ${host?`<div class="pf-preview-link"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg><span>${host}</span></div>`:''}
          </div>
        </div>
        <div class="pf-preview-actions">
          <button type="button" class="pf-action-btn edit"   onclick="pfEdit(${p.id})" title="Edit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
          <button type="button" class="pf-action-btn delete" onclick="pfRemove(${p.id})" title="Delete"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
        </div>
      </div>`;
  }

  function editCard(p){
    const selected = (p.skill_ids||[]).map(id=>skills.find(s=>s.id===id)).filter(Boolean);
    return `
      <div class="pf-edit-card" id="mport-${p.id}">
        <div class="pf-edit-header">
          <h4>Edit Project</h4>
          <button type="button" class="pf-close-edit" onclick="pfCancel(${p.id})" title="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <div class="pf-edit-body">
          <div class="pf-form-grid">
            <div class="pf-form-col">
              <div class="pf-field">
                <label class="pf-label">Project Title <span class="pf-required">*</span></label>
                <input id="mtitle-${p.id}" type="text" class="pf-input" maxlength="80"
                       value="${p.title||''}" oninput="pfUpdate(${p.id},'title',this.value)">
              </div>
              <div class="pf-field">
                <label class="pf-label">Description <span class="pf-required">*</span></label>
                <textarea class="pf-textarea" rows="4" maxlength="280"
                          oninput="pfUpdate(${p.id},'description',this.value)">${p.description||''}</textarea>
              </div>
              <div class="pf-field">
                <label class="pf-label">Project Link</label>
                <input type="url" class="pf-input" value="${p.link||''}" oninput="pfUpdate(${p.id},'link',this.value)">
              </div>
              <div class="pf-field pf-skills-dropdown">
                <label class="pf-label">Technologies & Skills</label>
                <div class="pf-skills-dropdown-menu" style="max-height:240px;overflow:auto;">
                  ${skills.map(s=>`
                    <label class="pf-skill-option">
                      <input type="checkbox" ${p.skill_ids.includes(s.id)?'checked':''}
                             onclick="pfToggleSkill(${p.id},${s.id})">
                      <span>${s.name}</span>
                    </label>`).join('')}
                </div>
                <div class="pf-skills-selected">
                  ${selected.length?selected.map(s=>`<span class="pf-skill-tag">${s.name}<button type="button" onclick="pfToggleSkill(${p.id},${s.id})">×</button></span>`).join(''):`<div class="pf-no-skills-msg">No skills selected</div>`}
                </div>
              </div>
            </div>
            <div class="pf-form-col">
              <div class="pf-field">
                <label class="pf-label">Cover Image</label>
                <div class="pf-img-upload">
                  ${p.image?`
                    <div class="pf-img-preview">
                      <img src="${p.image}" alt="">
                      <div class="pf-img-overlay">
                        <button type="button" class="pf-img-remove" onclick="pfRemoveImage(${p.id})">Remove</button>
                      </div>
                    </div>`:`
                    <div class="pf-img-empty" onclick="document.getElementById('mimg-${p.id}').click()">
                      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                      </svg>
                      <p>Click to upload</p><span>PNG, JPG up to 10MB</span>
                    </div>`}
                  <input type="file" id="mimg-${p.id}" accept="image/*" hidden onchange="pfHandleImage(${p.id}, this)">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="pf-edit-footer">
          <button type="button" class="pf-btn secondary" onclick="pfCancel(${p.id})">Cancel</button>
          <button type="button" class="pf-btn primary"   onclick="pfSave(${p.id})">Save Project</button>
        </div>
      </div>`;
  }

  function render(){
    if (!items.length){
      el.empty.style.display='flex';
      el.list.style.display='none';
    } else {
      el.empty.style.display='none';
      el.list.style.display='block';
      el.list.innerHTML = items.map(p => editingId===p.id ? editCard(p) : previewCard(p)).join('');
      if (sortMode==='custom' && editingId===null) enableDrag();
    }
    saveState();
    if (editingId!==null) setTimeout(()=>document.getElementById(`mtitle-${editingId}`)?.focus(),0);
  }

  function enableDrag(){
    el.list.querySelectorAll('.pf-preview').forEach((node,idx)=>{
      node.draggable = true;
      node.dataset.index = idx;
      node.addEventListener('dragstart', e => { dragged=node; node.classList.add('dragging'); e.dataTransfer.effectAllowed='move'; });
      node.addEventListener('dragover', e => { e.preventDefault(); const a=dragged, b=node; if(a===b) return;
        const ai=[...a.parentNode.children].indexOf(a), bi=[...b.parentNode.children].indexOf(b);
        if(ai<bi) b.after(a); else b.before(a);
      });
      node.addEventListener('drop', () => {
        const newOrder=[...el.list.querySelectorAll('.pf-preview')].map(n=>{
          const id=parseInt(n.id.replace('mport-','')); return items.find(x=>x.id===id);
        });
        items=newOrder; saveState();
      });
      node.addEventListener('dragend', ()=>{ node.classList.remove('dragging'); });
    });
  }

  // exposed actions
  window.pfEdit   = id => { editingId=id; render(); };
  window.pfCancel = id => { if (editingId===id) editingId=null; render(); };
  window.pfSave   = id => { const p=items.find(x=>x.id===id); if(!p||!p.title?.trim()||!p.description?.trim()){ alert('Title & description required.'); return;} editingId=null; render(); };
  window.pfRemove = id => { if(confirm('Remove this project?')){ items=items.filter(x=>x.id!==id); if(editingId===id) editingId=null; render(); } };
  window.pfUpdate = (id,field,val)=>{ const p=items.find(x=>x.id===id); if(!p) return; p[field]=val; saveState(); };
  window.pfToggleSkill = (id,sid)=>{ const p=items.find(x=>x.id===id); if(!p) return; const i=p.skill_ids.indexOf(sid); if(i>-1) p.skill_ids.splice(i,1); else p.skill_ids.push(sid); render(); };
  window.pfHandleImage = (id,input)=>{ const f=input.files?.[0]; if(!f) return; const r=new FileReader(); r.onload=e=>{ const p=items.find(x=>x.id===id); if(p){ p.image=e.target.result; render(); } }; r.readAsDataURL(f); input.value=''; };
  window.pfRemoveImage = id => { const p=items.find(x=>x.id===id); if(p){ p.image=''; render(); } };

  load(); render();
})();
</script>
