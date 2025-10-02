@props([
    'variant' => 'solid', // solid, outlined, special-outlined, special-solid
    'shape' => 'rounded', // rounded, square
    'size' => 'md', // sm, md, lg
    'color' => 'primary', // primary, secondary, success, danger
    'type' => 'button',
    'href' => null,
    'onclick' => null
])

@php
    $baseClasses = 'btn';
    $variantClasses = match($variant) {
        'outlined' => 'btn-outlined',
        'special-outlined' => 'btn-special-outlined',
        'special-solid' => 'btn-special-solid',
        default => 'btn-solid'
    };
    $shapeClasses = $shape === 'square' ? 'btn-square' : 'btn-rounded';
    $sizeClasses = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => 'btn-md'
    };
    $colorClasses = 'btn-' . $color;
    
    $classes = implode(' ', [$baseClasses, $variantClasses, $shapeClasses, $sizeClasses, $colorClasses]);
@endphp

@if($href)
    <a 
        href="{{ $href }}" 
        class="{{ $classes }}"
        @if($onclick) onclick="{{ $onclick }}" @endif
        {{ $attributes }}
    >
        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $type }}"
        class="{{ $classes }}"
        @if($onclick) onclick="{{ $onclick }}" @endif
        {{ $attributes }}
    >
        {{ $slot }}
    </button>
@endif


<style>
    /* ===== PROFESSIONAL BUTTON SYSTEM WITH INSET BORDERS ===== */

/* Base Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-family: inherit;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    user-select: none;
    outline: none;
    box-sizing: border-box;
}
.btn:hover   .ui-icon {
        transform: scale(1.08) !important;
        filter: brightness(1.1) !important;
    }

.btn:focus {
    outline: none;
}

/* Button Sizes */
.btn-sm {
    padding: 0px 10px;
    font-size: var(--fs-micro);
    min-height: 32px;
}

.btn-md {
    padding: 10px 20px;
    font-size: var(--fs-subtle);
    min-height: 40px;
}

.btn-lg {
    padding: 12px 24px;
    font-size: var(--fs-title);
    min-height: 48px;
}

/* Button Shapes */
.btn-rounded {
    border-radius: 50px;
}

.btn-square {
    border-radius: var(--radius);
}

/* ===== SOLID BUTTONS ===== */

/* Primary Solid */
.btn-solid.btn-primary {
    background: var(--accent);
    color: #ffffff;
    border-color: var(--accent);
}

.btn-solid.btn-primary:hover {
    background: #0f46c4;
    color: #ffffff;
    /* box-shadow: inset 0 0 0 1px #0f46c4, 0 4px 12px rgba(19, 81, 216, 0.3); */
}

.btn-solid.btn-primary:active {
    /* box-shadow: inset 0 0 0 1px var(--accent), 0 2px 6px rgba(19, 81, 216, 0.3); */
}

/* Secondary Solid */
.btn-solid.btn-secondary {
    background: var(--muted);
    color: #ffffff;
    border-color: var(--muted);
}

.btn-solid.btn-secondary:hover {
    background: #4b5563;
    color: #ffffff;
    box-shadow: inset 0 0 0 1px #4b5563, 0 4px 12px rgba(107, 114, 128, 0.3);
}

/* Success Solid */
.btn-solid.btn-success {
    background: #10b981;
    color: #ffffff;
    border-color: #10b981;
}

