{{-- resources/views/emails/auth/login-notification.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Sign-In Detected</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 40px 32px;
            text-align: center;
        }
        .email-header .icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
        }
        .email-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .email-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 32px;
        }
        .greeting {
            font-size: 16px;
            color: #111827;
            margin-bottom: 20px;
        }
        .info-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }
        .info-value {
            font-weight: 600;
            color: #111827;
            font-size: 14px;
            text-align: right;
        }
        .alert-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .alert-box strong {
            display: block;
            color: #92400e;
            font-size: 15px;
            margin-bottom: 8px;
        }
        .alert-box p {
            color: #78350f;
            font-size: 14px;
            margin: 0;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            margin: 16px 0;
        }
        .email-footer {
            background: #f9fafb;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #6b7280;
            font-size: 13px;
            margin: 8px 0;
        }
        .email-footer a {
            color: #3b82f6;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .email-body {
                padding: 24px;
            }
            .info-row {
                flex-direction: column;
                gap: 4px;
            }
            .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">🔐</div>
            <h1>New Sign-In Detected</h1>
            <p>We noticed a new sign-in to your account</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Hi <strong>{{ $user->name ?? $user->username }}</strong>,
            </div>

            <p style="color: #374151; font-size: 14px; margin-bottom: 20px;">
                A new sign-in to your <strong>{{ config('app.name') }}</strong> account was detected. If this was you, you can safely ignore this email.
            </p>

            <!-- Login Details -->
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">🖥️ Device</span>
                    <span class="info-value">{{ $device }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🌐 Browser</span>
                    <span class="info-value">{{ $browser }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">💻 Platform</span>
                    <span class="info-value">{{ $platform }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📍 Location</span>
                    <span class="info-value">{{ $location }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🌍 IP Address</span>
                    <span class="info-value">{{ $ip }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🕒 Time</span>
                    <span class="info-value">{{ $timestamp->format('M d, Y - h:i A') }}</span>
                </div>
            </div>

            <!-- Security Alert -->
            <div class="alert-box">
                <strong>⚠️ Wasn't you?</strong>
                <p>If you didn't sign in, your account may be compromised. Secure your account immediately by changing your password.</p>
            </div>

            <center>
                <a href="{{ url('/' . $user->username . '/settings/account') }}" class="btn">
                    🔒 Secure My Account
                </a>
            </center>

            <p style="color: #6b7280; font-size: 13px; margin-top: 24px;">
                You're receiving this email because login notifications are enabled for your account. 
                You can disable this in your 
                <a href="{{ url('/' . $user->username . '/settings/security') }}" style="color: #3b82f6; text-decoration: none;">security settings</a>.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>
                <a href="{{ config('app.url') }}">Visit Website</a> • 
                <a href="{{ url('/' . $user->username . '/settings/security') }}">Security Settings</a> • 
                <a href="{{ url('/support') }}">Get Help</a>
            </p>
            <p style="margin-top: 16px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>