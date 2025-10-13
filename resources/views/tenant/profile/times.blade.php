<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Details - {{ $timeData['user']['username'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 ">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-6xl mx-auto">
            
            {{-- Header --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center gap-4">
                    <img src="{{ $timeData['user']['avatar_url'] }}" 
                         alt="{{ $timeData['user']['name'] }}" 
                         class="w-16 h-16 rounded-full">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $timeData['user']['name'] }}
                        </h1>
                        <p class="text-gray-600">@{{ $timeData['user']['username'] }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @if($timeData['online_status']['is_online'])
                                <span class="flex h-3 w-3">
                                    <span class="animate-ping absolute h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <span class="text-sm font-medium text-green-600">Online</span>
                            @else
                                <span class="h-3 w-3 rounded-full bg-gray-400"></span>
                                <span class="text-sm text-gray-600">
                                    {{ $timeData['online_status']['status_text'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timezone Info --}}
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                {{-- User's Timezone --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold text-blue-900 mb-4">
                        👤 User's Timezone
                    </h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Timezone:</span>
                            <span class="text-blue-900 font-mono">{{ $timeData['user']['timezone'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Offset:</span>
                            <span class="text-blue-900 font-mono">UTC{{ $timeData['user']['timezone_offset'] }}</span>
                        </div>
                        @if($timeData['location']['location_string'])
                            <div class="flex justify-between">
                                <span class="text-blue-700 font-medium">Location:</span>
                                <span class="text-blue-900">{{ $timeData['location']['location_string'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Viewer's Timezone --}}
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold text-purple-900 mb-4">
                        👁️ Your Timezone (Viewer)
                    </h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-purple-700 font-medium">Timezone:</span>
                            <span class="text-purple-900 font-mono">{{ $timeData['viewer']['timezone'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-purple-700 font-medium">Offset:</span>
                            <span class="text-purple-900 font-mono">UTC{{ $timeData['viewer']['timezone_offset'] }}</span>
                        </div>
                        @if($timeData['viewer']['is_owner'])
                            <div class="mt-3 px-3 py-2 bg-purple-200 rounded-md">
                                <p class="text-sm text-purple-900 font-medium">
                                    ✅ You are viewing your own profile
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">⏰ Timestamps</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($timeData['timestamps'] as $key => $timestamp)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $timestamp['label'] }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $timestamp['human'] }}
                                    </p>
                                </div>
                                @if($key === 'last_seen_at')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($timeData['online_status']['is_online']) 
                                            bg-green-100 text-green-800
                                        @elseif($timeData['online_status']['status'] === 'active_recently')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ strtoupper($timeData['online_status']['status']) }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid md:grid-cols-3 gap-4 text-sm">
                                {{-- UTC Time --}}
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">
                                        🌍 UTC (Database)
                                    </p>
                                    <p class="font-mono text-gray-900">
                                        {{ $timestamp['utc_formatted'] }}
                                    </p>
                                </div>

                                {{-- User's Timezone --}}
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-xs font-medium text-blue-700 uppercase mb-1">
                                        👤 User's Time ({{ $timeData['user']['timezone'] }})
                                    </p>
                                    <p class="font-mono text-blue-900">
                                        {{ $timestamp['user_timezone'] }}
                                    </p>
                                </div>

                                {{-- Viewer's Timezone --}}
                                <div class="bg-purple-50 rounded-lg p-3">
                                    <p class="text-xs font-medium text-purple-700 uppercase mb-1">
                                        👁️ Your Time ({{ $timeData['viewer']['timezone'] }})
                                    </p>
                                    <p class="font-mono text-purple-900">
                                        {{ $timestamp['viewer_timezone'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Activity Summary --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📊 Activity Summary</h2>
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <p class="text-sm text-green-700 font-medium">Total Logins</p>
                        <p class="text-3xl font-bold text-green-900">
                            {{ number_format($timeData['activity']['login_count']) }}
                        </p>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-blue-700 font-medium">Account Age</p>
                        <p class="text-3xl font-bold text-blue-900">
                            {{ number_format($timeData['activity']['account_age_days']) }}
                        </p>
                        <p class="text-xs text-blue-600">days</p>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <p class="text-sm text-purple-700 font-medium">Account Status</p>
                        <p class="text-lg font-bold text-purple-900 capitalize">
                            {{ str_replace('_', ' ', $timeData['activity']['account_status']) }}
                        </p>
                    </div>
                    
                    <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                        <p class="text-sm text-orange-700 font-medium">Profile Status</p>
                        <p class="text-lg font-bold text-orange-900 capitalize">
                            {{ str_replace('_', ' ', $timeData['activity']['profile_complete']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Recent Devices --}}
            @if(count($timeData['devices']) > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">💻 Recent Devices</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @foreach($timeData['devices'] as $device)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                {{ $device['device_name'] }}
                                            </h4>
                                            @if($device['is_current'])
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                    CURRENT
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            {{ $device['browser'] }} on {{ $device['platform'] }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $device['last_seen_at']['human'] }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $device['last_seen_at']['formatted'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Back Button --}}
            <div class="mt-6 text-center">
                <a href="{{ route('tenant.profile', $timeData['user']['username']) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    ← Back to Profile
                </a>
            </div>

        </div>
    </div>

    {{-- Auto-refresh script (optional) --}}
    <script>
        // Auto-refresh every 30 seconds to show updated times
        setTimeout(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>