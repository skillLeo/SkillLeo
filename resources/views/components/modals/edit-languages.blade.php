<x-modals.base-modal id="editLanguagesModal" title="Languages" size="md">
    <form id="languagesForm" method="POST" action="#">
        @csrf
        @method('PUT')

        <div class="lang-input-section">
            <div class="input-grid">
                <input 
                    type="text" 
                    id="langInput" 
                    class="form-input" 
                    placeholder="e.g., English, Spanish..."
                    maxlength="30"
                >
                <select id="proficiencySelect" class="form-select">
                    <option value="Native">Native or Bilingual</option>
                    <option value="Fluent">Professional Working</option>
                    <option value="Intermediate">Limited Working</option>
                    <option value="Basic">Elementary</option>
                </select>
                <button type="button" class="btn-add-lang" onclick="addLanguage()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Add
                </button>
            </div>
            <p class="form-hint">Add languages you can communicate in professionally</p>
        </div>

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
</x-modals.base-modal>

<style>
.lang-input-section { margin-bottom: var(--space-lg); }

.input-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 12px;
    margin-bottom: 8px;
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

.btn-add-lang:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

.languages-display {
    min-height: 120px;
    padding: var(--space-md);
    border: 1px dashed var(--border);
    border-radius: var(--radius);
    background: var(--apc-bg);
}

.lang-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100px;
    color: var(--text-muted);
    font-size: var(--fs-subtle);
}

.lang-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.lang-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.lang-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.lang-name {
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
}

.lang-level {
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

.lang-remove {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.2s ease;
}

.lang-remove:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
}

@media (max-width: 640px) {
    .input-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
let languages = [];

function addLanguage() {
    const input = document.getElementById('langInput');
    const select = document.getElementById('proficiencySelect');
    
    const name = input.value.trim();
    const level = select.value;
    
    if (!name) {
        input.focus();
        return;
    }
    
    // Check duplicate
    if (languages.some(l => l.name.toLowerCase() === name.toLowerCase())) {
        alert('Language already added');
        input.value = '';
        input.focus();
        return;
    }
    
    languages.push({ name, level });
    input.value = '';
    input.focus();
    renderLanguages();
}

function removeLanguage(index) {
    languages.splice(index, 1);
    renderLanguages();
}

function renderLanguages() {
    const empty = document.getElementById('langEmpty');
    const list = document.getElementById('langList');
    const data = document.getElementById('langData');
    
    if (languages.length === 0) {
        empty.style.display = 'flex';
        list.style.display = 'none';
    } else {
        empty.style.display = 'none';
        list.style.display = 'flex';
        list.innerHTML = languages.map((lang, i) => `
            <div class="lang-item">
                <div class="lang-info">
                    <span class="lang-name">${lang.name}</span>
                    <span class="lang-level">— ${lang.level}</span>
                </div>
                <button type="button" class="lang-remove" onclick="removeLanguage(${i})">×</button>
            </div>
        `).join('');
    }
    
    data.value = JSON.stringify(languages);
}

document.getElementById('langInput')?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        addLanguage();
    }
});

renderLanguages();
</script>