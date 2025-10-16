<?php
// app/Http/Controllers/Tenant/OnboardingController.php

namespace App\Http\Controllers\Tenant;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;

use App\Models\Skill;
use App\Models\State;
use App\Models\Country;
use App\Models\Education;
use App\Models\Portfolio;
use App\Models\Experience;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ExperienceSkill;
use Illuminate\Validation\Rule;
use App\Models\PortfolioProject;
use App\Services\TimezoneService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'first_name' => ['required', 'string', 'max:120'],
            'last_name'  => ['required', 'string', 'max:120'],
            'username'   => [
                'required',
                'string',
                'min:3',
                'max:50',
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
    
        // ============ VALIDATION ============
        $validated = $request->validate([
            'country'   => ['required', 'string', 'max:120'],
            'state'     => ['required', 'string', 'max:120'],
            'city'      => ['required', 'string', 'max:120'],
            'timezone'  => ['nullable', 'string', 'max:100'],
            'source'    => ['nullable', 'in:search,manual,nominatim,gps,auto'],
        ], [
            'country.required' => 'Please select your country',
            'state.required'   => 'Please select your state/province',
            'city.required'    => 'Please select your city',
        ]);
    
        try {
            DB::beginTransaction();
    
            // ============ NORMALIZE & PREPARE DATA ============
            $country = Str::of($validated['country'])->trim()->title()->substr(0, 120)->toString();
            $state   = Str::of($validated['state'])->trim()->title()->substr(0, 120)->toString();
            $city    = Str::of($validated['city'])->trim()->title()->substr(0, 120)->toString();
    
            // Detect timezone intelligently
            $timezone = $this->detectTimezone(
                $validated['timezone'] ?? null,
                $country,
                $state,
                $city
            );
    
            // ============ VERIFY LOCATION EXISTS IN DATABASE ============
            $locationData = $this->verifyAndGetLocation($country, $state, $city);
    
            // ============ PREPARE META DATA ============
            $existingMeta = $user->profile?->meta ?? [];
    
            $locationMeta = [
                'location' => [
                    'country_id' => $locationData['country_id'] ?? null,
                    'state_id'   => $locationData['state_id'] ?? null,
                    'city_id'    => $locationData['city_id'] ?? null,
                    'verified'   => $locationData['verified'] ?? false,
                    'source'     => $validated['source'] ?? 'search',
                    'updated_at' => now()->toIso8601String(),
                ],
                'timezone_detected' => $timezone,
                'timezone_source'   => $validated['timezone'] ? 'user' : 'auto',
            ];
    
            // Merge with existing meta
            $mergedMeta = array_replace_recursive($existingMeta, $locationMeta);
    
            // ============ UPDATE USER PROFILE ============
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'country' => $country,
                    'state'   => $state,
                    'city'    => $city,
                    'meta'    => $mergedMeta,
                ]
            );
    
            // ============ UPDATE USER (timezone + progress) ============
            // set is_profile_complete => 'education' here
            $user->forceFill([
                'timezone'           => $timezone,
                'is_profile_complete' => 'education',
            ])->save();
    
            // ============ LOG SUCCESS ============
            Log::info('Location stored successfully', [
                'user_id'  => $user->id,
                'location' => "{$city}, {$state}, {$country}",
                'timezone' => $timezone,
                'step'     => 'education',
            ]);
    
            DB::commit();
    
            // ============ SUCCESS RESPONSE ============
            return redirect()
                ->route('tenant.onboarding.education')
                ->with('success', '📍 Location saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            Log::error('Location storage failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
    
            return back()
                ->withInput()
                ->withErrors(['location' => 'Failed to save location. Please try again.']);
        }
    }
    

    /**
     * Verify location exists in database and get IDs
     * 
     * @param string $countryName
     * @param string $stateName
     * @param string $cityName
     * @return array
     */
    private function verifyAndGetLocation(string $countryName, string $stateName, string $cityName): array
    {
        $data = [
            'country_id' => null,
            'state_id'   => null,
            'city_id'    => null,
            'verified'   => false,
        ];

        try {
            // Find country (case-insensitive)
            $country = Country::whereRaw('LOWER(name) = ?', [Str::lower($countryName)])->first();
            
            if (!$country) {
                // Country not found - location not fully verified
                Log::warning('Country not found in database', ['country' => $countryName]);
                return $data;
            }
            
            $data['country_id'] = $country->id;

            // Find state
            $state = State::where('country_id', $country->id)
                ->whereRaw('LOWER(name) = ?', [Str::lower($stateName)])
                ->first();
            
            if (!$state) {
                Log::warning('State not found in database', [
                    'country' => $countryName,
                    'state' => $stateName
                ]);
                return $data;
            }
            
            $data['state_id'] = $state->id;

            // Find city
            $city = City::where('state_id', $state->id)
                ->whereRaw('LOWER(name) = ?', [Str::lower($cityName)])
                ->first();
            
            if (!$city) {
                Log::warning('City not found in database', [
                    'country' => $countryName,
                    'state' => $stateName,
                    'city' => $cityName
                ]);
                return $data;
            }
            
            $data['city_id'] = $city->id;
            $data['verified'] = true;

            return $data;

        } catch (\Exception $e) {
            Log::error('Location verification failed', [
                'error' => $e->getMessage(),
                'location' => "{$cityName}, {$stateName}, {$countryName}"
            ]);
            
            return $data;
        }
    }

    /**
     * Detect timezone from location or user input
     * 
     * @param string|null $userTimezone
     * @param string $country
     * @param string $state
     * @param string $city
     * @return string
     */
    private function detectTimezone(?string $userTimezone, string $country, string $state, string $city): string
    {
        // If user provided timezone, validate and use it
        if ($userTimezone && $this->isValidTimezone($userTimezone)) {
            return $userTimezone;
        }

        // Try to detect timezone from location
        try {
            // Common timezone mappings by country
            $timezoneMap = $this->getTimezoneMap();
            
            $countryLower = Str::lower($country);
            
            // Check if country has specific timezone
            if (isset($timezoneMap[$countryLower])) {
                $countryTimezone = $timezoneMap[$countryLower];
                
                // If it's an array (multiple timezones), try to match by state/city
                if (is_array($countryTimezone)) {
                    $stateLower = Str::lower($state);
                    $cityLower = Str::lower($city);
                    
                    // Try state match
                    foreach ($countryTimezone as $pattern => $tz) {
                        if (Str::contains($stateLower, Str::lower($pattern)) || 
                            Str::contains($cityLower, Str::lower($pattern))) {
                            return $tz;
                        }
                    }
                    
                    // Return first timezone as default
                    return reset($countryTimezone);
                }
                
                return $countryTimezone;
            }

            // Fallback to UTC
            return 'UTC';

        } catch (\Exception $e) {
            Log::warning('Timezone detection failed, using UTC', [
                'error' => $e->getMessage(),
                'location' => "{$city}, {$state}, {$country}"
            ]);
            
            return 'UTC';
        }
    }

    /**
     * Check if timezone is valid
     * 
     * @param string $timezone
     * @return bool
     */
    private function isValidTimezone(string $timezone): bool
    {
        try {
            new \DateTimeZone($timezone);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get comprehensive timezone mapping
     * 
     * @return array
     */
    private function getTimezoneMap(): array
    {
        return [
            // Pakistan
            'pakistan' => 'Asia/Karachi',
            
            // United States (multiple timezones)
            'united states' => [
                'eastern' => 'America/New_York',
                'central' => 'America/Chicago',
                'mountain' => 'America/Denver',
                'pacific' => 'America/Los_Angeles',
                'alaska' => 'America/Anchorage',
                'hawaii' => 'Pacific/Honolulu',
            ],
            
            // United Kingdom
            'united kingdom' => 'Europe/London',
            'england' => 'Europe/London',
            'scotland' => 'Europe/London',
            'wales' => 'Europe/London',
            
            // Canada (multiple timezones)
            'canada' => [
                'newfoundland' => 'America/St_Johns',
                'atlantic' => 'America/Halifax',
                'eastern' => 'America/Toronto',
                'central' => 'America/Winnipeg',
                'mountain' => 'America/Edmonton',
                'pacific' => 'America/Vancouver',
            ],
            
            // India
            'india' => 'Asia/Kolkata',
            
            // Australia (multiple timezones)
            'australia' => [
                'western' => 'Australia/Perth',
                'central' => 'Australia/Adelaide',
                'eastern' => 'Australia/Sydney',
            ],
            
            // European Countries
            'germany' => 'Europe/Berlin',
            'france' => 'Europe/Paris',
            'spain' => 'Europe/Madrid',
            'italy' => 'Europe/Rome',
            'netherlands' => 'Europe/Amsterdam',
            'belgium' => 'Europe/Brussels',
            'switzerland' => 'Europe/Zurich',
            'austria' => 'Europe/Vienna',
            'poland' => 'Europe/Warsaw',
            'sweden' => 'Europe/Stockholm',
            'norway' => 'Europe/Oslo',
            'denmark' => 'Europe/Copenhagen',
            'finland' => 'Europe/Helsinki',
            
            // Middle East
            'united arab emirates' => 'Asia/Dubai',
            'saudi arabia' => 'Asia/Riyadh',
            'turkey' => 'Europe/Istanbul',
            'israel' => 'Asia/Jerusalem',
            
            // Asia
            'china' => 'Asia/Shanghai',
            'japan' => 'Asia/Tokyo',
            'south korea' => 'Asia/Seoul',
            'singapore' => 'Asia/Singapore',
            'malaysia' => 'Asia/Kuala_Lumpur',
            'thailand' => 'Asia/Bangkok',
            'vietnam' => 'Asia/Ho_Chi_Minh',
            'indonesia' => 'Asia/Jakarta',
            'philippines' => 'Asia/Manila',
            'bangladesh' => 'Asia/Dhaka',
            
            // South America
            'brazil' => 'America/Sao_Paulo',
            'argentina' => 'America/Argentina/Buenos_Aires',
            'chile' => 'America/Santiago',
            'colombia' => 'America/Bogota',
            'peru' => 'America/Lima',
            
            // Africa
            'south africa' => 'Africa/Johannesburg',
            'nigeria' => 'Africa/Lagos',
            'kenya' => 'Africa/Nairobi',
            'egypt' => 'Africa/Cairo',
            
            // New Zealand
            'new zealand' => 'Pacific/Auckland',
        ];
    }






























































































    // public function storeLocation(Request $request)
    // {
    //     $user = $request->user();
    
    //     // Validate and normalize inputs
    //     $data = $request->validate([
    //         'country'        => ['required', 'string', 'max:120'],
    //         'state'          => ['required', 'string', 'max:120'],
    //         'city'           => ['required', 'string', 'max:120'],
    //         'timezone'       => ['nullable', 'string', 'max:64'],
    //         'coords.lat'     => ['nullable', 'numeric'],
    //         'coords.lng'     => ['nullable', 'numeric'],
    //         'source'         => ['nullable', 'in:manual,nominatim,gps'],
    //     ]);
    
    //     // Tidy/case – keep readable names
    //     $country = Str::of($data['country'])->trim()->substr(0, 120)->value();
    //     $state   = Str::of($data['state'])->trim()->substr(0, 120)->value();
    //     $city    = Str::of($data['city'])->trim()->substr(0, 120)->value();
    
    //     // Update user profile with location data
    //     $user->profile()->updateOrCreate(
    //         ['user_id' => $user->id],
    //         [
    //             'country' => $country,
    //             'state'   => $state,
    //             'city'    => $city,
    //             'meta'    => array_merge($user->profile?->meta ?? [], [
    //                 'coords' => [
    //                     'lat' => Arr::get($data, 'coords.lat'),
    //                     'lng' => Arr::get($data, 'coords.lng'),
    //                 ],
    //                 'source' => $request->input('source', 'manual'),
    //             ]),
    //         ]
    //     );
    
    //     // Update user's timezone if provided
    //     if (isset($data['timezone'])) {
    //         $user->update(['timezone' => $data['timezone']]);
    //     }
    
    //     // Advance to next onboarding stage
    //     $user->update(['is_profile_complete' => 'skills']);
    
    //     return redirect()
    //         ->route('tenant.onboarding.skills')
    //         ->with('status', 'Location saved. Lets add your skills.');
    // }
    








    // public function storeLocation(Request $request)
    // {
    //     $user = $request->user();

    //     // Validate and normalize inputs coming from manual select OR GPS flow
    //     $data = $request->validate([
    //         'country'        => ['required', 'string', 'max:120'],
    //         'state'          => ['required', 'string', 'max:120'],
    //         'city'           => ['required', 'string', 'max:120'],
    //         'timezone'       => ['nullable', 'string', 'max:64'],
    //         'coords.lat'     => ['nullable', 'numeric'],
    //         'coords.lng'     => ['nullable', 'numeric'],
    //         'source'         => ['nullable', 'in:manual,nominatim,gps'], // optional telemetry
    //     ]);

    //     // tidy/case – keep readable names
    //     $country = Str::of($data['country'])->trim()->substr(0, 120)->value();
    //     $state   = Str::of($data['state'])->trim()->substr(0, 120)->value();
    //     $city    = Str::of($data['city'])->trim()->substr(0, 120)->value();

    //     // merge location telemetry into meta without nuking other keys
    //     $meta = $user->meta ?? [];
    //     $meta['location'] = [
    //         'country' => $country,
    //         'state'   => $state,
    //         'city'    => $city,
    //         'coords'  => [
    //             'lat' => Arr::get($data, 'coords.lat'),
    //             'lng' => Arr::get($data, 'coords.lng'),
    //         ],
    //         'source'  => $request->input('source', 'manual'),
    //     ];

    //     $user->forceFill([
    //         'country'             => $country,
    //         'state'               => $state,
    //         'city'                => $city,
    //         // keep existing timezone unless a valid one is posted
    //         'timezone'            => $data['timezone'] ?? $user->timezone,
    //         // advance to next stage
    //         'is_profile_complete' => 'skills',
    //         'meta'                => $meta,
    //     ])->save();

    //     return redirect()
    //         ->route('tenant.onboarding.skills')
    //         ->with('status', 'Location saved. Let’s add your skills.');
    // }






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
            if (!in_array($level, [1, 2, 3], true)) {
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
            'education' => ['required', 'string'],
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
            if ($start !== null && $end !== null && $end < $start) {
                [$start, $end] = [$end, $start];
            }

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
            'currency'       => ['required', Rule::in(['PKR', 'USD', 'EUR', 'GBP', 'AED', 'INR'])],
            'rate'           => ['nullable', 'numeric', 'min:0'],
            'unit'           => ['required', Rule::in(['/hour', '/day', '/project'])],
            'availability'   => ['required', Rule::in(['now', '1week', '2weeks', '1month'])],
            'hours_per_week' => ['required', Rule::in(['part-time', 'full-time', 'flexible'])],

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
            'is_public' => ['required', Rule::in(['0', '1'])],
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



















    protected function storeDataUrlImage(string $dataUrl, string $dir): ?array
    {
        // returns ['disk' => 'public', 'path' => 'portfolio/{user}/file.jpg']
        if (!preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $m)) {
            return null;
        }
        $ext = strtolower($m[1]);
        $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';

        $binary = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));
        if ($binary === false) return null;

        $name = Str::random(20) . '.' . $ext;
        $path = trim($dir, '/') . '/' . $name;

        Storage::disk('public')->put($path, $binary);

        return ['disk' => 'public', 'path' => $path];
    }


    public function storePortfolio(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'projects' => ['required', 'string'],
        ]);

        $items = json_decode($request->input('projects'), true);
        if (!is_array($items)) {
            return back()->withErrors(['projects' => 'Invalid projects payload.'])->withInput();
        }

        $rows = [];
        foreach (array_values($items) as $i => $p) {
            $title = trim((string) ($p['title'] ?? ''));
            $desc  = trim((string) ($p['description'] ?? ''));
            $link  = trim((string) ($p['link'] ?? ''));
            $img64 = $p['image'] ?? null;

            if ($title === '' || $desc === '') {
                continue;
            }

            // normalize link
            if ($link !== '' && !Str::startsWith($link, ['http://', 'https://'])) {
                $link = 'https://' . ltrim($link, '/');
            }
            if ($link === '') $link = null;

            // store optional dataURL
            $imagePath = null;
            $imageDisk = null;
            if ($img64 && preg_match('/^data:image\/(\w+);base64,/', $img64, $m)) {
                $ext  = strtolower($m[1]);
                $ext  = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';
                $data = base64_decode(substr($img64, strpos($img64, ',') + 1));
                if ($data !== false) {
                    $name = Str::random(20) . '.' . $ext;
                    $dir  = 'portfolio/' . $user->id;
                    $imagePath = $dir . '/' . $name;
                    $imageDisk = 'public';
                    Storage::disk($imageDisk)->put($imagePath, $data);
                }
            }

            $rows[] = [
                'user_id'     => $user->id,
                'title'       => mb_substr($title, 0, 160),
                'description' => mb_substr($desc, 0, 3000),
                'link_url'    => $link,
                'image_path'  => $imagePath,
                'image_disk'  => $imageDisk,
                'position'    => (int) $i,
                'meta'        => null, // or encode tags/category here
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        if (empty($rows)) {
            return back()->withErrors(['projects' => 'Please add at least one project with a title and description.'])->withInput();
        }

        DB::transaction(function () use ($user, $rows) {
            $user->portfolios()->delete();
            Portfolio::insert($rows);

            $user->forceFill(['is_profile_complete' => 'preferences'])->save();
        });

        return redirect()->route('tenant.onboarding.preferences')
            ->with('status', 'Portfolio saved. Set your work preferences.');
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
            return back()
                ->withErrors(['experiences' => 'Invalid experiences payload.'])
                ->withInput();
        }
    
        $now = now();
        $expRows = [];
        $skillsByIndex = [];
    
        foreach ($raw as $i => $row) {
            $company = trim((string)($row['company'] ?? ''));
            $title   = trim((string)($row['title'] ?? ''));
    
            if ($company === '' || $title === '') {
                continue; // skip incomplete
            }
    
            // Normalize dates
            $sm = ($row['startMonth'] ?? '') !== '' ? (int)$row['startMonth'] : null;
            $sy = ($row['startYear']  ?? '') !== '' ? (int)$row['startYear']  : null;
            $em = ($row['endMonth']   ?? '') !== '' ? (int)$row['endMonth']   : null;
            $ey = ($row['endYear']    ?? '') !== '' ? (int)$row['endYear']    : null;
            $current = (bool)($row['current'] ?? false);
    
            if ($current) {
                $em = null;
                $ey = null;
            }
    
            // Guard: end cannot be before start
            if (!$current && $sm && $sy && $em && $ey) {
                $start = Carbon::createFromDate($sy, $sm, 1);
                $end   = Carbon::createFromDate($ey, $em, 1);
                if ($end->lt($start)) {
                    $em = null;
                    $ey = null;
                }
            }
    
            $description     = trim((string)($row['description'] ?? ''));
            $locationCity    = trim((string)($row['locationCity'] ?? ''));
            $locationCountry = trim((string)($row['locationCountry'] ?? ''));
    
            // ✅ Build the experience row (this was missing)
            $expRows[$i] = [
                'user_id'          => $user->id,
                'company'          => mb_substr($company, 0, 120),
                'title'            => mb_substr($title, 0, 120),
                'start_month'      => $sm,
                'start_year'       => $sy,
                'end_month'        => $em,
                'end_year'         => $ey,
                'is_current'       => $current,
                'location_city'    => $locationCity,
                'location_country' => $locationCountry,
                'description'      => mb_substr($description, 0, 2000),
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
    
            // Collect skills
            $skills = array_values(array_filter(($row['skills'] ?? []), fn($s) =>
                isset($s['name']) && trim((string)$s['name']) !== ''
            ));
    
            $skillsByIndex[$i] = array_map(function ($s, $k) use ($now) {
                $lvl = (int)($s['level'] ?? 2);
                $lvl = max(1, min(3, $lvl));
                return [
                    'name'       => mb_substr(trim((string)$s['name']), 0, 120),
                    'level'      => $lvl,
                    'position'   => (int)$k,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $skills, array_keys($skills));
        }
    
        // Guard: must have at least one experience
        if (empty($expRows)) {
            return back()->withErrors([
                'experiences' => 'Please add at least one experience with company & job title.',
            ])->withInput();
        }
    
        DB::transaction(function () use ($user, $expRows, $skillsByIndex) {
            // Clear existing experiences + skills first
            $user->experiences()->each(fn($exp) => $exp->skills()->delete());
            $user->experiences()->delete();
    
            // Create all new experiences + related skills
            foreach ($expRows as $idx => $row) {
                $exp = Experience::create($row);
    
                $skillRows = $skillsByIndex[$idx] ?? [];
                foreach ($skillRows as &$s) {
                    $s['experience_id'] = $exp->id;
                }
                if (!empty($skillRows)) {
                    ExperienceSkill::insert($skillRows);
                }
            }
    
            // Update profile progress
            $user->forceFill(['is_profile_complete' => 'portfolio'])->save();
        });
    
        return redirect()
            ->route('tenant.onboarding.portfolio')
            ->with('status', 'Experience saved successfully. Continue to Portfolio setup.');
    }






















    public function review(Request $request)
    {
        $user = $request->user()->loadMissing([
            'educations'        => fn($q) => $q->orderBy('position'),
            'experiences'       => fn($q) => $q->orderBy('position')->with('skills'),
            'portfolios' => fn($q) => $q->orderBy('position'),
            'preference',
        ]);

        // Build a light snapshot for the view (used if localStorage is empty or incomplete)
        $profile = [
            'name'        => trim($user->first_name . ' ' . $user->last_name) ?: $user->name,
            'initial'     => strtoupper(mb_substr($user->first_name ?: $user->name, 0, 1)),
            'username'    => $user->username ?? ('user-' . $user->id),
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
            'projects'    => $user->portfolios->map(function ($p) {
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
