@extends('tenant.settings.layout')

@section('settings-content')

<style>
    /* ============================================
   ACCOUNT PAGE - PROFESSIONAL CSS
   Enhanced Forms, Sessions, and Visual Feedback
   ============================================ */

/* ============================================
   1. ENHANCED FORM INPUTS WITH ICONS
   ============================================ */

/* Input with Icon Container */
.settings-form-group {
    position: relative;
}

/* Icon Positioning for Inputs */
.settings-form-input-with-icon {
    padding-left: 44px !important;
}

.settings-form-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 16px;
    pointer-events: none;
    transition: all var(--transition-base);
}

.settings-form-group:focus-within .settings-form-icon {
    color: var(--accent);
}

/* Enhanced Input States */
.settings-form-input:focus + .settings-form-icon {
    color: var(--accent);
}

/* ============================================
   2. VERIFIED EMAIL INDICATOR
   ============================================ */

.email-verification-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
    margin-top: 8px;
}

.email-verification-status.verified {
    color: #10b981;
}

.email-verification-status.unverified {
    color: #f59e0b;
}

.email-verification-status i {
    font-size: 14px;
}

.verify-email-link {
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    margin-left: 8px;
    transition: all var(--transition-base);
}

.verify-email-link:hover {
    color: #0d47a1;
    text-decoration: underline;
}

/* ============================================
   3. PASSWORD STRENGTH METER
   ============================================ */

.password-strength-wrapper {
    margin-top: 12px;
}

.password-strength-bar {
    height: 6px;
    background: var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 8px;
}

.password-strength-fill {
    height: 100%;
    width: 0;
    transition: all 0.4s ease;
    border-radius: 10px;
}

.password-strength-fill.weak {
    width: 25%;
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}

.password-strength-fill.fair {
    width: 50%;
    background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
}

.password-strength-fill.good {
    width: 75%;
    background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
}

.password-strength-fill.strong {
    width: 100%;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}

.password-strength-text {
    font-size: 13px;
    font-weight: 600;
}

.password-strength-text.weak {
    color: #ef4444;
}

.password-strength-text.fair {
    color: #f59e0b;
}

.password-strength-text.good {
    color: #3b82f6;
}

.password-strength-text.strong {
    color: #10b981;
}

/* Password Requirements Checklist */
.password-requirements {
    background: var(--bg);
    padding: 18px;
    border-radius: var(--radius-md);
    margin-top: 16px;
    border: 1px solid var(--border);
}

.password-requirements-title {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--text-heading);
}

.password-requirements ul {
    margin: 0;
    padding-left: 20px;
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.8;
}

.password-requirements li {
    position: relative;
    list-style: none;
    padding-left: 24px;
}

.password-requirements li::before {
    content: '○';
    position: absolute;
    left: 0;
    color: var(--border);
    font-weight: bold;
}

.password-requirements li.met::before {
    content: '✓';
    color: #10b981;
}

/* ============================================
   4. ACTIVE SESSIONS - MODERN CARDS
   ============================================ */

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
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
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

/* ============================================
   5. LOGIN HISTORY TABLE - ENHANCED
   ============================================ */

.login-history-wrapper {
    overflow-x: auto;
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
}

.login-history-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--card);
}

.login-history-table thead {
    background: var(--bg);
}

.login-history-table th {
    text-align: left;
    padding: 14px 12px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border);
}

.login-history-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: all var(--transition-fast);
}

.login-history-table tbody tr:hover {
    background: rgba(19, 81, 216, 0.02);
}

.login-history-table tbody tr:last-child {
    border-bottom: none;
}

.login-history-table td {
    padding: 16px 12px;
    font-size: 14px;
    color: var(--text-body);
}

.login-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.login-status-badge.success {
    background: #d1fae5;
    color: #065f46;
}

.login-status-badge.failed {
    background: #fee2e2;
    color: #991b1b;
}

.login-status-badge i {
    font-size: 10px;
}

/* ============================================
   6. PROFILE BASICS ENHANCEMENTS
   ============================================ */

