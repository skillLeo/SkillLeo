@props(['id', 'title', 'size' => 'md'])

<div id="{{ $id }}" class="modal-overlay-v2" onclick="handleOverlayClick(event, '{{ $id }}')">
    <div class="modal-dialog-v2 modal-{{ $size }}-v2" onclick="event.stopPropagation()">
        <div class="modal-header-v2">
            <h2 class="modal-title-v2">{{ $title }}</h2>
            <button type="button" class="modal-close-v2" onclick="closeModal('{{ $id }}')" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M5 5L15 15M15 5L5 15"/>
                </svg>
            </button>
        </div>

        <div class="modal-body-v2">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
/* Modal Overlay */
.modal-overlay-v2 {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(2px);
    display: none;
    align-items: flex-start;
    justify-content: center;
    z-index: 99999;
    overflow-y: auto;
    padding: 40px 20px;
    transition: opacity 0.2s ease;
}

.modal-overlay-v2.active {
    display: flex;
    opacity: 1;
}

/* Modal Dialog */
.modal-dialog-v2 {
    background: var(--card);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    width: 100%;
    margin: auto 0;
    transform: scale(0.95) translateY(-20px);
    transition: transform 0.2s ease;
}

.modal-overlay-v2.active .modal-dialog-v2 {
    transform: scale(1) translateY(0);
}

.modal-md-v2 { max-width: 680px; }
.modal-lg-v2 { max-width: 900px; }
.modal-xl-v2 { max-width: 1140px; }

/* Header */
.modal-header-v2 {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    background: var(--card);
    border-radius: var(--radius) var(--radius) 0 0;
}

.modal-title-v2 {
    font-size: var(--fs-h4);
    font-weight: var(--fw-semibold);
    color: var(--text-heading);
    margin: 0;
    line-height: 1.3;
}

/* Close Button */
.modal-close-v2 {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    color: var(--text-muted);
    cursor: pointer;
    border-radius: 50%;
    transition: all 0.15s ease;
    flex-shrink: 0;
    margin: -8px -8px -8px 12px;
}

.modal-close-v2:hover {
    background: var(--hover-bg);
    color: var(--text-heading);
}

.modal-close-v2:active {
    transform: scale(0.95);
}

.modal-close-v2:focus {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}

/* Body */
.modal-body-v2 {
    padding: 0;
    max-height: calc(100vh - 160px);
    overflow-y: auto;
    background: var(--body);
    border-radius: 0 0 var(--radius) var(--radius);
    scroll-behavior: smooth;
}

/* Scrollbar */
.modal-body-v2::-webkit-scrollbar {
    width: 8px;
}

.modal-body-v2::-webkit-scrollbar-track {
    background: transparent;
}

.modal-body-v2::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
    border: 2px solid transparent;
    background-clip: padding-box;
}

.modal-body-v2::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
    background-clip: padding-box;
}

/* Mobile */
@media (max-width: 768px) {
    .modal-overlay-v2 {
        padding: 0;
        align-items: stretch;
    }

    .modal-dialog-v2 {
        border-radius: 0;
        max-height: 100vh;
        min-height: 100vh;
        margin: 0;
        box-shadow: none;
    }

    .modal-md-v2, 
    .modal-lg-v2, 
    .modal-xl-v2 {
        max-width: 100%;
    }

    .modal-header-v2 {
        padding: 16px 20px;
        border-radius: 0;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: var(--shadow-sm);
    }

    .modal-title-v2 {
        font-size: var(--fs-h5);
    }

    .modal-close-v2 {
        width: 36px;
        height: 36px;
    }

    .modal-body-v2 {
        max-height: calc(100vh - 64px);
        border-radius: 0;
    }
}

/* Tablet */
@media (max-width: 1024px) and (min-width: 769px) {
    .modal-overlay-v2 {
        padding: 24px 16px;
    }

    .modal-lg-v2 { max-width: 90%; }
    .modal-xl-v2 { max-width: 95%; }
}

/* Small Mobile */
@media (max-width: 480px) {
    .modal-header-v2 {
        padding: 14px 16px;
    }

    .modal-title-v2 {
        font-size: var(--fs-body);
    }

    .modal-close-v2 {
        width: 32px;
        height: 32px;
        margin: -6px -6px -6px 8px;
    }

    .modal-close-v2 svg {
        width: 18px;
        height: 18px;
    }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    .modal-overlay-v2,
    .modal-dialog-v2,
    .modal-close-v2 {
        transition: none;
    }
}

/* Print */
@media print {
    .modal-overlay-v2 {
        display: none !important;
    }
}
</style>

<script>
window.handleOverlayClick = function(event, modalId) {
    if (event.target.classList.contains('modal-overlay-v2')) {
        closeModal(modalId);
    }
};
</script>