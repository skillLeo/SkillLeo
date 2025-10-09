<x-modals.base-modal id="editSkillsModal" title="Edit Skills" size="md">
    <form id="skillsForm" method="POST" action="#">
        @csrf
        @method('PUT')

        <div class="modal-section">
            <h3 class="section-title">Technical Skills</h3>
            
            <div class="skill-input-group">
                <div class="skill-input-row">
                    <input 
                        type="text" 
                        class="form-input" 
                        id="skillInput"
                        placeholder="Type a skill and press Enter"
                        autocomplete="off"
                    >
                    <button type="button" class="btn-add-skill" onclick="addSkill()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                    </button>
                </div>
                <p class="form-hint">Add your top technical skills (e.g., React, Laravel, Python)</p>
            </div>

            <div class="skills-display" id="skillsDisplay">
                <div class="skills-empty" id="skillsEmpty">No skills added yet</div>
                <div class="skills-list" id="skillsList"></div>
            </div>

            <input type="hidden" name="skills" id="skillsData">
        </div>

        <div class="modal-section">
            <h3 class="section-title">Soft Skills</h3>
            
            <div class="checkbox-grid">
                @foreach([
                    'communication' => 'Communication',
                    'leadership' => 'Leadership',
                    'teamwork' => 'Teamwork',
                    'problem-solving' => 'Problem Solving',
                    'creativity' => 'Creativity',
                    'time-management' => 'Time Management',
                ] as $value => $label)
                    <label class="checkbox-card">
                        <input type="checkbox" name="soft_skills[]" value="{{ $value }}">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editSkillsModal')">Cancel</button>
        <button type="submit" form="skillsForm" class="btn-modal btn-save">Save</button>
    </x-slot:footer>
</x-modals.base-modal>

<style>
.skill-input-group {
    margin-bottom: var(--space-lg);
}

.skill-input-row {
    display: flex;
    gap: var(--space-sm);
}

.btn-add-skill {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent);
    color: var(--btn-text-primary);
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-add-skill:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

.skills-display {
    margin-top: var(--space-md);
    padding: var(--space-md);
    border: 1px dashed var(--border);
    border-radius: var(--radius);
    background: var(--apc-bg);
    min-height: 80px;
}

.skills-empty {
    text-align: center;
    color: var(--text-muted);
    font-size: var(--fs-subtle);
}

.skills-list {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
}

.skill-tag {
    display: inline-flex;
    align-items: center;
    gap: var(--space-xs);
    padding: 6px 12px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 20px;
    font-size: var(--fs-subtle);
    color: var(--text-body);
}

.skill-remove {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--border);
    border-radius: 50%;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 12px;
    line-height: 1;
    transition: all 0.2s ease;
}

.skill-remove:hover {
    background: var(--error);
    color: var(--btn-text-primary);
}

.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-sm);
}

.checkbox-card {
    display: flex;
    align-items: center;
    gap: var(--space-sm);
    padding: var(--space-sm) var(--space-md);
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.2s ease;
}

.checkbox-card:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.checkbox-card input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
    cursor: pointer;
}

@media (max-width: 640px) {
    .checkbox-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
let skills = [];

function addSkill() {
    const input = document.getElementById('skillInput');
    const skill = input.value.trim();
    
    if (skill && !skills.includes(skill)) {
        skills.push(skill);
        input.value = '';
        renderSkills();
    }
}

function removeSkill(skill) {
    skills = skills.filter(s => s !== skill);
    renderSkills();
}

function renderSkills() {
    const empty = document.getElementById('skillsEmpty');
    const list = document.getElementById('skillsList');
    const data = document.getElementById('skillsData');
    
    if (skills.length === 0) {
        empty.style.display = 'block';
        list.style.display = 'none';
    } else {
        empty.style.display = 'none';
        list.style.display = 'flex';
        list.innerHTML = skills.map(skill => `
            <div class="skill-tag">
                <span>${skill}</span>
                <button type="button" class="skill-remove" onclick="removeSkill('${skill}')">×</button>
            </div>
        `).join('');
    }
    
    data.value = JSON.stringify(skills);
}

document.getElementById('skillInput')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSkill();
    }
});
</script>