/* Profile Avatar Upload (if added later) */
.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 24px;
    background: var(--bg);
    border-radius: var(--radius-lg);
    margin-bottom: 24px;
    border: 1px solid var(--border);
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent) 0%, #0d47a1 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    font-weight: 700;
    box-shadow: 0 4px 16px rgba(19, 81, 216, 0.2);
    flex-shrink: 0;
}

.profile-avatar-info {
    flex: 1;
}

.profile-avatar-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 6px;
}

.profile-avatar-desc {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 12px;
}

.profile-avatar-actions {
    display: flex;
    gap: 8px;
}

/* Profile URL Display */
.profile-url-preview {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--bg);
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    color: var(--text-muted);
    font-family: 'Courier New', monospace;
    margin-top: 8px;
    border: 1px solid var(--border);
}

.profile-url-preview i {
    color: var(--accent);
}

.copy-url-btn {
    background: none;
    border: none;
    color: var(--accent);
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all var(--transition-base);
}

.copy-url-btn:hover {
    background: var(--accent-light, rgba(19, 81, 216, 0.1));
}

/* ============================================
   7. FORM VALIDATION STATES
   ============================================ */

.settings-form-input.is-valid {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.02);
}

.settings-form-input.is-valid:focus {
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
}

.settings-form-input.is-invalid {
    border-color: #ef4444;
    background: rgba(239, 68, 68, 0.02);
}

.settings-form-input.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

.form-validation-message {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
    font-size: 13px;
    font-weight: 500;
}

.form-validation-message.success {
    color: #10b981;
}

.form-validation-message.error {
    color: #ef4444;
}

.form-validation-message i {
    font-size: 12px;
}

/* ============================================
   8. ENHANCED CARD FOOTERS
   ============================================ */

.settings-card-footer-enhanced {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid var(--border);
    margin-top: 24px;
}

.last-updated-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--bg);
    border-radius: 6px;
    font-size: 12px;
    color: var(--text-muted);
    border: 1px solid var(--border);
}

.last-updated-badge i {
    color: var(--accent);
    font-size: 11px;
}

/* ============================================
   9. PHONE NUMBER INPUT WITH FLAG
   ============================================ */

.phone-input-wrapper {
    position: relative;
    display: flex;
    gap: 8px;
}

.country-code-select {
    width: 100px;
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    font-size: 14px;
    background: var(--card);
    color: var(--text-body);
    cursor: pointer;
    transition: all var(--transition-base);
}

.country-code-select:hover {
    border-color: rgba(19, 81, 216, 0.3);
}

.country-code-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 4px rgba(19, 81, 216, 0.08);
}

.phone-number-input {
    flex: 1;
}

/* ============================================
   10. SECONDARY EMAIL SECTION
   ============================================ */

.email-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.email-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: var(--bg);
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    transition: all var(--transition-base);
}

.email-item:hover {
    border-color: rgba(19, 81, 216, 0.2);
    box-shadow: var(--shadow-sm);
}

.email-item-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.email-item-icon {
    width: 36px;
    height: 36px;
    background: var(--accent-light, rgba(19, 81, 216, 0.1));
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.email-item-icon i {
    color: var(--accent);
    font-size: 16px;
}

.email-item-details {
    flex: 1;
}

.email-item-address {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 2px;
}

.email-item-status {
    font-size: 12px;
    color: var(--text-muted);
}

.email-item-actions {
    display: flex;
    gap: 8px;
}

.email-action-btn {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text-body);
    cursor: pointer;
    transition: all var(--transition-base);
    font-weight: 600;
}

.email-action-btn:hover {
    background: var(--bg);
    border-color: var(--accent);
    color: var(--accent);
}

.email-action-btn.primary {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}

.email-action-btn.primary:hover {
    background: #0d47a1;
}

/* ============================================
   11. RESPONSIVE ENHANCEMENTS FOR ACCOUNT PAGE
   ============================================ */

