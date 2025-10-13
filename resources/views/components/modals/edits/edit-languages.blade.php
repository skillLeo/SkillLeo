@props(['modalLanguages' => []])

<x-modals.edits.base-modal id="editLanguagesModal" title="Languages" size="md">
    <form id="languagesForm" method="POST" action="{{ route('tenant.language.update') }}">
        @csrf
        @method('PUT')

        {{-- Input row, same vibe as Skills --}}
        <div class="lang-input-section">
            <div class="input-grid">
                <input 
                    type="text" 
                    id="langInput" 
                    class="form-input" 
                    placeholder="e.g., English, Spanish..."
                    maxlength="30"
                    autocomplete="off"
                >
                <select id="proficiencySelect" class="form-select">
                    <option value="4">Native or Bilingual</option>
                    <option value="3">Professional Working</option>
                    <option value="2">Limited Working</option>
                    <option value="1">Elementary</option>
                </select>
                <button type="button" class="btn-add-lang" id="addLangBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Add
                </button>
            </div>
            <p class="form-hint">Add languages you can communicate in professionally</p>
        </div>

        {{-- List (preloaded from backend) --}}
        <div class="languages-display" id="langDisplay">
            <div class="lang-empty" id="langEmpty">No languages added yet</div>
            <div class="lang-list" id="langList"></div>
        </div>

        <input type="hidden" name="languages" id="langData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editLanguagesModal')">Cancel</button>
        <button type="submit" form="languagesForm" class="btn-modal btn-save">Save</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<style>
/* --- Layout & inputs --- */
.lang-input-section { margin-bottom: var(--space-lg); }

.input-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 12px;
    margin-bottom: 8px;
}

.form-input, .form-select {
    height: 44px;
    border: 1.5px solid var(--input-border);
    background: var(--input-bg);
    color: var(--input-text);
    border-radius: var(--radius);
    padding: 0 12px;
}

.btn-add-lang {
    padding: 10px 20px;
    background: var(--accent);
    color: var(--btn-text-primary);
    border: none;
    border-radius: var(--radius);
    font-weight: var(--fw-semibold);
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-add-lang:hover { background: var(--accent-dark); transform: translateY(-1px); }

/* --- List --- */
.languages-display {
    min-height: 160px;
    padding: var(--space-md);
    border: 1px dashed var(--border);
    border-radius: var(--radius);
    background: var(--apc-bg);
}
.lang-empty {
    display: flex; align-items: center; justify-content: center;
    min-height: 120px; color: var(--text-muted); font-size: var(--fs-subtle);
}
.lang-list { display: flex; flex-direction: column; gap: 10px; }

.lang-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 14px; background: var(--card);
    border: 1px solid var(--border); border-radius: var(--radius);
}

.lang-info { display: flex; align-items: center; gap: 12px; min-width: 0; }
.lang-name { font-weight: var(--fw-semibold); color: var(--text-heading); }
.lang-badge {
    font-size: 12px; padding: 4px 10px; border-radius: 6px; font-weight: 600; white-space: nowrap;
}
.badge-1 { background: rgba(234,179,8,.15); color:#a16207; }   /* Elementary */
.badge-2 { background: rgba(59,130,246,.15); color:#2563eb; }   /* Limited */
.badge-3 { background: rgba(16,185,129,.15); color:#059669; }   /* Professional */
.badge-4 { background: rgba(99,102,241,.15); color:#4f46e5; }   /* Native */

.lang-actions { display:flex; gap:6px; }

.lang-btn, .lang-remove {
    width: 32px; height: 32px; border: none; background: transparent;
    color: var(--text-muted); border-radius: 6px; cursor: pointer; display:flex; align-items:center; justify-content:center;
}
.lang-btn:hover { background: var(--apc-bg); color: var(--accent); }
.lang-remove:hover { background: rgba(239,68,68,.1); color: #dc2626; }

@media (max-width: 640px) { .input-grid { grid-template-columns: 1fr; } }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // This now has your old languages:
        const initialLanguages = @json($modalLanguages);
    
        const LEVEL_LABELS = {1:'Elementary',2:'Limited Working',3:'Professional Working',4:'Native or Bilingual'};
    
        let languages = (initialLanguages || []).map((l,i)=>({
            id: l.id ?? null,
            name: String(l.name || '').trim(),
            level: Math.max(1, Math.min(4, parseInt(l.level || 2))),
            position: Number.isFinite(l.position) ? l.position : i
        }));
    

    // ===== Elements =====
    const input  = document.getElementById('langInput');
    const select = document.getElementById('proficiencySelect');
    const addBtn = document.getElementById('addLangBtn');
    const empty  = document.getElementById('langEmpty');
    const list   = document.getElementById('langList');
    const hidden = document.getElementById('langData');

    // ===== Helpers =====
    function clampLevel(l){ return Math.max(1, Math.min(4, l || 2)); }
    function serialize(){ return languages.map((l,idx)=>({ id:l.id ?? null, name:l.name, level:clampLevel(l.level), position: idx })); }

    // ===== Render =====
    function render(){
        if (!languages.length){
            empty.style.display = 'flex';
            list.style.display = 'none';
        } else {
            empty.style.display = 'none';
            list.style.display = 'flex';
            list.innerHTML = languages.map((l,idx)=>`
                <div class="lang-item" data-idx="${idx}">
                    <div class="lang-info">
                        <span class="lang-name">${escapeHtml(l.name)}</span>
                        <span class="lang-badge badge-${l.level}" title="Click the rotate icon to change level">${LEVEL_LABELS[l.level]}</span>
                    </div>
                    <div class="lang-actions">
                        <button type="button" class="lang-btn" data-action="cycle" title="Change proficiency">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                        </button>
                        <button type="button" class="lang-remove" data-action="remove" title="Remove">×</button>
                    </div>
                </div>
            `).join('');
        }
        hidden.value = JSON.stringify(serialize());
    }

    function escapeHtml(t){ const d=document.createElement('div'); d.textContent=t; return d.innerHTML; }

    // ===== Actions =====
    function addLanguage(){
        const name = (input.value || '').trim();
        const lvl  = clampLevel(parseInt(select.value));
        if (!name){ input.focus(); return; }
        if (languages.some(l => l.name.toLowerCase() === name.toLowerCase())){
            alert('Language already added'); input.value=''; input.focus(); return;
        }
        languages.push({ id:null, name, level:lvl, position: languages.length });
        input.value=''; input.focus();
        render();
    }
    function cycleLevel(idx){
        languages[idx].level = languages[idx].level === 4 ? 1 : (languages[idx].level + 1);
        render();
    }
    function removeLanguage(idx){
        languages.splice(idx,1);
        render();
    }

    // ===== Events =====
    addBtn.addEventListener('click', addLanguage);
    input.addEventListener('keydown', e => { if (e.key === 'Enter'){ e.preventDefault(); addLanguage(); } });
    list.addEventListener('click', e => {
        const row = e.target.closest('.lang-item'); if (!row) return;
        const idx = parseInt(row.dataset.idx,10);
        const btn = e.target.closest('[data-action]'); if (!btn) return;
        const action = btn.dataset.action;
        if (action === 'cycle') cycleLevel(idx);
        if (action === 'remove') removeLanguage(idx);
    });

    // Initial render with old languages
    render();
});
</script>
