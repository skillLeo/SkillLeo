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
use App\Http\Controllers\Tenant\Manage\DashboardController;
use App\Http\Controllers\Tenant\Manage\ManageProfileController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\AccountController;
use App\Http\Controllers\Settings\DangerController;
use App\Http\Controllers\Settings\SecurityController;




// In routes/web.php

Route::prefix('tenant/onboarding')->name('tenant.onboarding.')->group(function () {
    Route::post('/cv-upload.json', [CvUploadController::class, 'uploadJson'])->name('cv.upload.json');
    Route::post('/cv-upload',      [CvUploadController::class, 'upload'])->name('cv.upload');
    Route::get('/cv-output',       [CvUploadController::class, 'output'])->name('cv.output');
    Route::post('/cv-save', [CvUploadController::class, 'save'])->name('cv.save');
});



Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');




// NOT in auth group:
Route::get('/settings/account/password/confirm/{token}', [AccountController::class, 'confirmPasswordChange'])
    ->name('tenant.settings.password.confirm')
    ->middleware('signed');












// keep these in auth group
Route::middleware(['auth'])->group(function () {
    Route::post('/{username}/settings/account/password', [AccountController::class, 'updatePassword'])
        ->name('tenant.settings.password.update');
    // ... other authenticated settings routes






    Route::prefix('{username}')
    ->name('tenant.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::get('/', [ProfileController::class, 'index'])->name('profile');

        Route::prefix('manage')->name('manage.')->group(function () {
            Route::get('',            [DashboardController::class,     'index'])->name('dashboard');
            Route::get('personal',    [ManageProfileController::class, 'personal'])->name('personal');
            Route::get('skills',      [ManageProfileController::class, 'skills'])->name('skills');
            Route::get('education',   [ManageProfileController::class, 'education'])->name('education');
            Route::get('experience',  [ManageProfileController::class, 'experience'])->name('experience');
            Route::get('portfolio',   [ManageProfileController::class, 'portfolio'])->name('portfolio');
            Route::get('languages',   [ManageProfileController::class, 'languages'])->name('languages');
        });

        // Profile updates
        Route::put('skills/update',      [ProfileController::class, 'updateSkills'])->name('skills.update');
        Route::put('education/update',   [ProfileController::class, 'updateEducation'])->name('education.update');
        Route::put('experience/update',  [ProfileController::class, 'updateExperience'])->name('experience.update');
        Route::put('portfolio/update',   [ProfileController::class, 'updatePortfolio'])->name('portfolio.update');
        Route::put('languages/update',   [ProfileController::class, 'updateLanguages'])->name('language.update');
        Route::put('profile/update',     [ProfileController::class, 'updatePersonal'])->name('profile.update');

        // Settings hub
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/',               [SettingsController::class, 'index'])->name('index');
            Route::get('/privacy',        [SettingsController::class, 'privacy'])->name('privacy');
            Route::get('/notifications',  [SettingsController::class, 'notifications'])->name('notifications');
            Route::get('/appearance',     [SettingsController::class, 'appearance'])->name('appearance');
            Route::get('/billing',        [SettingsController::class, 'billing'])->name('billing');
            Route::get('/data',           [SettingsController::class, 'data'])->name('data');
            Route::get('/advanced',       [SettingsController::class, 'advanced'])->name('advanced');

            // Account (profile/email/password/devices)
            Route::get('/account',                            [AccountController::class, 'account'])->name('account');
            Route::post('/account/profile',                   [AccountController::class, 'updateProfile'])->name('account.profile.update');
            Route::post('/account/password',                  [AccountController::class, 'updatePassword'])->name('password.update');
            Route::get('/account/password/confirm/{token}',   [AccountController::class, 'confirmPasswordChange'])->name('password.confirm');
            Route::post('/account/email/verification/send',   [AccountController::class, 'sendVerification'])->name('account.email.verify.send');

            // Devices (Account page) - Using POST for all actions
            Route::post('/account/devices/revoke-others',     [AccountController::class, 'revokeOtherSessions'])->name('devices.revoke_others');
            Route::post('/account/devices/{device}/trust',    [AccountController::class, 'trustDevice'])->name('devices.trust');
            Route::post('/account/devices/{device}/revoke',   [AccountController::class, 'revokeDevice'])->name('devices.revoke');

            // Danger Zone
            Route::get('/danger',                              [DangerController::class, 'danger'])->name('danger');
            Route::post('/danger/hibernate',                   [DangerController::class, 'hibernate'])->name('danger.hibernate');
            Route::post('/danger/delete/start',                [DangerController::class, 'deleteStart'])->name('danger.delete.start');
            Route::post('/danger/delete/resend',               [DangerController::class, 'deleteResend'])->name('danger.delete.resend');
            Route::post('/danger/delete/verify',               [DangerController::class, 'deleteVerify'])->name('danger.delete.verify');
            Route::post('/danger/delete/cancel',               [DangerController::class, 'deleteCancel'])->name('danger.delete.cancel');

            // Security Settings - Main Page
            Route::get('/security', [SecurityController::class, 'security'])->name('security');

            // Security - 2FA & Trusted Devices - Using POST for all actions
            Route::prefix('security')->name('security.')->group(function () {
                // Authenticator App
                Route::post('/enable-2fa-step1',  [SecurityController::class, 'enable2FAStep1'])->name('enable2fa.step1');
                Route::post('/enable-2fa-verify', [SecurityController::class, 'enable2FAVerify'])->name('enable2fa.verify');
                Route::post('/disable-2fa',       [SecurityController::class, 'disable2FA'])->name('disable2fa');

                // Email OTP
                Route::post('/send-email-otp',    [SecurityController::class, 'sendEmailOtp'])->name('sendEmailOtp');
                Route::post('/verify-email-otp',  [SecurityController::class, 'verifyEmailOtp'])->name('verifyEmailOtp');
                Route::post('/disable-email-otp', [SecurityController::class, 'disableEmailOtp'])->name('disableEmailOtp');

                // Phone/SMS OTP
                Route::post('/send-phone-otp',    [SecurityController::class, 'sendPhoneOtp'])->name('sendPhoneOtp');
                Route::post('/verify-phone-otp',  [SecurityController::class, 'verifyPhoneOtp'])->name('verifyPhoneOtp');
                Route::post('/disable-phone-otp', [SecurityController::class, 'disablePhoneOtp'])->name('disablePhoneOtp');

                // Recovery Codes
                Route::post('/regenerate-recovery-codes', [SecurityController::class, 'regenerateRecoveryCodes'])->name('regenerateRecoveryCodes');

                // Advanced Security Settings
                Route::post('/toggle-2fa-new-location',   [SecurityController::class, 'toggle2FANewLocation'])->name('toggle2FANewLocation');
                Route::post('/toggle-2fa-sensitive',      [SecurityController::class, 'toggle2FASensitive'])->name('toggle2FASensitive');
                Route::post('/toggle-login-notifications',[SecurityController::class, 'toggleLoginNotifications'])->name('toggleLoginNotifications');

                // Trusted Devices (Security page) - Using POST for all actions
                Route::get ('/trusted-devices',            [SecurityController::class, 'trustedDevices'])->name('trustedDevices');
                Route::post('/trusted/{device}/untrust',   [SecurityController::class, 'untrustDevice'])->name('trusted.untrust');
                Route::post('/trusted/untrust-all',        [SecurityController::class, 'untrustAllDevices'])->name('trusted.untrust_all');
            });
        });
    });








    Route::get('/client/accept-invitation/{token}', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'acceptInvitation'])
    ->name('client.accept-invitation');


    Route::prefix('{username}/manage/projects')
    ->name('tenant.manage.projects.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
       // Search users endpoint
       Route::get('/search-users', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'searchUsers'])
       ->name('search-users');
       Route::get('/search-clients', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'searchClients'])
       ->name('search-clients');
       Route::post('/invite-client', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'inviteClient'])
       ->name('invite-client');




        // Team
        Route::prefix('/team')->name('team.')->group(function () {
            Route::get('/', [App\Http\Controllers\Tenant\Project\TeamController::class, 'index'])->name('index');
            Route::get('/workload', [App\Http\Controllers\Tenant\Project\TeamController::class, 'workload'])->name('workload');
        });

        // Reports
        Route::prefix('/reports')->name('reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Tenant\Project\ReportController::class, 'index'])->name('index');
            Route::get('/velocity', [App\Http\Controllers\Tenant\Project\ReportController::class, 'velocity'])->name('velocity');
            Route::get('/burndown', [App\Http\Controllers\Tenant\Project\ReportController::class, 'burndown'])->name('burndown');
            Route::get('/time-tracking', [App\Http\Controllers\Tenant\Project\ReportController::class, 'timeTracking'])->name('time-tracking');
        });

        // Clients
        Route::get('/clients', [App\Http\Controllers\Tenant\Project\ClientController::class, 'index'])
            ->name('clients.index');

        // Dashboard / overview
        Route::get('/', [App\Http\Controllers\Tenant\Manage\DashboardController::class, 'index'])
            ->name('dashboard');
 

        // save draft, list, create, etc.
        Route::post('/draft', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'saveDraft'])
            ->name('draft');

        Route::get('/list', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'index'])
            ->name('list');

        Route::post('/store', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'store'])
            ->name('store');

        // project-specific pages
        Route::get('/{project}/board', [App\Http\Controllers\Tenant\Project\BoardController::class, 'show'])
            ->name('board');

        Route::get('/{project}/backlog', [App\Http\Controllers\Tenant\Project\BacklogController::class, 'index'])
            ->name('backlog');

        Route::get('/{project}/timeline', [App\Http\Controllers\Tenant\Project\TimelineController::class, 'show'])
            ->name('timeline');

        Route::prefix('/{project}/issues')->name('issues.')->group(function () {
            Route::get('/', [App\Http\Controllers\Tenant\Project\IssueController::class, 'index'])->name('index');
            Route::get('/{issue}', [App\Http\Controllers\Tenant\Project\IssueController::class, 'show'])->name('show');
        });

        Route::prefix('/{project}/sprints')->name('sprints.')->group(function () {
            Route::get('/', [App\Http\Controllers\Tenant\Project\SprintController::class, 'index'])->name('index');
            Route::get('/active', [App\Http\Controllers\Tenant\Project\SprintController::class, 'active'])->name('active');
            Route::get('/planning', [App\Http\Controllers\Tenant\Project\SprintController::class, 'planning'])->name('planning');
        });

        Route::prefix('/{project}/milestones')->name('milestones.')->group(function () {
            Route::get('/', [App\Http\Controllers\Tenant\Project\MilestoneController::class, 'index'])->name('index');
            Route::get('/{milestone}', [App\Http\Controllers\Tenant\Project\MilestoneController::class, 'show'])->name('show');
        });

        // MUST BE LAST: catch-all single project view
        Route::get('/{project}', [App\Http\Controllers\Tenant\Project\ProjectController::class, 'show'])
            ->name('show');
    });






































    Route::put('reviews', [ProfileController::class, 'updateReviews'])
        ->name('tenant.reviews.update');

    Route::put('services', [ProfileController::class, 'updateServices'])
        ->name('tenant.services.update');

    Route::put('why-choose-me', [ProfileController::class, 'updateWhyChoose'])
        ->name('tenant.why.update');




    Route::post('/filter-preferences', [ProfileController::class, 'updateFilterPreferences'])
        ->name('tenant.filter-preferences')
        ->middleware('auth');


    Route::post('/banner', [ProfileController::class, 'updateBanner'])
        ->name('tenant.banner.update')
        ->middleware(['auth']);
});








