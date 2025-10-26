<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Session;

class OnboardingController extends Controller
{
    public function welcome()
    {
        return view('onboarding.welcome');
    }

    public function personal()
    {
        return view('onboarding.personal');
    }

    public function storePersonal(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'username'   => ['required','string','max:255','unique:users,username'],
        ]);

        Session::put('onboarding.personal', $validated);

        return redirect()->route('tenant.onboarding.location');
    }

    public function location()
    {
        return view('onboarding.location');
    }

    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'country' => ['required','string'],
            'state'   => ['required','string'],
            'city'    => ['required','string'],
        ]);

        Session::put('onboarding.location', $validated);

        return redirect()->route('tenant.onboarding.skills');
    }

    public function skills()
    {
        return view('onboarding.skills');
    }

    public function storeSkills(Request $request)
    {
        $skills = json_decode($request->input('skills'), true);
        Session::put('onboarding.skills', $skills);

        return redirect()->route('tenant.onboarding.experience');
    }

    public function experience()
    {
        return view('onboarding.experience');
    }

    public function storeExperience(Request $request)
    {
        $experiences = json_decode($request->input('experiences'), true);
        Session::put('onboarding.experience', $experiences);

        return redirect()->route('tenant.onboarding.portfolio');
    }

    public function portfolio()
    {
        return view('onboarding.portfolio');
    }

    public function storePortfolio(Request $request)
    {
        $projects = json_decode($request->input('projects'), true);
        Session::put('onboarding.portfolio', $projects);

        return redirect()->route('tenant.onboarding.education');
    }

    public function education()
    {
        return view('onboarding.education');
    }

    public function storeEducation(Request $request)
    {
        $education = json_decode($request->input('education'), true);
        Session::put('onboarding.education', $education);

        return redirect()->route('tenant.onboarding.preferences');
    }

    public function preferences()
    {
        return view('onboarding.preferences');
    }

    public function storePreferences(Request $request)
    {
        $validated = $request->validate([
            'currency'       => ['required','string'],
            'rate'           => ['required','numeric'],
            'unit'           => ['required','string'],
            'availability'   => ['required','string'],
            'hours_per_week' => ['required','string'],
            'remote_work'    => ['boolean'],
            'open_to_work'   => ['boolean'],
            'long_term'      => ['boolean'],
        ]);

        Session::put('onboarding.preferences', $validated);

        return redirect()->route('tenant.onboarding.review');
    }

    public function review()
    {
        $data = [
            'personal'    => Session::get('onboarding.personal'),
            'location'    => Session::get('onboarding.location'),
            'skills'      => Session::get('onboarding.skills'),
            'experience'  => Session::get('onboarding.experience'),
            'portfolio'   => Session::get('onboarding.portfolio'),
            'education'   => Session::get('onboarding.education'),
            'preferences' => Session::get('onboarding.preferences'),
        ];

        return view('onboarding.review', compact('data'));
    }

    public function publish(Request $request)
    {
        $personal    = Session::get('onboarding.personal');
        $location    = Session::get('onboarding.location');
        $skills      = Session::get('onboarding.skills');
        $experience  = Session::get('onboarding.experience');
        $portfolio   = Session::get('onboarding.portfolio');
        $education   = Session::get('onboarding.education');
        $preferences = Session::get('onboarding.preferences');

        $user = Auth::user();

        if (! $user) {
            $request->validate([
                'email'    => ['required','email','unique:users,email'],
                'password' => ['required','string','min:8'],
            ]);

            $user = User::create([
                'name' => $personal['name'] ?? null,
                'last_name'  => $personal['last_name'] ?? null,
                'username'   => $personal['username'] ?? null,
                'email'      => strtolower($request->input('email')),
                'password'   => bcrypt($request->input('password')),
            ]);
        }

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'country'         => $location['country'] ?? null,
                'state'           => $location['state'] ?? null,
                'city'            => $location['city'] ?? null,
                'skills'          => json_encode($skills ?? []),
                'experience'      => json_encode($experience ?? []),
                'portfolio'       => json_encode($portfolio ?? []),
                'education'       => json_encode($education ?? []),
                'currency'        => $preferences['currency'] ?? null,
                'rate'            => $preferences['rate'] ?? null,
                'rate_unit'       => $preferences['unit'] ?? null,
                'availability'    => $preferences['availability'] ?? null,
                'hours_per_week'  => $preferences['hours_per_week'] ?? null,
                'remote_work'     => (bool) ($preferences['remote_work'] ?? false),
                'open_to_work'    => (bool) ($preferences['open_to_work'] ?? false),
                'long_term'       => (bool) ($preferences['long_term'] ?? false),
                'is_public'       => $request->boolean('makePublic'),
                'onboarding_completed' => true,
            ]
        );

        Session::forget('onboarding');

        return redirect()->route('tenant.profile', ['username' => $user->username])
            ->with('success', 'Profile created successfully!');
    }

    public function accountType()
    {
        return view('onboarding.account-type');
    }
}
