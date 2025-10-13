@props([
    'id' => 'dropdown-' . uniqid(),
    'trigger' => null,
    'align' => 'right', // left, right, center
])

<div class="dropdown-wrapper" x-data="{ open: false }" @click.away="open = false">
    {{-- Trigger Button --}}
    <div @click="open = !open" class="dropdown-trigger">
        {{ $trigger }}
    </div>

    {{-- Dropdown Menu --}}
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="dropdown-menu dropdown-{{ $align }}"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</div>

<style>
    .dropdown-wrapper {
        position: relative;
        display: inline-block;
    }

    .dropdown-trigger {
        cursor: pointer;
    }

    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        min-width: 220px;
        max-width: 240px;
        z-index: 999999;
        overflow: hidden;
    }

    .dropdown-right {
        right: 0;
    }

    .dropdown-left {
        left: 0;
    }

    .dropdown-center {
        left: 50%;
        transform: translateX(-50%);
    }
</style>