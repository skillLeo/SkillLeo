<section class="linkedin-projects-section">
    @php
        use Illuminate\Support\Str;
        $totalProjects = count($portfolios ?? []);
        $visibleProjects = collect($portfolios ?? [])->take(3);
    @endphp

    {{-- Section Header --}}
    <div class="lps-header">
        <h2 class="lps-title">Projects</h2>
        <div class="lps-actions">
            <button class="lps-btn-add" onclick="openModal('editPortfolioModal')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
            <button class="lps-btn-edit edit-card" aria-label="Edit projects">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Filter Tabs - Only show if there are tags --}}
    @if(count($allTags ?? []) > 0)
        <div class="lps-filter-section">
            <div class="lps-filter-tabs" id="filterTabs">
                <button class="lps-filter-btn active" data-tag="all" onclick="filterByTag('all', this)">
                    All
                </button>
                
                @foreach($visibleTags ?? [] as $tag)
                    <button class="lps-filter-btn" data-tag="{{ Str::slug($tag) }}" onclick="filterByTag('{{ Str::slug($tag) }}', this)">
                        {{ $tag }}
                    </button>
                @endforeach

                @if(count($hiddenTags ?? []) > 0)
                    <button class="lps-filter-more" onclick="openFilterModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="1"/>
                            <circle cx="12" cy="5" r="1"/>
                            <circle cx="12" cy="19" r="1"/>
                        </svg>
                        More
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Projects List --}}
    <div class="lps-list" id="portfolioList">
        @forelse ($visibleProjects as $p)
            <div class="portfolio-item" data-tags="{{ implode(',', array_map(fn($t) => Str::slug($t), $p['tags'] ?? [])) }}">
                <x-cards.portfolio-card :portfolio="$p" />
            </div>
        @empty
            <div class="lps-empty">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                <p>Showcase your work</p>
                <span>Add projects to highlight your portfolio</span>
                <button class="lps-empty-btn" onclick="openModal('editPortfolioModal')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add project
                </button>
            </div>
        @endforelse
    </div>

    {{-- See All Button --}}
    @if ($totalProjects > 3)
        <div class="lps-footer">
            <button class="lps-see-all" onclick="showAllProjects()">
                Show all {{ $totalProjects }} projects
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    @endif
</section>

{{-- Filter Preferences Modal --}}
<div class="lps-filter-modal" id="filterModal" onclick="closeFilterModal(event)">
    <div class="lps-filter-modal-content" onclick="event.stopPropagation()">
        <div class="lps-filter-modal-header">
            <h3>Customize Filter Tags</h3>
            <button class="lps-filter-modal-close" onclick="closeFilterModal()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <div class="lps-filter-modal-body">
            <p class="lps-filter-instruction">Select up to 5 tags to display in your filter. Tags are automatically created from the technologies you add to your projects.</p>
            
            <div class="lps-filter-selected">
                <h4>Selected Tags (<span id="selectedCount">{{ count($visibleTags ?? []) }}</span>/5)</h4>
                <div class="lps-filter-selected-list" id="selectedTagsList">
                    @foreach($visibleTags ?? [] as $tag)
                        <div class="lps-filter-tag selected" data-tag="{{ $tag }}">
                            <span>{{ $tag }}</span>
                            <button type="button" onclick="toggleTag('{{ $tag }}')">×</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lps-filter-available">
                <h4>Available Tags ({{ count($hiddenTags ?? []) }})</h4>
                <div class="lps-filter-available-list" id="availableTagsList">
                    @foreach($hiddenTags ?? [] as $tag)
                        <div class="lps-filter-tag" data-tag="{{ $tag }}" onclick="toggleTag('{{ $tag }}')">
                            <span>{{ $tag }}</span>
                            <button type="button">+</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lps-filter-modal-footer">
            <button class="lps-filter-btn-cancel" onclick="closeFilterModal()">Cancel</button>
            <button class="lps-filter-btn-save" onclick="saveFilterPreferences()">Save Preferences</button>
        </div>
    </div>