@media (max-width: 768px) {
    .session-card {
        flex-direction: column;
        gap: 12px;
    }
    
    .session-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .session-revoke-btn {
        flex: 1;
    }
    
    .login-history-wrapper {
        font-size: 13px;
    }
    
    .login-history-table th,
    .login-history-table td {
        padding: 10px 8px;
        font-size: 13px;
    }
    
    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-avatar-actions {
        justify-content: center;
    }
    
    .phone-input-wrapper {
        flex-direction: column;
    }
    
    .country-code-select {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .session-card {
        padding: 16px;
    }
    
    .session-icon {
        width: 44px;
        height: 44px;
    }
    
    .session-icon i {
        font-size: 18px;
    }
    
    .password-strength-bar {
        height: 5px;
    }
    
    .login-history-table {
        font-size: 12px;
    }
    
    .login-status-badge {
        font-size: 11px;
        padding: 4px 8px;
    }
}

/* ============================================
   12. LOADING STATES & ANIMATIONS
   ============================================ */

.form-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.6;
}

.form-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 3px solid var(--accent);
    border-right-color: transparent;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Success Animation */
@keyframes successPulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
    }
}

.form-success {
    animation: successPulse 1s ease-out;
}

/* ============================================
   13. TOOLTIP ENHANCEMENTS
   ============================================ */

.tooltip-trigger {
    position: relative;
    display: inline-block;
    cursor: help;
}

.tooltip-content {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(-8px);
    padding: 8px 12px;
    background: #1f2937;
    color: white;
    font-size: 12px;
    border-radius: 6px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: all var(--transition-base);
    z-index: 1000;
}

.tooltip-content::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: #1f2937;
}

.tooltip-trigger:hover .tooltip-content {
    opacity: 1;
    transform: translateX(-50%) translateY(-4px);
}

/* ============================================
   14. ACCESSIBILITY ENHANCEMENTS
   ============================================ */

/* Focus visible for keyboard navigation */
.settings-form-input:focus-visible,
.settings-btn:focus-visible,
.session-revoke-btn:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 2px;
}

/* Skip to content link */
.skip-to-content {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--accent);
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 0 0 4px 0;
    z-index: 10000;
}

.skip-to-content:focus {
    top: 0;
}

/* Screen reader only content */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

/* ============================================
   15. PRINT STYLES
   ============================================ */

