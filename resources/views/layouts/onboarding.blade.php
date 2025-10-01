@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary: #0061FF;
        --dark: #000000;
        --white: #FFFFFF;
        --gray-900: #111111;
        --gray-700: #404040;
        --gray-500: #737373;
        --gray-300: #E5E7EB;
        --gray-100: #F9FAFB;
        --error: #EF4444;
        --success: #10B981;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: var(--gray-900);
        background: var(--white);
        -webkit-font-smoothing: antialiased;
        line-height: 1.5;
        position: relative;
        overflow-x: hidden;
    }
.celebration{display: none;}
    .bg-canvas {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0.02;
    }

    .bg-decoration {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -2;
    }

    .floating-shape {
        position: absolute;
        border-radius: 50%;
        will-change: transform, opacity;
        filter: blur(6px);
        background:
            radial-gradient(circle at 30% 30%, rgba(0, 97, 255, 0.22), rgba(0, 97, 255, 0.10) 45%, transparent 65%),
            radial-gradient(circle at 70% 70%, rgba(0, 97, 255, 0.18), transparent 60%);
        animation: floatBlob 14s ease-in-out infinite;
        opacity: 0.6;
    }

    .floating-shape:nth-child(1) {
        --size: 300px;
        width: var(--size);
        height: var(--size);
        top: 10%;
        right: 10%;
        animation-delay: 0s;
    }

    .floating-shape:nth-child(2) {
        --size: 220px;
        width: var(--size);
        height: var(--size);
        bottom: 18%;
        left: 6%;
        animation-delay: 4.2s;
    }

    .floating-shape:nth-child(3) {
        --size: 160px;
        width: var(--size);
        height: var(--size);
        top: 52%;
        right: 28%;
        animation-delay: 8.4s;
    }

    @keyframes floatBlob {
        0%, 100% { transform: translate3d(0, 0, 0) scale(1) rotate(0deg); opacity: 0.55; }
        25% { transform: translate3d(-12px, -24px, 0) scale(1.03) rotate(8deg); opacity: 0.65; }
        50% { transform: translate3d(0px, -14px, 0) scale(0.98) rotate(0deg); opacity: 0.60; }
        75% { transform: translate3d(12px, -28px, 0) scale(1.04) rotate(-8deg); opacity: 0.68; }
    }

    @media (prefers-reduced-motion: reduce) {
        .floating-shape { animation: none !important; }
    }

    .container {
        max-width: 640px;
        margin: 0 auto;
        padding: 100px 24px 40px;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .form-card {
        width: 100%;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid var(--gray-300);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 640px) {
        .container { padding: 80px 16px 24px; }
        .form-card { padding: 32px 24px; }
        .floating-shape { opacity: .05; }
    }
</style>
@endpush

@section('content')
    <!-- Background -->
    <canvas class="bg-canvas" id="bgCanvas" aria-hidden="true"></canvas>
    <div class="bg-decoration" aria-hidden="true">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <!-- Header -->
    @include('partials.nav.onboarding')
    <canvas id="celebrationCanvas" class="celebration" aria-hidden="true"></canvas>

    <!-- Main Content -->
    <main class="container">
        <section class="form-card" aria-labelledby="page-title">
            @yield('card-content')
        </section>
    </main>
@endsection

@push('scripts')
<script>
    // Particle background
    (() => {
        const canvas = document.getElementById('bgCanvas');
        const ctx = canvas.getContext('2d');
        let w, h, particles = [];
        const COUNT = 30;

        function size() {
            w = canvas.width = window.innerWidth;
            h = canvas.height = window.innerHeight;
        }
        size();
        window.addEventListener('resize', size);

        class P {
            constructor(init = false) { this.reset(init); }
            reset(init) {
                this.x = init ? Math.random() * w : (Math.random() < 0.5 ? 0 : w);
                this.y = Math.random() * h;
                this.s = Math.random() * 2 + 0.5;
                this.vx = Math.random() * 0.5 - 0.25;
                this.vy = Math.random() * 0.5 - 0.25;
            }
            step() {
                this.x += this.vx;
                this.y += this.vy;
                if (this.x > w || this.x < 0 || this.y > h || this.y < 0) this.reset();
            }
            draw() {
                ctx.fillStyle = '#000';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.s, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for (let i = 0; i < COUNT; i++) particles.push(new P(true));

        function tick() {
            ctx.clearRect(0, 0, w, h);
            particles.forEach(p => { p.step(); p.draw(); });
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const a = particles[i], b = particles[j];
                    const d = Math.hypot(a.x - b.x, a.y - b.y);
                    if (d < 100) {
                        ctx.strokeStyle = `rgba(0,0,0,${1 - d / 100})`;
                        ctx.lineWidth = 0.3;
                        ctx.beginPath();
                        ctx.moveTo(a.x, a.y);
                        ctx.lineTo(b.x, b.y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(tick);
        }

        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) tick();
    })();
</script>
@endpush