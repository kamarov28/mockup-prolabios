<script>
  (function () {
    if (document.documentElement.classList.contains('no-motion')) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    function loadScript(src) {
      return new Promise(function (resolve, reject) {
        var s = document.createElement('script');
        s.src = src;
        s.async = true;
        s.onload = resolve;
        s.onerror = reject;
        document.head.appendChild(s);
      });
    }

    var boot = function () {
      loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js')
        .then(function () {
          return loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js');
        })
        .then(function () {
          if (typeof initGSAPAnimations === 'function') {
            initGSAPAnimations();
          }
        })
        .catch(function () {
          if (typeof revealHeroStatic === 'function') revealHeroStatic();
          else {
            var hero = document.querySelector('.typo-hero-entrance');
            if (hero) hero.style.opacity = '1';
          }
        });
    };

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', boot);
    } else {
      boot();
    }
  })();
</script>
