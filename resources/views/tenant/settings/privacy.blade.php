## 6. Privacy Settings (resources/views/tenant/settings/privacy.blade.php)
```blade
@extends('tenant.settings.layout')

@section('settings-content')
<div class="settings-page-header">
    <h2 class="settings-page-title">Privacy & Visibility</h2>
    <p class="settings-page-desc">Control who can see your information and how you appear on the platform.</p>
</div>

<!-- Profile Visibility -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Profile Visibility</h3>
        <p class="settings-card-desc">Choose who can view your profile and information</p>
    </div>
    <div class="settings-card-body">
        <div class="settings-toggle">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Public profile</div>
                <div class="settings-toggle-desc">Make your profile visible to everyone on the internet</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Show in search engines</div>
                <div class="settings-toggle-desc">Allow search engines like Google to index your profile</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Show profile to recruiters</div>
                <div class="settings-toggle-desc">Let recruiters find you for job opportunities</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>
</div>

<!-- Contact Information -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Contact Information Visibility</h3>
        <p class="settings-card-desc">Control who can see your contact details</p>
    </div>
    <div class="settings-card-body">
        <div class="settings-form-group">
            <label class="settings-form-label">Who can see your email address?</label>
            <select class="settings-form-input">
                <option value="everyone">Everyone</option>
                <option value="connections" selected>My connections only</option>
                <option value="nobody">Nobody</option>
            </select>
        </div>

        <div class="settings-form-group">
            <label class="settings-form-label">Who can see your phone number?</label>
            <select class="settings-form-input">
                <option value="everyone">Everyone</option>
                <option value="connections" selected>My connections only</option>
                <option value="nobody">Nobody</option>
            </select>
        </div>

        <div class="settings-form-group">
            <label class="settings-form-label">Who can see your location?</label>
            <select class="settings-form-input">
                <option value="everyone" selected>Everyone</option>
                <option value="connections">My connections only</option>
                <option value="city">City only (hide exact address)</option>
                <option value="nobody">Nobody</option>
            </select>
        </div>
    </div>
    <div class="settings-card-footer">
        <span class="settings-card-meta"></span>
        <button class="settings-btn settings-btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</div>

<!-- Activity & Status -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Activity & Status</h3>
        <p class="settings-card-desc">Control your online presence and activity visibility</p>
    </div>
    <div class="settings-card-body">
        <div class="settings-toggle">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Show online status</div>
                <div class="settings-toggle-desc">Let others see when you're online</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Show last seen</div>
                <div class="settings-toggle-desc">Display when you were last active</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Show typing indicator</div>
                <div class="settings-toggle-desc">Let others see when you're typing a message</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Show read receipts</div>
                <div class="settings-toggle-desc">Let senders know when you've read their messages</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>
</div>

<!-- Data Collection -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Data Collection & Analytics</h3>
        <p class="settings-card-desc">Manage how we collect and use your data</p>
    </div>
    <div class="settings-card-body">
        <div class="settings-toggle">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Analytics & performance</div>
                <div class="settings-toggle-desc">Help us improve by sharing usage data</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Personalized recommendations</div>
                <div class="settings-toggle-desc">Use my activity to show relevant content and connections</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Third-party cookies</div>
                <div class="settings-toggle-desc">Allow third-party services to track your activity</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>
</div>

<!-- Blocked Users -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Blocked Users</h3>
        <p class="settings-card-desc">Manage users you've blocked from contacting you</p>
    </div>
    <div class="settings-card-body">
        <div style="text-align: center; padding: 40px 20px;">
            <div style="width: 64px; height: 64px; background: var(--bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-user-slash" style="font-size: 28px; color: var(--text-muted);"></i>
            </div>
            <h4 style="font-size: 16px; font-weight: var(--fw-semibold); color: var(--text-heading); margin: 0 0 8px 0;">
                No blocked users
            </h4>
            <p style="font-size: var(--fs-subtle); color: var(--text-muted); margin: 0;">
                You haven't blocked anyone yet
            </p>
        </div>
    </div>
</div>

@endsection
