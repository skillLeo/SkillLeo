<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 440px;
            width: 100%;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .shield-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .shield-icon i {
            font-size: 36px;
            color: white;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #718096;
            line-height: 1.5;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #86efac;
            color: #065f46;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .otp-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 24px;
        }

        .otp-input {
            width: 56px;
            height: 64px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #f7fafc;
            color: #1a202c;
            transition: all 0.2s;
        }

        .otp-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .otp-separator {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            color: #cbd5e0;
            font-weight: 700;
            font-size: 20px;
        }

        .btn {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-box-content {
            display: flex;
            gap: 12px;
            font-size: 13px;
            color: #1e40af;
            line-height: 1.6;
        }

        .info-box i {
            font-size: 18px;
            color: #3b82f6;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .help-text {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #718096;
        }

        .help-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .help-text a:hover {
            text-decoration: underline;
        }

        .recovery-link {
            text-align: center;
            margin-top: 16px;
        }

        .recovery-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .recovery-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="shield-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>Two-Factor Authentication</h1>
            <p class="subtitle">Enter the 6-digit code from your authenticator app</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="info-box">
            <div class="info-box-content">
                <i class="fas fa-mobile-alt"></i>
                <div>
                    Open your authenticator app (Google Authenticator, Microsoft Authenticator, Authy, etc.) and enter the code shown for <strong>{{ config('app.name') }}</strong>.
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('auth.2fa.verify') }}" id="verify2faForm">
            @csrf

            <div class="form-group">
                <label>Verification Code</label>
                <div class="otp-container">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" required autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" required autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" required autocomplete="off">
                    <div class="otp-separator">-</div>
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" required autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" required autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" inputmode="numeric" pattern="[0-9]" required autocomplete="off">
                </div>
                <input type="hidden" name="code" id="fullCode">
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-check-circle"></i>
                Verify & Continue
            </button>
        </form>

        <div class="recovery-link">
            <a href="{{ route('auth.2fa.recovery') }}">
                <i class="fas fa-key"></i> Use recovery code instead
            </a>
        </div>

        <div class="help-text">
            Having trouble? <a href="{{ route('auth.login') }}">Back to login</a>
        </div>
    </div>

    <script>
        // Auto-focus first input
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('.otp-input');
            if (firstInput) firstInput.focus();
        });

        // OTP Input handling
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

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
                } else if (inputs[inputs.length - 1]) {
                    inputs[inputs.length - 1].focus();
                }
            });
        });

        // Form submission
        document.getElementById('verify2faForm').addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('.otp-input');
            const code = Array.from(inputs).map(input => input.value).join('');
            
            if (code.length !== 6) {
                e.preventDefault();
                alert('Please enter the complete 6-digit code');
                return false;
            }
            
            document.getElementById('fullCode').value = code;
        });
    </script>
</body>
</html>