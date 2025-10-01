{{-- Icon Component: resources/views/components/ui/icon.blade.php --}}
@props([
    'name' => 'home',
    'variant' => 'outlined', // outlined, solid, duotone
    'size' => 'md', // xs, sm, md, lg, xl
    'color' => 'default', // default, primary, secondary, success, warning, danger, light, dark
    'strokeWidth' => '2.5', // Increased default stroke width for modern look
    
])

@php
    $sizeClasses = match ($size) {
        'xs' => 'w-3 h-3', // 12px
        'sm' => 'w-4 h-4', // 16px
        'md' => 'w-5 h-5', // 20px
        'lg' => 'w-6 h-6', // 24px
        'xl' => 'w-8 h-8', // 32px
        default => 'w-5 h-5',
    };

    $colorClasses = match ($color) {
        'primary' => 'text-blue-600',
        'secondary' => 'text-gray-600',
        'success' => 'text-green-600',
        'warning' => 'text-amber-600',
        'danger' => 'text-red-600',
        'light' => 'text-gray-300',
        'dark' => 'text-gray-900',
        default => 'text-gray-700',
        'primary_muted' => 'btn-primary_muted',  // ADD THIS LINE

    };

    $iconClass = "ui-icon {$sizeClasses} {$colorClasses}";
@endphp

