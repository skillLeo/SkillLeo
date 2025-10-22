
@extends('tenant.settings.layout')

@section('settings-content')
    <div class="settings-page-header">
        <h2 class="settings-page-title">Security</h2>
        <p class="settings-page-desc">Manage two-factor authentication, trusted devices, and security preferences.</p>
    </div>

    <!-- Two-Factor Authentication -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Two-Factor Authentication (2FA)</h3>
            <p class="settings-card-desc">Add an extra layer of security to your account</p>
        </div>
        <div class="settings-card-body">
            @if (!$twoFactorEnabled)
                <!-- 2FA Not Enabled -->
                <div
                    style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                    <div style="display: flex; gap: 12px;">
                        <i class="fas fa-shield-alt" style="color: #f59e0b; font-size: 20px;"></i>
                        <div>
                            <div style="font-weight: var(--fw-semibold); color: #92400e; margin-bottom: 4px;">
                                Two-factor authentication is not enabled
                            </div>
                            <div style="font-size: var(--fs-subtle); color: #78350f;">
                                Protect your account by requiring a verification code in addition to your password.
                            </div>
                        </div>
                    </div>
                </div>

                <button class="settings-btn settings-btn-primary" onclick="open2FASetup()">
                    <i class="fas fa-plus-circle"></i> Enable Two-Factor Authentication
                </button>
            @else
                <!-- 2FA Enabled -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- TOTP (Authenticator) -->
                    <div style="padding: 20px; background: var(--bg); border-radius: 10px; border: 2px solid #d1fae5;">
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 40px; height: 40px; background: #d1fae5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-mobile-alt" style="color: #059669; font-size: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: var(--fw-semibold); color: var(--text-heading);">Authenticator
                                        App</div>
                                    <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Primary method
                                    </div>
                                </div>
                            </div>
                            <span
                                style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 6px; font-size: var(--fs-micro); font-weight: var(--fw-semibold);">
                                <i class="fas fa-check-circle"></i> Enabled
                            </span>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button class="settings-btn settings-btn-secondary"
                                style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                Regenerate Codes
                            </button>
                            <button class="settings-btn settings-btn-danger"
                                style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                Disable
                            </button>
                        </div>
                    </div>

                    <!-- SMS Backup -->
                    <div style="padding: 20px; background: var(--bg); border-radius: 10px;">
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 40px; height: 40px; background: rgba(0, 0, 0, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-sms" style="color: var(--text-muted); font-size: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: var(--fw-semibold); color: var(--text-heading);">SMS Backup
                                    </div>
                                    <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Backup verification
                                        method</div>
                                </div>
                            </div>
                            <span
                                style="background: var(--bg); color: var(--text-muted); padding: 4px 12px; border-radius: 6px; font-size: var(--fs-micro); font-weight: var(--fw-semibold); border: 1px solid var(--border);">
                                Not enabled
                            </span>
                        </div>
                        <button class="settings-btn settings-btn-primary"
                            style="padding: 8px 16px; font-size: var(--fs-subtle);">
                            <i class="fas fa-plus"></i> Set up SMS Backup
                        </button>
                    </div>

                    <!-- Recovery Codes -->
                    <div style="padding: 20px; background: var(--bg); border-radius: 10px;">
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 40px; height: 40px; background: rgba(0, 0, 0, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-key" style="color: var(--text-muted); font-size: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: var(--fw-semibold); color: var(--text-heading);">Recovery
                                        Codes</div>
                                    <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Generated on Jan
                                        15, 2025</div>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button class="settings-btn settings-btn-secondary"
                                style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button class="settings-btn settings-btn-secondary"
                                style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                <i class="fas fa-sync"></i> Regenerate
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="settings-card-footer">
            <a href="#"
                style="color: var(--accent); font-size: var(--fs-body); font-weight: var(--fw-medium); text-decoration: none;">
                View trusted devices ({{ $trustedDevicesCount }}) →
            </a>
        </div>
    </div>

    <!-- App Passwords / API Tokens -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">App Passwords & API Tokens</h3>
            <p class="settings-card-desc">Manage tokens for third-party applications</p>
        </div>
        <div class="settings-card-body">
            <div style="text-align: center; padding: 40px 20px;">
                <div
                    style="width: 64px; height: 64px; background: var(--bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-key" style="font-size: 28px; color: var(--text-muted);"></i>
                </div>
                <h4
                    style="font-size: 16px; font-weight: var(--fw-semibold); color: var(--text-heading); margin: 0 0 8px 0;">
                    No app passwords created
                </h4>
                <p style="font-size: var(--fs-subtle); color: var(--text-muted); margin: 0 0 20px 0;">
                    Create app-specific passwords for services that don't support 2FA
                </p>
                <button class="settings-btn settings-btn-primary">
                    <i class="fas fa-plus-circle"></i> Create New Token
                </button>
            </div>
        </div>
    </div>

    <!-- Email OTP -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Email OTP</h3>
            <p class="settings-card-desc">Receive one-time passwords via email for additional security</p>
        </div>
        <div class="settings-card-body">
            <div class="settings-toggle">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">Enable email OTP</div>
                    <div class="settings-toggle-desc">Send verification codes to your email for sensitive actions</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="settings-toggle"
                style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">OTP for login from new devices</div>
                    <div class="settings-toggle-desc">Require email verification when logging in from unrecognized devices
                    </div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- 2FA Setup Modal (Hidden by default) -->
    <div id="setup2FAModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); z-index: 9999; align-items: center; justify-content: center;">
        <div
            style="background: var(--card); border-radius: 16px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-2xl);">
            <!-- Modal Header -->
            <div
                style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <h3 style="margin: 0; font-size: 20px; font-weight: var(--fw-bold); color: var(--text-heading);">
                    Enable Two-Factor Authentication
                </h3>
                <button onclick="close2FASetup()"
                    style="background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 20px;">
                    <i class="fas fa-times"></i>
                </button>
            </div><!-- Step Indicator -->
            <div style="padding: 24px; border-bottom: 1px solid var(--border);">
                <div style="display: flex; gap: 8px;">
                    <div class="2fa-step active" data-step="1"
                        style="flex: 1; height: 4px; background: var(--accent); border-radius: 2px; transition: all 0.3s;">
                    </div>
                    <div class="2fa-step" data-step="2"
                        style="flex: 1; height: 4px; background: var(--border); border-radius: 2px; transition: all 0.3s;">
                    </div>
                    <div class="2fa-step" data-step="3"
                        style="flex: 1; height: 4px; background: var(--border); border-radius: 2px; transition: all 0.3s;">
                    </div>
                </div>
                <div style="margin-top: 12px; font-size: var(--fs-subtle); color: var(--text-muted); text-align: center;">
                    Step <span id="currentStep">1</span> of 3
                </div>
            </div>

            <!-- Step 1: Scan QR Code -->
            <div id="step1" class="2fa-step-content" style="padding: 24px;">
                <h4
                    style="margin: 0 0 16px 0; font-size: 18px; font-weight: var(--fw-semibold); color: var(--text-heading);">
                    Scan QR Code
                </h4>
                <p style="margin: 0 0 20px 0; font-size: var(--fs-body); color: var(--text-muted); line-height: 1.5;">
                    Use an authenticator app like Google Authenticator, Authy, or Microsoft Authenticator to scan this QR
                    code.
                </p>

                <!-- QR Code Placeholder -->
                <div
                    style="background: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 20px; border: 1px solid var(--border);">
                    <div
                        style="width: 200px; height: 200px; margin: 0 auto; background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0), linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0); background-size: 20px 20px; background-position: 0 0, 10px 10px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        <i class="fas fa-qrcode" style="font-size: 64px; color: #999;"></i>
                    </div>
                </div>

                <!-- Manual Entry Code -->
                <div style="background: var(--bg); padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                    <div
                        style="font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted); margin-bottom: 8px;">
                        Can't scan? Enter this code manually:
                    </div>
                    <div
                        style="font-family: 'Courier New', monospace; font-size: 14px; font-weight: var(--fw-bold); color: var(--text-heading); letter-spacing: 2px;">
                        ABCD EFGH IJKL MNOP
                    </div>
                </div>

                <button onclick="next2FAStep(2)" class="settings-btn settings-btn-primary" style="width: 100%;">
                    Next: Verify Code
                </button>
            </div>

            <!-- Step 2: Verify Code -->
            <div id="step2" class="2fa-step-content" style="display: none; padding: 24px;">
                <h4
                    style="margin: 0 0 16px 0; font-size: 18px; font-weight: var(--fw-semibold); color: var(--text-heading);">
                    Verify Code
                </h4>
                <p style="margin: 0 0 20px 0; font-size: var(--fs-body); color: var(--text-muted); line-height: 1.5;">
                    Enter the 6-digit verification code from your authenticator app.
                </p>

                <!-- OTP Input -->
                <div style="display: flex; gap: 8px; justify-content: center; margin-bottom: 20px;">
                    <input type="text" maxlength="1" class="otp-input"
                        style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px; background: var(--card);">
                    <input type="text" maxlength="1" class="otp-input"
                        style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px; background: var(--card);">
                    <input type="text" maxlength="1" class="otp-input"
                        style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px; background: var(--card);">
                    <div style="width: 16px;"></div>
                    <input type="text" maxlength="1" class="otp-input"
                        style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px; background: var(--card);">
                    <input type="text" maxlength="1" class="otp-input"
                        style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px; background: var(--card);">
                    <input type="text" maxlength="1" class="otp-input"
                        style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px; background: var(--card);">
                </div>

                <div style="display: flex; gap: 8px;">
                    <button onclick="next2FAStep(1)" class="settings-btn settings-btn-secondary" style="flex: 1;">
                        Back
                    </button>
                    <button onclick="next2FAStep(3)" class="settings-btn settings-btn-primary" style="flex: 1;">
                        Verify & Continue
                    </button>
                </div>
            </div>

            <!-- Step 3: Backup Codes -->
            <div id="step3" class="2fa-step-content" style="display: none; padding: 24px;">
                <h4
                    style="margin: 0 0 16px 0; font-size: 18px; font-weight: var(--fw-semibold); color: var(--text-heading);">
                    Save Recovery Codes
                </h4>
                <p style="margin: 0 0 20px 0; font-size: var(--fs-body); color: var(--text-muted); line-height: 1.5;">
                    Keep these recovery codes safe. You can use them to access your account if you lose your authenticator
                    device.
                </p>

                <!-- Recovery Codes -->
                <div
                    style="background: var(--bg); padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid var(--border);">
                    <div
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-family: 'Courier New', monospace; font-size: 14px; font-weight: var(--fw-semibold); color: var(--text-heading);">
                        <div>1. ABCD-EFGH-IJKL</div>
                        <div>2. MNOP-QRST-UVWX</div>
                        <div>3. YZAB-CDEF-GHIJ</div>
                        <div>4. KLMN-OPQR-STUV</div>
                        <div>5. WXYZ-1234-5678</div>
                        <div>6. 9012-3456-7890</div>
                        <div>7. ABCD-EFGH-IJKL</div>
                        <div>8. MNOP-QRST-UVWX</div>
                    </div>
                </div>

                <!-- Warning -->
                <div
                    style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                    <div style="display: flex; gap: 12px;">
                        <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 18px;"></i>
                        <div style="font-size: var(--fs-subtle); color: #78350f; line-height: 1.5;">
                            <strong>Important:</strong> Store these codes in a secure location. Each code can only be used
                            once.
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button class="settings-btn settings-btn-secondary" style="flex: 1;">
                        <i class="fas fa-download"></i> Download
                    </button>
                    <button class="settings-btn settings-btn-secondary" style="flex: 1;">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>

                <button onclick="complete2FASetup()" class="settings-btn settings-btn-primary"
                    style="width: 100%; margin-top: 16px;">
                    <i class="fas fa-check-circle"></i> Complete Setup
                </button>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // 2FA Setup Modal
            function open2FASetup() {
                document.getElementById('setup2FAModal').style.display = 'flex';
            }

            function close2FASetup() {
                document.getElementById('setup2FAModal').style.display = 'none';
                next2FAStep(1); // Reset to step 1
            }

            function next2FAStep(step) {
                // Hide all steps
                document.querySelectorAll('.2fa-step-content').forEach(el => el.style.display = 'none');

                // Show current step
                document.getElementById(`step${step}`).style.display = 'block';
                document.getElementById('currentStep').textContent = step;

                // Update progress bar
                document.querySelectorAll('.2fa-step').forEach((el, index) => {
                    if (index < step) {
                        el.style.background = 'var(--accent)';
                    } else {
                        el.style.background = 'var(--border)';
                    }
                });
            }

            function complete2FASetup() {
                alert('Two-Factor Authentication enabled successfully! (This is a demo)');
                close2FASetup();
                location.reload(); // Reload to show enabled state
            }

            // OTP Input Auto-advance
            document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Auto-paste support
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
                    pastedData.split('').forEach((char, i) => {
                        if (inputs[index + i]) {
                            inputs[index + i].value = char;
                        }
                    });
                    if (inputs[index + pastedData.length - 1]) {
                        inputs[index + pastedData.length - 1].focus();
                    }
                });
            });

            // Close modal on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    close2FASetup();
                }
            });

            // Close modal on overlay click
            document.getElementById('setup2FAModal')?.addEventListener('click', (e) => {
                if (e.target.id === 'setup2FAModal') {
                    close2FASetup();
                }
            });
        </script>
    @endpush
@endsection