@media print {
    .settings-sidebar,
    .settings-toggle-btn,
    .settings-btn,
    .session-actions {
        display: none !important;
    }
    
    .settings-content {
        max-width: 100%;
        padding: 0;
    }
    
    .settings-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #ddd;
    }
}
</style>
    <div class="settings-page-header">
        <h2 class="settings-page-title">Account</h2>
        <p class="settings-page-desc">Manage your basic account information, email, and password.</p>
    </div>

    <!-- Profile Basics -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Profile Basics</h3>
            <p class="settings-card-desc">Update your name, username, and contact information</p>
        </div>
        <div class="settings-card-body">
            <form id="profileBasicsForm">
                <div class="settings-form-row">
                    <div class="settings-form-group">
                        <label class="settings-form-label">
                            First Name <span class="required">*</span>
                        </label>
                        <input type="text" class="settings-form-input" value="{{ $user->name ?? '' }}"
                            placeholder="Hassan">
                    </div>
                    <div class="settings-form-group">
                        <label class="settings-form-label">Last Name</label>
                        <input type="text" class="settings-form-input" value="{{ $user->last_name ?? '' }}"
                            placeholder="Mehmood">
                    </div>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Username <span class="required">*</span>
                    </label>
                    <input type="text" class="settings-form-input" value="{{ $user->username }}"
                        placeholder="hassanmehmood">
                    <span class="settings-form-help">
                        Your profile URL: skillleo.com/{{ $user->username }}
                    </span>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Primary Email <span class="required">*</span>
                    </label>
                    <input type="email" class="settings-form-input" value="{{ $user->email }}"
                        placeholder="hassan@example.com">
                    <span class="settings-form-help">
                        @if ($user->email_verified_at)
                            <i class="fas fa-check-circle" style="color: #10b981;"></i> Verified
                        @else
                            <i class="fas fa-exclamation-circle" style="color: #f59e0b;"></i> Not verified
                            <a href="#" style="color: var(--accent); margin-left: 8px;">Verify now</a>
                        @endif
                    </span>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">Secondary Email</label>
                    <input type="email" class="settings-form-input" placeholder="backup@example.com">
                    <span class="settings-form-help">Add a backup email for account recovery</span>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">Phone Number</label>
                    <input type="tel" class="settings-form-input" value="{{ $user->profile->phone ?? '' }}"
                        placeholder="+92 300 1234567">
                </div>
            </form>
        </div>
        <div class="settings-card-footer">
            <span class="settings-card-meta">Last updated 3 days ago</span>
            <div class="settings-card-actions">
                <button class="settings-btn settings-btn-secondary">Cancel</button>
                <button class="settings-btn settings-btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Change Password</h3>
            <p class="settings-card-desc">Update your password to keep your account secure</p>
        </div>
        <div class="settings-card-body">
            <form id="changePasswordForm">
                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Current Password <span class="required">*</span>
                    </label>
                    <input type="password" class="settings-form-input" placeholder="Enter current password">
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">
                        New Password <span class="required">*</span>
                    </label>
                    <input type="password" class="settings-form-input" placeholder="Enter new password" id="newPassword">
                    <span class="settings-form-help">
                        <span id="passwordStrength"></span>
                    </span>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Confirm New Password <span class="required">*</span>
                    </label>
                    <input type="password" class="settings-form-input" placeholder="Confirm new password">
                </div>

                <!-- Password Requirements -->
                <div style="background: var(--bg); padding: 16px; border-radius: 8px; margin-top: 16px;">
                    <div
                        style="font-size: var(--fs-subtle); font-weight: var(--fw-semibold); margin-bottom: 8px; color: var(--text-heading);">
                        Password Requirements:
                    </div>
                    <ul style="margin: 0; padding-left: 20px; font-size: var(--fs-subtle); color: var(--text-muted);">
                        <li>At least 8 characters long</li>
                        <li>Contains uppercase and lowercase letters</li>
                        <li>Contains at least one number</li>
                        <li>Contains at least one special character</li>
                    </ul>
                </div>
            </form>
        </div>
        <div class="settings-card-footer">
            <span class="settings-card-meta">Last changed 45 days ago</span>
            <div class="settings-card-actions">
                <button class="settings-btn settings-btn-secondary">Cancel</button>
                <button class="settings-btn settings-btn-primary">
                    <i class="fas fa-key"></i> Update Password
                </button>
            </div>
        </div>
    </div>

    <!-- Sessions & Devices -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Active Sessions</h3>
            <p class="settings-card-desc">Manage devices where you're currently signed in</p>
        </div>
        <div class="settings-card-body">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <!-- Current Session -->
                <div
                    style="display: flex; align-items: flex-start; gap: 16px; padding: 16px; background: var(--bg); border-radius: 10px; border: 2px solid var(--accent-light);">
                    <div
                        style="width: 48px; height: 48px; background: var(--accent-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-desktop" style="color: var(--accent); font-size: 20px;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="font-weight: var(--fw-semibold); color: var(--text-heading);">Chrome on
                                Windows</span>
                            <span
                                style="background: var(--accent); color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: var(--fw-semibold);">Current</span>
                        </div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                            Sargodha, Pakistan • 192.168.1.1
                        </div>
                        <div style="font-size: var(--fs-micro); color: var(--text-muted); margin-top: 2px;">
                            <i class="fas fa-circle" style="font-size: 6px; color: #10b981;"></i> Active now
                        </div>
                    </div>
                </div>

                <!-- Other Sessions -->
                <div
                    style="display: flex; align-items: flex-start; gap: 16px; padding: 16px; background: var(--bg); border-radius: 10px;">
                    <div
                        style="width: 48px; height: 48px; background: rgba(0, 0, 0, 0.05); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-mobile-alt" style="color: var(--text-muted); font-size: 20px;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">
                            Safari on iPhone
                        </div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                            Lahore, Pakistan • 192.168.1.2
                        </div>
                        <div style="font-size: var(--fs-micro); color: var(--text-muted); margin-top: 2px;">
                            Last active 2 hours ago
                        </div>
                    </div>
                    <button class="settings-btn settings-btn-secondary"
                        style="padding: 8px 16px; font-size: var(--fs-subtle);">
                        Revoke
                    </button>
                </div>

                <div
                    style="display: flex; align-items: flex-start; gap: 16px; padding: 16px; background: var(--bg); border-radius: 10px;">
                    <div
                        style="width: 48px; height: 48px; background: rgba(0, 0, 0, 0.05); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fab fa-firefox" style="color: var(--text-muted); font-size: 20px;"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">
                            Firefox on macOS
                        </div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                            Karachi, Pakistan • 192.168.1.3
                        </div>
                        <div style="font-size: var(--fs-micro); color: var(--text-muted); margin-top: 2px;">
                            Last active 3 days ago
                        </div>
                    </div>
                    <button class="settings-btn settings-btn-secondary"
                        style="padding: 8px 16px; font-size: var(--fs-subtle);">
                        Revoke
                    </button>
                </div>
            </div>
        </div>
        <div class="settings-card-footer">
            <span class="settings-card-meta">3 active sessions</span>
            <button class="settings-btn settings-btn-danger">
                <i class="fas fa-sign-out-alt"></i> Revoke All Other Sessions
            </button>
        </div>
    </div>

    <!-- Login History -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Login History</h3>
            <p class="settings-card-desc">Recent login activity on your account</p>
        </div>
        <div class="settings-card-body">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Date & Time</th>
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Device</th>
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Location</th>
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-body);">Jan 21, 2025
                                10:30 AM</td>
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-body);">Chrome on
                                Windows</td>
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-muted);">Sargodha,
                                PK</td>
                            <td style="padding: 12px 8px;">
                                <span
                                    style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 6px; font-size: var(--fs-micro); font-weight: var(--fw-semibold);">Success</span>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-body);">Jan 20, 2025
                                8:15 PM</td>
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-body);">Safari on
                                iPhone</td>
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-muted);">Lahore, PK
                            </td>
                            <td style="padding: 12px 8px;">
                                <span
                                    style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 6px; font-size: var(--fs-micro); font-weight: var(--fw-semibold);">Success</span>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-body);">Jan 18, 2025
                                2:45 PM</td>
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-body);">Chrome on
                                Unknown</td>
                            <td style="padding: 12px 8px; font-size: var(--fs-body); color: var(--text-muted);">Unknown
                                Location</td>
                            <td style="padding: 12px 8px;">
                                <span
                                    style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 6px; font-size: var(--fs-micro); font-weight: var(--fw-semibold);">Failed</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="settings-card-footer">
            <span class="settings-card-meta">Showing last 10 logins</span>
            <a href="#"
                style="color: var(--accent); font-size: var(--fs-body); font-weight: var(--fw-medium); text-decoration: none;">
                View full history →
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            // Password strength indicator
            const newPasswordInput = document.getElementById('newPassword');
            const strengthIndicator = document.getElementById('passwordStrength');

            newPasswordInput?.addEventListener('input', (e) => {
                const password = e.target.value;
                let strength = 0;
                let strengthText = '';
                let strengthColor = '';

                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[^a-zA-Z\d]/.test(password)) strength++;

                switch (strength) {
                    case 0:
                    case 1:
                        strengthText = 'Weak';
                        strengthColor = '#ef4444';
                        break;
                    case 2:
                        strengthText = 'Fair';
                        strengthColor = '#f59e0b';
                        break;
                    case 3:
                        strengthText = 'Good';
                        strengthColor = '#3b82f6';
                        break;
                    case 4:
                        strengthText = 'Strong';
                        strengthColor = '#10b981';
                        break;
                }

                strengthIndicator.innerHTML = password ?
                    `Password strength: <span style="color: ${strengthColor}; font-weight: 600;">${strengthText}</span>` :
                    '';
            });

            // Form submission handlers (mock)
            document.getElementById('profileBasicsForm')?.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Profile updated successfully! (This is a demo)');
            });

            document.getElementById('changePasswordForm')?.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Password changed successfully! (This is a demo)');
            });
        </script>
    @endpush
@endsection





