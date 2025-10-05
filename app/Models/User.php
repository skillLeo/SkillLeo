<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'avatar_url',
        'username',
        'locale',
        'timezone',
        'status',
        'is_profile_complete',
        'intent',
        'meta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_profile_complete' => 'bool',
        'meta' => 'array',
        'last_login_at' => 'datetime',
        'login_count' => 'integer',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function oauthIdentities()
    {
        return $this->hasMany(OAuthIdentity::class);
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function emailOtps()
    {
        return $this->hasMany(EmailOtp::class);
    }

    // Role Helpers
    public function isTenant(): bool
    {
        return $this->intent === 'professional';
    }

    public function isClient(): bool
    {
        return $this->intent === 'client';
    }

    public function isSuperAdmin(): bool
    {
        return $this->intent === 'super_admin';
    }

    public function hasRole(string $role): bool
    {
        $roleMap = [
            'tenant' => 'professional',
            'professional' => 'professional',
            'client' => 'client',
            'super_admin' => 'super_admin',
        ];

        return $this->intent === ($roleMap[$role] ?? $role);
    }

    public function getDashboardRoute(): string
    {
        return match($this->intent) {
            'professional' => route('tenant.dashboard'),
            'client' => route('client.dashboard'),
            'super_admin' => route('admin.dashboard'),
            default => route('home'),
        };
    }

    public function getProfileRoute(): ?string
    {
        if ($this->isTenant() && $this->username) {
            return route('profile.show', $this->username);
        }
        return null;
    }

    // Scopes
    public function scopeTenants($query)
    {
        return $query->where('intent', 'professional');
    }

    public function scopeClients($query)
    {
        return $query->where('intent', 'client');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeProfileComplete($query)
    {
        return $query->where('is_profile_complete', true);
    }
}