<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LandingController, MarketingController};
use App\Http\Controllers\Auth\{
    AuthController,
    OtpController,
    RegisterController,
    PreSignupController,
    EmailVerificationController,
    OAuthController,
    GatewayController
};
use App\Http\Controllers\Tenant\{
    OnboardingController as TenantOnboardingController,
    ProfileController as TenantProfileController,
};
use App\Http\Controllers\Client\{
    OnboardingController as ClientOnboardingController,
};
use App\Http\Controllers\Settings\ConnectedAccountsController;






Route::post('/auth/select-account-type', [AuthController::class, 'selectAccountType'])
    ->middleware('auth')
    ->name('auth.select-account-type');
    
/*
|--------------------------------------------------------------------------
| Public Routes - Landing & Marketing
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::prefix('marketing')->name('marketing.')->group(function() {
    Route::get('home', [MarketingController::class, 'home'])->name('home');
    Route::get('features', [MarketingController::class, 'features'])->name('features');
    Route::get('pricing', [MarketingController::class, 'pricing'])->name('pricing');
    Route::get('about', [MarketingController::class, 'about'])->name('about');
    Route::get('contact', [MarketingController::class, 'contact'])->name('contact');
});

/*
|--------------------------------------------------------------------------
| Guest Routes - Authentication
|--------------------------------------------------------------------------
*/

// Route::middleware('guest')->group(function() {
    
    // Login
    Route::get('login', [AuthController::class, 'loginshow'])->name('login');
    Route::post('login', [AuthController::class, 'submitLogin'])->name('login.submit');
    
    // Registration
    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [PreSignupController::class, 'sendLink'])
        
        ->name('register.submit');
    Route::get('register/confirm/{token}', [PreSignupController::class, 'confirm'])
        
        ->name('register.confirm');
    Route::get('register/existing', [RegisterController::class, 'existing'])
        ->name('register.existing');
    Route::post('register/resend', [PreSignupController::class, 'resend'])
        ->name('register.resend');
    
    // Email Verification (Guest can access)
    Route::get('email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');
    Route::post('email/resend', [EmailVerificationController::class, 'resend'])
        ->name('verification.resend');
    
    // OTP Verification
    Route::get('otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('otp/verify', [OtpController::class, 'verify'])
        ->name('otp.verify');
    Route::post('otp/resend', [OtpController::class, 'resend'])
        ->name('otp.resend');
    
    // OAuth
 
// Route::get('/auth/{provider}/redirect', [OAuthController::class, 'redirect'])
// ->whereIn('provider', ['google','github','linkedin'])
// ->name('oauth.redirect');

// Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback'])
// ->whereIn('provider', ['google','github','linkedin'])
// ->name('oauth.callback');
// });



Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}/redirect', [OAuthController::class, 'redirect'])
        ->whereIn('provider', ['google', 'linkedin', 'github'])
        ->name('oauth.redirect');

    Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback'])
        ->whereIn('provider', ['google', 'linkedin', 'github'])
        ->name('oauth.callback');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes - Account Type Gateway
|--------------------------------------------------------------------------
*/

// Route::middleware('auth')->group(function() {
    
    // Account type selection (after registration)
    Route::get('account-type', [GatewayController::class, 'accountType'])
        ->name('auth.account-type');
    Route::post('account-type', [GatewayController::class, 'setAccountType'])
        ->name('auth.account-type.set');
    
    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('logout', [AuthController::class, 'logout_get'])->name('logout_get');
    
    // Settings - Connected Accounts (All authenticated users)
    Route::prefix('settings')->name('settings.')->group(function() {
        Route::get('connected-accounts', [ConnectedAccountsController::class, 'index'])
            ->name('connected-accounts');
        Route::post('connected-accounts/{provider}/link', [ConnectedAccountsController::class, 'startLink'])
            ->name('connected-accounts.link');
        Route::delete('connected-accounts/{provider}', [ConnectedAccountsController::class, 'unlink'])
            ->name('connected-accounts.unlink');
    });
 
    Route::prefix('onboarding')->name('tenant.onboarding.')->group(function() {
        Route::get('welcome', [TenantOnboardingController::class, 'welcome'])->name('welcome');
        Route::get('personal', [TenantOnboardingController::class, 'personal'])->name('personal');
        Route::post('personal', [TenantOnboardingController::class, 'storePersonal'])->name('personal.store');
        Route::get('location', [TenantOnboardingController::class, 'location'])->name('location');
        Route::post('location', [TenantOnboardingController::class, 'storeLocation'])->name('location.store');
        Route::get('skills', [TenantOnboardingController::class, 'skills'])->name('skills');
        Route::post('skills', [TenantOnboardingController::class, 'storeSkills'])->name('skills.store');
        Route::get('experience', [TenantOnboardingController::class, 'experience'])->name('experience');
        Route::post('experience', [TenantOnboardingController::class, 'storeExperience'])->name('experience.store');
        Route::get('portfolio', [TenantOnboardingController::class, 'portfolio'])->name('portfolio');
        Route::post('portfolio', [TenantOnboardingController::class, 'storePortfolio'])->name('portfolio.store');
        Route::get('education', [TenantOnboardingController::class, 'education'])->name('education');
        Route::post('education', [TenantOnboardingController::class, 'storeEducation'])->name('education.store');
        Route::get('preferences', [TenantOnboardingController::class, 'preferences'])->name('preferences');
        Route::post('preferences', [TenantOnboardingController::class, 'storePreferences'])->name('preferences.store');
        Route::get('review', [TenantOnboardingController::class, 'review'])->name('review');
        Route::get('publish', [TenantOnboardingController::class, 'publish'])->name('publish');
        Route::post('publish', [TenantOnboardingController::class, 'storepublish'])->name('publish.store');
    });
    
 
    // Onboarding Flow
    Route::prefix('onboarding')->name('client.onboarding.')->group(function() {
        Route::get('info', [ClientOnboardingController::class, 'info'])->name('info');
        Route::post('info', [ClientOnboardingController::class, 'storeInfo'])->name('info.store');
        Route::get('project', [ClientOnboardingController::class, 'project'])->name('project');
        Route::post('project', [ClientOnboardingController::class, 'storeProject'])->name('project.store');
        Route::get('budget', [ClientOnboardingController::class, 'budget'])->name('budget');
        Route::post('budget', [ClientOnboardingController::class, 'storeBudget'])->name('budget.store');
        Route::get('preferences', [ClientOnboardingController::class, 'preferences'])->name('preferences');
        Route::post('preferences', [ClientOnboardingController::class, 'storePreferences'])->name('preferences.store');
        Route::get('review', [ClientOnboardingController::class, 'review'])->name('review');
        Route::post('publish', [ClientOnboardingController::class, 'publish'])->name('publish');
    });
    
Route::get('{username}', [TenantProfileController::class, 'show'])
    ->where('username', '[a-zA-Z0-9_-]+')
    ->name('profile.show');