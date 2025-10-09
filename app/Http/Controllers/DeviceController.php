<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Auth\DeviceTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function __construct(
        protected DeviceTrackingService $deviceTracking
    ) {}

    /**
     * Display user's devices (security page)
     */
    public function index()
    {
        $user = Auth::user();
        $devices = $this->deviceTracking->getActiveDevices($user);
        
        return view('user.devices', [
            'devices' => $devices,
            'currentDeviceId' => request()->fingerprint(),
        ]);
    }

    /**
     * Revoke/remove a device
     */
    public function destroy(Request $request, int $deviceId)
    {
        $user = Auth::user();
        
        $device = $user->devices()->findOrFail($deviceId);
        
        // Prevent removing current device
        if ($device->device_id === request()->fingerprint()) {
            return back()->withErrors(['device' => 'You cannot remove your current device.']);
        }
        
        $this->deviceTracking->revokeDevice($user, $deviceId);
        
        return back()->with('status', 'Device removed successfully.');
    }

    /**
     * Mark device as trusted
     */
    public function trust(Request $request, int $deviceId)
    {
        $user = Auth::user();
        $device = $user->devices()->findOrFail($deviceId);
        $device->markAsTrusted();
        
        return back()->with('status', 'Device marked as trusted.');
    }
}