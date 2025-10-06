<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the tenant's public profile
     * URL: skillleo.com/{username}
     */
    public function show(Request $request, string $username)
    {
        // Find user by username
        $user = User::where('username', $username)
            ->where('is_profile_complete', 'completed')
            ->where('is_active', 'active')
            ->firstOrFail();

        // Load relationships if needed
        $user->load([
            'tenant',
            // Add other relationships as needed
        ]);

        return view('tenant.profile.show', [
            'user' => $user,
            'isOwner' => Auth::check() && Auth::id() === $user->id,
        ]);
    }

    /**
     * Show the form for editing the authenticated tenant's profile
     */
    public function edit(Request $request)
    {
        $user = $request->user();

      

        return view('tenant.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the authenticated tenant's profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

      

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar_url' => ['nullable', 'url'],
            // Add more fields as needed
        ]);

        $user->update($validated);

        return redirect()->route('profile.show', $user->username)
            ->with('success', 'Profile updated successfully!');
    }
}