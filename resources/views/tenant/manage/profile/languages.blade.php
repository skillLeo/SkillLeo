@extends('tenant.manage.app')

@section('title', 'Manage Languages - ' . $user->name)

@section('main')
    @if (session('success'))
        <div class="alert alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1 class="page-title">Languages</h1>
            <p class="page-subtitle">Manage your language proficiency</p>
        </div>
        <div class="page-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('editLanguagesModal')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Edit Languages
            </button>
        </div>
    </div>

    <div class="content-section">
        <div class="languages-list">
            @forelse($owner->languages as $language)
                @php
                    $level = optional($language->pivot)->level ?? ($language->level ?? 0);
                    $level = max(0, min(4, (int) $level));
                    $percent = ($level / 4) * 100;
                @endphp
        
                <div class="language-item" data-language-id="{{ $language->id }}">
                    <div class="language-content">
                        <div class="language-main">
                            <h4 class="language-name">{{ $language->name }}</h4>
        
                            <span class="language-level level-{{ $level }}">
                                @if($level >= 4)
                                    Native or Bilingual
                                @elseif($level === 3)
                                    Professional Working
                                @elseif($level === 2)
                                    Limited Working
                                @elseif($level === 1)
                                    Elementary
                                @else
                                    Beginner
                                @endif
                            </span>
                        </div>
        
                        <div class="language-proficiency">
                            <div class="proficiency-bar">
                                <div class="proficiency-fill" style="width: {{ $percent }}%"></div>
                            </div>
        
                            <span class="proficiency-dots">
                                @for($i = 1; $i <= 4; $i++)
                                    <span class="dot {{ $i <= $level ? 'active' : '' }}"></span>
                                @endfor
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    <h3>No languages added yet</h3>
                    <p>Add languages you speak to showcase your communication skills</p>
                    <button class="btn btn-primary" onclick="openModal('editLanguagesModal')">Add First Language</button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Include Edit Languages Modal --}}
    <x-modals.edits.edit-languages 
        :modal-languages="$modalLanguages" 
        :username="$username" 
    />
@endsection

@section('right')
    <div class="inspector-panel">
        <div class="inspector-header">
            <h3 class="inspector-title">🌍 Language Guide</h3>
            <p class="inspector-desc">Understanding proficiency levels</p>
        </div>

        <div class="help-card">
            <h4>Proficiency Levels</h4>
            <ul class="help-list">
                <li><strong>Elementary:</strong> Basic words and phrases</li>
                <li><strong>Limited Working:</strong> Simple conversations</li>
                <li><strong>Professional Working:</strong> Business fluency</li>
                <li><strong>Native/Bilingual:</strong> Full proficiency</li>
            </ul>
        </div>

        <div class="help-card accent">
            <h4>🎯 Pro Tip</h4>
            <p>Being multilingual increases job opportunities by 30%</p>
        </div>
    </div>
@endsection

@push('styles')
<style>
.languages-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.language-item {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.25s ease;
}

.language-item:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 16px rgba(var(--accent-rgb), 0.08);
}

