@extends('tenant.settings.layout')

@section('settings-content')
    <style>
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
        .settings-form-input:focus+.settings-form-icon {
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
            to {
                transform: rotate(360deg);
            }
        }

        /* Success Animation */
        @keyframes successPulse {

            0%,
            100% {
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


    <style>
        /* === Username input (accessibility + states) === */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .username-group {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 8px;
            background: var(--card, #fff);
            color: var(--text-body, #111827);
            font-size: 14px;
            transition: box-shadow .2s, border-color .2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent, #1351d8);
            box-shadow: 0 0 0 4px rgba(19, 81, 216, .12);
        }

        .form-input.is-valid {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, .12);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, .12);
        }

        /* live status line */
        .username-status {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 22px;
            margin-top: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted, #6b7280);
        }

        .username-status[data-type="loading"] {
            color: var(--text-muted, #6b7280);
        }

        .username-status[data-type="ok"] {
            color: #065f46;
        }

        .username-status[data-type="error"] {
            color: #991b1b;
        }

        /* tiny spinner when loading */
        .username-status[data-type="loading"]::before {
            content: "";
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--accent, #1351d8);
            border-right-color: transparent;
            animation: usernameSpin .6s linear infinite;
        }

        @keyframes usernameSpin {
            to {
                transform: rotate(360deg);
            }
        }

        /* profile preview area */
        .username-preview {
            display: none;
            margin-top: 8px;
            padding: 10px 12px;
            background: var(--bg, #f9fafb);
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 8px;
            font-size: 13px;
            color: var(--text-muted, #6b7280);
        }

        .username-preview.show {
            display: block;
        }

        .username-url {
            color: var(--accent, #1351d8);
            text-decoration: none;
        }

        .username-url:hover {
            text-decoration: underline;
        }

        /* regenerate / suggestion button */
        .regenerate-btn {
            margin-left: 12px;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid var(--border, #e5e7eb);
            background: var(--card, #fff);
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
        }

        .regenerate-btn:hover {
            border-color: var(--accent, #1351d8);
            color: var(--accent, #1351d8);
        }

        .regenerate-btn.is-suggestion {
            background: var(--accent, #1351d8);
            border-color: var(--accent, #1351d8);
            color: #fff;
        }

        /* screen reader only helper */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
    <div class="settings-page-header">
        <h2 class="settings-page-title">Account</h2>
        <p class="settings-page-desc">Manage your basic account information, email, and password.</p>
    </div>

    <!-- Profile Basics (POST, no AJAX) -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Profile Basics</h3>
            <p class="settings-card-desc">Update your username and contact information</p>
        </div>

        <div class="settings-card-body">
            @if (session('success'))
                <div class="flash-message success" style="margin-bottom:16px;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="flash-message error"
                    style="margin-bottom:16px; background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%); border:1px solid #f59e0b; color:#92400e;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            <form id="profileBasicsForm" action="{{ route('tenant.settings.account.profile.update', $username) }}"
                method="POST" novalidate>
                @csrf

         {{-- Username --}}
@props([
    'label' => 'Choose your username',
    'name' => 'username',
    'id' => 'username',
    'placeholder' => 'john-smith-2024',
    'value' => '',
    'showPreview' => true,
  ])
  
  @php
      $appUrl   = rtrim(config('app.url'), '/');
      $host     = parse_url($appUrl, PHP_URL_HOST) ?: 'promatch.com';
      // <- this is the key line: prefer old() (after validation) otherwise the user's current username
      $current  = old($name, $value ?: ($user->username ?? ''));
  @endphp
  
  <div class="form-group">
    @if ($label)
      <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    @endif
  
    <div class="username-group">
      <input
        type="text"
        class="form-input"
        id="{{ $id }}"                {{-- JS expects id="username" --}}
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ $current }}"
        autocomplete="username"
        aria-describedby="usernameHelp usernameStatus"
        maxlength="50"
        {{ $attributes }}
      />
      <div class="username-status" id="usernameStatus" aria-live="polite"></div>
    </div>
  
    {{-- show the currently saved (old) username as helper text --}}
    <span class="settings-form-help" style="display:block; margin-top:8px;">
      Current username: <code>{{ $user->username }}</code>
    </span>
  
    @if ($showPreview)
      <div class="username-preview show" id="usernamePreview" aria-live="polite">
        Your profile:
        <a
          id="profilePreviewLink"
          class="username-url"
          target="_blank"
          rel="noopener"
          data-base="{{ $appUrl }}/"
          href="{{ $appUrl }}/{{ $current }}"
        >
          {{ $host }}/<span id="previewUsername">{{ $current }}</span>
        </a>
  
        <button type="button" class="regenerate-btn" id="regenBtn">Regenerate</button>
      </div>
    @endif
  
    <p class="sr-only" id="usernameHelp">
      Only letters, numbers, underscores, and hyphens. Minimum 3 characters.
    </p>
  </div>
  

                {{-- Primary Email --}}
                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Primary Email <span class="required">*</span>
                    </label>
                    <input type="email" name="email" class="settings-form-input @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" placeholder="you@example.com" required>
                    <span class="settings-form-help">
                        @if ($user->email_verified_at)
                            <i class="fas fa-check-circle" style="color:#10b981;"></i> Verified
                        @else
                            <i class="fas fa-exclamation-circle" style="color:#f59e0b;"></i> Not verified
                            <form method="POST"
                                action="{{ route('tenant.settings.account.email.verify.send', $username) }}"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="verify-email-link"
                                    style="border:none;background:none;cursor:pointer;">
                                    Verify now
                                </button>
                            </form>
                        @endif
                    </span>
                    @error('email')
                        <span class="form-validation-message error"><i class="fas fa-times-circle"></i>
                            {{ $message }}</span>
                    @enderror
                </div>

                {{-- Secondary Email (optional) --}}
                <div class="settings-form-group">
                    <label class="settings-form-label">Secondary Email</label>
                    <input type="email" name="secondary_email"
                        class="settings-form-input @error('secondary_email') is-invalid @enderror"
                        value="{{ old('secondary_email', $user->profile->meta['secondary_email'] ?? '') }}"
                        placeholder="backup@example.com">
                    <span class="settings-form-help">Used for recovery and important alerts.</span>
                    @error('secondary_email')
                        <span class="form-validation-message error"><i class="fas fa-times-circle"></i>
                            {{ $message }}</span>
                    @enderror
                </div>

                {{-- Phone (optional) --}}
                <div class="settings-form-group">
                    <label class="settings-form-label">Phone</label>
                    <input type="tel" name="phone" class="settings-form-input @error('phone') is-invalid @enderror"
                        value="{{ old('phone', $user->profile->phone ?? '') }}" placeholder="+1 555 123 4567">
                    @error('phone')
                        <span class="form-validation-message error"><i class="fas fa-times-circle"></i>
                            {{ $message }}</span>
                    @enderror
                </div>

                {{-- Secondary Phone (optional) --}}
                <div class="settings-form-group">
                    <label class="settings-form-label">Secondary Phone</label>
                    <input type="tel" name="secondary_phone"
                        class="settings-form-input @error('secondary_phone') is-invalid @enderror"
                        value="{{ old('secondary_phone', $user->profile->meta['secondary_phone'] ?? '') }}"
                        placeholder="+1 555 987 6543">
                    @error('secondary_phone')
                        <span class="form-validation-message error"><i class="fas fa-times-circle"></i>
                            {{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-card-footer">

                    <div class="settings-card-actions">
                        <button type="reset" class="settings-btn settings-btn-secondary">Cancel</button>
                        <button type="submit" class="settings-btn settings-btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password (non-AJAX) -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Change Password</h3>
            <p class="settings-card-desc">
                @if ($user->password)
                    Update your password. We'll email you a confirmation link to finalize the change.
                @else
                    You signed in with Google/social. Set a new password below to enable email login.
                @endif
            </p>
        </div>

        <div class="settings-card-body">
            @if (session('status'))
                <div
                    style="margin-bottom:12px; padding:12px; border:1px solid #d1fae5; background:#ecfdf5; color:#065f46; border-radius:8px;">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('tenant.settings.password.update', $username) }}" method="POST"
                id="changePasswordForm" novalidate>
                @csrf

                {{-- carry the page we want to come back to after confirming --}}
                <input type="hidden" name="intended" value="{{ url()->full() }}">

                @if ($user->password)
                    <div class="settings-form-group">
                        <label class="settings-form-label">Current Password <span class="required">*</span></label>
                        <input type="password" name="current_password" class="settings-form-input"
                            autocomplete="current-password">
                        @error('current_password')
                            <span class="form-validation-message error">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="settings-form-group"
                        style="background:var(--bg);padding:12px;border-radius:8px;border:1px solid var(--border);">
                        <i class="fas fa-info-circle"></i>
                        You don’t have a password yet. Create one with the fields below.
                    </div>
                @endif

                <div class="settings-form-group">
                    <label class="settings-form-label">New Password <span class="required">*</span></label>
                    <input type="password" name="new_password" class="settings-form-input" id="newPassword"
                        autocomplete="new-password">
                    <span class="settings-form-help"><span id="passwordStrength"></span></span>
                    @error('new_password')
                        <span class="form-validation-message error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">Confirm New Password <span class="required">*</span></label>
                    <input type="password" name="new_password_confirmation" class="settings-form-input"
                        autocomplete="new-password">
                    @error('new_password_confirmation')
                        <span class="form-validation-message error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="background: var(--bg); padding: 16px; border-radius: 8px; margin-top: 16px;">
                    <div
                        style="font-size: var(--fs-subtle); font-weight: var(--fw-semibold); margin-bottom: 8px; color: var(--text-heading);">
                        Password Requirements:
                    </div>
                    <ul style="margin:0; padding-left:20px; font-size:var(--fs-subtle); color:var(--text-muted);">
                        <li>At least 8 characters long</li>
                        <li>Contains uppercase and lowercase letters</li>
                        <li>Contains at least one number</li>
                        <li>Contains at least one special character</li>
                    </ul>
                </div>

                <div class="settings-card-footer" style="padding:0; border:none; margin-top:16px;">
                    <div class="settings-card-actions">
                        <button type="reset" class="settings-btn settings-btn-secondary">Cancel</button>
                        <button type="submit" class="settings-btn settings-btn-primary">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>



    </div>

    {{-- Optional: keep your existing strength meter JS if you want --}}
    <script>
        document.getElementById('newPassword')?.addEventListener('input', function(e) {
            const p = e.target.value;
            let s = 0;
            if (p.length >= 8) s++;
            if (/[a-z]/.test(p) && /[A-Z]/.test(p)) s++;
            if (/\d/.test(p)) s++;
            if (/[^A-Za-z0-9]/.test(p)) s++;
            const t = ['', 'Weak', 'Fair', 'Good', 'Strong'][s];
            const c = ['', '#ef4444', '#f59e0b', '#3b82f6', '#10b981'][s];
            document.getElementById('passwordStrength').innerHTML = p ?
                `Password strength: <span style="color:${c};font-weight:600;">${t}</span>` : '';
        });
    </script>





    @push('scripts')
        <script>
            (function() {
                'use strict';

                // CSRF Token setup for AJAX
                const csrfToken = '{{ csrf_token() }}';
                const username = '{{ $username }}';

                // Helper: Show toast notification
                function showToast(message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.className = `toast toast-${type}`;
                    toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
            `;
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.style.animation = 'slideOut 0.3s ease-in';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                // Helper: Show field error
                function showFieldError(fieldName, message) {
                    const errorEl = document.getElementById(`${fieldName}-error`);
                    const inputEl = document.querySelector(`[name="${fieldName}"]`);

                    if (errorEl) {
                        errorEl.textContent = message;
                        errorEl.style.display = 'flex';
                    }
                    if (inputEl) {
                        inputEl.classList.add('is-invalid');
                    }
                }

                // Helper: Clear all errors
                function clearErrors(formId) {
                    document.querySelectorAll(`#${formId} .form-validation-message`).forEach(el => {
                        el.style.display = 'none';
                    });
                    document.querySelectorAll(`#${formId} .is-invalid`).forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                }

                // Password strength indicator
                const newPasswordInput = document.getElementById('newPassword');
                const strengthIndicator = document.getElementById('passwordStrength');

                if (newPasswordInput) {
                    newPasswordInput.addEventListener('input', (e) => {
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
                }

                // Update Profile
                document.getElementById('saveProfileBtn')?.addEventListener('click', async function() {
                    clearErrors('profileBasicsForm');
                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                    const formData = new FormData(document.getElementById('profileBasicsForm'));

                    try {
                        const response = await fetch(`/${username}/settings/account/profile`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const data = await response.json();

                        if (data.success) {
                            showToast(data.message, 'success');
                            if (data.email_changed) {
                                showToast('Email changed. Please verify your new email.', 'warning');
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 2000);
                            } else {
                                setTimeout(() => window.location.reload(), 1500);
                            }
                        } else if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                showFieldError(field, data.errors[field][0]);
                            });
                            showToast('Please fix the errors and try again.', 'error');
                        }
                    } catch (error) {
                        showToast('Network error. Please try again.', 'error');
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                });

                // Update Password
                document.getElementById('updatePasswordBtn')?.addEventListener('click', async function() {
                    clearErrors('changePasswordForm');
                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

                    const formData = new FormData(document.getElementById('changePasswordForm'));

                    try {
                        const response = await fetch(`/${username}/settings/account/password`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const data = await response.json();

                        if (data.success) {
                            showToast(data.message, 'success');
                            document.getElementById('changePasswordForm').reset();
                            strengthIndicator.innerHTML = '';
                        } else if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                showFieldError(field, data.errors[field][0]);
                            });
                            showToast('Please fix the errors and try again.', 'error');
                        }
                    } catch (error) {
                        showToast('Network error. Please try again.', 'error');
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                });

                // Send Email Verification
                document.getElementById('sendVerificationBtn')?.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const btn = this;
                    btn.style.pointerEvents = 'none';
                    btn.textContent = 'Sending...';

                    try {
                        const response = await fetch(`/${username}/settings/account/email/verification/send`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();
                        showToast(data.message, data.success ? 'success' : 'error');

                        if (data.success) {
                            btn.textContent = 'Email sent!';
                            setTimeout(() => {
                                btn.textContent = 'Resend verification';
                                btn.style.pointerEvents = 'auto';
                            }, 5000);
                        }
                    } catch (error) {
                        showToast('Network error. Please try again.', 'error');
                        btn.style.pointerEvents = 'auto';
                        btn.textContent = 'Verify now';
                    }
                });

                // Trust Device
                document.querySelectorAll('.trust-device-btn').forEach(btn => {
                    btn.addEventListener('click', async function() {
                        const deviceId = this.dataset.deviceId;
                        const originalText = this.innerHTML;
                        this.disabled = true;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                        try {
                            const response = await fetch(
                                `/${username}/settings/account/devices/${deviceId}/trust`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json',
                                    },
                                });

                            const data = await response.json();

                            if (data.success) {
                                showToast(data.message, 'success');
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                showToast(data.message, 'error');
                                this.disabled = false;
                                this.innerHTML = originalText;
                            }
                        } catch (error) {
                            showToast('Network error. Please try again.', 'error');
                            this.disabled = false;
                            this.innerHTML = originalText;
                        }
                    });
                });

                // Revoke Single Device
                document.querySelectorAll('.revoke-device-btn').forEach(btn => {
                    btn.addEventListener('click', async function() {
                        if (!confirm(
                                'Are you sure you want to revoke this device? You will need to sign in again on that device.'
                                )) {
                            return;
                        }

                        const deviceId = this.dataset.deviceId;
                        const deviceCard = this.closest('.session-card');
                        const originalText = this.innerHTML;
                        this.disabled = true;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                        try {
                            const response = await fetch(
                                `/${username}/settings/account/devices/${deviceId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json',
                                    },
                                });

                            const data = await response.json();

                            if (data.success) {
                                showToast(data.message, 'success');
                                deviceCard.style.animation = 'fadeOut 0.3s ease-out';
                                setTimeout(() => {
                                    deviceCard.remove();
                                    // Check if no devices left
                                    if (document.querySelectorAll('.session-card').length ===
                                        0) {
                                        document.getElementById('devicesContainer').innerHTML = `
                                    <div style="text-align: center; padding: 32px; color: var(--text-muted);">
                                        <i class="fas fa-desktop" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                                        <p>No active devices found</p>
                                    </div>
                                `;
                                    }
                                }, 300);
                            } else {
                                showToast(data.message, 'error');
                                this.disabled = false;
                                this.innerHTML = originalText;
                            }
                        } catch (error) {
                            showToast('Network error. Please try again.', 'error');
                            this.disabled = false;
                            this.innerHTML = originalText;
                        }
                    });
                });

                // Revoke All Other Sessions
                document.getElementById('revokeAllBtn')?.addEventListener('click', async function() {
                    if (!confirm(
                            'Are you sure you want to revoke all other sessions? You will need to sign in again on all other devices.'
                            )) {
                        return;
                    }

                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Revoking...';

                    try {
                        const response = await fetch(`/${username}/settings/account/devices/revoke-others`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showToast(data.message, 'error');
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    } catch (error) {
                        showToast('Network error. Please try again.', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                });

                // Add CSS animations
                const style = document.createElement('style');
                style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: scale(1);
                }
                to {
                    opacity: 0;
                    transform: scale(0.95);
                }
            }
        `;
                document.head.appendChild(style);

            })();
        </script>
        <script>
            document.getElementById('updatePasswordBtn')?.addEventListener('click', async function() {
                const formId = 'changePasswordForm';
                const btn = this;
                const originalText = btn.innerHTML;

                // clear previous errors
                document.querySelectorAll(`#${formId} .form-validation-message`).forEach(el => el.style.display =
                    'none');
                document.querySelectorAll(`#${formId} .is-invalid`).forEach(el => el.classList.remove(
                'is-invalid'));

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

                try {
                    const response = await fetch(`/{{ $username }}/settings/account/password`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: new FormData(document.getElementById(formId))
                    });

                    const data = await response.json();

                    if (data.success) {
                        if (data.flow === 'confirm_link_sent') {
                            showToast(
                                'We sent a confirmation link to your email. Please confirm to finalize the change.',
                                'success');
                        } else {
                            showToast(data.message || 'Password updated!', 'success');
                        }
                        document.getElementById(formId).reset();
                        const strengthIndicator = document.getElementById('passwordStrength');
                        if (strengthIndicator) strengthIndicator.innerHTML = '';
                    } else if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById(`${field}-error`);
                            const inputEl = document.querySelector(`[name="${field}"]`);
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.style.display = 'flex';
                            }
                            if (inputEl) {
                                inputEl.classList.add('is-invalid');
                            }
                        });
                        showToast('Please fix the errors and try again.', 'error');
                    } else {
                        showToast(data.message || 'Something went wrong.', 'error');
                    }
                } catch (e) {
                    showToast('Network error. Please try again.', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Elements
                const form = document.getElementById('personalForm');
                const firstName = document.getElementById('firstName');
                const lastName = document.getElementById('lastName');
                const username = document.getElementById('username');
                const statusEl = document.getElementById('usernameStatus');
                const previewWrap = document.getElementById('usernamePreview');
                const previewUsername = document.getElementById('previewUsername');
                const previewLink = document.getElementById('profilePreviewLink');
                const successEl = document.getElementById('usernameSuccess'); // optional
                const regenBtn = document.getElementById('regenBtn');
                const continueBtn = (document.getElementById('continueBtn') || (form ? form.querySelector(
                    'button[type="submit"]') : null));
                const btnText = document.getElementById('btnText'); // optional

                // State
                let userManuallyEdited = false;
                let debounceTimer = null;
                let isChecking = false;
                let lastCheckedUsername = '';
                let requestCounter = 0;

                // Helpers
                const slugify = (str) => {
                    return (str || '')
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .toLowerCase()
                        .replace(/[^a-z0-9_-]+/g, '-') // only a-z 0-9 _ -
                        .replace(/^[-_]+|[-_]+$/g, '') // trim - _
                        .replace(/--+/g, '-') // collapse --
                        .slice(0, 50);
                };

                const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

                const generateBaseUsername = () => {
                    const f = slugify(firstName?.value?.trim() || '');
                    const l = slugify(lastName?.value?.trim() || '');
                    if (f && l) return `${f}-${l}`;
                    if (f) return f;
                    if (l) return l;
                    return '';
                };

                const generateCandidates = (base) => {
                    if (!base) return [];
                    const year = new Date().getFullYear();

                    const f = slugify(firstName?.value?.trim() || '');
                    const l = slugify(lastName?.value?.trim() || '');

                    const arr = [
                        base,
                        `${base}-${year}`,
                        `${base}-${randomInt(10, 99)}`,
                        `${base}-${randomInt(100, 999)}`,
                        `${base}-${randomInt(1000, 9999)}`
                    ];
                    if (f && l && f.length > 0) arr.splice(2, 0, `${f[0]}-${l}`);

                    // unique + min length 3 + slug again to be sure
                    return [...new Set(arr.map(slugify).filter(v => v.length >= 3))];
                };

                const setUIState = (text, type) => {
                    if (statusEl) {
                        statusEl.textContent = text || '';
                        statusEl.dataset.type = type || '';
                    }
                    if (username) {
                        username.classList.toggle('is-valid', type === 'ok');
                        username.classList.toggle('is-invalid', type === 'error');
                    }
                    if (successEl) successEl.classList.toggle('show', type === 'ok');
                    if (previewWrap) previewWrap.classList.toggle('show', type === 'ok');

                    if (continueBtn) {
                        // only enable submit when username is valid/available
                        continueBtn.disabled = (type !== 'ok');
                    }

                    // Reset regenerate button when not in an error state
                    if (regenBtn && type !== 'error') {
                        regenBtn.textContent = 'Regenerate';
                        regenBtn.classList.remove('is-suggestion');
                        regenBtn.onclick = handleRegenerate;
                    }
                };

                const updatePreview = (uname) => {
                    if (!previewUsername || !previewLink) return;
                    previewUsername.textContent = uname || '';
                    const base = previewLink.getAttribute('data-base') || '/';
                    previewLink.href = base + (uname || '');
                };

                const showSuggestionButton = (suggestion) => {
                    if (!regenBtn || !suggestion) return;
                    regenBtn.textContent = `Use "${suggestion}"`;
                    regenBtn.classList.add('is-suggestion');
                    regenBtn.onclick = (e) => {
                        e.preventDefault();
                        userManuallyEdited = true;
                        username.value = suggestion;
                        checkUsernameWithDebounce();
                    };
                };

                const checkUsernameAvailability = async (uname) => {
                    if (!uname || uname.length < 3) {
                        setUIState('Username must be at least 3 characters', 'error');
                        return {
                            status: 'invalid'
                        };
                    }

                    // prevent redundant checks
                    if (uname === lastCheckedUsername && !isChecking) {
                        return {
                            status: statusEl?.dataset?.type === 'ok' ? 'available' : 'invalid'
                        };
                    }

                    lastCheckedUsername = uname;
                    isChecking = true;
                    const currentRequest = ++requestCounter;
                    setUIState('Checking availability…', 'loading');

                    try {
                        const url = `{{ route('api.username.check') }}?username=${encodeURIComponent(uname)}`;
                        const res = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });

                        // Ignore stale responses
                        if (currentRequest !== requestCounter) return {
                            status: 'stale'
                        };

                        const data = await res.json().catch(() => ({}));

                        if (res.ok && (data.status === 'available' || data.status === 'self')) {
                            setUIState(data.status === 'self' ? 'This is already your username ✓' :
                                'Username is available ✓', 'ok');
                            updatePreview(uname);
                            return {
                                status: 'available',
                                username: uname
                            };
                        }

                        if (res.status === 409 && data.status === 'taken') {
                            setUIState('This username is taken', 'error');
                            if (data.suggestion) showSuggestionButton(data.suggestion);
                            return {
                                status: 'taken',
                                suggestion: data.suggestion || null
                            };
                        }

                        if (res.status === 422 && data.error) {
                            setUIState(data.error, 'error');
                            return {
                                status: 'invalid'
                            };
                        }

                        setUIState(data.error || 'Invalid username format', 'error');
                        return {
                            status: 'invalid'
                        };

                    } catch (err) {
                        if (currentRequest !== requestCounter) return {
                            status: 'stale'
                        };
                        setUIState('Connection error. Please try again.', 'error');
                        return {
                            status: 'error'
                        };
                    } finally {
                        isChecking = false;
                    }
                };

                const findAvailableUsername = async (base) => {
                    const candidates = generateCandidates(base);
                    for (const cand of candidates) {
                        const result = await checkUsernameAvailability(cand);
                        if (result.status === 'available') return cand;
                    }
                    return null;
                };

                const handleRegenerate = async (e) => {
                    e?.preventDefault?.();
                    const base = generateBaseUsername() || slugify(username?.value || '') || 'user';
                    const randomizedBase = `${base}-${randomInt(10, 99)}`;
                    const available = await findAvailableUsername(randomizedBase);
                    if (available) {
                        userManuallyEdited = true;
                        username.value = available;
                        await checkUsernameAvailability(available);
                    } else {
                        setUIState('No available usernames found. Try different names.', 'error');
                    }
                };

                const checkUsernameWithDebounce = () => {
                    clearTimeout(debounceTimer);
                    if (!username) return;

                    // live slugging
                    const slugged = slugify(username.value);
                    username.value = slugged;

                    if (!slugged) {
                        setUIState('', '');
                        updatePreview('');
                        return;
                    }
                    if (slugged.length < 3) {
                        setUIState('Username must be at least 3 characters', 'error');
                        return;
                    }

                    debounceTimer = setTimeout(() => {
                        checkUsernameAvailability(slugged);
                    }, 400);
                };

                const autoSuggestUsername = async () => {
                    if (userManuallyEdited || !username) return;
                    const base = generateBaseUsername();
                    if (!base) {
                        username.value = '';
                        setUIState('', '');
                        updatePreview('');
                        return;
                    }

                    username.value = base;
                    const result = await checkUsernameAvailability(base);
                    if (result.status === 'taken') {
                        const available = await findAvailableUsername(base);
                        if (available) {
                            username.value = available;
                            await checkUsernameAvailability(available);
                        }
                    }
                };

                // Events
                firstName?.addEventListener('input', autoSuggestUsername);
                lastName?.addEventListener('input', autoSuggestUsername);
                username?.addEventListener('input', () => {
                    userManuallyEdited = true;
                    checkUsernameWithDebounce();
                });
                username?.addEventListener('blur', () => {
                    if (username.value) checkUsernameWithDebounce();
                });
                regenBtn?.addEventListener('click', handleRegenerate);

                form?.addEventListener('submit', (e) => {
                    if (continueBtn && continueBtn.disabled) {
                        e.preventDefault();
                        setUIState('Please wait for username validation', 'error');
                        return;
                    }
                    if (btnText) btnText.innerHTML = '<span class="loading-spinner"></span>';
                    if (continueBtn) continueBtn.disabled = true;
                });

                // Init: check existing value or auto-suggest from names
                if (username?.value?.trim()) {
                    userManuallyEdited = true;
                    checkUsernameWithDebounce();
                } else {
                    autoSuggestUsername();
                }
            });
        </script>
    @endpush
@endsection
