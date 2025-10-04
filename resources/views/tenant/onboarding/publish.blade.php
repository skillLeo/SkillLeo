@extends('layouts.onboarding')

@section('title', 'Welcome to ProMatch!')

@section('card-content')

<div style="text-align: center;">
    <x-onboarding.badge variant="success">Welcome to ProMatch</x-onboarding.badge>

    <div style="display: flex; justify-content: center; margin: var(--space-xl) 0;">
        <div class="success-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
    </div>

    <h1 class="form-title">Your account is live!</h1>
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
        <a href="{{route('tenant.profile')}}" class="btn btn-primary">Go to profile</a>
        <a href="#" class="btn btn-success">Go to dashboard</a>
        <button class="btn btn-secondary" id="shareBtn">Share</button>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-value" id="skillsCount">0</div>
            <div class="stat-label">Skills</div>
        </div>
        <div class="stat">
            <div class="stat-value" id="profileComplete">0%</div>
            <div class="stat-label">Complete</div>
        </div>
        <div class="stat">
            <div class="stat-value">Live</div>
            <div class="stat-label">Status</div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.success-icon {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-lg);
}

.profile-card {
    border: 1px solid var(--border);
    background: var(--apc-bg);
    border-radius: var(--radius);
    padding: var(--space-lg);
    margin: var(--space-xl) 0;
}

.profile-url {
    font-weight: var(--fw-bold);
    color: var(--accent);
    margin-bottom: var(--space-md);
}

.actions {
    display: flex;
    gap: var(--space-sm);
    justify-content: center;
    margin-bottom: var(--space-xl);
}

.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-md);
}

.stat {
    background: var(--apc-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: var(--space-md);
    text-align: center;
}

.stat-value {
    font-weight: var(--fw-extrabold);
    color: var(--text-heading);
    font-size: var(--fs-h3);
}

.stat-label {
    font-size: var(--fs-micro);
    color: var(--text-muted);
    margin-top: 2px;
}

@media (max-width: 640px) {
    .actions { flex-direction: column; }
    .actions .btn { width: 100%; }
    .stats { grid-template-columns: 1fr; }
}
</style>
@endpush

 
    @push('styles')
        
        <style>
   
            .success-badge {
                background: rgba(16, 185, 129, 0.1);
                color: var(--success);
                border: 1px solid rgba(16, 185, 129, 0.3);
            }

            .success-badge::before {
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
                color: var(--btn-text-primary);
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

            

            .celebration {
                position: fixed;
                inset: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 9999;
            }

      
        </style>
    @endpush

    @push('scripts')
    <script>
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

        const username = personal.username || 'username';
        const url = `promatch.com/${username}`;
        document.getElementById('profileUrl').textContent = url;

        // Update profile link
        const profileBtn = document.getElementById('profileBtn');
        if (profileBtn) {
            profileBtn.href = profileBtn.href.replace('placeholder', username);
        }

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
        if (!el) return;
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
        if (!el) return;
        const start = performance.now();
        
        function tick(now) {
            const t = Math.min(1, (now - start) / dur);
            el.textContent = Math.round(t * target) + '%';
            if (t < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    document.getElementById('copyBtn').addEventListener('click', async (e) => {
        const full = 'https://' + document.getElementById('profileUrl').textContent;
        try {
            await navigator.clipboard.writeText(full);
            toast('Link copied');
        } catch {
            toast('Could not copy link');
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

    // Minimal celebration animation
    (() => {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReduced) {
            loadProfileBits();
            return;
        }

        const cvs = document.createElement('canvas');
        cvs.className = 'celebration';
        cvs.setAttribute('aria-hidden', 'true');
        document.body.appendChild(cvs);

        const ctx = cvs.getContext('2d');
        let w, h;
        const DPR = Math.min(2, window.devicePixelRatio || 1);
        const colors = ['#1351d8', '#10B981', '#111111'];
        const particles = [];

        function resize() {
            w = cvs.width = Math.floor(window.innerWidth * DPR);
            h = cvs.height = Math.floor(window.innerHeight * DPR);
            cvs.style.width = window.innerWidth + 'px';
            cvs.style.height = window.innerHeight + 'px';
            ctx.scale(DPR, DPR);
        }
        resize();

        // Create particles
        for (let i = 0; i < 50; i++) {
            particles.push({
                x: Math.random() * window.innerWidth,
                y: -10,
                vx: -0.3 + Math.random() * 0.6,
                vy: 0.8 + Math.random() * 1,
                color: colors[Math.floor(Math.random() * colors.length)],
                size: 2 + Math.random() * 3,
                alpha: 1,
                life: 200
            });
        }

        let frame = 0;
        function loop() {
            frame++;
            ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);

            for (let i = particles.length - 1; i >= 0; i--) {
                const p = particles[i];
                p.vy += 0.02;
                p.x += p.vx;
                p.y += p.vy;
                p.life--;
                p.alpha = Math.max(0, p.life / 200);

                ctx.globalAlpha = p.alpha * 0.7;
                ctx.fillStyle = p.color;
                ctx.fillRect(p.x - p.size/2, p.y - p.size/2, p.size, p.size);

                if (p.life <= 0 || p.y > window.innerHeight) {
                    particles.splice(i, 1);
                }
            }

            if (particles.length > 0 && frame < 300) {
                requestAnimationFrame(loop);
            } else {
                cvs.style.opacity = '0';
                setTimeout(() => cvs.remove(), 300);
            }
        }

        setTimeout(() => requestAnimationFrame(loop), 100);
        loadProfileBits();
    })();

    document.addEventListener('DOMContentLoaded', loadProfileBits);
    </script>
    @endpush