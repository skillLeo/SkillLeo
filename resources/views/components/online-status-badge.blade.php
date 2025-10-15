 
@props([
    'user',
    'size' => 'md',
    'showText' => false,
    'badgePosition' => 'bottom-right'
])

@php
    // Avatar size configurations
    $avatarSizes = [
        'sm' => 'w-10 h-10',
        'md' => 'w-16 h-16',
        'lg' => 'w-24 h-24',
        'xl' => 'w-32 h-32',
    ];
    
    // Badge size configurations
    $badgeSizes = [
        'sm' => 'w-3 h-3 border-2',
        'md' => 'w-4 h-4 border-2',
        'lg' => 'w-5 h-5 border-[3px]',
        'xl' => 'w-6 h-6 border-[3px]',
    ];
    
    // Badge position classes
    $positionClasses = [
        'bottom-right' => 'bottom-0 right-0',
        'bottom-left' => 'bottom-0 left-0',
        'top-right' => 'top-0 right-0',
        'top-left' => 'top-0 left-0',
    ];
    
    $avatarClass = $avatarSizes[$size] ?? $avatarSizes['md'];
    $badgeClass = $badgeSizes[$size] ?? $badgeSizes['md'];
    $positionClass = $positionClasses[$badgePosition] ?? $positionClasses['bottom-right'];
    
    // Determine online status
    $isOnline = false;
    $lastSeenText = 'Offline';
    
    if (isset($user->last_seen_at)) {
        $lastSeenTime = \Carbon\Carbon::parse($user->last_seen_at);
        $now = \Carbon\Carbon::now();
        $diffInMinutes = $now->diffInMinutes($lastSeenTime);
        
        // User is online if last seen within 1 minute
        if ($diffInMinutes < 1) {
            $isOnline = true;
            $lastSeenText = 'Online';
        } else {
            // Format last seen text professionally
            if ($diffInMinutes < 60) {
                $lastSeenText = $diffInMinutes . ' min ago';
            } elseif ($diffInMinutes < 1440) { // Less than 24 hours
                $hours = floor($diffInMinutes / 60);
                $lastSeenText = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
            } elseif ($diffInMinutes < 10080) { // Less than 7 days
                $days = floor($diffInMinutes / 1440);
                $lastSeenText = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
            } else {
                $lastSeenText = 'Last seen ' . $lastSeenTime->format('M d');
            }
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center gap-2']) }}>
    {{-- Avatar with Status Badge --}}
    <div class="relative {{ $avatarClass }} flex-shrink-0">
        {{-- Avatar Image --}}
        <div class="w-full h-full rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
            @if($user->avatar)
                <img 
                    src="{{ $user->avatar }}" 
                    alt="{{ $user->name }}"
                    class="w-full h-full object-cover"
                    loading="lazy"
                >
            @else
                <svg class="w-1/2 h-1/2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>
        
        {{-- Online Status Badge --}}
        <span class="absolute {{ $positionClass }} {{ $badgeClass }} rounded-full border-white bg-gray-400 ring-2 ring-white
            @if($isOnline) bg-green-500 @endif"
            title="{{ $lastSeenText }}"
        >
            @if($isOnline)
                {{-- Pulse animation for online status --}}
                <span class="absolute inset-0 rounded-full bg-green-400 animate-ping opacity-75"></span>
            @endif
        </span>
    </div>
    
    {{-- Optional Status Text --}}
    @if($showText)
        <div class="text-center">
            @if($isOnline)
                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                    Online
                </span>
            @else
                <span class="text-xs text-gray-500">{{ $lastSeenText }}</span>
            @endif
        </div>
    @endif
</div>
