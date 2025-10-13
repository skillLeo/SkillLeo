@props(['reasons' => []])

<x-modals.base-modal id="seeAllWhyChooseModal" title="Why Choose Me" size="md">
    <div class="modal-content-v2">
        @forelse($reasons as $reason)
            <div class="reason-item-v2">
                <svg class="reason-check-v2" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="reason-text-v2">{{ $reason }}</span>
            </div>
        @empty
            <div class="empty-state-v2">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2" opacity="0.2"/>
                    <path d="M32 20v24M20 32h24" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.2"/>
                </svg>
                <p class="empty-title-v2">No reasons added yet</p>
                <p class="empty-subtitle-v2">Add reasons why clients should choose you</p>
            </div>
        @endforelse
    </div>
</x-modals.base-modal>

<style>
/* Reason Item */
.reason-item-v2 {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border, #e0e0e0);
}

.reason-item-v2:last-child {
    border-bottom: none;
}

.reason-check-v2 {
    color: #10b981;
    flex-shrink: 0;
    margin-top: 2px;
}

.reason-text-v2 {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-body, #000000de);
}

/* Mobile */
@media (max-width: 768px) {
    .reason-item-v2 {
        padding: 14px 16px;
    }

    .reason-text-v2 {
        font-size: 13px;
    }
}
</style>

<script>
function showAllWhyChooseMe() {
    openModal('seeAllWhyChooseModal');
}
</script>