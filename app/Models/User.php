<?php
 namespace App\Models;
 
    use Illuminate\Support\Str;
  
    
    use App\Models\Skill;
    use App\Models\Education;
    use App\Models\Portfolio;
    use App\Models\SoftSkill;
    use App\Models\Experience;
    use App\Models\Preference;
    use App\Models\UserDevice;
    use App\Models\UserReason;
    use App\Models\UserProfile;
    use App\Models\UserService;
    use App\Models\UserLanguage;
    use App\Models\OAuthIdentity;
    use App\Services\TimezoneService;
    use Laravel\Sanctum\HasApiTokens;
    use Illuminate\Notifications\Notifiable;
    use App\Services\Auth\OnlineStatusService;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Support\Facades\Storage;
    use App\Support\Device;
    use Illuminate\Support\Facades\DB;

 
    
    class User extends Authenticatable
    {
        use HasApiTokens, Notifiable;

        protected $fillable = [
            'tenant_id',
            'name',
            'last_name',
            'email',
            'password',
            'avatar_url',
            'username',
            'locale',
            'timezone',
            'is_active',
            'account_status',
            'is_profile_complete',
            'is_public',
            'meta',
            'last_login_at',
            'last_seen_at',
            'login_count',
            'email_verified_at',
        ];

        protected $hidden = [
            'password',
            'remember_token',
        ];

        protected $casts = [
            'email_verified_at'   => 'datetime',
            'is_profile_complete' => 'string',
            'meta'                => 'array',
            'last_login_at'       => 'datetime',
            'last_seen_at'        => 'datetime',
            'login_count'         => 'integer',
        ];

        protected $appends = [
            'is_online',
            'last_seen_text',
            'online_status',
        ];

     

        protected static function boot()
        {
            parent::boot();
        
            // Auto-create empty profile when user is created
            static::created(function ($user) {
                $user->profile()->create([]);
                
                // Auto-create security record
                $user->security()->create([
                    'two_factor_email' => false,
                    'two_factor_phone' => false,
                    'two_factor_enabled' => false,
                ]);
            });
        }
    

        /**
         * Get the route key for the model.
         */
        public function getRouteKeyName(): string
        {
            return 'username';
        }

        // ============================================
        // Relations
        // ============================================

        public function profile(): HasOne
        {
            return $this->hasOne(UserProfile::class);
        }

        public function educations(): HasMany
        {
            return $this->hasMany(Education::class)->orderBy('position');
        }

        public function experiences(): HasMany
        {
            return $this->hasMany(Experience::class)->orderBy('position');
        }

        public function portfolios(): HasMany
        {
            return $this->hasMany(Portfolio::class)->orderBy('position');
        }

        public function preference(): HasOne
        {
            return $this->hasOne(Preference::class);
        }


        public function devices(): HasMany
        {
            return $this->hasMany(UserDevice::class);
        }

        public function activeDevices(): HasMany
        {
            return $this->devices()
                ->where('last_seen_at', '>=', now()->subDays(90))
                ->orderByDesc('last_activity_at');
        }

        
        public function currentDevice(): ?UserDevice
        {
            $id = Device::id(request());
            return $this->devices()->where('device_id', $id)->first();
        }
        


        public function oauthIdentities(): HasMany
        {
            return $this->hasMany(OAuthIdentity::class);
        }
 
        public function getIsOnlineAttribute(): bool
        {
            return app(OnlineStatusService::class)->isOnline($this);
        }

        /**
         * Get online status (online|active_recently|offline)
         */
        public function getOnlineStatusAttribute(): string
        {
            return app(OnlineStatusService::class)->getStatus($this);
        }

        /**
         * ✅ SINGLE METHOD - Get last seen text in viewer's timezone
         */
        public function getLastSeenTextAttribute(): string
        {
            $viewerTimezone = TimezoneService::getViewerTimezone();
            return TimezoneService::getOnlineStatusText($this->last_seen_at, $viewerTimezone);
        }

        /**
         * Get formatted last seen datetime in viewer's timezone
         */
        public function getLastSeenFormattedAttribute(): ?string
        {
            return TimezoneService::formatForDisplay($this->last_seen_at);
        }

        /**
         * Get member since text (human-readable)
         */
        public function getMemberSinceTextAttribute(): ?string
        {
            return TimezoneService::humanTime($this->created_at);
        }

        /**
         * Get member since formatted (Month Year)
         */
        public function getMemberSinceAttribute(): string
        {
            return TimezoneService::formatForDisplay($this->created_at, null, 'F Y') ?? 'Unknown';
        }

        /**
         * Get last login text (human-readable)
         */
        public function getLastLoginTextAttribute(): ?string
        {
            return TimezoneService::humanTime($this->last_login_at);
        }

        /**
         * Get last login formatted
         */
        public function getLastLoginFormattedAttribute(): ?string
        {
            return TimezoneService::formatForDisplay($this->last_login_at);
        }

        // ============================================
        // Online Status Methods
        // ============================================

        public function markOnline(): void
        {
            app(OnlineStatusService::class)->markOnline($this);
        }

        public function markOffline(): void
        {
            app(OnlineStatusService::class)->markOffline($this);
        }

        // ============================================
        // Profile Accessor Methods (Delegated)
        // ============================================

        public function getPhoneAttribute(): ?string
        {
            return $this->profile?->phone;
        }

        public function getCountryAttribute(): ?string
        {
            return $this->profile?->country;
        }

        public function getStateAttribute(): ?string
        {
            return $this->profile?->state;
        }

        public function getCityAttribute(): ?string
        {
            return $this->profile?->city;
        }

        public function getheadlineAttribute(): ?string
        {
            return $this->profile?->headline;
        }

        public function getaboutAttribute(): ?string
        {
            return $this->profile?->about;
        }

        public function getLocationAttribute(): ?string
        {
            return $this->profile?->location;
        }

        // ============================================
        // Helper Methods
        // ============================================

        public function hasMultipleDevices(): bool
        {
            return $this->activeDevices()->count() > 1;
        }

        public function getFullNameAttribute(): string
        {
            return trim("{$this->name} {$this->last_name}");
        }

        public function isActive(): bool
        {
            return $this->is_active === 'active';
        }

        public function hasCompletedProfile(): bool
        {
            return in_array($this->is_profile_complete, ['complete', 'completed']);
        }

        public function needsOnboarding(): bool
        {
            return $this->account_status === 'pending_onboarding';
        }

        public function hasVerifiedEmail(): bool
        {
            return !is_null($this->email_verified_at);
        }

        public function recordLogin(): void
        {
            $this->forceFill([
                'last_login_at' => now(),
                'login_count'   => ($this->login_count ?? 0) + 1,
            ])->save();
        }

        public function hasOAuthProvider(string $provider): bool
        {
            return $this->oauthIdentities()
                ->where('provider', $provider)
                ->exists();
        }
        public function getAvatarUrlAttribute(): string
        {
            // Check both 'avatar_url' and 'avatar' columns
            $raw = trim((string) ($this->attributes['avatar_url'] ?? $this->attributes['avatar'] ?? ''));
        
            // Fallback if nothing saved
            if ($raw === '') {
                return asset('images/avatar-fallback.png');
            }
        
            // If already an absolute URL (Google/LinkedIn/OAuth providers)
            if (preg_match('~^https?://~i', $raw)) {
                return $raw;
            }
        
            // If the db holds "storage/..." (public/storage symlinked)
            if (Str::startsWith($raw, ['storage/', '/storage/'])) {
                return asset(ltrim($raw, '/'));
            }
        
            // Otherwise treat it as a path on the public disk (e.g. "avatars/3/file.jpg")
            if (Storage::disk('public')->exists($raw)) {
                return Storage::disk('public')->url($raw);
            }
        
            // As a last resort, try asset()
            return asset(ltrim($raw, '/'));
        }

        // ============================================
        // Query Scopes
        // ============================================

        public function scopeActive($query)
        {
            return $query->where('is_active', 'active');
        }

        public function scopeOnboarded($query)
        {
            return $query->where('account_status', '!=', 'pending_onboarding');
        }

        public function scopeVerified($query)
        {
            return $query->whereNotNull('email_verified_at');
        }

        public function scopeSearch($query, string $term)
        {
            return $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('username', 'like', "%{$term}%");
            });
        }

        public function scopeOnline($query)
        {
            $threshold = now()->subMinutes(5);
            return $query->where('last_seen_at', '>=', $threshold);
        }

        public function scopeRecentlyActive($query)
        {
            return $query->where('last_seen_at', '>=', now()->subDay())
                        ->orderByDesc('last_seen_at');
        }

        public function scopeByCountry($query, string $country)
        {
            return $query->whereHas('profile', function ($q) use ($country) {
                $q->where('country', $country);
            });
        }

        public function scopeByLocation($query, ?string $country = null, ?string $state = null, ?string $city = null)
        {
            return $query->whereHas('profile', function ($q) use ($country, $state, $city) {
                if ($country) $q->where('country', $country);
                if ($state) $q->where('state', $state);
                if ($city) $q->where('city', $city);
            });
        }

        public function scopeWithTimezone($query)
        {
            return $query->select('users.*')
                ->selectRaw('COALESCE(users.timezone, "UTC") as effective_timezone');
        }

        public function recentDevices()
        {
            return $this->devices()
                ->where('last_seen_at', '>=', now()->subDays(30))
                ->orderByDesc('last_seen_at')
                ->limit(5)
                ->get();
        }

        public function activate(): void
        {
            $this->update(['is_active' => 'active']);
        }

        public function deactivate(): void
        {
            $this->update(['is_active' => 'inactive']);
        }

        public function completeProfile(): void
        {
            $this->update([
                'is_profile_complete' => 'complete',
                'account_status' => $this->account_status === 'pending_onboarding' 
                    ? 'active' 
                    : $this->account_status,
            ]);
        }




        public function softSkills(): BelongsToMany
    {
        return $this->belongsToMany(SoftSkill::class, 'user_soft_skills')
            ->withPivot(['level', 'position'])
            ->withTimestamps()
            ->orderBy('user_soft_skills.position');
    }




    public function languages(): HasMany
    {
        // simple child records, sorted by position
        return $this->hasMany(UserLanguage::class)->orderBy('position');
    }


    public function services()
    {
        return $this->hasMany(\App\Models\UserService::class);
    }

    public function reasons()
    {
        return $this->hasMany(\App\Models\UserReason::class);
    }


    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'user_skills',  // pivot table name
            'user_id',      // foreign key on pivot table for current model
            'skill_id'      // foreign key on pivot table for related model
        )
        ->withPivot(['level', 'position'])
        ->orderBy('user_skills.position');
    }


































 