</div>

<script>
// Filter by tag
window.filterByTag = function(tag, button) {
    const items = document.querySelectorAll('.portfolio-item');
    const buttons = document.querySelectorAll('.lps-filter-btn');
    
    // Update active state
    buttons.forEach(btn => btn.classList.remove('active'));
    if (button) button.classList.add('active');
    
    // Filter items
    items.forEach(item => {
        const itemTags = item.dataset.tags ? item.dataset.tags.split(',') : [];
        if (tag === 'all' || itemTags.includes(tag)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
};

// Open filter modal
window.openFilterModal = function() {
    document.getElementById('filterModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
};

// Close filter modal
window.closeFilterModal = function(event) {
    if (event && event.target.classList.contains('lps-filter-modal-content')) return;
    document.getElementById('filterModal').style.display = 'none';
    document.body.style.overflow = '';
};

// Toggle tag selection
window.toggleTag = function(tag) {
    const selectedList = document.getElementById('selectedTagsList');
    const availableList = document.getElementById('availableTagsList');
    const countEl = document.getElementById('selectedCount');
    
    const selectedTags = selectedList.querySelectorAll('.lps-filter-tag');
    const tagElement = document.querySelector(`.lps-filter-tag[data-tag="${tag}"]`);
    
    if (!tagElement) return;
    
    if (tagElement.classList.contains('selected')) {
        // Remove from selected
        availableList.appendChild(tagElement);
        tagElement.classList.remove('selected');
        tagElement.querySelector('button').textContent = '+';
    } else {
        // Add to selected (max 5)
        if (selectedTags.length >= 5) {
            alert('You can select maximum 5 tags');
            return;
        }
        selectedList.appendChild(tagElement);
        tagElement.classList.add('selected');
        tagElement.querySelector('button').textContent = '×';
    }
    
    // Update count
    countEl.textContent = selectedList.querySelectorAll('.lps-filter-tag').length;
};

// Save filter preferences
window.saveFilterPreferences = async function() {
    const selectedTags = Array.from(document.getElementById('selectedTagsList').querySelectorAll('.lps-filter-tag'))
        .map(el => el.dataset.tag);
    
    if (selectedTags.length === 0) {
        alert('Please select at least one tag');
        return;
    }
    
    try {
        const response = await fetch('{{ route("tenant.filter-preferences") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ visible_tags: selectedTags })
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload(); // Reload to show updated filters
        } else {
            alert('Failed to save preferences');
        }
    } catch (error) {
        console.error('Error saving preferences:', error);
        alert('Failed to save preferences');
    }
};

window.showAllProjects = function () {
    openModal('seeAllProjectsModal');
};

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFilterModal();
    }
});
</script>

{{-- Filter Preferences Modal --}}
<div class="lps-filter-modal" id="filterModal" onclick="closeFilterModal(event)">
    <div class="lps-filter-modal-content" onclick="event.stopPropagation()">
        <div class="lps-filter-modal-header">
            <h3>Customize Filter Tags</h3>
            <button class="lps-filter-modal-close" onclick="closeFilterModal()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <div class="lps-filter-modal-body">
            <p class="lps-filter-instruction">Select up to 5 tags to display in your filter</p>
            
            <div class="lps-filter-selected">
                <h4>Selected Tags (<span id="selectedCount">{{ count($visibleTags ?? []) }}</span>/5)</h4>
                <div class="lps-filter-selected-list" id="selectedTagsList">
                    @foreach($visibleTags ?? [] as $tag)
                        <div class="lps-filter-tag selected" data-tag="{{ $tag }}">
                            <span>{{ $tag }}</span>
                            <button onclick="toggleTag('{{ $tag }}')">×</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lps-filter-available">
                <h4>Available Tags</h4>
                <div class="lps-filter-available-list" id="availableTagsList">
                    @foreach($hiddenTags ?? [] as $tag)
                        <div class="lps-filter-tag" data-tag="{{ $tag }}" onclick="toggleTag('{{ $tag }}')">
                            <span>{{ $tag }}</span>
                            <button>+</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lps-filter-modal-footer">
            <button class="lps-filter-btn-cancel" onclick="closeFilterModal()">Cancel</button>
            <button class="lps-filter-btn-save" onclick="saveFilterPreferences()">Save Preferences</button>
        </div>
    </div>
</div>

<script>
// Filter by tag
window.filterByTag = function(tag, button) {
    const items = document.querySelectorAll('.portfolio-item');
    const buttons = document.querySelectorAll('.lps-filter-btn');
    
    // Update active state
    buttons.forEach(btn => btn.classList.remove('active'));
    if (button) button.classList.add('active');
    
    // Filter items
    items.forEach(item => {
        const itemTags = item.dataset.tags ? item.dataset.tags.split(',') : [];
        if (tag === 'all' || itemTags.includes(tag)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
};

// Open filter modal
window.openFilterModal = function() {
    document.getElementById('filterModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
};

// Close filter modal
window.closeFilterModal = function(event) {
    if (event && event.target.classList.contains('lps-filter-modal-content')) return;
    document.getElementById('filterModal').style.display = 'none';
    document.body.style.overflow = '';
};

// Toggle tag selection
window.toggleTag = function(tag) {
    const selectedList = document.getElementById('selectedTagsList');
    const availableList = document.getElementById('availableTagsList');
    const countEl = document.getElementById('selectedCount');
    
    const selectedTags = selectedList.querySelectorAll('.lps-filter-tag');
    const tagElement = document.querySelector(`.lps-filter-tag[data-tag="${tag}"]`);
    
    if (!tagElement) return;
    
    if (tagElement.classList.contains('selected')) {
        // Remove from selected
        availableList.appendChild(tagElement);
        tagElement.classList.remove('selected');
        tagElement.querySelector('button').textContent = '+';
    } else {
        // Add to selected (max 5)
        if (selectedTags.length >= 5) {
            alert('You can select maximum 5 tags');
            return;
        }
        selectedList.appendChild(tagElement);
        tagElement.classList.add('selected');
        tagElement.querySelector('button').textContent = '×';
    }
    
    // Update count
    countEl.textContent = selectedList.querySelectorAll('.lps-filter-tag').length;
};

// Save filter preferences
window.saveFilterPreferences = async function() {
    const selectedTags = Array.from(document.getElementById('selectedTagsList').querySelectorAll('.lps-filter-tag'))
        .map(el => el.dataset.tag);
    
    if (selectedTags.length === 0) {
        alert('Please select at least one tag');
        return;
    }
    
    try {
        const response = await fetch('{{ route("tenant.filter-preferences") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ visible_tags: selectedTags })
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload(); // Reload to show updated filters
        }
    } catch (error) {
        console.error('Error saving preferences:', error);
        alert('Failed to save preferences');
    }
};

window.showAllProjects = function () {
    openModal('seeAllProjectsModal');
};

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFilterModal();
    }
});
</script>

<style>
/* ============================================
   LINKEDIN-STYLE PROJECTS SECTION
   ============================================ */

.linkedin-projects-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 0;
    margin-bottom: var(--mb-sections);
    overflow: hidden;
}

/* Header */
.lps-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 16px 12px 16px;
    border-bottom: 1px solid var(--border);
}

