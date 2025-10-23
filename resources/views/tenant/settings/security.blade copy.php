@extends('tenant.settings.layout')

@section('settings-content')
<div class="security-container">
    <!-- Flash Messages -->
    @if (session('success'))
    <div class="flash-message success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="flash-message error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="page-header">
        <h1>Security Settings</h1>
        <p>Protect your account with advanced security features</p>
    </div>

    <!-- Two-Factor Authentication -->
    <div class="security-card">
        <div class="card-header">
            <div>
                <h3>Two-Factor Authentication</h3>
                <p>Add an extra layer of security to your account</p>
            </div>
            @if($twoFactorEnabled)
            <span class="status-badge active">
                <i class="fas fa-shield-check"></i> Enabled
            </span>
            @else
            <span class="status-badge inactive">
                <i class="fas fa-shield-alt"></i> Disabled
            </span>
            @endif
        </div>

        <div class="card-body">
            @if(!$twoFactorEnabled)
            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-text">
                    <strong>Strengthen your account security</strong>
                    <p>Two-factor authentication requires both your password and a verification code from your phone to sign in.</p>
                </div>
            </div>
            <button onclick="openModal('setup2fa')" class="btn btn-primary">
                <i class="fas fa-lock"></i> Enable 2FA
            </button>
            @else
            <div class="feature-grid">
                <div class="feature-item active">
                    <i class="fas fa-mobile-alt"></i>
                    <h4>Authenticator App</h4>
                    <p>Active and protecting your account</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-key"></i>
                    <h4>Recovery Codes</h4>
                    <p>{{ $recoveryCodesRemaining }} of 8 remaining</p>
                </div>
            </div>
            <div class="button-group">
                <form method="POST" action="{{ route('tenant.settings.security.regenerateRecoveryCodes', $username) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Regenerate recovery codes?')">
                        <i class="fas fa-sync"></i> Regenerate Codes
                    </button>
                </form>
                <form method="POST" action="{{ route('tenant.settings.security.disable2fa', $username) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Disable 2FA? This will reduce your account security.')">
                        <i class="fas fa-times"></i> Disable 2FA
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Active Sessions</h3>
            <p class="settings-card-desc">Manage devices where you're currently signed in</p>
        </div>

        <div class="settings-card-body">
            <div id="devicesContainer" style="display:flex;flex-direction:column;gap:16px;">
                @forelse($devices as $device)
                    <div class="session-card {{ $device->is_current_device ? 'current' : '' }}"
                        data-device-id="{{ $device->id }}">
                        <div class="session-icon">
                            @if ($device->device_type === 'mobile')
                                <i class="fas fa-mobile-alt"></i>
                            @elseif($device->device_type === 'tablet')
                                <i class="fas fa-tablet-alt"></i>
                            @else
                                <i class="fas fa-desktop"></i>
                            @endif
                        </div>

                        <div class="session-details">
                            <div class="session-header">
                                <span class="session-name">{{ $device->device_display_name }}</span>

                                @if ($device->is_current_device)
                                    <span class="session-badge">Current</span>
                                @endif

                                @if ($device->is_trusted)
                                    <span class="session-badge" style="background:#10b981;">Trusted</span>
                                @endif
                            </div>

                            <div class="session-location">
                                @if ($device->location_city && $device->location_country)
                                    {{ $device->location_city }}, {{ $device->location_country }} •
                                @endif
                                {{ $device->ip_address }}
                            </div>

                            <div class="session-status">
                                @if ($device->last_activity_at && $device->last_activity_at->diffInMinutes(now()) < 5)
                                    <i class="fas fa-circle"></i> Active now
                                @else
                                    Last active {{ optional($device->last_activity_at)->diffForHumans() ?: '—' }}
                                @endif
                            </div>
                        </div>

                        <div class="session-actions">
                            @if (!$device->is_trusted)
                                <button class="session-revoke-btn trust-device-btn" data-device-id="{{ $device->id }}"
                                    style="background:#d1fae5;color:#065f46;border-color:#10b981;">
                                    <i class="fas fa-shield-alt"></i> Trust
                                </button>
                            @else
                                <button class="session-revoke-btn untrust-device-btn"
                                    data-device-id="{{ $device->id }}"
                                    style="background:#fff;border-color:#86efac;color:#065f46;">
                                    <i class="fas fa-shield-alt"></i> Remove Trust
                                </button>
                            @endif

                            @if (!$device->is_current_device)
                                <button class="session-revoke-btn revoke-device-btn"
                                    data-device-id="{{ $device->id }}">
                                    <i class="fas fa-times"></i> Revoke
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:32px;color:var(--text-muted);">
                        <i class="fas fa-desktop" style="font-size:48px;margin-bottom:16px;opacity:.3;"></i>
                        <p>No active devices found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="settings-card-footer">
            <span class="settings-card-meta">{{ $devices->count() }} active session(s)</span>
            @if ($devices->count() > 1)
                <button class="settings-btn settings-btn-danger" id="revokeAllBtn">
                    <i class="fas fa-sign-out-alt"></i> Revoke All Other Sessions
                </button>
            @endif
        </div>
    </div>

    <!-- Security Options -->
    <div class="security-card">
        <div class="card-header">
            <div>
                <h3>Security Preferences</h3>
                <p>Additional security controls for your account</p>
            </div>
        </div>

        <div class="card-body">
            <div class="option-item">
                <div class="option-info">
                    <i class="fas fa-globe"></i>
                    <div>
                        <h4>Require 2FA for new locations</h4>
                        <p>Always ask for verification from new cities or countries</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('tenant.settings.security.toggle2FANewLocation', $username) }}">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ $userSecurity->require_2fa_new_location ? '0' : '1' }}">
                    <button type="submit" class="toggle-switch {{ $userSecurity->require_2fa_new_location ? 'active' : '' }}">
                        <span></span>
                    </button>
                </form>
            </div>

            <div class="option-item">
                <div class="option-info">
                    <i class="fas fa-lock"></i>
                    <div>
                        <h4>Require 2FA for sensitive actions</h4>
                        <p>Request verification when changing security settings</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('tenant.settings.security.toggle2FASensitive', $username) }}">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ $userSecurity->require_2fa_sensitive_actions ? '0' : '1' }}">
                    <button type="submit" class="toggle-switch {{ $userSecurity->require_2fa_sensitive_actions ? 'active' : '' }}">
                        <span></span>
                    </button>
                </form>
            </div>

            <div class="option-item">
                <div class="option-info">
                    <i class="fas fa-bell"></i>
                    <div>
                        <h4>Login notifications</h4>
                        <p>Receive email alerts for new sign-ins</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('tenant.settings.security.toggleLoginNotifications', $username) }}">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ $userSecurity->login_notifications ? '0' : '1' }}">
                    <button type="submit" class="toggle-switch {{ $userSecurity->login_notifications ? 'active' : '' }}">
                        <span></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 2FA Setup Modal -->
