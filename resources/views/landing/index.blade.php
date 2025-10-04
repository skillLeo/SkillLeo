<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProMatch - Where Talent Meets Opportunity</title>
    <link rel="stylesheet" href="css/app.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background: var(--bg);
            color: var(--text-body);
            line-height: var(--lh-normal);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Navigation */
        .nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1000;
            border-bottom: 1px solid var(--border);
        }

        [data-theme="dark"] .nav {
            background: rgba(27, 31, 35, 0.8);
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-xl);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            height: 32px;
            width: auto;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: var(--space-xl);
        }

        .nav-links a {
            color: var(--text-body);
            text-decoration: none;
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            transition: color var(--transition-base);
        }

        .nav-links a:hover {
            color: var(--text-heading);
        }

        .nav-btn {
            padding: 10px 20px;
            background: var(--ink);
            color: var(--card);
            border: none;
            border-radius: var(--radius);
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .nav-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px var(--space-xl) var(--space-2xl);
            position: relative;
        }

        .hero-content {
            max-width: 1200px;
            width: 100%;
            text-align: center;
        }

        .hero-title {
            font-size: clamp(48px, 8vw, 96px);
            font-weight: var(--fw-extrabold);
            letter-spacing: -0.04em;
            line-height: var(--lh-compact);
            margin-bottom: 2vw;
            background: linear-gradient(90deg, var(--ink) 0%, var(--accent) 50%, var(--ink) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 8s ease infinite;
        }

        @keyframes gradient-shift {
            0% { background-position: 0% center; }
            50% { background-position: 100% center; }
            100% { background-position: 0% center; }
        }

        .hero-subtitle {
            font-size: var(--fs-h3);
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto var(--space-2xl);
            line-height: var(--lh-relaxed);
        }

        .hero-actions {
            display: flex;
            gap: var(--space-md);
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 32px;
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            border-radius: var(--radius);
            border: none;
            cursor: pointer;
            transition: all var(--transition-base);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .btn-primary {
            background: var(--ink);
            color: var(--card);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--card);
            color: var(--ink);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: var(--ink);
            background: var(--apc-bg);
        }

        /* Features */
        .features {
            padding: var(--space-2xl) var(--space-xl);
            max-width: 1200px;
            margin: 0 auto var(--space-2xl);
        }

        .features-header {
            text-align: center;
            margin-bottom: var(--space-2xl);
        }

        .features-title {
            font-size: var(--fs-display);
            font-weight: var(--fw-bold);
            letter-spacing: -0.02em;
            margin-bottom: var(--space-md);
            color: var(--text-heading);
        }

        .features-subtitle {
            font-size: var(--fs-h3);
            color: var(--text-muted);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-2xl);
        }

        .feature {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s forwards;
        }

        .feature:nth-child(1) { animation-delay: 0.1s; }
        .feature:nth-child(2) { animation-delay: 0.2s; }
        .feature:nth-child(3) { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: var(--apc-bg);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--space-lg);
            font-size: 24px;
        }

        .feature h3 {
            font-size: var(--fs-h3);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--space-sm);
            color: var(--text-heading);
        }

        .feature p {
            color: var(--text-muted);
            line-height: var(--lh-relaxed);
        }

        /* AI Section */
        .ai-section {
            padding: var(--space-2xl) var(--space-xl);
            background: var(--apc-bg);
        }

        .ai-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-2xl);
            align-items: center;
        }

        .ai-text h2 {
            font-size: var(--fs-display);
            font-weight: var(--fw-bold);
            letter-spacing: -0.02em;
            margin-bottom: var(--space-lg);
            color: var(--text-heading);
        }

        .ai-text p {
            font-size: var(--fs-h3);
            color: var(--text-muted);
            margin-bottom: var(--space-xl);
            line-height: var(--lh-relaxed);
        }

        .ai-visual {
            background: var(--card);
            padding: var(--space-xl);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }

        .ai-demo {
            text-align: center;
        }

        .ai-badge {
            display: inline-block;
            padding: 4px 12px;
            background: var(--accent);
            color: var(--btn-text-primary);
            border-radius: 20px;
            font-size: var(--fs-micro);
            font-weight: var(--fw-semibold);
            margin-bottom: var(--space-md);
        }

        .ai-demo h3 {
            font-size: var(--fs-h3);
            color: var(--text-heading);
            margin-bottom: var(--space-lg);
        }

        .ai-demo p {
            color: var(--text-muted);
            text-align: left;
            line-height: var(--lh-relaxed);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--card);
            border-radius: var(--radius);
            width: 90%;
            max-width: 440px;
            padding: var(--space-2xl);
            position: relative;
            border: 1px solid var(--border);
        }

        .modal-close {
            position: absolute;
            top: var(--space-lg);
            right: var(--space-lg);
            width: 32px;
            height: 32px;
            border: none;
            background: var(--apc-bg);
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--text-muted);
            transition: all var(--transition-base);
        }

        .modal-close:hover {
            background: var(--border);
        }

        .modal h2 {
            font-size: var(--fs-h1);
            font-weight: var(--fw-bold);
            margin-bottom: var(--space-sm);
            color: var(--text-heading);
        }

        .modal p {
            color: var(--text-muted);
            margin-bottom: var(--space-xl);
        }

        .form-group {
            margin-bottom: var(--space-lg);
        }

        .form-group label {
            display: block;
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            margin-bottom: var(--space-sm);
            color: var(--text-body);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--input-border);
            border-radius: var(--radius);
            font-size: var(--fs-body);
            background: var(--input-bg);
            color: var(--input-text);
            transition: border-color var(--transition-base);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-light);
        }

        .form-group input::placeholder {
            color: var(--input-placeholder);
        }

        .divider {
            text-align: center;
            margin: var(--space-lg) 0;
            color: var(--text-muted);
            font-size: var(--fs-subtle);
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: calc(50% - 70px);
            height: 1px;
            background: var(--border);
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .social-buttons {
            display: flex;
            flex-direction: column;
            gap: var(--space-md);
        }

        .social-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-md);
        }

        .social-btn {
            padding: 13px 20px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--card);
            font-size: var(--fs-body);
            font-weight: var(--fw-medium);
            color: var(--text-body);
            cursor: pointer;
            transition: all var(--transition-base);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
        }

        .social-btn:hover {
            border-color: var(--ink);
            background: var(--apc-bg);
            transform: translateY(-1px);
        }

        .social-btn.full-width {
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-inner {
                padding: 0 var(--space-lg);
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 100px var(--space-lg) var(--space-2xl);
            }

            .hero-title {
                font-size: 48px;
            }

            .hero-subtitle {
                font-size: var(--fs-body);
            }

            .features {
                padding: var(--space-2xl) var(--space-lg);
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: var(--space-xl);
            }

            .ai-content {
                grid-template-columns: 1fr;
                gap: var(--space-xl);
            }

            .modal-content {
                padding: var(--space-xl);
            }

            .social-row {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="nav-inner">
            <img class="logo" src="assets/images/logos/croped/logo_light.png" alt="ProMatch" id="navLogo">
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#ai">AI Tools</a>
                <a href="#pricing">Pricing</a>
                <button class="nav-btn" onclick="openModal()">Get Started</button>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Where talent meets opportunity</h1>
            <p class="hero-subtitle">
                The intelligent platform that connects professionals with projects. 
                AI-powered matching, seamless collaboration, guaranteed payments.
            </p>
            <div class="hero-actions">
                <button class="btn btn-primary" onclick="openModal()">
                    Start Free
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14m-7-7l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <button class="btn btn-secondary">Watch Demo</button>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="features-header">
            <h2 class="features-title">Built for modern work</h2>
            <p class="features-subtitle">Everything you need to succeed in the freelance economy</p>
        </div>
        <div class="features-grid">
            <div class="feature">
                <div class="feature-icon">🎯</div>
                <h3>Smart Matching</h3>
                <p>AI analyzes skills, budget, and timeline to connect you with perfect matches instantly.</p>
            </div>
            <div class="feature">
                <div class="feature-icon">💼</div>
                <h3>Project Management</h3>
                <p>Track progress, manage milestones, and collaborate seamlessly in one platform.</p>
            </div>
            <div class="feature">
                <div class="feature-icon">💳</div>
                <h3>Secure Payments</h3>
                <p>Automated invoicing, escrow protection, and guaranteed payments for completed work.</p>
            </div>
        </div>
    </section>

    <section class="ai-section" id="ai">
        <div class="ai-content">
            <div class="ai-text">
                <h2>AI that understands your needs</h2>
                <p>Upload project requirements and get instant cost estimates, timeline predictions, and skill recommendations powered by advanced AI.</p>
                <button class="btn btn-primary">Try AI Estimator</button>
            </div>
            <div class="ai-visual">
                <div class="ai-demo">
                    <div class="ai-badge">AI Analysis</div>
                    <h3>E-commerce Platform</h3>
                    <p style="margin-top: var(--space-lg);">
                        <strong>Timeline:</strong> 6-8 weeks<br>
                        <strong>Budget Range:</strong> $15,000 - $45,000<br>
                        <strong>Skills Required:</strong> React, Node.js, AWS
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="modal" id="authModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <h2>Welcome back</h2>
            <p>Sign in to continue to ProMatch</p>
            
            <form id="loginForm" method="POST" action="/api/auth/login">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" placeholder="you@example.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" placeholder="Enter password">
                </div>
                <button class="btn btn-primary" style="width: 100%;" type="submit">Continue</button>
            </form>
            
            <div class="divider">Or continue with</div>
            
<div class="social-buttons">
    <a class="social-btn full-width" href="/auth/google/redirect">Google</a>
    <div class="social-row">
      <a class="social-btn" href="/auth/github/redirect">GitHub</a>
      <a class="social-btn" href="{{ route('oauth.redirect', 'linkedin') }}">LinkedIn</a>
    </div>
  </div>
  
  <script>
  document.getElementById('loginForm').addEventListener('submit', async (e)=>{
    e.preventDefault();
    const fd=new FormData(e.target);
    const res=await fetch('/api/auth/login',{method:'POST',headers:{'X-CSRF-TOKEN':fd.get('_token')},body:fd});
    const json=await res.json();
    if(res.ok){ window.location.href='/onboarding'; }
    else { alert(json.message || 'Login failed'); }
  });
  </script>
            {{-- <div class="social-buttons">
                <button class="social-btn full-width">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>Google</span>
                </button>
                
                <div class="social-row">
                    <button class="social-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                        <span>GitHub</span>
                    </button>
                    
                    <button class="social-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                        <span>LinkedIn</span>
                    </button>
                </div>
            </div> --}}
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('authModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('authModal').classList.remove('active');
        }
        
        document.getElementById('authModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('authModal')) {
                closeModal();
            }
        });

        // Theme-aware logo switching
        const navLogo = document.getElementById('navLogo');
        const html = document.documentElement;
        
        function updateLogo() {
            const theme = html.getAttribute('data-theme');
            navLogo.src = theme === 'dark' 
                ? 'assets/images/logos/croped/logo_dark.png'
                : 'assets/images/logos/croped/logo_light.png';
        }

        // Watch for theme changes
        const observer = new MutationObserver(updateLogo);
        observer.observe(html, { attributes: true, attributeFilter: ['data-theme'] });
        
        // Initial logo
        updateLogo();
    </script>
</body>
</html>