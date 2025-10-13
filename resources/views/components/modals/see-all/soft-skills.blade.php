@props(['softSkills' => []])

<x-modals.base-modal id="seeAllSoftSkillsModal" title="All Soft Skills" size="md">
    <div class="see-all-content">
        <div class="soft-skills-grid-modal">
            @forelse($softSkills as $index => $skill)
                <div class="soft-skill-item" style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="soft-skill-icon-box">
                        @if(is_array($skill))
                            <i class="fa-solid fa-{{ $skill['icon'] ?? 'lightbulb' }}"></i>
                        @else
                            <i class="fa-solid fa-lightbulb"></i>
                        @endif
                    </div>
                    <span class="soft-skill-label">
                        @if(is_array($skill))
                            {{ $skill['name'] ?? $skill }}
                        @else
                            {{ $skill }}
                        @endif
                    </span>
                    <div class="skill-check-mark">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fa-solid fa-sparkles"></i>
                    <p>No soft skills added yet</p>
                    <span class="empty-subtitle">Add your interpersonal skills</span>
                </div>
            @endforelse
        </div>
    </div>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-primary" onclick="closeModal('seeAllSoftSkillsModal')">Close</button>
    </x-slot:footer>
</x-modals.base-modal>

<style>
.soft-skills-grid-modal {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 12px;
    padding: 8px 0;
}

.soft-skill-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 10px;
    transition: all 0.3s ease;
    opacity: 0;
    animation: slideInRight 0.4s ease forwards;
    cursor: default;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.soft-skill-item:hover {
    border-color: var(--accent);
    background: var(--accent-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.soft-skill-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--accent-light);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.soft-skill-item:hover .soft-skill-icon-box {
    background: var(--accent);
    transform: rotate(-5deg) scale(1.05);
}

.soft-skill-icon-box i {
    font-size: 16px;
    color: var(--accent);
    transition: color 0.3s ease;
}

.soft-skill-item:hover .soft-skill-icon-box i {
    color: white;
}

.soft-skill-label {
    flex: 1;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-body);
    line-height: 1.4;
}

.skill-check-mark {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.soft-skill-item:hover .skill-check-mark {
    opacity: 1;
}

.skill-check-mark i {
    font-size: 16px;
    color: #10b981;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px 20px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 52px;
    opacity: 0.3;
    margin-bottom: 14px;
    display: block;
}

.empty-state p {
    margin: 0 0 6px 0;
    font-size: 16px;
    font-weight: 600;
}

.empty-subtitle {
    font-size: 13px;
    opacity: 0.7;
}

/* Responsive */
@media (max-width: 768px) {
    .soft-skills-grid-modal {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 10px;
    }

    .soft-skill-item {
        padding: 12px 14px;
    }

    .soft-skill-icon-box {
        width: 32px;
        height: 32px;
    }

    .soft-skill-icon-box i {
        font-size: 14px;
    }

    .soft-skill-label {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .soft-skills-grid-modal {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showAllSoftSkills() {
    openModal('seeAllSoftSkillsModal');
}
</script>