<!-- this isauth landing page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProMatch - Where Tenant Meets Opportunity</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0061FF;
            --dark: #000000;
            --gray-900: #111111;
            --gray-700: #404040;
            --gray-500: #737373;
            --gray-300: #D4D4D4;
            --gray-100: #F5F5F5;
            --white: #FFFFFF;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--white);
            color: var(--gray-900);
            line-height: 1.5;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Animated Background Canvas */
        .bg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.03;
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
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            height: 28px;
            width: auto;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .nav-links a {
            color: var(--gray-700);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--dark);
        }

        .nav-btn {
            padding: 8px 20px;
            background: var(--dark);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .nav-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 40px 80px;
            position: relative;
        }

        .hero-content {
            max-width: 1200px;
            width: 100%;
            text-align: center;
        }

        .hero-title {
            font-size: clamp(48px, 8vw, 96px);
            font-weight: 700;
            letter-spacing: -0.04em;
            line-height: 1.1;
            margin-bottom: 2vw;
            background: linear-gradient(90deg, #000 0%, #0061FF 50%, #000 100%);
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
            font-size: 20px;
            color: var(--gray-500);
            max-width: 600px;
            margin: 0 auto 48px;
            line-height: 1.6;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--dark);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--dark);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            border-color: var(--dark);
            background: var(--gray-100);
        }

        /* Features Grid */
        .features {
            padding: 10px 40px;
            max-width: 1200px;
            margin: 0 auto;
            margin-bottom: 4vw;
        }

        .features-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .features-title {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .features-subtitle {
            font-size: 18px;
            color: var(--gray-500);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 48px;
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
            background: var(--gray-100);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 24px;
        }

        .feature h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .feature p {
            color: var(--gray-500);
            line-height: 1.6;
        }

        /* AI Section */
        .ai-section {
            padding: 120px 40px;
            background: var(--gray-100);
        }

        .ai-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .ai-text h2 {
            font-size: 40px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 24px;
        }

        .ai-text p {
            font-size: 18px;
            color: var(--gray-500);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .ai-visual {
            background: var(--white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .ai-demo {
            text-align: center;
        }

        .ai-badge {
            display: inline-block;
            padding: 4px 12px;
            background: var(--primary);
            color: var(--white);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 16px;
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
            background: var(--white);
            border-radius: 16px;
            width: 90%;
            max-width: 440px;
            padding: 48px;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 24px;
            right: 24px;
            width: 32px;
            height: 32px;
            border: none;
            background: var(--gray-100);
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--gray-500);
        }

        .modal-close:hover {
            background: var(--gray-300);
        }

        .modal h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .modal p {
            color: var(--gray-500);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            color: var(--gray-500);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-inner {
                padding: 0 20px;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 100px 20px 60px;
            }

            .hero-title {
                font-size: 48px;
            }

            .hero-subtitle {
                font-size: 18px;
            }

            .features {
                padding: 80px 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .ai-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .modal-content {
                padding: 32px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <canvas class="bg-canvas" id="bgCanvas"></canvas>

    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-inner">
            <picture>
                <!-- <source media="(max-width: 768px)" srcset="logos/rm-bg/icon1.png"> -->
                <img class="logo" src="logos/rm-bg/logo1.png" alt="ProMatch">
            </picture>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#ai">AI Tools</a>
                <a href="#pricing">Pricing</a>
                <button class="nav-btn" onclick="openModal()">Get Started</button>
            </div>
        </div>
    </nav>

    <!-- Hero -->
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

    <!-- Features -->
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

    <!-- AI Section -->
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
                    <div style="margin-top: 24px; text-align: left;">
                        <p style="color: var(--gray-500); margin-bottom: 16px;">
                            <strong>Timeline:</strong> 6-8 weeks<br>
                            <strong>Budget Range:</strong> $15,000 - $45,000<br>
                            <strong>Skills Required:</strong> React, Node.js, AWS
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <style>
        /* Updated Social Buttons */
.social-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.social-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.social-btn {
    padding: 13px 20px;
    border: 1px solid #E5E5E5;
    border-radius: 8px;
    background: var(--white);
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-700);
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    position: relative;
}

.social-btn.full-width {
    width: 100%;
}

.social-btn:hover {
    border-color: var(--dark);
    background: #FAFAFA;
    transform: translateY(-1px);
}

.social-btn:active {
    transform: translateY(0);
}

.social-btn span {
    font-weight: 500;
}

/* Clean hover states */
.social-google:hover {
    border-color: #4285F4;
}

.social-github:hover {
    border-color: #24292e;
}

.social-linkedin:hover {
    border-color: #0077B5;
}

/* Icon colors */
.social-github svg {
    color: #24292e;
}

.social-linkedin svg {
    color: #0077B5;
}

/* Updated divider */
.divider {
    text-align: center;
    margin: 28px 0 20px;
    color: #9CA3AF;
    font-size: 13px;
    font-weight: 400;
    position: relative;
}

.divider::before,
.divider::after {
    content: '';
    position: absolute;
    top: 50%;
    width: calc(50% - 70px);
    height: 1px;
    background: #E5E5E5;
}

.divider::before {
    left: 0;
}

.divider::after {
    right: 0;
}

/* Mobile responsive */
@media (max-width: 480px) {
    .social-row {
        grid-template-columns: 1fr 1fr;
    }
    
    .social-btn span {
        font-size: 13px;
    }
    
    .social-btn {
        padding: 12px 16px;
    }
}
    </style>
<!-- Auth Modal -->
<!-- Auth Modal -->
<div class="modal" id="authModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <h2>Welcome back</h2>
        <p>Sign in to continue to ProMatch</p>
        
        <form>
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
            <button class="social-btn social-google full-width">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Google</span>
            </button>
            
            <div class="social-row">
                <button class="social-btn social-github">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    <span>GitHub</span>
                </button>
                
                <button class="social-btn social-linkedin">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                    <span>LinkedIn</span>
                </button>
            </div>
        </div>
    </div>
</div>
    <script>
        // Background animation
        const canvas = document.getElementById('bgCanvas');
        const ctx = canvas.getContext('2d');
        
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        const particles = [];
        const particleCount = 50;
        
        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2;
                this.speedX = Math.random() * 0.5 - 0.25;
                this.speedY = Math.random() * 0.5 - 0.25;
            }
            
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                
                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;
            }
            
            draw() {
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }
        
        function init() {
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }
        
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });
            
            particles.forEach((a, i) => {
                particles.slice(i + 1).forEach(b => {
                    const dx = a.x - b.x;
                    const dy = a.y - b.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < 100) {
                        ctx.strokeStyle = `rgba(0, 0, 0, ${1 - distance / 100})`;
                        ctx.lineWidth = 0.5;
                        ctx.beginPath();
                        ctx.moveTo(a.x, a.y);
                        ctx.lineTo(b.x, b.y);
                        ctx.stroke();
                    }
                });
            });
            
            requestAnimationFrame(animate);
        }
        
        init();
        animate();
        
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
        
        // Modal functions
        function openModal() {
            document.getElementById('authModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('authModal').classList.remove('active');
        }
        
        // Close modal on outside click
        document.getElementById('authModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('authModal')) {
                closeModal();
            }
        });
    </script>
</body>
</html>