@if($setupStep === 'scan' && !$twoFactorEnabled)
<div class="modal active" id="setup2fa">
    <div class="modal-backdrop" onclick="closeModal('setup2fa')"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Enable Two-Factor Authentication</h3>
            <button onclick="closeModal('setup2fa')" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="step-indicator">
                <div class="step active">1</div>
                <div class="step-line"></div>
                <div class="step active">2</div>
                <div class="step-line"></div>
                <div class="step">3</div>
            </div>

            <div class="qr-section">
                <h4>Scan QR Code</h4>
                <p>Use your authenticator app to scan this code</p>
                <div class="qr-container">
                    {!! QrCode::size(220)->generate("otpauth://totp/" . config('app.name') . ":" . $user->email . "?secret=" . $totpSecret . "&issuer=" . config('app.name')) !!}
                </div>
                <div class="secret-code">
                    <span>Or enter manually:</span>
                    <code>{{ $totpSecret }}</code>
                </div>
            </div>

            <form method="POST" action="{{ route('tenant.settings.security.enable2fa.verify', $username) }}">
                @csrf
                <div class="form-group">
                    <label>Enter 6-digit code</label>
                    <input type="text" name="code" class="input-code" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-check"></i> Verify & Enable
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Initial 2FA Modal Trigger -->
<div class="modal" id="setup2fa">
    <div class="modal-backdrop" onclick="closeModal('setup2fa')"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Enable Two-Factor Authentication</h3>
            <button onclick="closeModal('setup2fa')" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="auth-methods">
                <div class="auth-method">
                    <div class="method-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Authenticator App</h4>
                    <p>Use Google Authenticator, Authy, or similar apps</p>
                    <form method="POST" action="{{ route('tenant.settings.security.enable2fa.step1', $username) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Continue <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
