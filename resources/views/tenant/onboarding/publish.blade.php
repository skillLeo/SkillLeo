@extends('layouts.onboarding')

@section('title', 'Welcome to ProMatch!')

@php
    $currentStep = 8;
    $totalSteps = 8;
@endphp

@push('styles')
<style>
  .celebration{display: inline-block !important; z-index: 1000 !important;}

    /* Success-specific styles not in app.css */
    .step-badge {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .step-badge::before {
        background: var(--success);
    }

    .success-wrap {
        display: grid;
        place-items: center;
        margin-bottom: 20px;
        position: relative;
    }

    .success-icon {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        color: #fff;
        display: grid;
        place-items: center;
        box-shadow: var(--shadow-lg);
        position: relative;
        isolation: isolate;
    }

    .success-icon::after {
        content: '';
        position: absolute;
        inset: -10px;
        border-radius: 50%;
        background: conic-gradient(from 0deg, rgba(16,185,129,.0), rgba(16,185,129,.25), rgba(0,160,220,.25), rgba(16,185,129,.0));
        filter: blur(12px);
        z-index: -1;
        animation: spin 6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .profile-card {
        margin-top: 16px;
        border: 1px solid var(--gray-300);
        background: var(--gray-100);
        border-radius: 12px;
        padding: 18px;
        text-align: left;
    }

    .profile-url {
        font-weight: 700;
        color: var(--primary);
        word-break: break-all;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 18px;
    }

    .celebration {
        position: absolute;
        top: 0 !important;left: 0 !important;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .container {
        position: relative;
        z-index: 2;
    }

    .form-card {
        text-align: center;
    }

    /* Mobile adjustments */
    @media (max-width: 640px) {
        .actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }

        .stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('card-content')
    <!-- Celebration Canvas -->

    <div class="step-badge">Welcome to ProMatch</div>
    
    <div class="success-wrap">
        <div class="success-icon" aria-hidden="true">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
    </div>
    
    <h1 class="form-title">Your account is live! 🎉</h1>
    <p class="form-subtitle">Everything is set. Celebrate the launch and share your professional profile.</p>

    <div class="profile-card">
        <div class="profile-url" id="profileUrl">promatch.com/username</div>
        <button class="btn btn-secondary" id="copyBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                <path d="m5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="2"/>
            </svg>
            Copy link
        </button>
    </div>

    <div class="actions">
        <button class="btn btn-primary" id="profileBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
            </svg>
            Go to profile
        </button>
        
        <button class="btn btn-success" id="dashboardBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <rect x="3" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                <rect x="14" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                <rect x="14" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                <rect x="3" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>
            </svg>
            Go to dashboard
        </button>
        
        <button class="btn btn-secondary" id="shareBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7M16 6l-4-4-4 4M12 2v14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Share
        </button>
    </div>

    <div class="stats" aria-label="Quick stats">
        <div class="stat">
            <div class="n" id="skillsCount">0</div>
            <div class="l">Skills</div>
        </div>
        <div class="stat">
            <div class="n" id="profileComplete">0%</div>
            <div class="l">Complete</div>
        </div>
        <div class="stat">
            <div class="n" id="visibility">Live</div>
            <div class="l">Status</div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
/* =============== DATA & UI HELPERS =============== */
function toast(msg) {
    const t = document.createElement('div');
    t.className = 'toast';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => {
        t.style.opacity = '0';
        t.style.transform = 'translateY(-6px)';
        setTimeout(() => t.remove(), 200);
    }, 2200);
}

function loadProfileBits() {
    const personal = JSON.parse(localStorage.getItem('onboarding_personal') || '{}');
    const skills = JSON.parse(localStorage.getItem('onboarding_skills') || '[]');
    const exp = JSON.parse(localStorage.getItem('onboarding_experience') || '[]');
    const port = JSON.parse(localStorage.getItem('onboarding_portfolio') || '[]');
    const edu = JSON.parse(localStorage.getItem('onboarding_education') || '[]');

    const url = `promatch.com/${personal.username || 'username'}`;
    document.getElementById('profileUrl').textContent = url;

    // counters
    const skillsN = skills.length;
    const completePct = (() => {
        let p = 0;
        if (personal.firstName && personal.lastName) p += 25;
        if (personal.username) p += 15;
        if (skillsN >= 3) p += 30;
        if (exp.length) p += 20;
        if (port.length || edu.length) p += 10;
        return Math.min(100, p);
    })();

    animateCount('skillsCount', skillsN, 800);
    animateCountPercent('profileComplete', completePct, 1000);
}

function animateCount(id, target, dur) {
    const el = document.getElementById(id);
    const start = performance.now();
    
    function tick(now) {
        const t = Math.min(1, (now - start) / dur);
        el.textContent = Math.round(t * target);
        if (t < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}

function animateCountPercent(id, target, dur) {
    const el = document.getElementById(id);
    const start = performance.now();
    
    function tick(now) {
        const t = Math.min(1, (now - start) / dur);
        el.textContent = Math.round(t * target) + '%';
        if (t < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}

/* =============== ACTIONS =============== */
document.getElementById('copyBtn').addEventListener('click', async (e) => {
    const full = 'https://' + document.getElementById('profileUrl').textContent;
    try {
        await navigator.clipboard.writeText(full);
        toast('Link copied');
    } catch {
        toast('Could not copy link');
    }
});

document.getElementById('profileBtn').addEventListener('click', () => {
    const url = 'https://' + document.getElementById('profileUrl').textContent;
    try {
        window.location.href = url;
    } catch {
        toast('Opening profile…');
    }
});

document.getElementById('dashboardBtn').addEventListener('click', () => {
    try {
        window.location.href = '{{ route("tenant.dashboard") }}';
    } catch {
        toast('Opening dashboard…');
    }
});

document.getElementById('shareBtn').addEventListener('click', async () => {
    const full = 'https://' + document.getElementById('profileUrl').textContent;
    if (navigator.share) {
        try {
            await navigator.share({ title: 'My ProMatch Profile', url: full });
        } catch {}
    } else {
        try {
            await navigator.clipboard.writeText(full);
            toast('Link copied to share');
        } catch {}
    }
});



/* =============== MINIMAL PROFESSIONAL CELEBRATION =============== */
(() => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) {
        loadProfileBits();
        return;
    }

    const cvs = document.createElement('canvas');
    cvs.id = 'celebrationCanvas';
    cvs.className = 'celebration';
    cvs.setAttribute('aria-hidden', 'true');
    document.body.appendChild(cvs);

    const ctx = cvs.getContext('2d');
    let w, h, rafId, running = true;
    const DPR = Math.min(2, window.devicePixelRatio || 1);

    // Professional colors - more muted
    const colors = ['#0066CC', '#10B981', '#111111'];
    const fireworks = [];
    const confetti = [];

    function resize() {
        w = cvs.width = Math.floor(window.innerWidth * DPR);
        h = cvs.height = Math.floor(window.innerHeight * DPR);
        cvs.style.width = window.innerWidth + 'px';
        cvs.style.height = window.innerHeight + 'px';
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.scale(DPR, DPR);
    }
    resize();
    window.addEventListener('resize', resize);

    class Particle {
        constructor(x, y, angle, speed, color) {
            this.x = x;
            this.y = y;
            this.vx = Math.cos(angle) * speed;
            this.vy = Math.sin(angle) * speed;
            this.alpha = 1;
            this.color = color;
            this.life = 60; // Shorter life - 60 frames = ~1 second
            this.age = 0;
            this.size = 1.5 + Math.random() * 1; // Smaller particles
        }
        
        step() {
            this.age++;
            this.vy += 0.03; // Faster gravity
            this.vx *= 0.98; // More air resistance
            this.vy *= 0.98;
            this.x += this.vx;
            this.y += this.vy;
            this.alpha = Math.max(0, 1 - this.age / this.life);
            return this.alpha > 0.05 && this.y < window.innerHeight + 20;
        }
        
        draw() {
            ctx.globalAlpha = this.alpha * 0.8; // More transparent
            ctx.beginPath();
            ctx.fillStyle = this.color;
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    class MiniFirework {
        constructor() {
            this.x = Math.random() * window.innerWidth;
            this.y = window.innerHeight;
            this.tx = this.x + (-50 + Math.random() * 100); // Small spread
            this.ty = window.innerHeight * (0.4 + Math.random() * 0.2); // Higher target
            this.color = colors[Math.floor(Math.random() * colors.length)];
            this.speed = 8; // Faster
            this.exploded = false;
            this.particles = [];
        }
        
        step() {
            if (!this.exploded) {
                const dx = this.tx - this.x, dy = this.ty - this.y;
                const d = Math.hypot(dx, dy);
                const vx = (dx / d) * this.speed;
                const vy = (dy / d) * this.speed;
                this.x += vx;
                this.y += vy;
                
                if (d < 5) {
                    this.exploded = true;
                    // Fewer particles - more professional
                    const count = 12 + Math.floor(Math.random() * 8);
                    for (let i = 0; i < count; i++) {
                        const angle = (i / count) * Math.PI * 2;
                        const speed = 1 + Math.random() * 2; // Slower spread
                        this.particles.push(new Particle(this.x, this.y, angle, speed, this.color));
                    }
                }
            } else {
                this.particles = this.particles.filter(p => {
                    const alive = p.step();
                    if (alive) p.draw();
                    return alive;
                });
            }
            return !this.exploded || this.particles.length > 0;
        }
    }

    class MiniConfetto {
        constructor() {
            this.x = Math.random() * window.innerWidth;
            this.y = -10;
            this.w = 3 + Math.random() * 3; // Smaller
            this.h = 6 + Math.random() * 4;
            this.color = colors[Math.floor(Math.random() * colors.length)];
            this.spin = Math.random() * Math.PI * 2;
            this.vx = -0.3 + Math.random() * 0.6; // Less horizontal drift
            this.vy = 0.8 + Math.random() * 1; // Faster fall
            this.g = 0.02; // More gravity
            this.alpha = 1;
            this.life = 200; // Shorter life
            this.age = 0;
        }
        
        step() {
            this.age++;
            this.vy += this.g;
            this.x += this.vx;
            this.y += this.vy;
            this.spin += 0.08;
            
            // Fade out faster
            this.alpha = Math.max(0, 1 - (this.age / this.life));
            
            return this.y < window.innerHeight + 10 && this.alpha > 0.1;
        }
        
        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.spin);
            ctx.fillStyle = this.color;
            ctx.globalAlpha = this.alpha * 0.7; // More transparent
            ctx.fillRect(-this.w / 2, -this.h / 2, this.w, this.h);
            ctx.restore();
        }
    }

    let startTime = performance.now();
    const FIREWORK_DURATION = 5000; // 5 seconds
    const TOTAL_DURATION = 12000; // 12 seconds total
    
    function loop(now) {
        const elapsed = now - startTime;
        
        ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);

        // Fireworks only for first 5 seconds, less frequent
        if (elapsed < FIREWORK_DURATION && Math.random() < 0.03) {
            fireworks.push(new MiniFirework());
        }
        
        // Less confetti overall
        if (elapsed < FIREWORK_DURATION + 3000 && Math.random() < 0.08 && confetti.length < 30) {
            confetti.push(new MiniConfetto());
        }

        // Update fireworks
        for (let i = fireworks.length - 1; i >= 0; i--) {
            const keep = fireworks[i].step();
            if (!keep) fireworks.splice(i, 1);
        }
        
        // Update confetti
        for (let i = confetti.length - 1; i >= 0; i--) {
            const c = confetti[i];
            const keep = c.step();
            if (keep) {
                c.draw();
            } else {
                confetti.splice(i, 1);
            }
        }

        // Stop after total duration or when no particles left
        if (elapsed < TOTAL_DURATION && (fireworks.length > 0 || confetti.length > 0)) {
            if (running) rafId = requestAnimationFrame(loop);
        } else {
            // Clean up
            cvs.style.opacity = '0';
            setTimeout(() => {
                if (cvs && cvs.parentNode) cvs.remove();
            }, 300);
        }
    }

    rafId = requestAnimationFrame(loop);
    
    // Cleanup
    window.addEventListener('beforeunload', () => {
        running = false;
        if (rafId) cancelAnimationFrame(rafId);
    });

    loadProfileBits();
})();


document.addEventListener('DOMContentLoaded', loadProfileBits);
</script>
@endpush