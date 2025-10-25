@php
    use App\Models\User;
    use Illuminate\Support\Str;

    // 1) Who's page are we on?
    $username = request()->route('username');

    // 2) Load the owner (fallback to the authenticated user if needed)
    /** @var \App\Models\User|null $owner */
    $owner = $username
        ? User::with('profile')->where('username', $username)->first()
        : (auth()->user()?->load('profile'));

    // 3) Build a small, safe view model for the sidebar
    $title = fn ($s) => $s !== '' ? Str::of($s)->squish()->title()->toString() : '';

    if ($owner) {
        $first = $owner->name ?? $owner->name ?? '';
        $last  = $owner->last_name  ?? '';
        $full  = trim($title($first).' '.$title($last)) ?: ($owner->username ?? 'User');
        $p     = $owner->profile;

        $user = (object)[
            'name'            => $full,
            'headline'        => (string)($p?->headline ?? $p?->headline ?? ''),
            'location'        => collect([$p?->city, $p?->state, $p?->country])->filter()->join(', ') ?: null,
            'avatar_url'      => $owner->avatar_url ?? asset('images/avatar-fallback.png'),
            'banner_url'      => $owner->banner_url ?? null,
            'banner_fit'      => $owner->banner_fit ?? 'cover',
            'banner_position' => $owner->banner_position ?? 'center',
            'is_online'       => (bool) ($owner->is_online ?? false),
        ];

        $ownerUsername = $owner->username ?? $username;
        $stats = [
            'clicks' => (int)($owner->connections_count ?? 0),
            'views'  => (int)($owner->profile_views_count ?? 0),
        ];
    } else {
        $user = (object)[
            'name'            => 'User',
            'headline'        => '',
            'location'        => null,
            'avatar_url'      => asset('images/avatar-fallback.png'),
            'banner_url'      => null,
            'banner_fit'      => 'cover',
            'banner_position' => 'center',
            'is_online'       => false,
        ];
        $ownerUsername = $username;
        $stats = ['clicks' => 0, 'views' => 0];
    }

    // 4) Active link helper
    $isActive = function (string $name): string {
        return request()->routeIs($name) ? 'active' : '';
    };
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <!-- Profile Card -->
        <div class="profile-card1">
            <!-- Cover / Banner -->
            <div class="profile-cover"
                 @if ($user->banner_url)
                    style="background-image:url('{{ $user->banner_url }}');
                           background-size: {{ $user->banner_fit }};
                           background-position: {{ $user->banner_position }};"
                 @endif>
            </div>

            <!-- Avatar -->
            <div class="profile-avatar-wrapper1">
                <div class="profile-avatar">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         style="width:100%;height:100%;border-radius:50%;object-fit:cover;"
                         referrerpolicy="no-referrer" crossorigin="anonymous"
                         onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                    @if ($user->is_online)
                        <span class="profile-status" title="Online"></span>
                    @endif
                </div>
            </div>

            <!-- Profile Info -->
            <div class="profile-info">
                <h6 class="profile-name">{{ $user->name }}</h6>

                @if (!empty($user->headline))
                    <p class="profile-headline">{{ $user->headline }}</p>
                @endif

                @if (!empty($user->location))
                    <div class="profile-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $user->location }}</span>
                    </div>
                @endif
            </div>

            <div class="profile-divider"></div>

            <!-- Stats -->
            <div class="profile-stats">
                <div class="profile-stat">
                    <span class="profile-stat-value">{{ number_format($stats['clicks']) }}</span>
                    <span class="profile-stat-label">Clicks</span>
                </div>
                <div class="profile-stat">
                    <span class="profile-stat-value">{{ number_format($stats['views']) }}</span>
                    <span class="profile-stat-label">Views</span>
                </div>
            </div>

            <!-- Action -->
            <div class="profile-action">
                <a href="{{ route('tenant.profile', $ownerUsername) }}" class="btn-view-profile ghost-style">
                    <span>View Profile</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Manage Profile -->
        <div class="nav-section">
            <div class="nav-section-title">Manage Profile</div>
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('tenant.manage.personal', $ownerUsername) }}"
                       class="{{ $isActive('tenant.manage.personal') }}">
                       <i class="fas fa-user"></i> Personal Info
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.manage.skills', $ownerUsername) }}"
                       class="{{ $isActive('tenant.manage.skills') }}">
                       <i class="fas fa-code"></i> Skills
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.manage.portfolio', $ownerUsername) }}"
                       class="{{ $isActive('tenant.manage.portfolio') }}">
                       <i class="fas fa-briefcase"></i> Portfolio
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.manage.experience', $ownerUsername) }}"
                       class="{{ $isActive('tenant.manage.experience') }}">
                       <i class="fas fa-history"></i> Experience
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.manage.education', $ownerUsername) }}"
                       class="{{ $isActive('tenant.manage.education') }}">
                       <i class="fas fa-graduation-cap"></i> Education
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.manage.languages', $ownerUsername) }}"
                       class="{{ $isActive('tenant.manage.languages') }}">
                       <i class="fas fa-language"></i> Languages
                    </a>
                </li>
            </ul>
        </div>

        <!-- Manage Projects -->
  
        



















































































































































        

        <!-- Legal & Finance -->
        <div class="nav-section">
            <div class="nav-section-title">Legal & Finance</div>
            <ul class="nav-menu">
                <li><a href="#"><i class="fas fa-file-invoice"></i> Invoices</a></li>
                <li><a href="#"><i class="fas fa-file-contract"></i> Contracts</a></li>
                <li><a href="#"><i class="fas fa-balance-scale"></i> Legal Docs</a></li>
            </ul>
        </div>

        <!-- Account -->
        <div class="nav-section">
            <div class="nav-section-title">Account</div>
            <ul class="nav-menu">
                <li><a href="{{route('tenant.settings.account',$username)}}"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="#"><i class="fas fa-user-circle"></i> Profile</a></li>
                <li  onclick="document.getElementById('logoutForm').submit()"><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Upgrade -->
        <div class="upgrade-card">
            <h6>Upgrade to Pro</h6>
            <div class="icon"><i class="fas fa-rocket"></i></div>
            <p style="font-size:12px;margin-bottom:16px;opacity:.9">
                Unlock premium features for smooth workflow
            </p>
            <button class="btn-upgrade">See plans</button>
        </div>
    </div>
</aside>
