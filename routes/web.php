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
    
};
use App\Http\Controllers\Tenant\{
    OnboardingController as TenantOnboardingController,
    ProfileController as TenantProfileController,
    ProfilePageController
};
use App\Http\Controllers\Client\{
    OnboardingController as ClientOnboardingController
};
use App\Http\Controllers\Api\LocationApiController;
use App\Http\Controllers\Settings\ConnectedAccountsController;
use App\Http\Controllers\Api\GeoController;
use App\Services\TimezoneService;
use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\CvUploadController;

use App\Http\Controllers\Tenant\ProfilePage\ProfileController;




// In routes/web.php

Route::prefix('tenant/onboarding')->name('tenant.onboarding.')->group(function () {
    Route::post('/cv-upload.json', [CvUploadController::class, 'uploadJson'])->name('cv.upload.json');
    Route::post('/cv-upload',      [CvUploadController::class, 'upload'])->name('cv.upload');
    Route::get('/cv-output',       [CvUploadController::class, 'output'])->name('cv.output');
    Route::post('/cv-save', [CvUploadController::class, 'save'])->name('cv.save');

});
// Route::post('/cv-upload.json', [CvUploadController::class, 'uploadJson'])->name('cv.upload.json');
// Route::post('/cv-upload', [CvUploadController::class, 'upload'])->name('cv.upload');
// Route::get('/cv-output', [CvUploadController::class, 'output'])->name('cv.output');
































    Route::prefix('{username}')
    ->name('tenant.')
    ->group(function () {



     
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('times', [ProfileController::class, 'times'])->name('times');











    });




    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('tenant.profile.update');
    Route::put('skills', [ProfileController::class, 'updateSkills'])->name('tenant.skills.update');
    Route::put('language', [ProfileController::class, 'updateLanguages'])->name('tenant.language.update');
    Route::put('educaiton', [ProfileController::class, 'updateEducation'])->name('tenant.education.update');
    Route::put('experience', [ProfileController::class, 'updateExperience'])->name('tenant.experience.update');
    Route::put('reviews', [ProfileController::class, 'updateReviews'])
    ->name('tenant.reviews.update');

    Route::put('services', [ProfileController::class, 'updateServices'])
    ->name('tenant.services.update');

Route::put('why-choose-me', [ProfileController::class, 'updateWhyChoose'])
    ->name('tenant.why.update');



    Route::put('portfolio', [ProfileController::class, 'updatePortfolio'])->name('tenant.portfolio.update');

    Route::post('/filter-preferences', [ProfileController::class, 'updateFilterPreferences'])
    ->name('tenant.filter-preferences')
    ->middleware('auth');


    Route::post('/banner', [ProfileController::class, 'updateBanner'])
    ->name('tenant.banner.update')
    ->middleware(['auth']);
































Route::post('/api/timezone/store', function (Request $request) {
    $request->validate(['timezone' => 'required|string|timezone']);
    TimezoneService::storeViewerTimezone($request->timezone);
    return response()->json(['success' => true]);
})->name('timezone.store');











Route::middleware(['auth','throttle:60,1'])
    ->get('/api/username/check', [TenantOnboardingController::class, 'checkUsername'])
    ->name('api.username.check');


Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('select-account-type', [AuthController::class, 'selectAccountType'])->name('select-account-type');
    Route::get('account-type', [AuthController::class, 'accountType'])->name('account-type');
    Route::post('account-type', [AuthController::class, 'setAccountType'])->name('account-type.set');
});



Route::post('skill', [AuthController::class, 'setAccountType'])->name('account-type.set');
Route::delete('skill', [AuthController::class, 'setAccountType'])->name('account-type.set');
Route::get('skill', [AuthController::class, 'setAccountType'])->name('account-type.set');
Route::put('skill', [AuthController::class, 'setAccountType'])->name('account-type.set');
Route::delete('skill', [AuthController::class, 'setAccountType'])->name('account-type.set');











Route::get('/admin/institutions', function () {
    $items = \App\Models\Institution::orderBy('country')->orderBy('name')->paginate(50);
    return view('admin.institutions.index', compact('items'));
})->middleware(['auth']);











// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// OAuth
Route::get('/auth/{provider}/redirect', [OAuthController::class, 'redirect'])
    ->whereIn('provider', ['google', 'github', 'linkedin', 'linkedin-openid']);

Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback'])
    ->whereIn('provider', ['google', 'github', 'linkedin', 'linkedin-openid']);

// Home/marketing

Route::prefix('marketing')->name('marketing.')->group(function () {
    Route::get('features', [MarketingController::class, 'features'])->name('features');
    Route::get('pricing', [MarketingController::class, 'pricing'])->name('pricing');
    Route::get('about', [MarketingController::class, 'about'])->name('about');
    Route::get('contact', [MarketingController::class, 'contact'])->name('contact');
});
Route::middleware('guest.only')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('home');
});

