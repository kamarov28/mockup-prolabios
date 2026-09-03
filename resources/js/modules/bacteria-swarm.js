/**
 * Bacteria Swarm Prototype — Kecebong / Organism Floating System
 * Autonomous boid-like floating organisms that wander gently across the homepage viewport.
 */
export function initBacteriaSwarm() {
  // Respect prefers-reduced-motion
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  const svgSources = [
    '/images/bactery-icon/bacteria@12x-1.0s-200px-200px.svg',
    '/images/bactery-icon/bacteria@12x-33.3s-200px-200px.svg',
    '/images/bactery-icon/bacteria@1x-1.0s-200px-200px.svg',
    '/images/bactery-icon/bacteria@1x-33.3s-200px-200px.svg',
  ];

  // Organism pool settings - reduced count & subtle presence
  const COUNT = window.innerWidth < 768 ? 5 : 9;
  const container = document.createElement('div');
  container.className = 'bacteria-swarm-container';
  container.setAttribute('aria-hidden', 'true');
  container.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    pointer-events: none;
    z-index: -1;
    overflow: hidden;
  `;
  document.body.appendChild(container);

  const organisms = [];
  const w = window.innerWidth;
  const h = window.innerHeight;

  // Grid distribution to ensure uniform spread across the entire viewport
  const cols = window.innerWidth < 768 ? 2 : 3;
  const rows = Math.ceil(COUNT / cols);
  const cellW = w / cols;
  const cellH = h / rows;

  for (let i = 0; i < COUNT; i++) {
    const el = document.createElement('img');
    const svgSrc = svgSources[Math.floor(Math.random() * svgSources.length)];
    el.src = svgSrc;
    el.alt = '';
    el.loading = 'eager';

    const size = Math.floor(Math.random() * 36) + 38; // 38px - 74px
    const opacity = (Math.random() * 0.25 + 0.65).toFixed(2); // 0.65 - 0.90 solid visibility

    // Native transparent SVGs with crisp neo-brutal drop-shadow
    el.style.cssText = `
      position: absolute;
      top: 0;
      left: 0;
      width: ${size}px;
      height: ${size}px;
      opacity: ${opacity};
      will-change: transform;
      user-select: none;
      filter: drop-shadow(2px 2px 0px rgba(30, 30, 30, 0.5));
      transition: opacity 0.4s ease;
    `;

    container.appendChild(el);

    // Distribute across viewport cells with jitter so they don't bunch in one corner
    const colIdx = i % cols;
    const rowIdx = Math.floor(i / cols);
    const startX = colIdx * cellW + Math.random() * (cellW - size);
    const startY = rowIdx * cellH + Math.random() * (cellH - size);

    // Initial random position & kinematics (gentler speed)
    organisms.push({
      el,
      x: startX,
      y: startY,
      vx: (Math.random() - 0.5) * 0.9,
      vy: (Math.random() - 0.5) * 0.9,
      angle: Math.random() * 360,
      turnSpeed: (Math.random() - 0.5) * 0.025,
      wigglePhase: Math.random() * Math.PI * 2,
      wiggleSpeed: 0.03 + Math.random() * 0.05,
      speed: 0.5 + Math.random() * 0.7,
      size,
    });
  }

  // Interactive mouse repulsion
  let mouseX = -9999;
  let mouseY = -9999;
  window.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
  }, { passive: true });

  let rafId = null;
  let lastTime = performance.now();

  function update() {
    const now = performance.now();
    const dt = Math.min((now - lastTime) / 16.66, 2.0); // normalize 60fps
    lastTime = now;

    const w = window.innerWidth;
    const h = window.innerHeight;

    for (let i = 0; i < organisms.length; i++) {
      const b = organisms[i];

      // Natural gentle wandering (kecebong / swim drift)
      b.wigglePhase += b.wiggleSpeed * dt;
      const wander = Math.sin(b.wigglePhase) * 0.4;
      b.angle += (b.turnSpeed + wander * 0.05);

      let targetVx = Math.cos(b.angle) * b.speed;
      let targetVy = Math.sin(b.angle) * b.speed;

      // Mouse evasion / interaction (flee when cursor gets close)
      const dx = b.x - mouseX;
      const dy = b.y - mouseY;
      const distSq = dx * dx + dy * dy;
      const repelDist = 180;

      if (distSq < repelDist * repelDist && distSq > 0) {
        const dist = Math.sqrt(distSq);
        const force = (1 - dist / repelDist) * 3.5;
        targetVx += (dx / dist) * force;
        targetVy += (dy / dist) * force;
        // Face away from mouse while fleeing
        b.angle = Math.atan2(dy, dx);
      }

      // Smooth velocity interpolation
      b.vx += (targetVx - b.vx) * 0.06 * dt;
      b.vy += (targetVy - b.vy) * 0.06 * dt;

      b.x += b.vx * dt;
      b.y += b.vy * dt;

      // Screen wrap-around with padding
      const pad = b.size * 1.5;
      if (b.x < -pad) b.x = w + pad;
      if (b.x > w + pad) b.x = -pad;
      if (b.y < -pad) b.y = h + pad;
      if (b.y > h + pad) b.y = -pad;

      // Calculate swimming orientation angle (pointing forward like kecebong)
      const swimAngle = Math.atan2(b.vy, b.vx) * (180 / Math.PI) + 90;
      const wiggleRotation = Math.sin(b.wigglePhase * 2) * 8; // gentle tail oscillation

      b.el.style.transform = `translate3d(${b.x.toFixed(1)}px, ${b.y.toFixed(1)}px, 0) rotate(${(swimAngle + wiggleRotation).toFixed(1)}deg)`;
    }

    rafId = requestAnimationFrame(update);
  }

  rafId = requestAnimationFrame(update);

  // Clean-up if page navigation changes
  window.addEventListener('beforeunload', () => {
    if (rafId) cancelAnimationFrame(rafId);
  });
}