* { box-sizing: border-box; }

.security-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

/* Flash Messages */
.flash-message {
    padding: 16px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    animation: slideDown 0.3s ease;
}
.flash-message.success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border: 1px solid #10b981;
    color: #065f46;
}
.flash-message.error {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border: 1px solid #ef4444;
    color: #991b1b;
}
.flash-message i { font-size: 20px; }

/* Page Header */
.page-header {
    margin-bottom: 40px;
}
.page-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 8px;
}
.page-header p {
    font-size: 16px;
    color: #6b7280;
    margin: 0;
}

/* Security Card */
.security-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 24px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.security-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.card-header {
    padding: 24px 28px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-header h3 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 4px;
}
.card-header p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

.card-body {
    padding: 28px;
}

/* Status Badges */
.status-badge {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}
.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.count-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
}

/* Info Box */
.info-box {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
}
.info-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2563eb;
    font-size: 20px;
}
.info-text strong {
    display: block;
    font-size: 15px;
    color: #1e40af;
    margin-bottom: 4px;
}
.info-text p {
    font-size: 14px;
    color: #1e3a8a;
    margin: 0;
    line-height: 1.5;
}

/* Feature Grid */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.feature-item {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
}
.feature-item.active {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-color: #10b981;
}
.feature-item i {
    font-size: 32px;
    color: #6b7280;
    margin-bottom: 12px;
    display: block;
}
.feature-item.active i { color: #059669; }
.feature-item h4 {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px;
}
.feature-item p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}

/* Device Card */
.device-card {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
}
.device-card:hover {
    border-color: #3b82f6;
    background: white;
}
.device-card.current {
    border-color: #10b981;
    background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%);
}

.device-icon {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 22px;
    flex-shrink: 0;
}
.device-card.current .device-icon {
    background: #059669;
    color: white;
}

.device-info {
    flex: 1;
}
.device-name {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.device-details {
    font-size: 13px;
    color: #6b7280;
}
.active-now {
    color: #10b981;
    font-weight: 600;
}

.badge {
    background: #3b82f6;
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}
.badge.trusted {
    background: #10b981;
}

.device-actions {
    display: flex;
    gap: 8px;
}

/* Option Item */
.option-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid #f3f4f6;
}
.option-item:last-child {
    border-bottom: none;
}

.option-info {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
}
.option-info > i {
    font-size: 24px;
    color: #3b82f6;
    width: 40px;
    text-align: center;
}
.option-info h4 {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px;
}
.option-info p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}

/* Buttons */
.btn {
    padding: 12px 24px;
    border-radius: 10px;
    border: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(59,130,246,0.3);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59,130,246,0.4);
}
.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}
.btn-secondary:hover {
    background: #e5e7eb;
}
.btn-danger {
    background: #fee2e2;
    color: #dc2626;
}
.btn-danger:hover {
    background: #fecaca;
}
.btn-danger-outline {
    background: white;
    color: #dc2626;
    border: 2px solid #fee2e2;
}
.btn-danger-outline:hover {
    background: #fee2e2;
}
.btn-block {
    width: 100%;
    justify-content: center;
}

.btn-icon {
    width: 40px;
    height: 40px;
    padding: 0;
    border-radius: 8px;
    background: white;
    border: 2px solid #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-icon:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: #eff6ff;
}
.btn-icon.danger:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: #fee2e2;
}

.button-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Toggle Switch */
.toggle-switch {
    width: 52px;
    height: 28px;
    background: #e5e7eb;
    border-radius: 14px;
    border: none;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
}
.toggle-switch span {
    position: absolute;
    width: 22px;
    height: 22px;
    background: white;
    border-radius: 50%;
    top: 3px;
    left: 3px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.toggle-switch.active {
    background: #10b981;
}
.toggle-switch.active span {
    left: 27px;
}

/* Modal */
.modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}
.modal.active {
    display: flex;
}

.modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    animation: modalSlide 0.3s ease;
}

