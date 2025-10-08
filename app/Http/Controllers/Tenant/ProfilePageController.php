<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfilePageController extends Controller
{
    public function index(Request $request, string $username)
    {
        // Find the user by username and eager-load all profile relations
        $user = User::query()
            ->where('username', $username)
            ->with([
                // Use your real relation names here
                'skills:id,user_id,name,level,position',
                'experiences' => function ($q) {
                    $q->orderBy('position')->orderByDesc('start_year')->orderByDesc('start_month');
                },
                'experiences.skills:id,experience_id,name,level,position',
                'educations' => function ($q) {
                    $q->orderBy('position')->orderByDesc('start_year');
                },
                'portfolioProjects' => function ($q) {
                    $q->orderBy('position')->latest('id');
                },
                'preference',
            ])
            ->first();

        if (! $user) {
            throw new NotFoundHttpException();
        }

        // If you want to gate profiles by visibility, uncomment:
        // if (! $user->is_public) { abort(404); }

        // Derive lightweight arrays for the view (keeps blade simple)
        $skills      = $user->skills?->sortBy('position')->values() ?? collect();
        $experiences = $user->experiences ?? collect();
        $educations  = $user->educations ?? collect();
        $projects    = $user->portfolioProjects ?? collect();
        $pref        = $user->preference;

        // Useful computed strings
        $fullName   = trim($user->first_name.' '.$user->last_name) ?: $user->username;
        $location   = trim(collect([$user->city_name, $user->country_name])->filter()->join(', '));
        $profileUrl = rtrim(config('app.url'), '/').'/'.$user->username;

        // Simple SEO meta
        $meta = [
            'title'       => $fullName.' • '.$skills->take(3)->pluck('name')->join(' · '),
            'description' => Str::limit(
                $experiences->first()?->description
                ?? $projects->first()?->description
                ?? 'Professional profile on ProMatch.',
                160
            ),
            'url'         => $profileUrl,
            'image'       => $projects->first()?->cover_url ?? null, // if you store cover images; adjust field name
        ];

        return view('tenant.profile.index', [
            'user'        => $user,
            'fullName'    => $fullName,
            'location'    => $location,
            'profileUrl'  => $profileUrl,
            'skills'      => $skills,
            'experiences' => $experiences,
            'educations'  => $educations,
            'projects'    => $projects,
            'preference'  => $pref,
            'meta'        => $meta,
        ]);
    }
}
