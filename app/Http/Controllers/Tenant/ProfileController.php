<?php
// app/Http/Controllers/Tenant/ProfileController.php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request, string $username)
    {
        $user = User::where('username', $username)
            ->where('is_profile_complete', 'completed')
            ->where('is_active', 'active')
            ->firstOrFail();

        $user->load(['tenant']);

        return view('tenant.profile.index', [
            'user'    => $user,
            'isOwner' => Auth::check() && Auth::id() === $user->id,
        ])->with([
            'brandName'    => 'Codefixxaaaer',
            'messageCount' => 2,
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        return view('tenant.profile.edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'bio'      => ['nullable', 'string', 'max:1000'],
            'avatar_url' => ['nullable', 'url'],
        ]);

        $user->update($validated);

        return redirect()->route('tenant.profile', ['username' => $user->username])
            ->with('success', 'Profile updated successfully!');
    }
}