.lps-title {
    font-size: var(--fs-h2);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.lps-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.lps-btn-add,
.lps-btn-edit {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    color: var(--text-muted);
    transition: all 0.2s ease;
}

.lps-btn-add:hover,
.lps-btn-edit:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
}

/* Filter Section */
.lps-filter-section {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
}

.lps-filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.lps-filter-btn {
    padding: 6px 14px;
    background: var(--apc-bg);
    color: var(--text-body);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: var(--fs-subtle);
    font-weight: var(--fw-medium);
    cursor: pointer;
    transition: all 0.2s ease;
}

.lps-filter-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
}

.lps-filter-btn.active {
    background: var(--accent);
    color: var(--text-white);
    border-color: var(--accent);
}

.lps-filter-more {
    padding: 6px 12px;
    background: transparent;
    color: var(--text-muted);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: var(--fs-subtle);
    font-weight: var(--fw-medium);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
}

.lps-filter-more:hover {
    background: var(--apc-bg);
    border-color: var(--accent);
    color: var(--accent);
}

/* Projects List */
.lps-list {
    padding: 0;
}

.portfolio-item {
    transition: opacity 0.3s ease;
}

/* Empty State */
.lps-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    text-align: center;
}

.lps-empty svg {
    margin-bottom: 16px;
    opacity: 0.2;
    color: var(--text-muted);
}

