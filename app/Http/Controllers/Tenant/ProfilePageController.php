<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Profile\ProfileService;
use App\Support\ProfileVisibility;
use App\ViewModels\ProfileViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ProfilePageController extends Controller
{
    /**
     * Inject the profile service that loads relations
     */
    public function __construct(
        private readonly ProfileService $service
    ) {}

    /**
     * Show the public tenant profile by username.
     */
    public function index(Request $request, string $username)
    {
        /** @var User $owner */
        $owner = User::query()
            ->where('username', $username)
            ->firstOrFail();

        // Check profile visibility (public or owner)
        ProfileVisibility::ensureVisible($owner);

        // Eager-load all relations for this profile
        $owner = $this->service->load($owner);

        // Wrap with ViewModel for Blade consumption
        $vm = new ProfileViewModel($owner);

        // Pass fully prepared data to the Blade view
        return view('tenant.profile.index', [
            // basic profile card
            'user'         => $vm->userCard(),

            // main portfolio content
            'portfolios'   => $vm->portfolios(),
            'categories'   => $vm->portfolioCategories(), // renamed for Blade

            // other profile modules
            'skillsData'   => $vm->skillsData(),
            'experiences'  => $vm->experiences(),
            'reviews'      => $vm->reviews(),

            // extra meta
            'brandName'    => config('app.name', 'ProMatch'),
            'messageCount' => method_exists($owner, 'unreadMessagesCount')
                ? (int) $owner->unreadMessagesCount(Auth::id())
                : 0,
        ]);
    }
}