.language-content {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.language-main {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.language-name {
    font-size: 17px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
}

.language-level {
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.language-level.level-1 { background: #fef3c7; color: #92400e; }
.language-level.level-2 { background: #dbeafe; color: #1e40af; }
.language-level.level-3 { background: #d1fae5; color: #065f46; }
.language-level.level-4 { background: #e0e7ff; color: #4338ca; }

.language-proficiency {
    display: flex;
    align-items: center;
    gap: 16px;
}

.proficiency-bar {
    flex: 1;
    height: 8px;
    background: var(--apc-bg);
    border-radius: 4px;
    overflow: hidden;
}

.proficiency-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent), var(--accent-dark));
    border-radius: 4px;
    transition: width 0.5s ease;
}

.proficiency-dots {
    display: flex;
    gap: 6px;
}

.proficiency-dots .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--border);
    transition: all 0.3s ease;
}

.proficiency-dots .dot.active {
    background: var(--accent);
    box-shadow: 0 0 8px rgba(var(--accent-rgb), 0.4);
}
</style>
@endpush
    
    @push('styles')
    <style>
    /* ============================================
       PROFESSIONAL LANGUAGES PAGE - CLEAN DESIGN
       ============================================ */
    
    /* ============ LANGUAGES LIST ============ */
    .languages-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .language-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }
    
    .language-item:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 16px rgba(var(--accent-rgb), 0.08);
        transform: translateY(-2px);
    }
    
    .language-item.selected {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1);
    }
    
    /* ============ LANGUAGE CONTENT ============ */
    .language-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    
    .language-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    
    .language-name {
        font-size: 17px;
        font-weight: 600;
        color: var(--text-heading);
        margin: 0;
        letter-spacing: -0.01em;
    }
    
    .language-level {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    
    .language-level.level-1 {
        background: #fef3c7;
        color: #92400e;
    }
    
    .language-level.level-2 {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .language-level.level-3 {
        background: #d1fae5;
        color: #065f46;
    }
    
    .language-level.level-4 {
        background: #e0e7ff;
        color: #4338ca;
    }
    
    /* ============ LANGUAGE PROFICIENCY ============ */
    .language-proficiency {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .proficiency-bar {
        flex: 1;
        height: 8px;
        background: var(--apc-bg);
        border-radius: 4px;
        overflow: hidden;
    }
    
    .proficiency-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent), var(--accent-dark));
        border-radius: 4px;
        transition: width 0.5s ease;
    }
    
    .proficiency-dots {
        display: flex;
        gap: 6px;
    }
    
    .proficiency-dots .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--border);
        transition: all 0.3s ease;
    }
    
    .proficiency-dots .dot.active {
        background: var(--accent);
        box-shadow: 0 0 8px rgba(var(--accent-rgb), 0.4);
    }
    
    /* ============ LANGUAGE DELETE ============ */
    .language-delete {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: 1px solid var(--border);
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 8px;
        opacity: 0;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    
    .language-item:hover .language-delete {
        opacity: 1;
    }
    
    .language-delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        transform: scale(1.1);
    }
    
    /* ============ PROFICIENCY SELECTOR ============ */
    .proficiency-selector {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .proficiency-btn {
        padding: 14px 16px;
        background: var(--card);
        border: 2px solid var(--border);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .proficiency-btn:hover {
        border-color: var(--accent);
        background: rgba(var(--accent-rgb), 0.02);
    }
    
    .proficiency-btn.active {
        border-color: var(--accent);
        background: rgba(var(--accent-rgb), 0.05);
    }
    
    .proficiency-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-heading);
        display: block;
    }
    
    .proficiency-desc {
        font-size: 12px;
        color: var(--text-muted);
        display: block;
    }
    
    .proficiency-visual {
        display: flex;
        gap: 6px;
        margin-top: 4px;
    }
    
    .proficiency-btn .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--border);
        transition: all 0.3s ease;
    }
    
    .proficiency-btn .dot.active {
        background: var(--accent);
    }
    
    .proficiency-btn.active .dot.active {
        box-shadow: 0 0 8px rgba(var(--accent-rgb), 0.4);
    }
    
    /* ============ EMPTY STATE ============ */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }
    
    .empty-state svg {
        color: var(--text-muted);
        opacity: 0.15;
        margin-bottom: 24px;
    }
    
    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-heading);
        margin: 0 0 12px 0;
        letter-spacing: -0.01em;
    }
    
    .empty-state p {
        font-size: 15px;
        color: var(--text-muted);
        margin: 0 0 28px 0;
        line-height: 1.5;
    }
    
    /* ============ RESPONSIVE ============ */
    @media (max-width: 768px) {
        .language-main {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    
        .language-proficiency {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
    
        .proficiency-dots {
            justify-content: center;
        }
    
        .language-delete {
            opacity: 1;
        }
    }
    
    /* ============ DARK MODE ============ */
    @media (prefers-color-scheme: dark) {
        .language-level.level-1 { background: rgba(253, 230, 138, 0.2); color: #fbbf24; }
        .language-level.level-2 { background: rgba(147, 197, 253, 0.2); color: #60a5fa; }
        .language-level.level-3 { background: rgba(167, 243, 208, 0.2); color: #34d399; }
        .language-level.level-4 { background: rgba(199, 210, 254, 0.2); color: #818cf8; }
    }
    </style>
    @endpush
    
    @push('scripts')
    @php
        $languagePayload = $owner->languages->map(function ($l) {
            return [
                'id' => $l->id,
                'name' => $l->name,
                'level' => $l->level,
                'position' => $l->position,
            ];
        })->values();
    @endphp
    
    <script>
    let languageArray = @json($languagePayload);
    
    function addNewLanguage() {
        document.getElementById('inspectorDefault').style.display = 'none';
        document.getElementById('inspectorForm').style.display = 'block';
        document.getElementById('formTitle').textContent = 'Add Language';
        document.getElementById('languageId').value = '';
        document.getElementById('languageName').value = '';
        document.getElementById('languageLevel').value = '2';
        
        document.querySelectorAll('.proficiency-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector('.proficiency-btn[data-level="2"]').classList.add('active');
        
        document.getElementById('languageName').focus();
    }
    
    function selectLanguage(id) {
        document.querySelectorAll('.language-item').forEach(item => item.classList.remove('selected'));
        const item = document.querySelector(`[data-language-id="${id}"]`);
        if (item) item.classList.add('selected');
        
        const language = languageArray.find(l => l.id === id);
        if (!language) return;
        
        document.getElementById('inspectorDefault').style.display = 'none';
        document.getElementById('inspectorForm').style.display = 'block';
        document.getElementById('formTitle').textContent = 'Edit Language';
        document.getElementById('languageId').value = id;
        document.getElementById('languageName').value = language.name;
        document.getElementById('languageLevel').value = language.level;
        
        document.querySelectorAll('.proficiency-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.proficiency-btn[data-level="${language.level}"]`)?.classList.add('active');
    }
    
    function saveLanguage() {
        const id = document.getElementById('languageId').value;
        const name = document.getElementById('languageName').value.trim();
        const level = parseInt(document.getElementById('languageLevel').value);
        
        if (!name) {
            alert('Please enter a language name');
            return;
        }
        
        const languageData = {
            name,
            level
        };
        
        if (id) {
            const index = languageArray.findIndex(l => l.id == id);
            if (index !== -1) {
                languageArray[index] = { ...languageArray[index], ...languageData };
            }
        } else {
            languageArray.push({
                id: Date.now(),
                ...languageData,
                position: languageArray.length
            });
        }
        
        submitLanguages();
    }
    
    function deleteLanguage(id, name) {
        if (confirm(`Delete ${name}?`)) {
            languageArray = languageArray.filter(l => l.id != id);
            submitLanguages();
        }
    }
    
    function submitLanguages() {
        document.getElementById('languageData').value = JSON.stringify(languageArray);
        document.getElementById('languageUpdateForm').submit();
    }
    
    function closeInspector() {
        document.getElementById('inspectorForm').style.display = 'none';
        document.getElementById('inspectorDefault').style.display = 'block';
        document.querySelectorAll('.language-item').forEach(item => item.classList.remove('selected'));
    }
    
    // Proficiency selector
    document.querySelectorAll('.proficiency-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.proficiency-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('languageLevel').value = btn.dataset.level;
        });
    });
    
    // Search
    document.getElementById('languageSearch')?.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.language-item').forEach(item => {
            const name = item.querySelector('.language-name').textContent.toLowerCase();
            item.style.display = name.includes(query) ? 'flex' : 'none';
        });
    });
    
    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.target.matches('input, textarea, select')) return;
        if (e.key.toLowerCase() === 'a') { e.preventDefault(); addNewLanguage(); }
        if (e.key === 'Escape') closeInspector();
    });
    </script>
    @endpush