.lps-empty p {
    font-size: var(--fs-title);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.lps-empty span {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin-bottom: 20px;
}

.lps-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 20px;
    background: transparent;
    color: var(--accent);
    border: 1.5px solid var(--accent);
    border-radius: 24px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
}

.lps-empty-btn:hover {
    background: var(--accent-light);
}

/* Footer */
.lps-footer {
    padding: 12px 16px;
    border-top: 1px solid var(--border);
}

.lps-see-all {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    background: transparent;
    color: var(--text-muted);
    border: none;
    border-radius: 4px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
}

.lps-see-all:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
}

.lps-see-all svg {
    flex-shrink: 0;
}

/* ==================== FILTER MODAL ==================== */
.lps-filter-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    padding: 20px;
}

.lps-filter-modal-content {
    background: var(--card);
    border-radius: var(--radius);
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.lps-filter-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}

.lps-filter-modal-header h3 {
    font-size: var(--fs-h3);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
}

.lps-filter-modal-close {
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

.lps-filter-modal-close:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
}

.lps-filter-modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.lps-filter-instruction {
    font-size: var(--fs-body);
    color: var(--text-muted);
    margin: 0 0 20px 0;
}

.lps-filter-selected,
.lps-filter-available {
    margin-bottom: 24px;
}

.lps-filter-selected h4,
.lps-filter-available h4 {
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0 0 12px 0;
}

.lps-filter-selected-list,
.lps-filter-available-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 44px;
    padding: 12px;
    background: var(--apc-bg);
    border-radius: var(--radius);
}

.lps-filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px 6px 12px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: var(--fs-body);
    font-weight: var(--fw-medium);
    color: var(--text-body);
    cursor: pointer;
    transition: all 0.2s ease;
}

.lps-filter-tag:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.lps-filter-tag.selected {
    background: var(--accent-light);
    border-color: var(--accent);
    color: var(--accent);
}

.lps-filter-tag button {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s ease;
}

.lps-filter-tag button:hover {
    background: rgba(220, 38, 38, 0.1);
    color: #dc2626;
}

.lps-filter-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    border-top: 1px solid var(--border);
}

.lps-filter-btn-cancel,
.lps-filter-btn-save {
    height: 40px;
    padding: 0 24px;
    border: none;
    border-radius: 8px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
}

.lps-filter-btn-cancel {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-body);
}

.lps-filter-btn-cancel:hover {
    background: var(--apc-bg);
}

.lps-filter-btn-save {
    background: var(--accent);
    color: var(--text-white);
}

.lps-filter-btn-save:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 640px) {
    .lps-header {
        padding: 12px 12px 10px 12px;
    }
    
    .lps-title {
        font-size: var(--fs-h3);
    }
    
    .lps-btn-add,
    .lps-btn-edit {
        width: 32px;
        height: 32px;
    }
    
    .lps-filter-section {
        padding: 10px 12px;
    }
    
    .lps-empty {
        padding: 32px 16px;
    }
    
    .lps-filter-modal-content {
        max-height: 90vh;
    }
    
    .lps-filter-modal-header,
    .lps-filter-modal-body,
    .lps-filter-modal-footer {
        padding: 16px;
    }
}
</style>