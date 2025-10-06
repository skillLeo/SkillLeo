<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OnboardingController extends Controller
{
    public function info()
    {
        return view('client.onboarding.info');
    }

    public function storeInfo(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:company,individual',
            'company_name' => 'required_if:account_type,company',
            'company_size' => 'nullable',
            'industry' => 'nullable',
            'contact_email' => 'required|email',
            'phone' => 'nullable',
            'website' => 'nullable|url',
            'about' => 'nullable|max:500',
        ]);

        Session::put('client.onboarding.info', $validated);
        return redirect()->route('client.onboarding.project');
    }

    public function project()
    {
        return view('client.onboarding.project');
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'project_title' => 'required|max:100',
            'project_description' => 'required|max:2000',
            'project_category' => 'required',
            'skills' => 'required',
            'project_type' => 'required|in:one-time,ongoing',
        ]);

        Session::put('client.onboarding.project', $validated);
        return redirect()->route('client.onboarding.budget');
    }

    public function budget()
    {
        return view('client.onboarding.budget');
    }

    public function storeBudget(Request $request)
    {
        $validated = $request->validate([
            'budget_range' => 'required',
            'currency' => 'nullable',
            'budget_min' => 'nullable|numeric',
            'budget_max' => 'nullable|numeric',
            'timeline' => 'required',
            'start_date' => 'required',
        ]);

        Session::put('client.onboarding.budget', $validated);
        return redirect()->route('client.onboarding.preferences');
    }

    public function preferences()
    {
        return view('client.onboarding.preferences');
    }

    public function storePreferences(Request $request)
    {
        $validated = $request->validate([
            'remote_ok' => 'boolean',
            'flexible_hours' => 'boolean',
            'nda_required' => 'boolean',
            'communication_frequency' => 'required',
            'channels' => 'array',
            'timezone' => 'required',
            'team_size' => 'required',
        ]);

        Session::put('client.onboarding.preferences', $validated);
        return redirect()->route('client.onboarding.review');
    }

    public function review()
    {
        $data = [
            'info' => Session::get('client.onboarding.info'),
            'project' => Session::get('client.onboarding.project'),
            'budget' => Session::get('client.onboarding.budget'),
            'preferences' => Session::get('client.onboarding.preferences'),
        ];

        return view('client.onboarding.review', compact('data'));
    }

    public function publish(Request $request)
    {
        // Create project logic here
        Session::forget('client.onboarding');
        return redirect()->route('clients')->with('success', 'Project posted successfully!');
    }
}