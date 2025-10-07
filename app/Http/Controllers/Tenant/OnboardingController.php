<?php
// app/Http/Controllers/Tenant/OnboardingController.php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

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

    // Validate minimal strong inputs. We accept ISO2 country code and state/city IDs.
    $data = $request->validate([
        'country'       => ['required','string','size:2'],     // ISO2 code (e.g. US, PK)
        'state_id'      => ['required','integer'],
        'city_id'       => ['required','integer'],

        // Friendly names come from hidden inputs (populated in JS before submit)
        'country_name'  => ['nullable','string','max:120'],
        'state_name'    => ['nullable','string','max:120'],
        'city_name'     => ['nullable','string','max:120'],
    ]);

    // Decide what to store in users table (you currently have string columns)
    // We'll store human-friendly names when provided, and also tuck codes/ids in meta.
    $countryName = $data['country_name'] ?: $data['country'];
    $stateName   = $data['state_name']   ?: (string) $data['state_id'];
    $cityName    = $data['city_name']    ?: (string) $data['city_id'];

    $user->forceFill([
        'country'             => $countryName,
        'state'               => $stateName,
        'city'                => $cityName,
        'is_profile_complete' => 'skills',
        'meta' => array_merge($user->meta ?? [], [
            'location' => [
                'country_code' => strtoupper($data['country']),
                'state_id'     => (int) $data['state_id'],
                'city_id'      => (int) $data['city_id'],
            ],
        ]),
    ])->save();

    return redirect()
        ->route('tenant.onboarding.skills')
        ->with('status', 'Location saved. Let’s add your skills.');
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

    public function storeSkills(Request $request)
    {
        $skills = json_decode($request->input('skills'), true);
        Session::put('tenant.onboarding.skills', $skills);
        return redirect()->route('tenant.onboarding.experience');
    }

    public function experience()
    {
        return view('tenant.onboarding.experience');
    }

    public function storeExperience(Request $request)
    {
        $experiences = json_decode($request->input('experiences'), true);
        Session::put('tenant.onboarding.experience', $experiences);
        return redirect()->route('tenant.onboarding.portfolio');
    }

    public function portfolio()
    {
        return view('tenant.onboarding.portfolio');
    }

    public function storePortfolio(Request $request)
    {
        $projects = json_decode($request->input('projects'), true);
        Session::put('tenant.onboarding.portfolio', $projects);
        return redirect()->route('tenant.onboarding.education');
    }

    public function education()
    {
        return view('tenant.onboarding.education');
    }

    public function storeEducation(Request $request)
    {
        $education = json_decode($request->input('education'), true);
        Session::put('tenant.onboarding.education', $education);
        return redirect()->route('tenant.onboarding.preferences');
    }

    public function preferences()
    {
        return view('tenant.onboarding.preferences');
    }

    public function storePreferences(Request $request)
    {
        // $validated = $request->validate([...]);
        // Session::put('tenant.onboarding.preferences', $validated);
        return redirect()->route('tenant.onboarding.review');
    }

    public function review()
    {
        $data = [
            'personal'    => Session::get('tenant.onboarding.personal'),
            'location'    => Session::get('tenant.onboarding.location'),
            'skills'      => Session::get('tenant.onboarding.skills'),
            'experience'  => Session::get('tenant.onboarding.experience'),
            'portfolio'   => Session::get('tenant.onboarding.portfolio'),
            'education'   => Session::get('tenant.onboarding.education'),
            'preferences' => Session::get('tenant.onboarding.preferences'),
        ];

        return view('tenant.onboarding.review', compact('data'));
    }

    public function storepublish()
    {
        return view('tenant.onboarding.publish');
    }
}
