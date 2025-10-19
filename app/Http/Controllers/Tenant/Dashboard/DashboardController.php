<?php

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{


    public function index(string $username)
    {
        /** @var \App\Models\User $owner */
        $owner = User::query()
            ->with([
                'profile',
                'skills'      => fn($q) => $q->withPivot(['level', 'position'])->orderBy('user_skills.position'),
                'softSkills'  => fn($q) => $q->withPivot(['level', 'position'])->orderBy('user_soft_skills.position'),
                'languages'   => fn($q) => $q->orderBy('position'),
                'educations'  => fn($q) => $q->orderBy('position'),
                'experiences' => fn($q) => $q->orderBy('position')->with('skills'),
                'portfolios'  => fn($q) => $q->orderBy('position'),
                'services'    => fn($q) => $q->orderBy('position'),
                'reasons'     => fn($q) => $q->orderBy('position'),
            ])
            ->where('username', $username)
            ->firstOrFail();
    
        // ===== Basic user view-model =====
        $titleCase = static fn($s) => $s !== '' ? \Illuminate\Support\Str::of($s)->squish()->title()->toString() : '';
        $first = $owner->name ?? '';
        $last  = $owner->last_name ?? '';
        $full  = trim($titleCase($first) . ' ' . $titleCase($last)) ?: ($owner->username ?? 'User');
    
        $p = $owner->profile;
        $social   = is_array($p?->social_links) ? $p->social_links : [];
        $avatar   = $owner->avatar_url;
        $location = collect([$p?->city, $p?->state, $p?->country])->filter()->join(', ');
    
        $user = (object) [
            'first_name'     => $titleCase($first),
            'last_name'      => $titleCase($last),
            'name'           => $full,
            'email'          => $owner->email,
            'headline'       => (string) ($p?->tagline ?? ''),
            'bio'            => (string) ($p?->bio ?? ''),
            'about'          => (string) ($p?->bio ?? ''),
            'location'       => $location ?: null,
            'city'           => $p?->city,
            'state'          => $p?->state,
            'country'        => $p?->country,
            'phone'          => $p?->phone,
            'avatar'         => $avatar,
            'avatar_url'     => $avatar,
            'banner_url'     => $owner->banner_url,           // ✅ Add banner
            'banner_fit'     => $owner->banner_fit,           // ✅ Add fit
            'banner_position'=> $owner->banner_position,      // ✅ Add position
            'facebook'       => $social['facebook'] ?? null,
            'instagram'      => $social['instagram'] ?? null,
            'twitter'        => $social['twitter'] ?? null,
            'linkedin'       => $social['linkedin'] ?? null,
            'is_online'      => (bool) ($owner->is_online ?? false),
            'last_seen_at'   => $owner->last_seen_at,
            'last_seen_text' => $owner->last_seen_text ?? null,
            'open_to_work'   => false,
        ];
    
        // ✅ Pass both $owner (Eloquent model) and $user (view object)
        return view('tenant.dashboard.index', compact('username', 'user', 'owner'));
    }










































































}