.btn-solid.btn-success:hover {
    background: #059669;
    color: #ffffff;
    box-shadow: inset 0 0 0 1px #047857, 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Danger Solid */
.btn-solid.btn-danger {
    background: #ef4444;
    color: #ffffff;
    border-color: #ef4444;
}

.btn-solid.btn-danger:hover {
    background: #dc2626;
    color: #ffffff;
    box-shadow: inset 0 0 0 1px #b91c1c, 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* ===== OUTLINED BUTTONS ===== */

/* Primary Outlined */
.btn-outlined.btn-primary {
    background: var(--card);
    color: var(--accent);
    border-color: var(--accent);
}

.btn-outlined.btn-primary:hover {
    background: rgba(19, 81, 216, 0.1);
    color: var(--accent);
}

/* Secondary Outlined */
.btn-outlined.btn-secondary {
    background: #ffffff;
    color: var(--muted);
    border-color: var(--muted);
}

.btn-outlined.btn-secondary:hover {
    background: rgba(107, 114, 128, 0.1);
    color: var(--muted);
    box-shadow: inset 0 0 0 1px var(--muted), 0 4px 12px rgba(107, 114, 128, 0.15);
}

/* Success Outlined */
.btn-outlined.btn-success {
    background: #ffffff;
    color: #10b981;
    border-color: #10b981;
}

.btn-outlined.btn-success:hover {
    background: rgba(16, 185, 129, 0.1);
    color: #047857;
    box-shadow: inset 0 0 0 1px #047857, 0 4px 12px rgba(16, 185, 129, 0.15);
}

/* Danger Outlined */
.btn-outlined.btn-danger {
    background: #ffffff;
    color: #ef4444;
    border-color: #ef4444;
}

.btn-outlined.btn-danger:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #b91c1c;
    box-shadow: inset 0 0 0 1px #b91c1c, 0 4px 12px rgba(239, 68, 68, 0.15);
}

/* ===== SPECIAL OUTLINED BUTTONS (Solid-style hover) ===== */

/* Special Outlined Primary */
.btn-special-outlined.btn-primary {
    background: transparent;
    color: var(--accent);
    border-color: var(--accent);
}
.portfolios-header .ui-icon{
    color: var(--accent) ;

}

.btn-special-outlined.btn-primary:hover {
    background: var(--accent);
    color: #ffffff;
    border-color: var(--accent);
}


.btn-special-outlined.btn-primary:hover .ui-icon{
    color: #ffffff !important;
}

.btn-special-outlined.btn-primary:active {
    background: #0f46c4;
    color: #ffffff;
}

/* Special Outlined Secondary */
.btn-special-outlined.btn-secondary {
    background: transparent;
    color: var(--muted);
    border-color: var(--muted);
}

.btn-special-outlined.btn-secondary:hover {
    background: var(--muted);
    color: #ffffff;
    border-color: var(--muted);
    box-shadow: inset 0 0 0 1px #4b5563, 0 4px 12px rgba(107, 114, 128, 0.3);
}

/* ===== SPECIAL SOLID BUTTONS (Enhanced hover) ===== */

/* Special Solid Primary */
.btn-special-solid.btn-primary {
    background: var(--accent);
    color: #ffffff;
    border-color: var(--accent);
}

.btn-special-solid.btn-primary:hover {
    background: #0f46c4;
    color: #ffffff;
    border-color: #0f46c4;
    transform: translateY(-2px);
}

.btn-special-solid.btn-primary:active {
    transform: translateY(0);
    box-shadow: inset 0 0 0 1px var(--accent), 0 2px 6px rgba(19, 81, 216, 0.3);
}

/* Special Solid Secondary */
.btn-special-solid.btn-secondary {
    background: var(--muted);
    color: #ffffff;
    border-color: var(--muted);
}

.btn-special-solid.btn-secondary:hover {
    background: #4b5563;
    color: #ffffff;
    border-color: #4b5563;
    box-shadow: inset 0 0 0 1px #4b5563, 0 6px 16px rgba(107, 114, 128, 0.4);
    transform: translateY(-2px);
}

/* ===== FOCUS STATES ===== */
.btn:focus-visible {
    outline: 2px solid currentColor;
    outline-offset: 2px;
}

/* ===== DISABLED STATE ===== */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}

.btn:disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
        min-height: 30px;
    }
    
    .btn-md {
        padding: 8px 16px;
        font-size: 0.85rem;
        min-height: 36px;
    }
    
    .btn-lg {
        padding: 10px 20px;
        font-size: 0.9rem;
        min-height: 44px;
    }
}

/* ===== ACCESSIBILITY ===== */
@media (prefers-reduced-motion: reduce) {
    .btn {
        transition: none !important;
    }
    
    .btn:hover {
        transform: none !important;
    }
}

/* Icon styling */
.fas.fa-edit {
    color: var(--accent) !important;
}

/* ===== FILTER TABS LAYOUT ===== */
.filter-tabs {
    display: flex;
    gap: 8px;
    margin: 4px 0 18px;
    flex-wrap: wrap;
}

.filter-tab-btn {
    flex-shrink: 0;
}

/* Mobile responsiveness for filter tabs */
@media (max-width: 767px) {
    .filter-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
        padding-bottom: 8px;
        gap: 6px;
    }
    
    .filter-tabs::-webkit-scrollbar {
        height: 2px;
    }
    
    .filter-tabs::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 1px;
    }
    
    .filter-tabs::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 1px;
    }
}

.btn-full {
    width: 100%;
    display: block;
}
.btn-solid {
    color: var(--card) !important;
}
</style>


