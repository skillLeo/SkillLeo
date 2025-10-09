{{-- resources/views/user/devices.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Where You're Logged In</h1>
        
        <p class="text-gray-600 mb-8">
            We'll alert you via email if there is any unusual activity on your account.
        </p>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($devices as $device)
                <div class="bg-white border rounded-lg p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            {{-- Device Icon --}}
                            <div class="text-4xl">
                                @if ($device->device_type === 'mobile')
                                    📱
                                @elseif ($device->device_type === 'tablet')
                                    📲
                                @else
                                    💻
                                @endif
                            </div>

                            <div class="flex-1">
                                {{-- Device Name --}}
                                <h3 class="font-semibold text-lg">
                                    {{ $device->device_display_name }}
                                    
                                    @if ($device->is_current_device)
                                        <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                            Current Device
                                        </span>
                                    @endif

                                    @if ($device->is_trusted)
                                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                            Trusted
                                        </span>
                                    @endif
                                </h3>

                                {{-- Device Details --}}
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    @if ($device->location_city && $device->location_country)
                                        <p>📍 {{ $device->location_city }}, {{ $device->location_country }}</p>
                                    @endif
                                    
                                    @if ($device->ip_address)
                                        <p>🌐 IP: {{ $device->ip_address }}</p>
                                    @endif
                                    
                                    <p>
                                        🕒 Last active: 
                                        <strong>{{ $device->last_activity_at->diffForHumans() }}</strong>
                                    </p>
                                    
                                    <p class="text-xs text-gray-500">
                                        First seen: {{ $device->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col space-y-2">
                            @unless ($device->is_current_device)
                                {{-- Remove Device Button --}}
                                <form method="POST" action="{{ route('account.devices.destroy', $device->id) }}" 
                                      onsubmit="return confirm('Are you sure you want to remove this device?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                        Remove
                                    </button>
                                </form>

                                {{-- Trust Device Button --}}
                                @unless ($device->is_trusted)
                                    <form method="POST" action="{{ route('account.devices.trust', $device->id) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Mark as Trusted
                                        </button>
                                    </form>
                                @endunless
                            @endunless
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <p class="text-lg">No devices found.</p>
                    <p class="text-sm mt-2">Your devices will appear here after you log in.</p>
                </div>
            @endforelse
        </div>

        {{-- Security Tips --}}
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-900 mb-3">Security Tips</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li>✓ Remove any devices you don't recognize</li>
                <li>✓ Keep your password strong and unique</li>
                <li>✓ Enable two-factor authentication for extra security</li>
                <li>✓ Log out from public or shared devices after use</li>
            </ul>
        </div>
    </div>
</div>
@endsection