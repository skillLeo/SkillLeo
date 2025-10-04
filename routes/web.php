<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LandingController, MarketingController};
use App\Http\Controllers\Auth\GatewayController;
use App\Http\Controllers\Tenant\OnboardingController as TenantOnboardingController;
use App\Http\Controllers\Client\OnboardingController as ClientOnboardingController;

/*
|--------------------------------------------------------------------------
| Public Landing & Marketing Routes
|--------------------------------------------------------------------------
*/

Route::get('/profile', [TenantOnboardingController::class, 'profile'])->name('tenant.profile');
Route::get('/dashbaord', [TenantOnboardingController::class, 'dashbaord'])->name('tenant.dashboard');

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::prefix('marketing')->name('marketing.')->group(function() {
    Route::get('/home', [MarketingController::class, 'home'])->name('home');
    Route::get('/features', [MarketingController::class, 'features'])->name('features');
    Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
    Route::get('/about', [MarketingController::class, 'about'])->name('about');
    Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');
});

/*
|--------------------------------------------------------------------------
| Authentication Gateway
|--------------------------------------------------------------------------
*/
Route::get('/account-type', [GatewayController::class, 'accountType'])->name('auth.account-type');

/*
|--------------------------------------------------------------------------
| Tenant Onboarding Routes
|--------------------------------------------------------------------------
*/

Route::prefix('tenant/onboarding')->name('tenant.onboarding.')->group(function () {
    Route::get('/info', [TenantOnboardingController::class, 'info'])->name('info');
    Route::get('/welcome', [TenantOnboardingController::class, 'welcome'])->name('welcome');
    Route::get('/personal', [TenantOnboardingController::class, 'personal'])->name('personal');
    Route::post('/personal', [TenantOnboardingController::class, 'storePersonal'])->name('personal.store');
    Route::get('/location', [TenantOnboardingController::class, 'location'])->name('location');
    Route::post('/location', [TenantOnboardingController::class, 'storeLocation'])->name('location.store');
    Route::get('/skills', [TenantOnboardingController::class, 'skills'])->name('skills');
    Route::post('/skills', [TenantOnboardingController::class, 'storeSkills'])->name('skills.store');
    Route::get('/experience', [TenantOnboardingController::class, 'experience'])->name('experience');
    Route::post('/experience', [TenantOnboardingController::class, 'storeExperience'])->name('experience.store');
    Route::get('/portfolio', [TenantOnboardingController::class, 'portfolio'])->name('portfolio');
    Route::post('/portfolio', [TenantOnboardingController::class, 'storePortfolio'])->name('portfolio.store');
    Route::get('/education', [TenantOnboardingController::class, 'education'])->name('education');
    Route::post('/education', [TenantOnboardingController::class, 'storeEducation'])->name('education.store');
    Route::get('/preferences', [TenantOnboardingController::class, 'preferences'])->name('preferences');
    Route::post('/preferences', [TenantOnboardingController::class, 'storePreferences'])->name('preferences.store');
    Route::get('/review', [TenantOnboardingController::class, 'review'])->name('review');
    Route::get('/publish', [TenantOnboardingController::class, 'publish'])->name('publish');
    Route::post('/publish', [TenantOnboardingController::class, 'storepublish'])->name('publish');
});

/*
|--------------------------------------------------------------------------
| Client Onboarding Routes
|--------------------------------------------------------------------------
*/
Route::prefix('client/onboarding')->name('client.onboarding.')->group(function () {
    Route::get('/info', [ClientOnboardingController::class, 'info'])->name('info');
    Route::post('/info', [ClientOnboardingController::class, 'storeInfo'])->name('info.store');
    Route::get('/project', [ClientOnboardingController::class, 'project'])->name('project');
    Route::post('/project', [ClientOnboardingController::class, 'storeProject'])->name('project.store');
    Route::get('/budget', [ClientOnboardingController::class, 'budget'])->name('budget');
    Route::post('/budget', [ClientOnboardingController::class, 'storeBudget'])->name('budget.store');
    Route::get('/preferences', [ClientOnboardingController::class, 'preferences'])->name('preferences');
    Route::post('/preferences', [ClientOnboardingController::class, 'storePreferences'])->name('preferences.store');
    Route::get('/review', [ClientOnboardingController::class, 'review'])->name('review');
    Route::post('/publish', [ClientOnboardingController::class, 'publish'])->name('publish');
});