<span {{ $attributes->merge(['class' => $iconClass]) }}>
    @switch($name)
        {{-- Navigation & System Icons --}}
        @case('share')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="5" cy="12" r="2" fill="currentColor" opacity="0.2" />
                    <circle cx="19" cy="5" r="2" fill="currentColor" opacity="0.2" />
                    <circle cx="19" cy="19" r="2" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 12h.01M19 5h.01M19 19h.01M7 12a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm14-7a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7.5 10.5L16.5 6m-9 7.5L16.5 18" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 1 1 0-2.684m9.632 6.316c-.202-.404-.316-.86-.316-1.342 0-.482.114-.938.316-1.342m0 2.684a3 3 0 1 1 0-2.684M9.316 10.658l6.368-3.316m-6.368 8l6.368 3.316M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @endif
        @break

        @case('link')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 11-2.828-2.828l3-3zm-4.95 6.364a2 2 0 012.829 0l3.535 3.535a2 2 0 11-2.828 2.828l-3.536-3.535a2 2 0 010-2.828zm7.071-7.071a4 4 0 00-5.657 0l-3 3a4 4 0 105.657 5.657l.707-.707a1 1 0 00-1.414-1.414l-.707.707a2 2 0 11-2.828-2.828l3-3a2 2 0 012.828 0 1 1 0 001.414-1.414zM19.071 17.314a4 4 0 00-5.657 0l-.707.707a1 1 0 101.414 1.414l.707-.707a2 2 0 012.828 2.828l-3 3a2 2 0 01-2.828-2.828 1 1 0 00-1.414-1.414 4 4 0 105.657 5.657l3-3a4 4 0 000-5.657z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.15"
                        d="M9.172 16.828a4 4 0 010-5.656l5.656-5.657a4 4 0 115.657 5.657l-2.475 2.475m-7.07 7.07a4 4 0 010-5.656l2.475-2.475" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            @endif
        @break

        {{-- Default fallback --}}
        @case('home')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M11.47 3.841a1 1 0 0 1 1.06 0l8 7.112a1 1 0 0 1 .47.846v7.2a3 3 0 0 1-3 3h-3a1 1 0 0 1-1-1v-5a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v5a1 1 0 0 1-1 1H6a3 3 0 0 1-3-3v-7.2a1 1 0 0 1 .47-.846l8-7.112z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2"
                        d="M3 12.766V19a2 2 0 0 0 2 2h4v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6h4a2 2 0 0 0 2-2v-6.234l-9-8-9 8z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 12.766l9-8 9 8M5 10.766V19a2 2 0 0 0 2 2h3m4 0h3a2 2 0 0 0 2-2v-8.234M10 21v-6a2 2 0 0 1 2-2v0a2 2 0 0 1 2 2v6" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l9-8 9 8m-2 8a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V10" />
                </svg>
            @endif
        @break

        @case('upload')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M2 12a10 10 0 1 1 20 0 10 10 0 0 1-20 0zm11 4a1 1 0 1 0-2 0v-3.586l-1.293 1.293a1 1 0 1 1-1.414-1.414l3-3a1 1 0 0 1 1.414 0l3 3a1 1 0 1 1-1.414 1.414L13 12.414V16z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M12 16v-5m0 0l-3 3m3-3l3 3m9-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 11v2m0 0v4m0-4l-3.5-3.5M12 11l3.5-3.5M8 21h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z" />
                </svg>
            @endif
        @break

        @case('search')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5zM2.25 10.5a8.25 8.25 0 1 1 16.5 0 8.25 8.25 0 0 1-16.5 0z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M15.78 15.78a.75.75 0 0 1 1.06 0l4.19 4.19a.75.75 0 1 1-1.06 1.06l-4.19-4.19a.75.75 0 0 1 0-1.06z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="m21 21-4.35-4.35M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.5 5.5a7.5 7.5 0 0 0 11.15 11.15z" />
                </svg>
            @endif
        @break

        @case('settings')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M11.078 1.82a2.25 2.25 0 0 1 2.844 0c.196.16.356.366.462.603l.348 1.04a16.47 16.47 0 0 1 2.55 1.47l1.006-.51a2.25 2.25 0 0 1 2.81.645 14.95 14.95 0 0 1 1.3 2.25 2.25 2.25 0 0 1-.504 2.505l-.657.857a16.45 16.45 0 0 1 0 2.64l.657.857a2.25 2.25 0 0 1 .504 2.505 14.95 14.95 0 0 1-1.3 2.25 2.25 2.25 0 0 1-2.81.645l-1.006-.51a16.47 16.47 0 0 1-2.55 1.47l-.348 1.04a2.25 2.25 0 0 1-2.462 1.565 15.04 15.04 0 0 1-2.594 0 2.25 2.25 0 0 1-1.866-2.168l-.348-1.04a16.47 16.47 0 0 1-2.55-1.47l-1.006.51a2.25 2.25 0 0 1-2.81-.645 14.95 14.95 0 0 1-1.3-2.25 2.25 2.25 0 0 1 .504-2.505l.657-.857a16.45 16.45 0 0 1 0-2.64l-.657-.857a2.25 2.25 0 0 1-.504-2.505 14.95 14.95 0 0 1 1.3-2.25 2.25 2.25 0 0 1 2.81-.645l1.006.51a16.47 16.47 0 0 1 2.55-1.47l.348-1.04a2.25 2.25 0 0 1 1.866-1.565 15.04 15.04 0 0 1 2.594 0zM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2"
                        d="M12 2l1.09 2.58 2.91.42.42 2.91L19 9l-2.58 1.09-.42 2.91-.42 2.91L19 15l-2.58 1.09-.42 2.91-2.91.42L12 22l-1.09-2.58L8 19l-.42-2.91L5 15l2.58-1.09L8 11l.42-2.91L5 9l2.58-1.09L8 5l2.91-.42z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.21 15a3 3 0 1 0 4.59-3.86 7.502 7.502 0 0 0-4.59 3.86zm0 0A7.502 7.502 0 0 0 15 12a7.502 7.502 0 0 0-4.79 3zm0 0l4.59-3.86M12 2l1.09 2.58 2.91.42.42 2.91L19 9l-2.58 1.09-.42 2.91-.42 2.91L19 15l-2.58 1.09-.42 2.91-2.91.42L12 22l-1.09-2.58L8 19l-.42-2.91L5 15l2.58-1.09L8 11l.42-2.91L5 9l2.58-1.09L8 5l2.91-.42L12 2z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            @endif
        @break

        @case('menu')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="6" width="18" height="3" rx="1.5" />
                    <rect x="3" y="10.5" width="18" height="3" rx="1.5" />
                    <rect x="3" y="15" width="18" height="3" rx="1.5" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="4" y="6" width="16" height="2" rx="1" fill="currentColor" opacity="0.2" />
                    <rect x="4" y="11" width="16" height="2" rx="1" fill="currentColor" opacity="0.2" />
                    <rect x="4" y="16" width="16" height="2" rx="1" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            @endif
        @break

        @case('close')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM8.707 7.293a1 1 0 0 0-1.414 1.414L10.586 12l-3.293 3.293a1 1 0 1 0 1.414 1.414L12 13.414l3.293 3.293a1 1 0 0 0 1.414-1.414L13.414 12l3.293-3.293a1 1 0 0 0-1.414-1.414L12 10.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M15 9l-6 6m0-6l6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @endif
        @break

        @case('arrow-right')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12.293 5.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L16.586 13H5a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2" d="M21 12l-6-6v4H3v4h12v4l6-6z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M13.5 7l4.5 5m0 0l-4.5 5m4.5-5H5" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            @endif
        @break

        @case('arrow-left')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M11.707 18.707a1 1 0 0 1-1.414 0l-6-6a1 1 0 0 1 0-1.414l6-6a1 1 0 1 1 1.414 1.414L7.414 11H19a1 1 0 1 1 0 2H7.414l4.293 4.293a1 1 0 0 1 0 1.414z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2" d="M3 12l6-6v4h12v4H9v4l-6-6z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M10.5 17l-4.5-5m0 0l4.5-5M6 12h13" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            @endif
        @break

        @case('user')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10zM3.5 19a8.5 8.5 0 0 1 17 0 1 1 0 0 1-1 1h-15a1 1 0 0 1-1-1z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="7" r="4" fill="currentColor" opacity="0.2" />
                    <path fill="currentColor" opacity="0.2" d="M4 19a8 8 0 1 1 16 0v1H4v-1z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 19a8 8 0 1 1 16 0v1H4v-1z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z" />
                </svg>
            @endif
        @break

        @case('calendar')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 0 0-1 1v1H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1V3a1 1 0 1 0-2 0v1H7V3a1 1 0 0 0-1-1zM4 9h16v11H4V9z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="6" width="18" height="15" rx="2" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 2v4m8-4v4M3 10h18M5 6h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 2v4m8-4v4M3 10h18M5 6h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" />
                </svg>
            @endif
        @break

        @case('heart')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2"
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78v0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78v0z" />
                </svg>
            @endif
        @break

        @case('location')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M11.54 22.351l.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 1 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742zM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2" d="M20 10.5c0 4.5-8 11.5-8 11.5s-8-7-8-11.5a8 8 0 1 1 16 0z" />
                    <circle cx="12" cy="10.5" r="2.5" fill="currentColor" opacity="0.4" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M20 10.5c0 4.5-8 11.5-8 11.5s-8-7-8-11.5a8 8 0 1 1 16 0z" />
                    <circle cx="12" cy="10.5" r="2.5" stroke="currentColor"
                        stroke-width="{{ $strokeWidth }}" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                </svg>
            @endif
        @break

        @case('user-plus')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M8 7a4 4 0 1 1 8 0 4 4 0 0 1-8 0zM6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2M20 8v6m3-3h-6" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="7" r="4" fill="currentColor" opacity="0.2" />
                    <path fill="currentColor" opacity="0.2" d="M4 19v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2H4z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 19v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2M16 11h3m0 0h3m-3 0v-3m0 3v3" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z" />
                </svg>
            @endif
        @break

        {{-- Social Media Icons (static) --}}
        @case('facebook')
            <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
        @break

        @case('instagram')
            <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z" />
            </svg>
        @break

        @case('twitter')
            <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
            </svg>
        @break

        @case('linkedin')
            <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
            </svg>
        @break

        {{-- Extra Icons --}}
        @case('notification')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M5 9a7 7 0 0 1 14 0v4.764l1.822 3.644A1 1 0 0 1 20 19h-4.268a3 3 0 0 1-5.464 0H4a1 1 0 0 1-.822-1.592L5 13.764V9z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2"
                        d="M18 13.764V9a6 6 0 0 0-12 0v4.764l-2 4A.5.5 0 0 0 4.447 19h15.106a.5.5 0 0 0 .447-.776l-2-4z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19a3 3 0 0 1-6 0m11-5.764l-2-4V9a6 6 0 1 0-12 0v.236l-2 4A.5.5 0 0 0 4.447 19h15.106a.5.5 0 0 0 .447-.776z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 19a3 3 0 0 1-6 0m11-5.764l-2-4V9a6 6 0 0 0-12 0v.236l-2 4A.5.5 0 0 0 4.447 19h15.106a.5.5 0 0 0 .447-.776z" />
                </svg>
            @endif
        @break

        @case('message')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5zm18 3l-9 6-9-6v11h18V8zm0-2H3l9 6 9-6z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2" d="M3 8.2V19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.2l-9 5.4-9-5.4z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M21 8l-9 5.4L3 8m0 0v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8m-18 0l9-5 9 5" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8l-9 5.4L3 8m0 0v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8m-18 0l9-5 9 5" />
                </svg>
            @endif
        @break

        @case('about')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-1 3a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0v-5z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M12 16v-5m0-3h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 16v-5m0-3h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @endif
        @break

        @case('eye')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2"
                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 10a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                    <circle stroke="currentColor" stroke-width="{{ $strokeWidth }}" cx="12" cy="12" r="3" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                    <circle stroke-linecap="round" stroke-linejoin="round" cx="12" cy="12" r="3" />
                </svg>
            @endif
        @break

        @case('edit')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M19.4 7.34L16.66 4.6a2 2 0 0 0-2.83 0l-10 10a2 2 0 0 0-.58 1.21L3 19.5a.5.5 0 0 0 .5.5l3.7-.25a2 2 0 0 0 1.21-.58l10-10a2 2 0 0 0 0-2.83z" />
                    <path fill="white" opacity="0.3" d="M6.5 17.5l9.5-9.5 1.5 1.5-9.5 9.5-2-.5.5-2z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.15"
                        d="M3.83 15.83l10-10 2.83 2.83-10 10a2 2 0 0 1-1.2.58l-3.7.25a.5.5 0 0 1-.51-.5l.25-3.7a2 2 0 0 1 .58-1.21z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.83 5.83l2.83 2.83m-12.83 7.17l10-10 2.83 2.83-10 10a2 2 0 0 1-1.2.58l-3.7.25a.5.5 0 0 1-.51-.5l.25-3.7a2 2 0 0 1 .58-1.21z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            @endif
        @break

        @case('edit_1')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            @endif
        @break

        @case('delete')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M8 5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v1h4a1 1 0 1 1 0 2h-1v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8H4a1 1 0 1 1 0-2h4V5zm2 3a1 1 0 0 0-1 1v8a1 1 0 1 0 2 0V9a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v8a1 1 0 1 0 2 0V9a1 1 0 0 0-1-1z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2" d="M7 8h10v11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V8z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1m-6 0h10m-1 0v11a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8m4 3v6m4-6v6" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M9 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-6-2h10" />
                </svg>
            @endif
        @break

        @case('add')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM8 12a1 1 0 0 1 1-1h2V9a1 1 0 1 1 2 0v2h2a1 1 0 1 1 0 2h-2v2a1 1 0 1 1-2 0v-2H9a1 1 0 0 1-1-1z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M12 8v8m4-4H8m13 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v8m4-4H8m13 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @endif
        @break

        @case('save')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            @endif
        @break

        @case('check')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 1 1 1.414-1.414L11 12.086l3.293-3.293a1 1 0 1 1 1.414 1.414z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @endif
        @break

        @case('warning')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a2 2 0 0 1 3.414 0l8 8a2 2 0 0 1 0 2.828l-8 8a2 2 0 0 1-2.828 0l-8-8a2 2 0 0 1 0-2.828l8-8zM12 9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0v-4a1 1 0 0 0-1-1zm0 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" opacity="0.2"
                        d="M10.293 3.293a2 2 0 0 1 3.414 0l8 8a2 2 0 0 1 0 2.828l-8 8a2 2 0 0 1-2.828 0l-8-8a2 2 0 0 1 0-2.828l8-8z" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M10.293 3.293a2 2 0 0 1 3.414 0l8 8a2 2 0 0 1 0 2.828l-8 8a2 2 0 0 1-2.828 0l-8-8a2 2 0 0 1 0-2.828l8-8z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3m0 3h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            @endif
        @break

        @case('error')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM8.707 7.293a1 1 0 0 0-1.414 1.414L10.586 12l-3.293 3.293a1 1 0 1 0 1.414 1.414L12 13.414l3.293 3.293a1 1 0 0 0 1.414-1.414L13.414 12l3.293-3.293a1 1 0 0 0-1.414-1.414L12 10.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @endif
        @break

        @case('share')
            @if ($variant === 'solid')
                <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z" />
                </svg>
            @elseif($variant === 'duotone')
                <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="5" cy="12" r="2" fill="currentColor" opacity="0.2" />
                    <circle cx="19" cy="5" r="2" fill="currentColor" opacity="0.2" />
                    <circle cx="19" cy="19" r="2" fill="currentColor" opacity="0.2" />
                    <path stroke="currentColor" stroke-width="{{ $strokeWidth }}" stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 12h.01M19 5h.01M19 19h.01M7 12a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm14-7a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7.5 10.5L16.5 6m-9 7.5L16.5 18" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                    stroke-width="{{ $strokeWidth }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 1 1 0-2.684m9.632 6.316c-.202-.404-.316-.86-.316-1.342 0-.482.114-.938.316-1.342m0 2.684a3 3 0 1 1 0-2.684M9.316 10.658l6.368-3.316m-6.368 8l6.368 3.316M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
            @endif
        @break




        @case('more-vertical')
    @if ($variant === 'solid')
        <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="5" r="2"/>
            <circle cx="12" cy="12" r="2"/>
            <circle cx="12" cy="19" r="2"/>
        </svg>
    @elseif($variant === 'duotone')
        <svg fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="5" r="2" fill="currentColor" opacity="0.2"/>
            <circle cx="12" cy="12" r="2" fill="currentColor" opacity="0.2"/>
            <circle cx="12" cy="19" r="2" fill="currentColor" opacity="0.2"/>
            <circle cx="12" cy="5" r="1.5" stroke="currentColor" stroke-width="{{ $strokeWidth }}"/>
            <circle cx="12" cy="12" r="1.5" stroke="currentColor" stroke-width="{{ $strokeWidth }}"/>
            <circle cx="12" cy="19" r="1.5" stroke="currentColor" stroke-width="{{ $strokeWidth }}"/>
        </svg>
    @else
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" stroke-width="{{ $strokeWidth }}">
            <circle cx="12" cy="5" r="1.5"/>
            <circle cx="12" cy="12" r="1.5"/>
            <circle cx="12" cy="19" r="1.5"/>
        </svg>
    @endif
@break
@case('quote-left')
    <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M10 8.5c0-2.21-1.79-4-4-4s-4 1.79-4 4c0 1.86 1.28 3.41 3 3.86v2.14c0 .55.45 1 1 1s1-.45 1-1v-2.14c1.72-.45 3-2 3-3.86zm8 0c0-2.21-1.79-4-4-4s-4 1.79-4 4c0 1.86 1.28 3.41 3 3.86v2.14c0 .55.45 1 1 1s1-.45 1-1v-2.14c1.72-.45 3-2 3-3.86z"/>
    </svg>
@break

@case('quote-right')
    <svg fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M14 15.5c0 2.21 1.79 4 4 4s4-1.79 4-4c0-1.86-1.28-3.41-3-3.86V9.5c0-.55-.45-1-1-1s-1 .45-1 1v2.14c-1.72.45-3 2-3 3.86zm-8 0c0 2.21 1.79 4 4 4s4-1.79 4-4c0-1.86-1.28-3.41-3-3.86V9.5c0-.55-.45-1-1-1s-1 .45-1 1v2.14c-1.72.45-3 2-3 3.86z"/>
    </svg>
@break
        {{-- Default fallback --}}

        @default
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                stroke-width="{{ $strokeWidth }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
    @endswitch
</span>

<style>
    /* ===== MODERN PROFESSIONAL ICON SYSTEM CSS ===== */
    .ui-icon {
        display: inline-block;
        line-height: 1;
        flex-shrink: 0;
        vertical-align: middle;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 700;
    }

    /* Icon sizes with modern scaling */
    .ui-icon.w-3 {
        width: 0.75rem;
        height: 0.75rem;
    }

    /* 12px */
    .ui-icon.w-4 {
        width: 1rem;
        height: 1rem;
    }

    /* 16px */
    .ui-icon.w-5 {
        width: 1.25rem;
        height: 1.25rem;
    }

    /* 20px */
    .ui-icon.w-6 {
        width: 1.5rem;
        height: 1.5rem;
    }

    /* 24px */
    .ui-icon.w-8 {
        width: 2rem;
        height: 2rem;
    }

    /* 32px */

    /* Modern color palette */
    .ui-icon.text-blue-600 {
        color: #2563eb;
    }

    .ui-icon.text-gray-600 {
        color: #4b5563;
    }

    .ui-icon.text-green-600 {
        color: #059669;
    }

    .ui-icon.text-amber-600 {
        color: #d97706;
    }

    .ui-icon.text-red-600 {
        color: #dc2626;
    }

    .ui-icon.text-gray-300 {
        color: var(--card);
    }

    .ui-icon.text-gray-900 {
        color: #111827;
    }

    .ui-icon.text-gray-700 {
        color: #374151;
    }

    /* Enhanced interactive states */
    .ui-icon:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }

    .ui-icon.clickable {
        cursor: pointer;
        padding: 0.375rem;
        border-radius: 0.5rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ui-icon.clickable:hover {
        background-color: rgba(0, 0, 0, 0.04);
        transform: scale(1.08);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .ui-icon.clickable:active {
        transform: scale(0.96);
        box-shadow: 0 0 0 rgba(0, 0, 0, 0.1);
    }

    /* Professional hover effects */
    .ui-icon.hover-lift:hover {
        transform: translateY(-2px);
        filter: brightness(1.15);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .ui-icon.hover-rotate:hover {
        transform: rotate(10deg) scale(1.05);
    }

    .ui-icon.hover-pulse:hover {
        animation: modernPulse 1.5s infinite;
    }

    @keyframes modernPulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Modern focus states */
    .ui-icon.clickable:focus,
    .ui-icon.clickable:focus-visible {
        outline: 2px solid #3b82f6;
        outline-offset: 3px;
        border-radius: 0.5rem;
    }

    /* Social icon enhancements */
    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #ffffff;
    }

    .social-link:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .social-link:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }

    /* Dark mode optimizations */
    @media (prefers-color-scheme: dark) {
        .ui-icon.text-gray-700 {
            color: #e5e7eb;
        }

        .ui-icon.text-gray-600 {
            color: var(--muted);
        }

        .ui-icon.text-blue-600 {
            color: var(--accent);

        }

        .ui-icon.text-green-600 {
            color: #34d399;
        }

        .ui-icon.text-red-600 {
            color: #f87171;
        }

        .social-link {
            border-color: #374151;
            background-color: #1f2937;
            color: #e5e7eb;
        }

        .social-link:hover {
            border-color: var(--accent);
            background-color: #1e293b;
            box-shadow: 0 4px 12px rgba(96, 165, 250, 0.2);
        }

        .ui-icon.clickable:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }
    }

    /* Enhanced responsive adjustments */
    @media (max-width: 768px) {
        .ui-icon.w-8 {
            width: 1.75rem;
            height: 1.75rem;
        }

        .ui-icon.w-6 {
            width: 1.375rem;
            height: 1.375rem;
        }

        .social-link {
            width: 36px;
            height: 36px;
        }
    }

    /* Accessibility improvements */
    @media (prefers-reduced-motion: reduce) {

        .ui-icon,
        .social-link,
        .ui-icon.hover-lift,
        .ui-icon.hover-rotate,
        .ui-icon.hover-pulse {
            transition: none !important;
            animation: none !important;
            transform: none !important;
        }

        .ui-icon:hover,
        .social-link:hover {
            transform: none !important;
        }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .ui-icon {
            stroke-width: 3;
            font-weight: 900;
        }

        .social-link {
            border-width: 3px;
        }
    }

    /* Icon loading animation */
    .ui-icon.loading {
        animation: iconSpin 1s linear infinite;
    }

    @keyframes iconSpin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Custom icon badges */
    .ui-icon-badge {
        position: relative;
        display: inline-block;
    }

    .ui-icon-badge::after {
        content: attr(data-badge);
        position: absolute;
        top: -6px;
        right: -6px;
        background-color: #ef4444;
        color: white;
        font-size: 0.625rem;
        font-weight: 700;
        padding: 0.125rem 0.375rem;
        border-radius: 9999px;
        min-width: 1.25rem;
        height: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }



    /* Base ensures SVGs follow currentColor for both stroke & fill */
    .ui-icon {
        color: var(--muted);
    }

    .ui-icon svg {
        display: inline-block;
        width: min-content;
        height: 1.4em !important;
    }

    .ui-icon svg [stroke="currentColor"] {
        stroke: currentColor;
    }

    .ui-icon svg [fill="currentColor"] {
        fill: currentColor;
    }

    /* 4 color variants */
    .ui-icon.color-card {
        color: var(--card, #ffffff);
    }

    .ui-icon.color-accent {
        color: var(--accent, #1351d8);
    }

    .ui-icon.color-ink {
        color: var(--ink, #111827);
    }

    .ui-icon.color-muted {
        color: var(--muted, #6b7280);
    }










    /* === ICON BUTTON (perfect centering) === */
    .edit-card {

        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: background-color .18s ease, transform .18s ease, box-shadow .18s ease;
        -webkit-tap-highlight-color: transparent;
        padding: 0;
    }

    /* hover = soft bg */
    .edit-card:hover {
        background: var(--apc-bg);
    }

    /* focus accessibility */
    .edit-card:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 3px;
    }

    /* === ICON CENTERING === */
    .edit-card .ui-icon {
        display: block;
        width: 45%;
        height: 45%;
        color: var(--ink);
    }

    .edit-card .ui-icon svg {
        width: 100%;
        height: 100%;
        display: block;
    }


    /* === Social bar — centered & professional === */
    .socials {
        /* layout */
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;

        /* sizing tokens */
        --social-size: 40px;
        /* circle */
        --icon-size: 18px;
        /* svg inside */

        margin-top: 8px;
    }

    /* circular button */
    .social-link {
        width: var(--social-size);
        aspect-ratio: 1 / 1;
        display: grid;
        /* perfect centering */
        place-items: center;
        /* ⇡ */
        border-radius: 9999px;

        background: var(--card) !important;
        border: 1px solid var(--border);
        box-shadow: inset 0 1px 0 rgba(0, 0, 0, .03);
        line-height: 0;
        /* no stray inline height */

        transition: background-color .18s ease,
            border-color .18s ease,
            box-shadow .18s ease,
            transform .12s ease;
        text-decoration: none;
    }

    /* icon inside (force neutral by default) */
    .social-link .ui-icon {
        width: var(--icon-size);
        height: var(--icon-size);
        color: var(--muted) !important;
        /* clean monochrome */
    }

    .social-link .ui-icon svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* hover / focus: gentle accent highlight */
    .social-link:hover {
        background: var(--border);
        border-color: var(--accent);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
    }

    .social-link:hover .ui-icon {
        color: var(--accent) !important;
    }

    .social-link:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }

    /* pressed */
    .social-link:active {
        transform: translateY(0);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .10);
    }

    /* Dark mode polish */
    @media (prefers-color-scheme: dark) {
        .social-link {
            background: #0f1115;
            border-color: #1f2430;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .03);
        }

        .social-link:hover {
            background: #151a24;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .35);
        }
    }

    .left-sidebar .ui-icon {
        margin-bottom: 2px !important;
    }



    .ui-edit {
        color: var(--muted) !important;
    }

  /* ===== PRIMARY MUTED VARIANT (New) ===== */

/* Solid version - same as primary solid */
.btn-solid.btn-primary_muted {
    background: var(--accent);
    color: #ffffff;
    border-color: var(--accent);
}

.btn-solid.btn-primary_muted:hover {
    background: #0f46c4;
    color: #ffffff;
}

/* Outlined version - muted border and text */
.btn-outlined.btn-primary_muted {
    background: transparent;
    color: var(--muted);
    border-color: var(--muted);
}

.btn-outlined.btn-primary_muted:hover {
    background: var(--border);
    color: var(--muted);
    border-color: var(--muted);
    box-shadow: inset 0 0 0 1px var(--muted);
}

.btn-outlined.btn-primary_muted:active {
    background: var(--border);
    box-shadow: inset 0 0 0 1px var(--muted), inset 0 2px 4px rgba(0, 0, 0, 0.1);
}





/* Icon color in outlined primary button */
.btn-outlined.btn-primary .ui-icon {
    color: var(--accent);
    transition: color 0.2s ease;
}

/* Icon stays accent color on hover */
.btn-outlined.btn-primary:hover .ui-icon {
    color: var(--accent);
}








/* More icon button styling */
.icon-btn .ui-icon {
    transition: transform 0.2s ease;
}

.icon-btn:hover .ui-icon {
    transform: scale(1.1);
}
</style>