// Guest-only (login/register/otp/email verify)
Route::middleware('guest.only')->prefix('auth')->name('auth.')->group(function () {

    Route::get('login', [AuthController::class, 'loginshow'])->name('login');
    Route::post('login', [AuthController::class, 'submitLogin'])->name('login.submit');

    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [PreSignupController::class, 'sendLink'])->name('register.submit');
    Route::get('register/confirm/{token}', [PreSignupController::class, 'confirm'])->name('register.confirm');
    Route::get('register/existing', [RegisterController::class, 'existing'])->name('register.existing');
    Route::post('register/resend', [PreSignupController::class, 'resend'])->name('register.resend');

    Route::get('email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');

    Route::get('otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});

// Settings (must have chosen an account type / not in start)
Route::middleware('account.type')->prefix('settings')->name('settings.')->group(function () {
    Route::get('connected-accounts', [ConnectedAccountsController::class, 'index'])->name('connected-accounts');
    Route::post('connected-accounts/{provider}/link', [ConnectedAccountsController::class, 'startLink'])->name('connected-accounts.link');
    Route::delete('connected-accounts/{provider}', [ConnectedAccountsController::class, 'unlink'])->name('connected-accounts.unlink');
});











// Authenticated
Route::middleware('auth')->group(function () {





    Route::prefix('onboarding/tenant')->middleware(['tenant','onboarding:tenant'])->name('tenant.onboarding.')
        ->group(function () {

            // GET routes (pages)
            Route::get('welcome',    [TenantOnboardingController::class, 'welcome'])->name('welcome');
            Route::get('personal',   [TenantOnboardingController::class, 'personal'])->name('personal');
            Route::get('location',   [TenantOnboardingController::class, 'location'])->name('location');
            Route::get('skills',     [TenantOnboardingController::class, 'skills'])->name('skills');
            Route::get('experience', [TenantOnboardingController::class, 'experience'])->name('experience');
            Route::get('portfolio',  [TenantOnboardingController::class, 'portfolio'])->name('portfolio');
            Route::get('education',  [TenantOnboardingController::class, 'education'])->name('education');
            Route::get('preferences',[TenantOnboardingController::class, 'preferences'])->name('preferences');
            Route::get('review',     [TenantOnboardingController::class, 'review'])->name('review');
            Route::get('publish',    [TenantOnboardingController::class, 'publish'])->name('publish');

            // CV Upload routes - GET
            // Route::get('cv-output', [CvUploadController::class, 'output'])->name('cv.output');

            // POST routes (form submissions)
            Route::middleware('onboarding.post:tenant')->group(function () {
                // Standard onboarding POST routes
                Route::post('personal',    [TenantOnboardingController::class, 'storePersonal'])->name('personal.store');
                Route::post('location',    [TenantOnboardingController::class, 'storeLocation'])->name('location.store');
                Route::post('skills',      [TenantOnboardingController::class, 'storeSkills'])->name('skills.store');
                Route::post('experience',  [TenantOnboardingController::class, 'storeExperience'])->name('experience.store');
                Route::post('portfolio',   [TenantOnboardingController::class, 'storePortfolio'])->name('portfolio.store');
                Route::post('education',   [TenantOnboardingController::class, 'storeEducation'])->name('education.store');
                Route::post('preferences', [TenantOnboardingController::class, 'storePreferences'])->name('preferences.store');
                Route::post('review',      [TenantOnboardingController::class, 'storeReview'])->name('review.store');
                Route::post('publish',     [TenantOnboardingController::class, 'storePublish'])->name('publish.store');

                // CV Upload POST routes
                // Route::post('cv-upload',      [CvUploadController::class, 'upload'])->name('cv.upload');
                // Route::post('cv-upload.json', [CvUploadController::class, 'uploadJson'])->name('cv.upload.json');
                
                // Additional actions
                Route::post('start-from-scratch', [TenantOnboardingController::class, 'scratch'])->name('scratch');
                Route::post('confirm', function () {
                    return redirect()->route('tenant.onboarding.personal');
                })->name('confirm');
            });
        });


 Route::prefix('onboarding/client')
    ->middleware(['client','onboarding:client']) 
    ->name('client.onboarding.')
    ->group(function () {
        Route::get('info',        [ClientOnboardingController::class, 'info'])->name('info');
        Route::get('project',     [ClientOnboardingController::class, 'project'])->name('project');
        Route::get('budget',      [ClientOnboardingController::class, 'budget'])->name('budget');
        Route::get('preferences', [ClientOnboardingController::class, 'preferences'])->name('preferences');
        Route::get('review',      [ClientOnboardingController::class, 'review'])->name('review');

        Route::middleware('onboarding.post:client')->group(function () {
            Route::post('info',        [ClientOnboardingController::class, 'storeInfo'])->name('info.store');
            Route::post('project',     [ClientOnboardingController::class, 'storeProject'])->name('project.store');
            Route::post('budget',      [ClientOnboardingController::class, 'storeBudget'])->name('budget.store');
            Route::post('preferences', [ClientOnboardingController::class, 'storePreferences'])->name('preferences.store');
            Route::post('publish',     [ClientOnboardingController::class, 'publish'])->name('publish'); 
        });
    });

});