/**
 * Get banner URL with fallback to gradient
 */
public function getBannerUrlAttribute(): ?string
{
    $path = $this->profile->banner ?? null;
    
    if (!$path) {
        return null; // Will use CSS gradient fallback
    }
    
    // If already a full URL (OAuth providers)
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    
    // Local storage path
    return Storage::disk('public')->url($path);
}

public function getBannerFitAttribute(): string
{
    return $this->profile->banner_preference['fit'] ?? 'cover';
}

public function getBannerPositionAttribute(): string
{
    return $this->profile->banner_preference['position'] ?? 'center center';
}
    
    public function getBannerZoomAttribute(): int
    {
        return (int)($this->profile->banner_preference['zoom'] ?? 100);
    }
    
    public function getBannerOffsetXAttribute(): float
    {
        return (float)($this->profile->banner_preference['offset_x'] ?? 0);
    }
    
    public function getBannerOffsetYAttribute(): float
    {
        return (float)($this->profile->banner_preference['offset_y'] ?? 0);
    }
    



























    public function security(): HasOne
    {
        return $this->hasOne(UserSecurity::class);
    }
    
    // Also add this to your User model's boot method:
 




    public function userSecurity()
    {
        return $this->hasOne(UserSecurity::class);
    }
    
    public function recoveryCodes()
    {
        return $this->hasMany(RecoveryCode::class);
    }
    
    public function trustedDevices()
    {
        return $this->hasMany(TrustedDevice::class);
    }
    
    public function has2FAEnabled()
    {
        $security = $this->userSecurity;
        
        if (!$security) {
            return false;
        }
        
        return $security->two_factor_enabled || 
               $security->two_factor_email || 
               $security->two_factor_phone;
    }










    public function projectsAsTeamMember()
{
    return $this->belongsToMany(\App\Models\Project::class, 'project_team')
        ->withTimestamps();
}

