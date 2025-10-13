@props([
    'icon' => null,
    'iconSize' => 'sm',
    'danger' => false,
    'divider' => false,
])

@if($divider)
    <div class="dropdown-divider"></div>
@else
    <button {{ $attributes->merge(['class' => 'dropdown-item' . ($danger ? ' danger' : '')]) }}>
        @if($icon)
            <x-ui.icon :name="$icon" :size="$iconSize" color="secondary" />
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif

<style>
    .dropdown-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: transparent;
        border: none;
        color: var(--text-body);
        font-size: var(--fs-body);
        font-weight: var(--fw-medium);
        cursor: pointer;
        transition: background 0.2s ease;
        text-align: left;
        font-family: inherit;
    }

    .dropdown-item:hover {
        background: var(--apc-bg);
    }

    .dropdown-item .ui-icon {
        flex-shrink: 0;
    }

    .dropdown-item.danger {
        color: var(--error);
    }

    .dropdown-item.danger .ui-icon {
        color: var(--error);
    }

    .dropdown-divider {
        height: 1px;
        background: var(--border);
        margin: 4px 0;
    }
</style>