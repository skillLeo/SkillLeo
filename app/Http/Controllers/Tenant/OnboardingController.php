<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OnboardingController extends Controller
{
     













    public function profile()
    {
        // Mock data - replace with your actual data logic
        $user = (object) [
            'name' => 'Hassam Mehmood',
            'facebook' => 'Hassam Mehmood',
            'instagram' => 'Hassam Mehmood',
            'twitter' => 'Hassam Mehmood',
            'linkedin' => 'Hassam Mehmood',
            'bio' => 'Full-Stack Laravel & React Developer | AI & Chatbot Integration Expert',
            'location' => 'Sargodha, Pakistan',
            'avatar' => null,
            'banner' => null,
            'open_to_work' => true,
            'about' => 'I am a problem solver who writes and maintains the code that powers websites, applications I am a problem solver who writes and maintains the code that powers websites, applications',
            'skills' => ['Web Development', 'Laravel', 'React'],
            'topSkills' => ['Web Development', 'PHP', 'Laravel'],
            'softSkills' => [
                ['name' => 'Problem-solving', 'icon' => 'lightbulb'],
                ['name' => 'Communication skills', 'icon' => 'mobile-screen'],
                ['name' => 'Time management', 'icon' => 'clock']
            ],
            'languages' => [
                ['name' => 'English', 'level' => 'Professional'],
                ['name' => 'Urdu', 'level' => 'Native'],
                ['name' => 'Arabic', 'level' => 'Basic']
            ],
            'education' => [
                [
                    'title' => 'Computer Science',
                    'institution' => 'University of Sargodha',
                    'period' => 'Jul 2024 - Jul 2025',
                    'location' => 'Sargodha, Pakistan',
                    'recent' => true
                ]
            ],
            'whyChooseMe' => [
                'Expert in modern technologies',
                'Fast delivery and quality work',
                'Available 24/7 for support'
            ],
            'services' => [
                'Web Development',
                'App Development',
                'API Integration'
            ]
        ];

        $portfolios = [];
        $portfolioCategories = ['All', 'Laravel', 'React Js', 'Node Js', 'AI'];
        
        $skillsData = [
            ['name' => 'Laravel', 'percentage' => 90],
            ['name' => 'React Js', 'percentage' => 95],
            ['name' => 'Machine Learning', 'percentage' => 100]
        ];

        $experiences = [];
        $reviews = [];

        return view('tenant.profile.index', compact(
            'user', 
            'portfolios', 
            'portfolioCategories', 
            'skillsData', 
            'experiences', 
            'reviews'
        ))->with([
            'brandName' => 'Codefixxaaaer',
            'messageCount' => 2
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

    public function storePersonal(Request $request)
    {
        
        return redirect()->route('tenant.onboarding.location');
    }

    public function location()
    {
        return view('tenant.onboarding.location');
    }
    public function publish()
    {
        return view('tenant.onboarding.publish');
    }

    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
        ]);

        Session::put('tenant.onboarding.location', $validated);
        return redirect()->route('tenant.onboarding.skills');
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
 
        return redirect()->route('tenant.onboarding.review');
    }

    public function review()
    {
        $data = [
            'personal' => Session::get('tenant.onboarding.personal'),
            'location' => Session::get('tenant.onboarding.location'),
            'skills' => Session::get('tenant.onboarding.skills'),
            'experience' => Session::get('tenant.onboarding.experience'),
            'portfolio' => Session::get('tenant.onboarding.portfolio'),
            'education' => Session::get('tenant.onboarding.education'),
            'preferences' => Session::get('tenant.onboarding.preferences'),
        ];

        return view('tenant.onboarding.review', compact('data'));
    }

    public function storepublish()
    {
        return view('tenant.onboarding.publish');
    
    }
}