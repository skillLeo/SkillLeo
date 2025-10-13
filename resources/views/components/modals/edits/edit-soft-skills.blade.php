@props(['softSkillOptions' => [], 'selectedSoft' => []])

<x-modals.edits.base-modal id="editSoftSkillsModal" title="Edit Soft Skills" size="md">
    <form id="softSkillsForm" method="POST" action="{{route('tenant.skills.update')}}">
        @csrf
        @method('PUT')

        {{-- Soft Skills Section --}}
        <div class="modal-section">
            <h3 class="section-title">Soft Skills</h3>
            <p class="section-desc">Select skills that describe how you work (max 6)</p>

            <div class="soft-skills-grid">
                @foreach($softSkillOptions as $skill)
                    <label class="soft-skill-card">
                        <input type="checkbox"
                               name="soft_skills[]"
                               value="{{ $skill['value'] }}"
                               class="soft-skill-checkbox">
                        <span class="soft-skill-content">
                            <i class="fa-solid fa-{{ $skill['icon'] ?? 'sparkles' }}"></i>
                            <span class="soft-skill-label">{{ $skill['label'] }}</span>
                        </span>
                    </label>
                @endforeach
            </div>

            <div class="soft-skills-counter">
                <span id="sidebarSoftSkillCount">0</span> / 6 selected
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editSoftSkillsModal')">Cancel</button>
        <button type="submit" form="softSkillsForm" class="btn-modal btn-save">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const preselectedSoft = @json($selectedSoft ?? []);
        const MAX_SOFT = 6;
        
        const softCheckboxes = document.querySelectorAll('#editSoftSkillsModal .soft-skill-checkbox');
        const softSkillCountEl = document.getElementById('sidebarSoftSkillCount');

        function updateSoftCount() {
            const count = Array.from(softCheckboxes).filter(cb => cb.checked).length;
            softSkillCountEl.textContent = count;
            
            softCheckboxes.forEach(cb => {
                if (!cb.checked && count >= MAX_SOFT) {
                    cb.disabled = true;
                    cb.closest('.soft-skill-card').style.opacity = '0.5';
                } else {
                    cb.disabled = false;
                    cb.closest('.soft-skill-card').style.opacity = '1';
                }
            });
        }

        // Apply preselected soft skills
        softCheckboxes.forEach(cb => {
            if (preselectedSoft.includes(cb.value)) {
                cb.checked = true;
            }
            cb.addEventListener('change', updateSoftCount);
        });
        
        updateSoftCount();
    });
</script>