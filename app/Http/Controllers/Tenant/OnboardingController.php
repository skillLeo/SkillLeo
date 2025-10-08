<?php
// app/Http/Controllers/Tenant/OnboardingController.php

namespace App\Http\Controllers\Tenant;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Skill;
 
use App\Models\Education;
use App\Models\Experience;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ExperienceSkill;
use Illuminate\Validation\Rule;
use App\Models\PortfolioProject;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{

    public function scratch(Request $request)
    {
        $user = $request->user();
    
        $user->update([
            'is_profile_complete' => 'personal',
        ]);
    
        return redirect()
            ->route('tenant.onboarding.personal')
            ->with('status', 'Youve started fresh! Please complete your personal details.');
    }
    

 
    
    public function storePersonal(Request $request)
    {
        $user = $request->user();
    
        $data = $request->validate([
            'first_name' => ['required','string','max:120'],
            'last_name'  => ['required','string','max:120'],
            'username'   => [
                'required','string','min:3','max:50',
                'regex:/^[a-z0-9](?:[a-z0-9_-]*[a-z0-9])?$/',
                // unique among OTHER users (ignore current user id)
                'unique:users,username,' . $user->id,
            ],
        ]);
    
        $user->forceFill([
            'name'                => $data['first_name'],
            'last_name'           => $data['last_name'],
            'username'            => $data['username'],
            'is_profile_complete' => 'location',  // ➜ next step
        ])->save();
    
        return redirect()->route('tenant.onboarding.location')
            ->with('status', 'Saved. Continue with your location.');
    }
    

    public function checkUsername(Request $request)
    {
        $raw = (string) $request->query('username', '');
        $username = Str::of($raw)
            ->lower()->ascii()
            ->replaceMatches('/[^a-z0-9_-]+/', '')
            ->trim('-_')
            ->limit(50, '')
            ->value();
    
        if (strlen($username) < 3) {
            return response()->json([
                'status' => 'invalid',
                'error'  => 'At least 3 characters (a-z, 0-9, _ or -).',
            ], 422);
        }
    
        $selfId = (int) optional($request->user())->id;
        $owner  = User::select('id')->where('username', $username)->first();
    
        if (! $owner) {
            return response()->json(['status' => 'available', 'username' => $username], 200);
        }
    
        if ($selfId && (int) $owner->id === $selfId) {
            return response()->json(['status' => 'self', 'username' => $username], 200);
        }
    
        // taken by someone else → find a clean suggestion
        $base = $username;
        $candidates = [
            "{$base}-" . now()->format('Y'),
            "{$base}-" . random_int(10, 99),
            "{$base}-" . random_int(100, 999),
            substr($base, 0, 42) . '-' . random_int(1000, 9999),
        ];
    
        $suggestion = null;
        foreach ($candidates as $cand) {
            $cand = Str::of($cand)->lower()->ascii()
                ->replaceMatches('/[^a-z0-9_-]+/', '')
                ->trim('-_')->limit(50, '')->value();
            if (! User::where('username', $cand)->exists()) {
                $suggestion = $cand;
                break;
            }
        }
    
        return response()->json([
            'status'     => 'taken',
            'username'   => $username,
            'suggestion' => $suggestion,
        ], 409);
    }
    

// app/Http/Controllers/Tenant/OnboardingController.php


public function storeLocation(Request $request)
    {
        $user = $request->user();

        // Validate and normalize inputs coming from manual select OR GPS flow
        $data = $request->validate([
            'country'        => ['required','string','max:120'],
            'state'          => ['required','string','max:120'],
            'city'           => ['required','string','max:120'],
            'timezone'       => ['nullable','string','max:64'],
            'coords.lat'     => ['nullable','numeric'],
            'coords.lng'     => ['nullable','numeric'],
            'source'         => ['nullable','in:manual,nominatim,gps'], // optional telemetry
        ]);

        // tidy/case – keep readable names
        $country = Str::of($data['country'])->trim()->substr(0,120)->value();
        $state   = Str::of($data['state'])->trim()->substr(0,120)->value();
        $city    = Str::of($data['city'])->trim()->substr(0,120)->value();

        // merge location telemetry into meta without nuking other keys
        $meta = $user->meta ?? [];
        $meta['location'] = [
            'country' => $country,
            'state'   => $state,
            'city'    => $city,
            'coords'  => [
                'lat' => Arr::get($data, 'coords.lat'),
                'lng' => Arr::get($data, 'coords.lng'),
            ],
            'source'  => $request->input('source', 'manual'),
        ];

        $user->forceFill([
            'country'             => $country,
            'state'               => $state,
            'city'                => $city,
            // keep existing timezone unless a valid one is posted
            'timezone'            => $data['timezone'] ?? $user->timezone,
            // advance to next stage
            'is_profile_complete' => 'skills',
            'meta'                => $meta,
        ])->save();

        return redirect()
            ->route('tenant.onboarding.skills')
            ->with('status', 'Location saved. Let’s add your skills.');
    }






// Add this method to your OnboardingController or relevant controller

// public function storeLocation(Request $request)
// {
//     $user = $request->user();

//     // Validate inputs
//     $data = $request->validate([
//         'country'        => ['required','string','max:120'],
//         'state'          => ['required','string','max:120'],
//         'city'           => ['required','string','max:120'],
//         'timezone'       => ['nullable','string','max:64'],
//         'coords.lat'     => ['nullable','numeric'],
//         'coords.lng'     => ['nullable','numeric'],
//         'source'         => ['nullable','in:manual,nominatim,gps'],
//     ]);

//     // Clean and trim inputs
//     $country = trim($data['country']);
//     $state   = trim($data['state']);
//     $city    = trim($data['city']);

//     // Update user location
//     $meta = $user->meta ?? [];
//     $meta['location'] = [
//         'country' => $country,
//         'state'   => $state,
//         'city'    => $city,
//         'coords'  => [
//             'lat' => $request->input('coords.lat'),
//             'lng' => $request->input('coords.lng'),
//         ],
//         'source'  => $request->input('source', 'manual'),
//     ];

//     $user->forceFill([
//         'country'             => $country,
//         'state'               => $state,
//         'city'                => $city,
//         'timezone'            => $data['timezone'] ?? $user->timezone ?? 'UTC',
//         'is_profile_complete' => 'education', // or next step
//         'meta'                => $meta,
//     ])->save();

//     return redirect()
//         ->route('tenant.onboarding.education')
//         ->with('status', 'Location saved successfully!');
// }


    public function storeSkills(Request $request)
    {
        $user = $request->user();

        // Expecting: skills = JSON string: [{ name: string, level: 1|2|3 }, ...]
        $request->validate([
            'skills' => ['required', 'string'],
        ]);

        // Decode and sanitize
        $raw = json_decode($request->input('skills'), true);
        if (!is_array($raw)) {
            return back()->withErrors(['skills' => 'Invalid skills payload.'])->withInput();
        }

        // Filter, normalize, validate items
        $clean = [];
        foreach ($raw as $i => $row) {
            $name  = isset($row['name']) ? trim((string) $row['name']) : '';
            $level = (int) ($row['level'] ?? 2);

            if ($name === '' || strlen($name) > 100) {
                continue;
            }
            if (!in_array($level, [1,2,3], true)) {
                $level = 2;
            }

            // Keep original display name; ensure unique by slug for dedupe
            $slug = Str::of($name)
                ->lower()
                ->ascii() // keep things URL friendly; "C#/.NET" -> "c-net"
                ->replaceMatches('/[^a-z0-9]+/i', '-')
                ->trim('-')
                ->substr(0, 120)
                ->value();

            if ($slug === '') continue;

            $clean[$slug] = [
                'name'  => $name,
                'slug'  => $slug,
                'level' => $level,
                'pos'   => $i, // preserve order user added
            ];
        }

        // Business rules
        if (count($clean) < 3) {
            return back()->withErrors(['skills' => 'Please add at least 3 skills.'])->withInput();
        }
        if (count($clean) > 10) {
            return back()->withErrors(['skills' => 'Maximum 10 skills allowed.'])->withInput();
        }

        DB::transaction(function () use ($user, $clean) {
            // Ensure skills exist (upsert by slug), collect IDs
            $idsBySlug = [];

            // Bulk-read existing
            $existing = Skill::query()
                ->whereIn('slug', array_keys($clean))
                ->pluck('id', 'slug')
                ->all();

            // Create missing
            foreach ($clean as $slug => $row) {
                if (isset($existing[$slug])) {
                    $idsBySlug[$slug] = $existing[$slug];
                    continue;
                }
                $skill = Skill::create([
                    'name' => $row['name'],
                    'slug' => $slug,
                ]);
                $idsBySlug[$slug] = $skill->id;
            }

            // Build sync payload: [skill_id => ['level'=>..,'position'=>..]]
            $sync = [];
            foreach ($clean as $slug => $row) {
                $skillId = $idsBySlug[$slug];
                $sync[$skillId] = [
                    'level'    => $row['level'],
                    'position' => (int) $row['pos'],
                ];
            }

            // Replace user's skills with new set (idempotent)
            $user->skills()->sync($sync);

            // Advance onboarding stage
            $user->forceFill([
                'is_profile_complete' => 'education',
            ])->save();
        });

        return redirect()
            ->route('tenant.onboarding.education')
            ->with('status', 'Skills saved. Let’s add your education.');
    }









    public function storeEducation(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'education' => ['required','string'],
        ]);

        $payload = json_decode($request->input('education'), true);
        if (!is_array($payload)) {
            return back()->withErrors(['education' => 'Invalid education payload.'])->withInput();
        }

        $currentYear = (int) now()->year;
        $minYear = 1950;
        $maxYear = $currentYear + 6;

        $rows = [];
        foreach ($payload as $i => $e) {
            $school = trim((string) ($e['school'] ?? ''));
            $degree = trim((string) ($e['degree'] ?? ''));
            $field  = trim((string) ($e['field']  ?? ''));
            if ($school === '' || $degree === '') continue;

            $start = ($e['startYear'] ?? '') !== '' ? (int) $e['startYear'] : null;
            $end   = ($e['endYear']   ?? '') !== '' ? (int) $e['endYear']   : null;
            $curr  = (bool) ($e['current'] ?? false);

            if ($start !== null && ($start < $minYear || $start > $maxYear)) $start = null;
            if ($end   !== null && ($end   < $minYear || $end   > $maxYear)) $end   = null;
            if ($curr) $end = null;
            if ($start !== null && $end !== null && $end < $start) { [$start, $end] = [$end, $start]; }

            $rows[] = [
                'user_id'        => $user->id,
                'institution_id' => $e['institution_id'] ?? null,
                'school'         => mb_substr($school, 0, 180),
                'degree'         => mb_substr($degree, 0, 160),
                'field'          => $field !== '' ? mb_substr($field, 0, 160) : null,
                'start_year'     => $start,
                'end_year'       => $end,
                'is_current'     => $curr,
                'position'       => (int) $i,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        if (empty($rows)) {
            return back()->withErrors(['education' => 'Please add at least one valid education entry.'])->withInput();
        }

        DB::transaction(function () use ($user, $rows) {
            $user->educations()->delete();
            Education::insert($rows);

            $user->forceFill([
                'is_profile_complete' => 'experience',
            ])->save();
        });

        return redirect()
            ->route('tenant.onboarding.experience')
            ->with('status', 'Education saved. Let’s add your experience.');
    }

 






    public function storePreferences(Request $request)
    {
        $user = $request->user();
    
        // Validate request
        $validated = $request->validate([
            'currency'       => ['required', Rule::in(['PKR','USD','EUR','GBP','AED','INR'])],
            'rate'           => ['nullable','numeric','min:0'],
            'unit'           => ['required', Rule::in(['/hour','/day','/project'])],
            'availability'   => ['required', Rule::in(['now','1week','2weeks','1month'])],
            'hours_per_week' => ['required', Rule::in(['part-time','full-time','flexible'])],
    
            // toggles come as "on"/null; we normalize below
            'remote_work'    => ['nullable'],
            'open_to_work'   => ['nullable'],
            'long_term'      => ['nullable'],
        ]);
    
        // Normalize toggles to booleans
        $data = [
            'currency'       => $validated['currency'],
            'rate'           => isset($validated['rate']) ? (float) $validated['rate'] : null,
            'unit'           => $validated['unit'],
            'availability'   => $validated['availability'],
            'hours_per_week' => $validated['hours_per_week'],
            'remote_work'    => (bool) $request->boolean('remote_work'),
            'open_to_work'   => (bool) $request->boolean('open_to_work'),
            'long_term'      => (bool) $request->boolean('long_term'),
        ];
    
        // Upsert preferences for this user
        $user->preference()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    
        // Advance onboarding
        $user->forceFill(['is_profile_complete' => 'review'])->save();
    
        return redirect()
            ->route('tenant.onboarding.review')
            ->with('status', 'Preferences saved. Review your profile before publishing.');
    }






    public function storeReview(Request $request)
    {
        $user = $request->user();
    
        $validated = $request->validate([
            'is_public' => ['required', Rule::in(['0','1'])],
        ]);
    
        $user->forceFill([
            'is_public'           => $validated['is_public'] === '1',
            'is_profile_complete' => 'publish',    // 👈 advance to publish step
        ])->save();
    
        return redirect()
            ->route('tenant.onboarding.publish')   // 👈 go to the publish page (GET)
            ->with('status', 'Ready to publish your profile.');
    }


    public function storePublish(Request $request)
    {
        $user = $request->user();

        // mark fully completed
        $user->forceFill([
            'is_profile_complete' => 'completed',
        ])->save();

        // you may also clear onboarding localStorage on the client if you wish

        // redirect to public profile route
        return redirect()->route('tenant.profile', ['username' => $user->username]);
    }

















    





    public function storePortfolio(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'experiences' => ['required', 'string'],
        ]);

        $payload = json_decode($request->input('experiences'), true);
        if (!is_array($payload)) {
            return back()->withErrors(['experiences' => 'Invalid experiences payload.'])->withInput();
        }

        // sanitize & normalize
        $rows = [];
        $skillsByIndex = [];
        $currentYear = (int) now()->year;
        $minYear = 1950;
        $maxYear = $currentYear + 6;

        foreach (array_values($payload) as $i => $e) {
            $company = trim((string) ($e['company'] ?? ''));
            $title   = trim((string) ($e['title']   ?? ''));
            if ($company === '' || $title === '') {
                continue; // skip incomplete row
            }

            $startMonth = $this->clampMonth($e['startMonth'] ?? null);
            $endMonth   = $this->clampMonth($e['endMonth']   ?? null);
            $startYear  = $this->clampYear($e['startYear']   ?? null, $minYear, $maxYear);
            $endYear    = $this->clampYear($e['endYear']     ?? null, $minYear, $maxYear);
            $current    = (bool) ($e['current'] ?? false);

            if ($current) {
                $endMonth = null;
                $endYear  = null;
            } else {
                // if both present and end < start, nullify end
                if ($startYear && $startMonth && $endYear && $endMonth) {
                    $s = ($startYear * 12) + $startMonth;
                    $n = ($endYear   * 12) + $endMonth;
                    if ($n < $s) {
                        $endMonth = null;
                        $endYear  = null;
                    }
                }
            }

            $rows[] = [
                'user_id'          => $user->id,
                'company'          => mb_substr($company, 0, 180),
                'company_id'       => $e['company_id'] ?? null,
                'title'            => mb_substr($title, 0, 160),
                'start_month'      => $startMonth,
                'start_year'       => $startYear,
                'end_month'        => $endMonth,
                'end_year'         => $endYear,
                'is_current'       => $current,
                'location_city'    => $e['locationCity']    ? mb_substr((string)$e['locationCity'], 0, 120)    : null,
                'location_country' => $e['locationCountry'] ? mb_substr((string)$e['locationCountry'], 0, 120) : null,
                'description'      => $e['description']     ? mb_substr((string)$e['description'], 0, 65535)   : null,
                'position'         => $i,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];

            // skills (allow duplicates)
            $skills = [];
            foreach (($e['skills'] ?? []) as $j => $s) {
                $name  = trim((string) ($s['name'] ?? ''));
                if ($name === '') continue;
                $level = (int) ($s['level'] ?? 2);
                if ($level < 1 || $level > 3) $level = 2;
                $skills[] = [
                    'name'      => mb_substr($name, 0, 120),
                    'level'     => $level,
                    'position'  => $j,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ];
            }
            $skillsByIndex[$i] = $skills;
        }

        if (empty($rows)) {
            return back()->withErrors(['experiences' => 'Add at least one experience with company & job title.'])->withInput();
        }

        DB::transaction(function () use ($user, $rows, $skillsByIndex) {
            // reset user experiences for idempotent onboarding
            $user->experiences()->delete();

            foreach ($rows as $i => $row) {
                /** @var \App\Models\Experience $exp */
                $exp = Experience::create($row);

                $skills = $skillsByIndex[$i] ?? [];
                foreach ($skills as $s) {
                    $exp->skills()->create($s);
                }
            }

            // advance onboarding
            $user->forceFill(['is_profile_complete' => 'portfolio'])->save();
        });

        return redirect()
            ->route('tenant.onboarding.portfolio')
            ->with('status', 'Experience saved. Let’s add your portfolio.');
    }

    private function clampMonth($m): ?int
    {
        $m = $m === '' || $m === null ? null : (int) $m;
        return ($m !== null && $m >= 1 && $m <= 12) ? $m : null;
    }

    private function clampYear($y, int $min, int $max): ?int
    {
        $y = $y === '' || $y === null ? null : (int) $y;
        return ($y !== null && $y >= $min && $y <= $max) ? $y : null;
    }



























    public function storeExperience(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'experiences' => ['required', 'string'],
        ]);

        $raw = json_decode($request->input('experiences'), true);
        if (!is_array($raw)) {
            return back()->withErrors(['experiences' => 'Invalid experiences payload.'])->withInput();
        }

        $now = now();
        $expRows = [];
        $skillsByIndex = [];

        foreach ($raw as $i => $row) {
            $company = trim((string)($row['company'] ?? ''));
            $title   = trim((string)($row['title']   ?? ''));

            if ($company === '' || $title === '') {
                // Ignore incomplete lines (UI should prevent these)
                continue;
            }

            // Normalize dates
            $sm = ($row['startMonth'] ?? '') !== '' ? (int)$row['startMonth'] : null;
            $sy = ($row['startYear']  ?? '') !== '' ? (int)$row['startYear']  : null;
            $em = ($row['endMonth']   ?? '') !== '' ? (int)$row['endMonth']   : null;
            $ey = ($row['endYear']    ?? '') !== '' ? (int)$row['endYear']    : null;
            $current = (bool)($row['current'] ?? false);

            if ($current) { $em = null; $ey = null; }

            // Guard: end cannot be before start
            if (!$current && $sm && $sy && $em && $ey) {
                $start = Carbon::createFromDate($sy, $sm, 1);
                $end   = Carbon::createFromDate($ey, $em, 1);
                if ($end->lt($start)) { $em = null; $ey = null; }
            }

            // ✅ FIX: the 2nd arg of mb_substr is START; we want a MAX LENGTH => use (0, N)
            $description      = trim((string)($row['description'] ?? ''));
            $locationCity     = trim((string)($row['locationCity'] ?? ''));
            $locationCountry  = trim((string)($row['locationCountry'] ?? ''));

            $expRows[] = [
                'user_id'          => $user->id,
                'company'          => mb_substr($company, 0, 160),
                'company_id'       => $row['company_id'] ?? null,
                'title'            => mb_substr($title, 0, 160),
                'description'      => $description !== '' ? mb_substr($description, 0, 3000) : null,
                'location_city'    => $locationCity !== '' ? mb_substr($locationCity, 0, 120) : null,
                'location_country' => $locationCountry !== '' ? mb_substr($locationCountry, 0, 120) : null,
                'start_month'      => $sm,
                'start_year'       => $sy,
                'end_month'        => $em,
                'end_year'         => $ey,
                'is_current'       => $current,
                'position'         => (int)$i,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];

            // Collect skills for this experience index
            $skills = array_values(array_filter(($row['skills'] ?? []), function ($s) {
                return isset($s['name']) && trim((string)$s['name']) !== '';
            }));

            $skillsByIndex[$i] = array_map(function ($s, $k) use ($now) {
                $lvl = (int)($s['level'] ?? 2);
                $lvl = max(1, min(3, $lvl));
                return [
                    'name'        => mb_substr(trim((string)$s['name']), 0, 120),
                    'level'       => $lvl,
                    'position'    => (int)$k,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }, $skills, array_keys($skills));
        }

        if (empty($expRows)) {
            return back()->withErrors([
                'experiences' => 'Please add at least one experience with Company & Job title.',
            ])->withInput();
        }

        DB::transaction(function () use ($user, $expRows, $skillsByIndex) {
            // Replace user’s experiences atomically
            $user->experiences()->each(function ($exp) { $exp->skills()->delete(); });
            $user->experiences()->delete();

            foreach ($expRows as $idx => $row) {
                /** @var \App\Models\Experience $exp */
                $exp = Experience::create($row);

                $skillRows = $skillsByIndex[$idx] ?? [];
                foreach ($skillRows as &$s) { $s['experience_id'] = $exp->id; }
                if (!empty($skillRows)) {
                    ExperienceSkill::insert($skillRows);
                }
            }

            // ✅ Advance to PREFERENCES (not portfolio)
            $user->forceFill(['is_profile_complete' => 'preferences'])->save();
        });

        // ✅ Redirect to the Preferences page
        return redirect()
            ->route('tenant.onboarding.preferences')
            ->with('status', 'Experience saved. Set your preferences next.');
    }














    /**
     * Store a base64 data URL image to public disk.
     * Returns the storage path or null.
     */
    protected function storeDataUrlImage(int $userId, string $dataUrl, int $projectId): ?string
    {
        // data:image/{type};base64,{payload}
        if (!preg_match('#^data:image/([a-zA-Z0-9.+-]+);base64,(.+)$#', $dataUrl, $m)) {
            return null;
        }
        $ext = strtolower($m[1]); // e.g. png, jpeg, webp
        $ext = $ext === 'jpg' ? 'jpeg' : $ext;

        // guard: only allow common formats
        $allowed = ['png','jpeg','webp'];
        if (!in_array($ext, $allowed, true)) {
            $ext = 'jpeg';
        }

        $bin = base64_decode($m[2], true);
        if ($bin === false || strlen($bin) < 16) {
            return null;
        }

        $dir = "portfolio/{$userId}";
        $name = 'p_'.$projectId.'_'.Str::random(8).'.'.$ext;
        $path = $dir.'/'.$name;

        Storage::disk('public')->put($path, $bin);
        return $path;
    }













    
    public function review(Request $request)
    {
        $user = $request->user()->loadMissing([
            'educations'        => fn ($q) => $q->orderBy('position'),
            'experiences'       => fn ($q) => $q->orderBy('position')->with('skills'),
            'portfolioProjects' => fn ($q) => $q->orderBy('position'),
            'preference',
        ]);
    
        // Build a light snapshot for the view (used if localStorage is empty or incomplete)
        $profile = [
            'name'        => trim($user->first_name.' '.$user->last_name) ?: $user->name,
            'initial'     => strtoupper(mb_substr($user->first_name ?: $user->name, 0, 1)),
            'username'    => $user->username ?? ('user-'.$user->id),
            'location'    => trim(implode(', ', array_filter([$user->city, $user->country]))),
            'skills'      => collect($user->skills ?? [])->values(), // if you store global skills on user
            'experiences' => $user->experiences->map(function ($e) {
                return [
                    'company'       => $e->company,
                    'title'         => $e->title,
                    'start_month'   => $e->start_month,
                    'start_year'    => $e->start_year,
                    'end_month'     => $e->end_month,
                    'end_year'      => $e->end_year,
                    'is_current'    => (bool)$e->is_current,
                    'skills'        => $e->skills->map(fn($s) => ['name' => $s->name, 'level' => (int)$s->level])->values(),
                ];
            })->values(),
            'projects'    => $user->portfolioProjects->map(function ($p) {
                return [
                    'title'       => $p->title,
                    'description' => $p->description,
                    'link'        => $p->url,
                    'image'       => $p->cover_url, // if you store one
                ];
            })->values(),
            'is_public'   => (bool) ($user->is_public ?? true),
        ];
    
        return view('tenant.onboarding.review', compact('profile'));
    }
    


























































































































































    public function profile()
    {
        $user = (object) [
            'name'        => 'Hassam Mehmood',
            'facebook'    => 'Hassam Mehmood',
            'instagram'   => 'Hassam Mehmood',
            'twitter'     => 'Hassam Mehmood',
            'linkedin'    => 'Hassam Mehmood',
            'bio'         => 'Full-Stack Laravel & React Developer | AI & Chatbot Integration Expert',
            'location'    => 'Sargodha, Pakistan',
            'avatar'      => null,
            'banner'      => null,
            'open_to_work'=> true,
            'about'       => 'I am a problem solver who writes and maintains the code that powers websites, applications I am a problem solver who writes and maintains the code that powers websites, applications',
            'skills'      => ['Web Development', 'Laravel', 'React'],
            'topSkills'   => ['Web Development', 'PHP', 'Laravel'],
            'softSkills'  => [
                ['name' => 'Problem-solving', 'icon' => 'lightbulb'],
                ['name' => 'Communication skills', 'icon' => 'mobile-screen'],
                ['name' => 'Time management', 'icon' => 'clock'],
            ],
            'languages'   => [
                ['name' => 'English', 'level' => 'Professional'],
                ['name' => 'Urdu', 'level' => 'Native'],
                ['name' => 'Arabic', 'level' => 'Basic'],
            ],
            'education'   => [
                [
                    'title'      => 'Computer Science',
                    'institution'=> 'University of Sargodha',
                    'period'     => 'Jul 2024 - Jul 2025',
                    'location'   => 'Sargodha, Pakistan',
                    'recent'     => true,
                ],
            ],
            'whyChooseMe' => [
                'Expert in modern technologies',
                'Fast delivery and quality work',
                'Available 24/7 for support',
            ],
            'services'    => ['Web Development', 'App Development', 'API Integration'],
        ];

        $portfolios          = [];
        $portfolioCategories = ['All', 'Laravel', 'React Js', 'Node Js', 'AI'];
        $skillsData          = [
            ['name' => 'Laravel', 'percentage' => 90],
            ['name' => 'React Js', 'percentage' => 95],
            ['name' => 'Machine Learning', 'percentage' => 100],
        ];
        $experiences         = [];
        $reviews             = [];

        return view('tenant.profile.index', compact(
            'user', 'portfolios', 'portfolioCategories', 'skillsData', 'experiences', 'reviews'
        ))->with([
            'brandName'    => 'Codefixxaaaer',
            'messageCount' => 2,
        ]);
    }

    public function marketing()
    {
        return view('marketing.index');
    }

    public function welcome()
    {
        return view('tenant.onboarding.welcome');
    }

    public function info()
    {
        return view('tenant.onboarding.info');
    }

    public function personal()
    {
        return view('tenant.onboarding.personal');
    }

 
    public function location()
    {
        return view('tenant.onboarding.location');
    }

    public function publish()
    {
        return view('tenant.onboarding.publish');
    }


    public function skills()
    {
        return view('tenant.onboarding.skills');
    }

    

    public function experience()
    {
        return view('tenant.onboarding.experience');
    }



    public function portfolio()
    {
        return view('tenant.onboarding.portfolio');
    }



    public function education()
    {
        return view('tenant.onboarding.education');
    }



    public function preferences()
    {
        return view('tenant.onboarding.preferences');
    }

  

   

 
}
