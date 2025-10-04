(() => {
    const canvas = document.getElementById('bgCanvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let w, h, particles = [];
    const COUNT = 30;

    function size() {
        w = canvas.width = window.innerWidth;
        h = canvas.height = window.innerHeight;
    }
    size();
    window.addEventListener('resize', size);

    class Particle {
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

    for (let i = 0; i < COUNT; i++) particles.push(new Particle(true));

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
