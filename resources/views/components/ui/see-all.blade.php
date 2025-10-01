@props([
    'text' => 'See all',
    'href' => '#',
    'onclick' => null,
])

<div class="see-all-container">
    <a href="{{ $href }}" class="see-all-link"
        @if ($onclick) onclick="{{ $onclick }}" @endif {{ $attributes }}>
        <span class="see-all-text">{{ $text }}</span>
        <i class="fas fa-arrow-right see-all-icon"></i>
    </a>
</div>


<style>
   /* ===== SMOOTH MINIMAL SEE ALL COMPONENT ===== */
.see-all-container {
    width: 100%;
    padding: 12px 0;
    margin: 0;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.see-all-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--muted2);
    text-decoration: none;
    font-size: var(--fs-micro);
    font-weight: 600;
    transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    pointer-events: none;
    transform-origin: center;
}

.see-all-container:hover .see-all-link {
    color: var(--accent);
    transform: scale(1.03);
}

.see-all-text {
    font-weight: 600;
    letter-spacing: 0.025em;
    transition: inherit;
}

.see-all-icon {
    font-size: 0.75rem;
    transition: inherit;
    opacity: 0.8;
}

.see-all-container:hover .see-all-icon {
    opacity: 1;
    transform: translateX(1px);
}

/* ===== RESPONSIVE DESIGN ===== */

/* Tablet */
@media (min-width: 768px) and (max-width: 1199px) {
    .see-all-container {
        padding: 10px 0;
    }
    
    .see-all-link {
        font-size: 0.8rem;
    }
    
    .see-all-container:hover .see-all-link {
        transform: scale(1.03);
    }
}

/* Mobile */
@media (max-width: 767px) {
    .see-all-container {
        padding: 14px 0;
        margin-top: 8px;
    }
    
    .see-all-link {
        font-size: 0.85rem;
    }
    
    .see-all-container:hover .see-all-link {
        transform: scale(1.03);
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .see-all-container {
        padding: 12px 0;
        margin-top: 6px;
    }
    
    .see-all-link {
        font-size: 0.8rem;
    }
    
    .see-all-icon {
        font-size: 0.7rem;
    }
    
    .see-all-container:hover .see-all-link {
        transform: scale(1.03);
    }
}

/* Touch devices */
@media (hover: none) and (pointer: coarse) {
    .see-all-container:active .see-all-link {
        color: var(--accent);
        transform: scale(1.03);
    }
    
    .see-all-container:active .see-all-icon {
        opacity: 1;
        transform: translateX(1px);
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .see-all-link,
    .see-all-text,
    .see-all-icon {
        transition: color 0.2s ease !important;
    }
    
    .see-all-container:hover .see-all-link {
        transform: none !important;
    }
    
    .see-all-container:hover .see-all-icon {
        transform: none !important;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .see-all-container {
        border-top: 2px solid #000;
    }
    
    .see-all-container:hover .see-all-link {
        color: #000;
    }
}


/* Fix: enable hover/click + force hover color */
.see-all-link{ pointer-events:auto; color: var(--muted2); }
.see-all-icon{ color: currentColor; }

/* win against other rules (some use !important) */
.see-all-container:hover .see-all-link,
.see-all-link:hover,
.see-all-link:focus-visible{
  color: var(--accent) !important;
}

</style>
