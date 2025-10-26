@php
    use App\Models\User;
    use App\Models\Project;
    use App\Models\Client;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    // ===== Resolve owner / username context =====
    $username = request()->route('username') ?? Auth::user()?->username;
    $owner = $username
        ? User::with('profile')->where('username', $username)->first()
        : Auth::user()?->load('profile');

    $ownerId = $owner?->id ?? Auth::id();

    $title = fn ($s) => $s !== '' ? Str::of($s)->squish()->title()->toString() : '';

    if ($owner) {
        $first = $owner->name ?? '';
        $last  = $owner->last_name  ?? '';
        $full  = trim($title($first).' '.$title($last)) ?: ($owner->username ?? 'User');
        $p     = $owner->profile;

        $user = (object)[
            'name'            => $full,
            'headline'        => (string)($p?->headline ?? ''),
            'location'        => collect([$p?->city, $p?->state, $p?->country])->filter()->join(', ') ?: null,
            'avatar_url'      => $owner->avatar_url ?? asset('images/avatar-fallback.png'),
            'banner_url'      => $owner->banner_url ?? null,
            'is_online'       => (bool) ($owner->is_online ?? false),
            'banner_fit'      => $owner->banner_fit ?? 'cover',
            'banner_position' => $owner->banner_position ?? 'center',
        ];

        $ownerUsername = $owner->username ?? $username;
        $stats = [
            'connections' => (int)($owner->connections_count ?? 0),
            'views'       => (int)($owner->profile_views_count ?? 0),
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
        $stats = ['connections' => 0, 'views' => 0];
    }

    // ===== Active route helpers =====
    $isActive = function (string|array $name): string {
        return request()->routeIs($name) ? 'active' : '';
    };

    // Ensure section open/close matches the "manage projects" area
    $isManageProfile  = request()->routeIs('tenant.manage.*');
    $isManageProjects = request()->routeIs('tenant.manage.projects.*');

    // ===== Dynamic sidebar counts (scoped to this owner) =====
    // Total projects owned by this user
    $sidebarCounts['projects'] = Project::where('user_id', $ownerId)->count();

    // Distinct team members across ALL the owner's projects (via project_team)
    $sidebarCounts['team'] = DB::table('project_team')
        ->join('projects', 'project_team.project_id', '=', 'projects.id')
        ->where('projects.user_id', $ownerId)
        ->distinct('project_team.user_id')
        ->count('project_team.user_id');

    // Clients belonging to this owner
    $sidebarCounts['clients'] = Client::where('user_id', $ownerId)->count();
@endphp
@php
    /** @var \App\Models\User $viewer */
    $viewer = Auth::user();
    $workspaceOwnerUser = $owner ?? Auth::user(); // same logic you already use
    // helper booleans
    $canSeeAllTasks = $viewer->canSeeAllTasks($workspaceOwnerUser);
    $canApproveTasks = $viewer->canApproveTasksFor($workspaceOwnerUser);
    $isClient = $viewer->isClientFor($workspaceOwnerUser);
@endphp



<div class="sidebar-scroll">
    
    <!-- Profile Card -->
    <div class="sidebar-profile-card">
        <div class="sidebar-profile-banner"    @if ($user->banner_url)
            style="background-image:url('{{ $user->banner_url }}');
                   background-size: {{ $user->banner_fit }};
                   background-position: {{ $user->banner_position }};"
         @endif
         ></div>
        
        <div class="sidebar-profile-avatar-wrapper">
            <div class="sidebar-profile-avatar">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         referrerpolicy="no-referrer" crossorigin="anonymous"
                         onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                @if ($user->is_online)
                    <span class="sidebar-profile-status"></span>
                @endif
            </div>
        </div>

        <div class="sidebar-profile-info">
            <h3 class="sidebar-profile-name">{{ $user->name }}</h3>
            @if (!empty($user->headline))
                <p class="sidebar-profile-headline">{{ $user->headline }}</p>
            @endif
            @if (!empty($user->location))
                <div class="sidebar-profile-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $user->location }}</span>
                </div>
            @endif
        </div>

        <div class="sidebar-profile-divider"></div>

        <div class="sidebar-profile-stats">
            <div class="sidebar-profile-stat">
                <span class="sidebar-profile-stat-value">{{ number_format($stats['connections']) }}</span>
                <span class="sidebar-profile-stat-label">Connections</span>
            </div>
            <div class="sidebar-profile-stat">
                <span class="sidebar-profile-stat-value">{{ number_format($stats['views']) }}</span>
                <span class="sidebar-profile-stat-label">Profile Views</span>
            </div>
        </div>

        <a href="{{ route('tenant.profile', $ownerUsername) }}" class="sidebar-profile-cta">
            <span>View Public Profile</span>
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Manage Projects Section -->
<div class="sidebar-nav-section">
    <button class="sidebar-nav-section-header" onclick="toggleSidebarSection('manageProjects')">
        <div class="sidebar-nav-section-title">
            <i class="fas fa-project-diagram"></i>
            <span>Manage Projects</span>
        </div>
        <i class="fas fa-chevron-down sidebar-nav-chevron {{ $isManageProjects ? 'active' : '' }}" id="chevron-manageProjects"></i>
    </button>
    
    <div class="sidebar-nav-section-content {{ $isManageProjects ? 'active' : '' }}" id="section-manageProjects">
        <button class="sidebar-nav-create-btn" onclick="openCreateProjectModal()">
            <i class="fas fa-plus"></i>
            <span>New Project</span>
        </button>

        <div class="sidebar-nav-group">
            <div class="sidebar-nav-group-label">OVERVIEW</div>
            <nav class="sidebar-nav-menu">
                <a href="{{ route('tenant.manage.projects.dashboard', $username) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.projects.dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('tenant.manage.projects.list', $username) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.projects.list') }}">
                    <i class="fas fa-th-large"></i>
                    <span>All Projects</span>
                    @if(($sidebarCounts['projects'] ?? 0) > 0)
                        <span class="sidebar-nav-badge">{{ number_format($sidebarCounts['projects']) }}</span>
                    @endif
                </a>
            </nav>
        </div>















{{-- ===== NEW WORK SECTION (Tasks / Approvals) ===== --}}
<div class="sidebar-nav-group">
    <div class="sidebar-nav-group-label">WORK</div>

    <nav class="sidebar-nav-menu">

        {{-- My Tasks: visible to everyone (owner, teammate, client) --}}
        <a href="{{ route('tenant.manage.projects.tasks.index', $username) }}"
           class="sidebar-nav-item {{ request()->routeIs('tenant.manage.projects.tasks.index') ? 'active' : '' }}">
            <i class="fas fa-check-square"></i>
            <span>Tasks</span>
        </a>
 
    </nav>
</div>
{{-- ===== /WORK SECTION ===== --}}














        <div class="sidebar-nav-group">
            <div class="sidebar-nav-group-label">MANAGE</div>
            <nav class="sidebar-nav-menu">
                <a href="{{ route('tenant.manage.projects.team.index', $username) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.projects.team.*') }}">
                    <i class="fas fa-users"></i>
                    <span>Team</span>
                    @if(($sidebarCounts['team'] ?? 0) > 0)
                        <span class="sidebar-nav-badge">{{ number_format($sidebarCounts['team']) }}</span>
                    @endif
                </a>
                <a href="{{ route('tenant.manage.projects.clients.index', $username) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.projects.clients.*') }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Clients</span>
                    @if(($sidebarCounts['clients'] ?? 0) > 0)
                        <span class="sidebar-nav-badge">{{ number_format($sidebarCounts['clients']) }}</span>
                    @endif
                </a>
            </nav>
        </div>

        <div class="sidebar-nav-group">
            <div class="sidebar-nav-group-label">REPORTS</div>
            <nav class="sidebar-nav-menu">
                <a href="{{ route('tenant.manage.projects.reports.index', $username) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.projects.reports.*') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
                <a href="{{ route('tenant.manage.projects.reports.velocity', $username) }}" class="sidebar-nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Velocity</span>
                </a>
                <a href="{{ route('tenant.manage.projects.reports.burndown', $username) }}" class="sidebar-nav-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Burndown</span>
                </a>
                <a href="{{ route('tenant.manage.projects.reports.time-tracking', $username) }}" class="sidebar-nav-item">
                    <i class="fas fa-clock"></i>
                    <span>Time Tracking</span>
                </a>
            </nav>
        </div>
    </div>
</div>


    <!-- Manage Profile Section -->
    <div class="sidebar-nav-section">
        <button class="sidebar-nav-section-header" onclick="toggleSidebarSection('manageProfile')">
            <div class="sidebar-nav-section-title">
                <i class="fas fa-user-circle"></i>
                <span>Manage Profile</span>
            </div>
            <i class="fas fa-chevron-down sidebar-nav-chevron {{ $isManageProfile ? 'active' : '' }}" id="chevron-manageProfile"></i>
        </button>
        
        <div class="sidebar-nav-section-content {{ $isManageProfile ? 'active' : '' }}" id="section-manageProfile">
            <nav class="sidebar-nav-menu">
                <a href="{{ route('tenant.manage.personal', $ownerUsername) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.personal') }}">
                    <i class="fas fa-id-card"></i>
                    <span>Personal Info</span>
                </a>
                <a href="{{ route('tenant.manage.skills', $ownerUsername) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.skills') }}">
                    <i class="fas fa-code"></i>
                    <span>Skills</span>
                </a>
                <a href="{{ route('tenant.manage.portfolio', $ownerUsername) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.portfolio') }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Portfolio</span>
                </a>
                <a href="{{ route('tenant.manage.experience', $ownerUsername) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.experience') }}">
                    <i class="fas fa-building"></i>
                    <span>Experience</span>
                </a>
                <a href="{{ route('tenant.manage.education', $ownerUsername) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.education') }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Education</span>
                </a>
                <a href="{{ route('tenant.manage.languages', $ownerUsername) }}" class="sidebar-nav-item {{ $isActive('tenant.manage.languages') }}">
                    <i class="fas fa-language"></i>
                    <span>Languages</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Account Section -->
    <div class="sidebar-nav-section">
        <div class="sidebar-nav-section-simple">
            <i class="fas fa-cog"></i>
            <span>Account</span>
        </div>
        <nav class="sidebar-nav-menu">
            <a href="{{ route('tenant.settings.account', $username) }}" class="sidebar-nav-item">
                <i class="fas fa-user-cog"></i>
                <span>Settings</span>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="sidebar-nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>

    <!-- Upgrade Card -->
    <div class="sidebar-upgrade-card">
        <div class="sidebar-upgrade-icon">
            <i class="fas fa-rocket"></i>
        </div>
        <h4>Upgrade to Pro</h4>
        <p>Unlock advanced analytics, AI helpers & priority support</p>
        <button class="sidebar-upgrade-btn">
            <span>See Plans</span>
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

</div>

<script>
    function toggleSidebarSection(sectionId) {
        const content = document.getElementById(`section-${sectionId}`);
        const chevron = document.getElementById(`chevron-${sectionId}`);
        
        if (content && chevron) {
            const isActive = content.classList.toggle('active');
            chevron.classList.toggle('active', isActive);
            localStorage.setItem(`sidebar-${sectionId}`, isActive ? 'expanded' : 'collapsed');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        ['manageProfile', 'manageProjects'].forEach(id => {
            const state = localStorage.getItem(`sidebar-${id}`);
            const content = document.getElementById(`section-${id}`);
            const chevron = document.getElementById(`chevron-${id}`);
            
            if (state === 'collapsed' && content && chevron) {
                content.classList.remove('active');
                chevron.classList.remove('active');
            } else if (state === 'expanded' && content && chevron) {
                content.classList.add('active');
                chevron.classList.add('active');
            }
        });
    });
</script>