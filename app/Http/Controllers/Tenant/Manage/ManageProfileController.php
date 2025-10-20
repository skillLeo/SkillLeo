<?php

namespace App\Http\Controllers\Tenant\Manage;

use App\Models\User;
use App\Models\Skill;
use App\Models\Company;
use App\Models\Language;
use App\Models\Education;
use App\Models\Portfolio;
use App\Models\SoftSkill;
use App\Models\Experience;
use App\Models\Institution;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManageProfileController extends Controller
{
 

    public function personal(string $username)
    {
        $owner = User::query()
            ->with([
                'profile:id,user_id,phone,country,state,city,headline,about,banner,banner_preference,social_links,meta',
                'skills',
                'softSkills',
                'languages',
            ])
            ->where('username', $username)
            ->firstOrFail();

        // Canonical username
        $username = $owner->username;

        // Authorization (use your helper if available)
        if (method_exists($this, 'authorizeOwner')) {
            $this->authorizeOwner($owner);
        } else {
            abort_unless(Auth::check() && Auth::id() === $owner->id, 403);
        }

        // Ensure a profile exists
        if (!$owner->profile) {
            $owner->profile()->create([]);
            $owner->load('profile');
        }

        $user    = $owner;          // Blade expects $user
        $profile = $owner->profile; // Blade optionally uses $profile

        return view('tenant.manage.profile.personal', compact(
            'username',
            'owner',
            'user',
            'profile'
        ));
    }


    

    











    public function skills(string $username)
    {
        $owner = User::query()
            ->with([
                'profile',
                'skills' => fn($q) => $q->withPivot(['level', 'position'])->orderBy('user_skills.position'),
                'softSkills' => fn($q) => $q->withPivot(['level', 'position'])->orderBy('user_soft_skills.position'),
            ])
            ->where('username', $username)
            ->firstOrFail();
    
        $username = $owner->username;
        $this->authorizeOwner($owner);
    
        // Build user object for view
        $title = fn($s) => $s !== '' ? Str::of($s)->squish()->title()->toString() : '';
        $first = $owner->name ?? '';
        $last = $owner->last_name ?? '';
        $full = trim($title($first) . ' ' . $title($last)) ?: ($owner->username ?? 'User');
    
        $p = $owner->profile;
        $user = (object)[
            'name' => $full,
            'first_name' => $title($first),
            'last_name' => $title($last),
            'email' => $owner->email,
            'headline' => (string)($p?->headline ?? ''),
            'about' => (string)($p?->about ?? ''),
            'location' => collect([$p?->city, $p?->state, $p?->country])->filter()->join(', ') ?: null,
            'avatar_url' => $owner->avatar_url,
        ];
    
        // Prepare modal data
        $modalSkills = $owner->skills->map(function ($s, $i) {
            return [
                'id' => (int)$s->id,
                'name' => (string)$s->name,
                'level' => (int)($s->pivot->level ?? 2),
                'position' => (int)($s->pivot->position ?? $i),
            ];
        })->values()->all();
    
        $softSkillOptions = \App\Models\SoftSkill::query()
            ->orderBy('name')
            ->get(['slug', 'name', 'icon'])
            ->map(fn($s) => [
                'value' => $s->slug,
                'label' => $s->name,
                'icon' => $s->icon ?: 'sparkles'
            ])
            ->values()->all();
    
        $selectedSoft = $owner->softSkills->pluck('slug')->values()->all();
    
        $skills = $owner->skills;
    
        return view('tenant.manage.profile.skills', compact(
            'username',
            'owner',
            'user',
            'skills',
            'modalSkills',
            'softSkillOptions',
            'selectedSoft'
        ));
    }



















    public function education(string $username)
    {
        $owner = User::query()
            ->with([
                'profile',
                'educations' => fn($q) => $q->orderBy('position'),
            ])
            ->where('username', $username)
            ->firstOrFail();
    
        $username = $owner->username;
        $this->authorizeOwner($owner);
    
        [$owner, $user] = $this->ownerAndView($username, ['educations']);
    
        // Prepare modal data
        $modalEducations = $owner->educations->sortBy('position')->values()->map(function ($e, $i) {
            return [
                'id' => (int)$e->id,
                'school' => (string)($e->school ?? ''),
                'institution_id' => $e->institution_id ? (int)$e->institution_id : null,
                'degree' => (string)($e->degree ?? ''),
                'field' => (string)($e->field ?? ''),
                'start_year' => $e->start_year ? (int)$e->start_year : null,
                'end_year' => $e->end_year ? (int)$e->end_year : null,
                'is_current' => (bool)($e->is_current ?? false),
                'position' => (int)($e->position ?? $i),
            ];
        })->all();
    
        $userEducations = $owner->educations;
    
        return view('tenant.manage.profile.education', compact(
            'username',
            'owner',
            'user',
            'modalEducations',
            'userEducations'
        ));
    }













    








































    public function experience(string $username)
    {
        $owner = User::query()
            ->with([
                'profile',
                'experiences' => fn($q) => $q->with('skills')->orderBy('position'),
            ])
            ->where('username', $username)
            ->firstOrFail();
    
        $username = $owner->username;
        $this->authorizeOwner($owner);
    
        [$owner, $user] = $this->ownerAndView($username, ['experiences.skills']);
    
        // Prepare modal data
        $modalExperiences = $owner->experiences->sortBy('position')->values()->map(function ($e, $i) {
            return [
                'id' => (int)$e->id,
                'company' => (string)($e->company ?? ''),
                'company_id' => $e->company_id ? (int)$e->company_id : null,
                'title' => (string)($e->title ?? ''),
                'start_month' => $e->start_month ? (int)$e->start_month : null,
                'start_year' => $e->start_year ? (int)$e->start_year : null,
                'end_month' => $e->end_month ? (int)$e->end_month : null,
                'end_year' => $e->end_year ? (int)$e->end_year : null,
                'is_current' => (bool)($e->is_current ?? false),
                'location_city' => (string)($e->location_city ?? ''),
                'location_country' => (string)($e->location_country ?? ''),
                'description' => (string)($e->description ?? ''),
                'skills' => $e->skills->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'level' => $s->pivot->level ?? 2
                ])->values()->all(),
                'position' => (int)($e->position ?? $i),
            ];
        })->all();
    
        return view('tenant.manage.profile.experience', compact(
            'username',
            'owner',
            'user',
            'modalExperiences'
        ));
    }
    public function portfolio(string $username)
    {
        $owner = User::query()
            ->with([
                'profile',
                'portfolios' => fn($q) => $q->orderBy('position'),
                'skills' => fn($q) => $q->withPivot(['level','position'])->orderBy('user_skills.position'),
            ])
            ->where('username', $username)
            ->firstOrFail();
    
        $username = $owner->username;
        $this->authorizeOwner($owner);
    
        [$owner, $user] = $this->ownerAndView($username, ['portfolios', 'skills']);
    
        // Prepare modal portfolios
        $modalPortfolios = $owner->portfolios->sortBy('position')->values()->map(function ($p, $i) {
            $meta = is_array($p->meta) ? $p->meta : [];
            return [
                'id' => (int)$p->id,
                'title' => (string)($p->title ?? ''),
                'description' => (string)($p->description ?? ''),
                'link_url' => (string)($p->link_url ?? ''),
                'image_path' => $p->image_path,
                'image_disk' => $p->image_disk ?? 'public',
                'image_url' => $p->image_path 
                    ? Storage::disk($p->image_disk ?? 'public')->url($p->image_path) 
                    : null,
                'skill_ids' => $meta['skill_ids'] ?? [],
                'position' => (int)($p->position ?? $i),
            ];
        })->all();
    
        // User skills for dropdown
        $userSkills = $owner->skills->map(fn($s) => [
            'id' => (int)$s->id,
            'name' => (string)$s->name
        ])->values()->all();
    
        return view('tenant.manage.profile.portfolio', compact(
            'username',
            'owner',
            'user',
            'modalPortfolios',
            'userSkills'
        ));
    }

    public function languages(string $username)
    {
        $owner = User::query()
            ->with([
                'profile',
                // just order the hasMany relation
                'languages' => fn ($q) => $q->orderBy('position'),
            ])
            ->where('username', $username)
            ->firstOrFail();
    
        $username = $owner->username;
        $this->authorizeOwner($owner);
    
        // if your helper also loads, keep it; otherwise you can remove this line
        [$owner, $user] = $this->ownerAndView($username, ['languages']);
    
        // Build modal payload from direct fields
        $modalLanguages = $owner->languages
            ->sortBy('position')
            ->values()
            ->map(function ($l, $i) {
                return [
                    'id'       => (int) $l->id,
                    'name'     => (string) ($l->name ?? ''),
                    'level'    => (int) ($l->level ?? 2),
                    'position' => (int) ($l->position ?? $i),
                ];
            })
            ->all();
    
        return view('tenant.manage.profile.languages', compact(
            'username',
            'owner',
            'user',
            'modalLanguages'
        ));
    }
    


    /* =========================
       SIMPLE SEARCH APIs
       (no username in route)
       ========================= */

    public function companies(Request $request)
    {
        $q     = trim((string)$request->query('q', ''));
        $limit = min(20, (int)$request->query('limit', 10));

        if (class_exists(\App\Models\Company::class)) {
            $data = \App\Models\Company::query()
                ->when($q !== '', fn ($qq) => $qq->where('name', 'like', "%{$q}%"))
                ->orderBy('name')
                ->limit($limit)
                ->get(['id','name','city','country','logo'])
                ->map(fn ($c) => [
                    'id'      => $c->id,
                    'name'    => $c->name,
                    'city'    => $c->city,
                    'country' => $c->country,
                    'logo'    => $c->logo,
                ]);
        } else {
            $data = collect();
        }

        return response()->json(['data' => $data]);
    }

    public function institutions(Request $request)
    {
        $q     = trim((string)$request->query('q', ''));
        $limit = min(20, (int)$request->query('limit', 8));

        if (class_exists(\App\Models\Institution::class)) {
            $data = \App\Models\Institution::query()
                ->when($q !== '', fn ($qq) => $qq->where('name', 'like', "%{$q}%"))
                ->orderBy('name')
                ->limit($limit)
                ->get(['id','name','city','country','logo'])
                ->map(fn ($i) => [
                    'id'      => $i->id,
                    'name'    => $i->name,
                    'city'    => $i->city,
                    'country' => $i->country,
                    'logo'    => $i->logo,
                ]);
        } else {
            $data = collect();
        }

        return response()->json(['data' => $data]);
    }

    /* =========================
       HELPERS
       ========================= */

    protected function owner(string $username, array $with = [])
    {
        return User::query()->with($with)->where('username', $username)->firstOrFail();
    }

    protected function ownerAndView(string $username, array $with = [])
    {
        /** @var \App\Models\User $owner */
        $owner = $this->owner($username, $with);

        $title = static fn ($s) => $s !== '' ? Str::of($s)->squish()->title()->toString() : '';
        $first = $owner->name ?? $owner->name ?? '';
        $last  = $owner->last_name  ?? '';
        $full  = trim($title($first).' '.$title($last)) ?: ($owner->username ?? 'User');

        $p = $owner->profile;
        $user = (object)[
            'name'        => $full,
            'name'  => $title($first),
            'last_name'   => $title($last),
            'email'       => $owner->email,
            'headline'    => (string)($p?->headline ?? $p?->headline ?? ''),
            'about'         => (string)($p?->about ?? ''),
            'location'    => collect([$p?->city, $p?->state, $p?->country])->filter()->join(', ') ?: null,
            'avatar_url'  => $owner->avatar_url,
            'banner_url'  => $owner->banner_url ?? null,
        ];

        return [$owner, $user];
    }

    protected function authorizeOwner(User $owner): void
    {
        if (!auth()->check() || (int)auth()->id() !== (int)$owner->id) {
            abort(403);
        }
    }

    protected function storeBase64(string $dataUri, string $disk, int $userId): string
    {
        [$meta, $content] = explode(',', $dataUri, 2);
        $ext  = str_contains($meta, 'image/png') ? 'png' : 'jpg';
        $path = "users/{$userId}/portfolio/".uniqid('pf_', true).".".$ext;
        Storage::disk($disk)->put($path, base64_decode($content));
        return $path;
    }
}
