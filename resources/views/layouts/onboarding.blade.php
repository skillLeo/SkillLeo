{{-- resources/views/layouts/onboarding.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Onboarding - SkillLeo')</title>
    <link rel="shortcut icon" href="{{asset('assets/images/logos/fav/fav7.png')}}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/onboarding-components.css') }}">
    @stack('styles')
    <x-timezone-detector />

    <style>
        @keyframes spin {
    to { transform: rotate(360deg); }
}

.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: currentColor;
    animation: spin 0.6s linear infinite;
}
        /* Theme-based logo visibility */
.logo-light {
    display: block;
}

.logo-dark {
    display: none;
}

[data-theme="dark"] .logo-light {
    display: none;
}

[data-theme="dark"] .logo-dark {
    display: block;
}
    </style>
</head>
<body>
    <div class="onboarding-container">

<x-navigation.onboarding />

        <main class="onboarding-main">
            <div class="onboarding-content">
                <div class="form-card">
                    @yield('card-content')
                </div>
            </div>
        </main>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            
            // Toggle theme
            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            });
        });
        </script>



<script>
    (function () {
      try {
        var tz = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
        if (tz && (!document.cookie.includes('tz=') || !document.cookie.includes(encodeURIComponent(tz)))) {
          var expires = new Date(Date.now() + 365*24*60*60*1000).toUTCString();
          document.cookie = "tz=" + encodeURIComponent(tz) + "; path=/; expires=" + expires + "; samesite=lax";
        }
      } catch(e) {}
    })();
  </script>
  
    @stack('scripts')
</body>
</html>