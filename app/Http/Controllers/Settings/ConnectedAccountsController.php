<?php
// app/Http/Controllers/Settings/ConnectedAccountsController.php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\OAuthIdentity;
use Illuminate\Http\Request;

class ConnectedAccountsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $linked = $user->oauthIdentities()
            ->select('provider','email','created_at')
            ->orderBy('provider')->get()
            ->groupBy('provider');

            $providers = ['google','linkedin','github'];
        return view('settings.connected-accounts', compact('linked','providers'));
    }

    public function startLink(Request $request, string $provider)
    {
        abort_unless(in_array($provider, ['google','linkedin','github']), 404);
        // Flag the OAuth flow as "linking"
        $request->session()->put('oauth.mode', 'link');
        $request->session()->put('oauth.link.user_id', $request->user()->id);

        return redirect()->route('oauth.redirect', $provider);
    }

    public function unlink(Request $request, string $provider)
    {
        abort_unless(in_array($provider, ['google','linkedin','github']), 404);
        $request->user()->oauthIdentities()
            ->where('provider', $provider)
            ->delete();

        return back()->with('status', ucfirst($provider).' disconnected.');
    }
}
