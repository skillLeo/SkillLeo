@props(['languages' => []])

<x-modals.base-modal id="seeAllLanguagesModal" title="Languages" size="md">
    <div class="modal-content-v2">
        @forelse($languages as $lang)
            <div class="language-item-v2">
                <div class="language-content-v2">
                    <h3 class="language-name-v2">{{ $lang['name'] ?? 'Language' }}</h3>
                    <p class="language-level-v2">{{ $lang['level'] ?? 'Intermediate' }}</p>
                </div>
            </div>
        @empty
            <div class="empty-state-v2">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                    <path d="M32 20v24M20 32h24" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.2"/>
                </svg>
                <p class="empty-title-v2">No languages added yet</p>
                <p class="empty-subtitle-v2">Add languages you speak to your profile</p>
            </div>
        @endforelse
    </div>
</x-modals.base-modal>

<style>
/* Language Item */
.language-item-v2 {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border, #e0e0e0);
}

.language-item-v2:last-child {
    border-bottom: none;
}

.language-content-v2 {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.language-name-v2 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-heading, #000000de);
    margin: 0;
    line-height: 1.3;
}

.language-level-v2 {
    font-size: 14px;
    color: var(--text-muted, #00000099);
    margin: 0;
    line-height: 1.4;
}

/* Mobile */
@media (max-width: 768px) {
    .language-item-v2 {
        padding: 14px 16px;
    }

    .language-name-v2 {
        font-size: 15px;
    }

    .language-level-v2 {
        font-size: 13px;
    }
}
</style>

<script>
function showAllLanguages() {
    openModal('seeAllLanguagesModal');
}
</script>