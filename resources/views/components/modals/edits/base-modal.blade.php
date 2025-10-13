@props([
    'id',
    'title',
    'size' => 'md',
    'showFooter' => true
])

<div class="modal-overlay" id="{{ $id }}" style="display: none;">
    <div class="modal-container modal-{{ $size }}">
        <div class="modal-header">
            <h2 class="modal-title">{{ $title }}</h2>
            <button type="button" class="modal-close" onclick="closeModal('{{ $id }}')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="modal-body">
            {{ $slot }}
        </div>

        @if($showFooter)
            <div class="modal-footer">
                {{ $footer ?? '' }}
            </div>
        @endif
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-lg);
    animation: fadeIn 0.2s ease;
    backdrop-filter: blur(2px);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-container {
    background: var(--card);
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-sm { width: 100%; max-width: 480px; }
.modal-md { width: 100%; max-width: 680px; }
.modal-lg { width: 100%; max-width: 840px; }
.modal-xl { width: 100%; max-width: 1000px; }

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    background: var(--card);
}

.modal-title {
    font-size: var(--fs-h2);
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    margin: 0;
    letter-spacing: -0.01em;
}

.modal-close {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    border-radius: 50%;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: var(--apc-bg);
    color: var(--text-heading);
}

.modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: var(--apc-bg);
}

.modal-body::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    background: var(--card);
}

.btn-modal {
    padding: 10px 24px;
    border-radius: var(--radius);
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    font-family: inherit;
    min-height: 40px;
}

.btn-cancel {
    background: var(--card);
    color: var(--text-body);
    border: 1px solid var(--border);
}

.btn-cancel:hover {
    background: var(--apc-bg);
    border-color: var(--text-muted);
}

.btn-save {
    background: var(--accent);
    color: var(--btn-text-primary);
    min-width: 100px;
}

.btn-save:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(19, 81, 216, 0.3);
}

.btn-save:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 768px) {
    .modal-overlay {
        padding: 0;
        align-items: flex-end;
    }

    .modal-container {
        max-height: 95vh;
        border-radius: 12px 12px 0 0;
        width: 100% !important;
        max-width: 100% !important;
    }

    .modal-header {
        padding: 16px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 14px 20px;
    }
}
</style>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = '';
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            if (modal.style.display === 'flex') {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }
});
</script>