@keyframes modalSlide {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    padding: 24px 28px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h3 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}
.modal-close {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.modal-close:hover {
    background: #e5e7eb;
    color: #111827;
}

.modal-body {
    padding: 28px;
}

/* Step Indicator */
.step-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 32px;
}
.step {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
}
.step.active {
    background: #3b82f6;
    color: white;
}
.step-line {
    width: 40px;
    height: 3px;
    background: #f3f4f6;
}

/* QR Section */
.qr-section {
    text-align: center;
}
.qr-section h4 {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 8px;
}
.qr-section > p {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 24px;
}
.qr-container {
    background: white;
    padding: 24px;
    border-radius: 16px;
    border: 2px solid #e5e7eb;
    display: inline-block;
    margin-bottom: 20px;
}
.secret-code {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 24px;
}
.secret-code span {
    display: block;
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
}
.secret-code code {
    font-family: 'Courier New', monospace;
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    letter-spacing: 2px;
}

/* Auth Methods */
.auth-methods {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.auth-method {
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    transition: all 0.2s ease;
}
.auth-method:hover {
    border-color: #3b82f6;
    background: white;
}
.method-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: white;
    font-size: 28px;
}
.auth-method h4 {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 8px;
}
.auth-method p {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 20px;
}

/* Form Elements */
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}
.input-code {
    width: 100%;
    padding: 16px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 24px;
    text-align: center;
    letter-spacing: 8px;
    font-weight: 700;
    color: #111827;
    transition: all 0.2s ease;
}
.input-code:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #9ca3af;
}
.empty-state i {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.3;
}
.empty-state p {
    font-size: 15px;
    margin: 0;
}

/* Animations */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .security-container {
        padding: 20px 16px;
    }
    .page-header h1 {
        font-size: 24px;
    }
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    .card-body {
        padding: 20px;
    }
    .device-card {
        flex-direction: column;
        align-items: flex-start;
    }
    .device-actions {
        width: 100%;
        justify-content: flex-end;
    }
    .button-group {
        flex-direction: column;
    }
    .button-group .btn {
        width: 100%;
        justify-content: center;
    }
    .feature-grid {
        grid-template-columns: 1fr;
    }
    .option-info {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>




















<style>
    
 
    .session-card {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: var(--bg);
        border-radius: var(--radius-lg);
        border: 2px solid transparent;
        transition: all var(--transition-base);
        margin-bottom: 16px;
    }

    .session-card:hover {
        border-color: rgba(19, 81, 216, 0.1);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .session-card.current {
        border-color: var(--accent);
        background: linear-gradient(135deg, rgba(19, 81, 216, 0.05) 0%, rgba(19, 81, 216, 0.02) 100%);
        box-shadow: 0 0 0 4px rgba(19, 81, 216, 0.08);
    }

    /* Session Device Icon */
    .session-icon {
        width: 52px;
        height: 52px;
        background: var(--accent-light, rgba(19, 81, 216, 0.1));
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all var(--transition-base);
    }

    .session-card:hover .session-icon {
        transform: scale(1.05);
    }

    .session-icon i {
        color: var(--accent);
        font-size: 22px;
    }

    .session-card.current .session-icon {
        background: var(--accent);
        box-shadow: 0 4px 12px rgba(19, 81, 216, 0.3);
    }

    .session-card.current .session-icon i {
        color: white;
    }

    /* Session Details */
    .session-details {
        flex: 1;
        min-width: 0;
    }

    .session-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
        flex-wrap: wrap;
    }

    .session-name {
        font-weight: 600;
        color: var(--text-heading);
        font-size: 15px;
    }

    .session-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--accent);
        color: white;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .session-location {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .session-status {
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .session-status i.fa-circle {
        font-size: 6px;
        color: #10b981;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    /* Session Actions */
    .session-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .session-revoke-btn {
        padding: 8px 16px;
        font-size: 13px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        background: var(--card);
        color: var(--text-body);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-base);
    }

    .session-revoke-btn:hover {
        background: #fee;
        border-color: #fca5a5;
        color: #dc2626;
        transform: translateY(-1px);
    }

   
   
</style>
@endpush

@push('scripts')
<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.style.overflow = '';
    }
});

// Auto-dismiss flash messages
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'all 0.3s ease';
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-20px)';
            setTimeout(() => msg.remove(), 300);
        }, 5000);
    });
});
</script>
@endpush