{{-- resources/views/emails/auth/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px 32px;
            text-align: center;
        }
        .email-header .icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .email-header p {
            font-size: 16px;
            opacity: 0.95;
        }
        .email-body {
            padding: 40px 32px;
        }
        .greeting {
            font-size: 18px;
            color: #111827;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .content p {
            color: #374151;
            font-size: 15px;
            margin-bottom: 16px;
            line-height: 1.7;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin: 32px 0;
        }
        .feature-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .feature-card .icon {
            font-size: 32px;
            margin-bottom: 12px;
        }
        .feature-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }
        .feature-card p {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }
        .btn {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 24px 0;
        }
        .info-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #bfdbfe;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .info-box strong {
            display: block;
            color: #1e40af;
            font-size: 15px;
            margin-bottom: 8px;
        }
        .info-box p {
            color: #1e3a8a;
            font-size: 14px;
            margin: 0;
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
            .feature-grid {
                grid-template-columns: 1fr;
            }
            .email-body {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">🎉</div>
            <h1>Welcome to {{ config('app.name') }}!</h1>
            <p>Your account has been created successfully</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Hi {{ $user->name ?? $user->username }}! 👋
            </div>

            <div class="content">
                <p>
                    Thank you for joining <strong>{{ config('app.name') }}</strong>! We're excited to have you on board.
                </p>
                <p>
                    Your account is now active and ready to use. Here's what you can do next:
                </p>
            </div>

            <!-- Feature Grid -->
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="icon">👤</div>
                    <h3>Complete Your Profile</h3>
                    <p>Add your details and customize your account</p>
                </div>
                <div class="feature-card">
                    <div class="icon">🔒</div>
                    <h3>Enable 2FA</h3>
                    <p>Secure your account with two-factor authentication</p>
                </div>
                <div class="feature-card">
                    <div class="icon">⚙️</div>
                    <h3>Explore Settings</h3>
                    <p>Personalize your experience</p>
                </div>
                <div class="feature-card">
                    <div class="icon">🚀</div>
                    <h3>Get Started</h3>
                    <p>Discover all features available to you</p>
                </div>
            </div>

            <center>
                <a href="{{ config('app.url') . '/' . $user->username }}" class="btn">
                    🎯 Go to Dashboard
                </a>
            </center>

            <!-- Registration Info -->
            <div class="info-box">
                <strong>📋 Registration Details</strong>
                <p>
                    <strong>Device:</strong> {{ $device }}<br>
                    <strong>IP Address:</strong> {{ $ip }}<br>
                    <strong>Time:</strong> {{ $timestamp->format('M d, Y - h:i A') }}
                </p>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 24px;">
                Need help getting started? Check out our 
                <a href="{{ url('/help') }}" style="color: #3b82f6; text-decoration: none;">Help Center</a> 
                or contact our 
                <a href="{{ url('/support') }}" style="color: #3b82f6; text-decoration: none;">Support Team</a>.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>
                <a href="{{ config('app.url') }}">Visit Website</a> • 
                <a href="{{ url('/' . $user->username . '/settings') }}">Account Settings</a> • 
                <a href="{{ url('/support') }}">Get Help</a>
            </p>
            <p style="margin-top: 16px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>