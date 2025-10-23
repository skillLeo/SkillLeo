<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recovery Code - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
        .container{background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.3);max-width:440px;width:100%;padding:40px}
        .header{text-align:center;margin-bottom:32px}
        .shield-icon{width:80px;height:80px;background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px}
        .shield-icon i{font-size:36px;color:#fff}
        h1{font-size:24px;font-weight:700;color:#1a202c;margin-bottom:8px}
        .subtitle{font-size:14px;color:#718096;line-height:1.5}
        .alert{padding:12px 16px;border-radius:8px;margin-bottom:24px;display:flex;align-items:center;gap:10px;font-size:14px}
        .alert-error{background:#fee;border:1px solid #fcc;color:#c00}
        .alert-warning{background:#fffbeb;border:1px solid #fcd34d;color:#78350f}
        label{display:block;font-weight:600;color:#2d3748;margin-bottom:10px;font-size:14px}
        .form-control{width:100%;padding:14px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:15px;font-family:'Courier New',monospace;background:#f7fafc;color:#1a202c;transition:all .2s;letter-spacing:2px;text-transform:uppercase}
        .form-control:focus{outline:none;border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.1);background:#fff}
        .btn{width:100%;padding:14px 24px;background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%);color:#fff;border:none;border-radius:10px;font-size:16px;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:12px}
        .btn:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(245,158,11,.3)}
        .help-text{text-align:center;margin-top:20px;font-size:13px;color:#718096}
        .help-text a{color:#667eea;text-decoration:none;font-weight:600}
        .help-text a:hover{text-decoration:underline}
        .notice{background:#fffbeb;border:1px solid #fcd34d;border-radius:10px;padding:16px;margin-bottom:24px;color:#78350f;font-size:13px}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="shield-icon"><i class="fas fa-key"></i></div>
            <h1>Use Recovery Code</h1>
            <p class="subtitle">Enter one of the unused recovery codes you saved.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="notice">
            Each recovery code can be used once. After logging in, generate new codes in your Security settings.
        </div>

        <form method="POST" action="{{ route('auth.2fa.recovery.verify') }}">
            @csrf
            <label for="recovery_code">Recovery code</label>
            <input id="recovery_code" name="recovery_code" class="form-control" placeholder="ABCD-EFGH-IJKL" required autofocus>
            <button type="submit" class="btn">
                <i class="fas fa-unlock-alt"></i> Verify & Continue
            </button>
        </form>

        <div class="help-text">
            Want to use authenticator code instead? <a href="{{ route('auth.2fa.show') }}">Go back</a><br>
            Or <a href="{{ route('auth.login') }}">return to login</a>.
        </div>
    </div>
</body>
</html>