Route::post('/api/timezone/store', function (Request $request) {
    $request->validate(['timezone' => 'required|string|timezone']);
    TimezoneService::storeViewerTimezone($request->timezone);
    return response()->json(['success' => true]);
})->name('timezone.store');











Route::middleware(['auth', 'throttle:60,1'])
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


    // 2FA (Authenticator) gate
    Route::get('/2fa',               [AuthController::class, 'show2FA'])->name('2fa.show');
    Route::post('/2fa',              [AuthController::class, 'verify2FA'])->name('2fa.verify')->middleware('throttle:6,1');
    Route::get('/2fa/recovery',      [AuthController::class, 'show2FARecovery'])->name('2fa.recovery');
    Route::post('/2fa/recovery',     [AuthController::class, 'verify2FARecovery'])->name('2fa.recovery.verify')->middleware('throttle:6,1');




    Route::get('login', [AuthController::class, 'loginshow'])->name('login');
    Route::post('login', [AuthController::class, 'submitLogin'])->name('login.submit');

    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register/post', [PreSignupController::class, 'sendLink'])->name('register.submit');
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





    Route::prefix('onboarding/tenant')->middleware(['tenant', 'onboarding:tenant'])->name('tenant.onboarding.')
        ->group(function () {

            // GET routes (pages)
            Route::get('welcome',    [TenantOnboardingController::class, 'welcome'])->name('welcome');
            Route::get('personal',   [TenantOnboardingController::class, 'personal'])->name('personal');
            Route::get('location',   [TenantOnboardingController::class, 'location'])->name('location');
            Route::get('skills',     [TenantOnboardingController::class, 'skills'])->name('skills');
            Route::get('experience', [TenantOnboardingController::class, 'experience'])->name('experience');
            Route::get('portfolio',  [TenantOnboardingController::class, 'portfolio'])->name('portfolio');
            Route::get('education',  [TenantOnboardingController::class, 'education'])->name('education');
            Route::get('preferences', [TenantOnboardingController::class, 'preferences'])->name('preferences');
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
        ->middleware(['client', 'onboarding:client'])
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