public function assignedTasks()
{
    return $this->hasMany(\App\Models\Task::class, 'assigned_to');
}

    /**
     * Is this user the workspace owner whose profile/dashboard we're viewing?
     */
    public function isWorkspaceOwner(User $workspaceOwner): bool
    {
        return $this->id === $workspaceOwner->id;
    }

    /**
     * Very light "manager" check.
     * In real life you'd check pivot role = 'project_manager' or similar.
     */
    public function isProjectManagerFor(User $workspaceOwner): bool
    {
        if ($this->isWorkspaceOwner($workspaceOwner)) {
            return true;
        }

        // Example heuristic: user is on at least one project_team row
        // for a project owned by $workspaceOwner with role containing 'manager'.
        return \DB::table('project_team')
            ->join('projects', 'project_team.project_id', '=', 'projects.id')
            ->where('projects.user_id', $workspaceOwner->id)
            ->where('project_team.user_id', $this->id)
            ->where('project_team.role', 'like', '%manager%')
            ->exists();
    }

    /**
     * Is this user a client of this workspace owner?
     */
    public function isClientFor(User $workspaceOwner): bool
    {
        return DB::table('clients')
            ->where('user_id', $workspaceOwner->id)
            ->where('client_user_id', $this->id)
            ->exists();
    }

    /**
     * Can this user view all tasks across the workspace?
     */
    public function canSeeAllTasks(User $workspaceOwner): bool
    {
        return $this->isWorkspaceOwner($workspaceOwner) ||
               $this->isProjectManagerFor($workspaceOwner);
    }

    /**
     * Can this user approve work / mark final done?
     * (workspace owner, PM, OR the client tied to that project for client_approval tasks)
     */
    public function canApproveTasksFor(User $workspaceOwner): bool
    {
        if ($this->isWorkspaceOwner($workspaceOwner) ||
            $this->isProjectManagerFor($workspaceOwner)) {
            return true;
        }

        // clients can "approve" only if they are client of that workspace
        return $this->isClientFor($workspaceOwner);
    }


}
    


    