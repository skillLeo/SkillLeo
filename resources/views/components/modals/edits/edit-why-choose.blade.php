@props(['reasons' => []])

<x-modals.edits.base-modal id="editWhyChooseModal" title="Why Choose Me" size="md">
    <form id="whyChooseForm" method="POST" action="{{ route('tenant.why.update') }}">
        @csrf
        @method('PUT')

        <div class="why-input-section">
            <div class="input-row">
                <textarea 
                    id="reasonInput" 
                    class="form-textarea" 
                    rows="2"
                    placeholder="e.g., 5+ years of experience in full-stack development..."
                    maxlength="120"
                ></textarea>
                <button type="button" class="btn-add-reason" onclick="addReason()">Add</button>
            </div>
            <div class="char-count" id="charCount">0 / 120</div>
            <p class="form-hint">Add key reasons clients should choose you</p>
        </div>

        <div class="reasons-display" id="reasonsDisplay">
            <div class="reasons-empty" id="reasonsEmpty">No reasons added yet</div>
            <div class="reasons-list" id="reasonsList"></div>
        </div>

        <input type="hidden" name="reasons" id="reasonsData">
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editWhyChooseModal')">Cancel</button>
        <button type="submit" form="whyChooseForm" class="btn-modal btn-save">Save</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

@push('scripts')
<script>
let reasons = @json(array_values($reasons ?? []));

function addReason() {
    const input = document.getElementById('reasonInput');
    const text = input.value.trim();
    if (!text) { input.focus(); return; }

    // avoid duplicates
    if (reasons.some(r => r.toLowerCase() === text.toLowerCase())) {
        alert('Reason already added');
        input.value = '';
        input.focus();
        return;
    }

    reasons.push(text);
    input.value = '';
    updateCharCount();
    input.focus();
    renderReasons();
}

function removeReason(index) {
    reasons.splice(index, 1);
    renderReasons();
}

function updateCharCount() {
    const input = document.getElementById('reasonInput');
    const counter = document.getElementById('charCount');
    if (!input || !counter) return;
    counter.textContent = `${input.value.length} / 120`;
}

function renderReasons() {
    const empty = document.getElementById('reasonsEmpty');
    const list = document.getElementById('reasonsList');
    const data = document.getElementById('reasonsData');

    if (!list || !empty || !data) return;

    if (reasons.length === 0) {
        empty.style.display = 'flex';
        list.style.display = 'none';
    } else {
        empty.style.display = 'none';
        list.style.display = 'flex';
        list.innerHTML = reasons.map((reason, i) => `
            <div class="reason-item">
                <svg class="reason-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
                <span class="reason-text">${escapeHtml(reason)}</span>
                <button type="button" class="reason-remove" onclick="removeReason(${i})">×</button>
            </div>
        `).join('');
    }

    data.value = JSON.stringify(reasons);
}

document.getElementById('reasonInput')?.addEventListener('input', updateCharCount);
document.getElementById('reasonInput')?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        addReason();
    }
});

// escape helper reused
function escapeHtml(s) {
    return String(s).replace(/[&<>"'`=\/]/g, function (c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[c];
    });
}

updateCharCount();
renderReasons();
</script>
@endpush










<style>
    .why-input-section {
        margin-bottom: var(--space-lg);
    }

    .input-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        align-items: start;
    }

    .btn-add-reason {
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
        white-space: nowrap;
        height: fit-content;
    }

    .btn-add-reason:hover {
        background: var(--accent-dark);
        transform: translateY(-1px);
    }

    .reasons-display {
        min-height: 160px;
        padding: var(--space-md);
        border: 1px dashed var(--border);
        border-radius: var(--radius);
        background: var(--apc-bg);
    }

    .reasons-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 140px;
        color: var(--text-muted);
        font-size: var(--fs-subtle);
    }

    .reasons-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .reason-item {
        display: flex;
        align-items: start;
        gap: 12px;
        padding: 14px 16px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: all 0.2s ease;
    }

    .reason-item:hover {
        border-color: var(--accent);
    }

    .reason-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        color: var(--success);
        margin-top: 2px;
    }

    .reason-text {
        flex: 1;
        color: var(--text-body);
        font-size: var(--fs-body);
        line-height: 1.5;
    }

    .reason-remove {
        width: 28px;
        height: 28px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .reason-remove:hover {
        background: rgba(239, 68, 68, 0.1);
        color: var(--error);
    }

    @media (max-width: 640px) {
        .input-row {
            grid-template-columns: 1fr;
        }

        .btn-add-reason {
            width: 100%;
            justify-content: center;
        }
    }
</style>
