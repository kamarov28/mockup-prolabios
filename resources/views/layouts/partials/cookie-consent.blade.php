<!-- B2B Cookie Consent Notice (UU PDP & GDPR Compliance) -->
<div id="cookieConsentBanner" class="position-fixed" style="display: none; z-index: 9999; bottom: 24px; right: 24px; max-width: 440px; width: calc(100% - 48px); background: #0c0d12; border: 1px solid rgba(255,255,255,0.12); border-left: 3px solid var(--color-accent); box-shadow: 0 16px 40px rgba(0,0,0,0.85); border-radius: 0px;">
  <div style="padding: 16px 20px;">
    
    <div class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-shield-check" style="color: var(--color-accent); font-size: 1.15rem;"></i>
      <span class="fw-bold" style="font-family: var(--font-headline); font-size: 0.9rem; color: #ffffff; letter-spacing: 0.5px;">Privasi &amp; Penggunaan Cookie</span>
    </div>

    <p class="mb-3" style="font-size: 0.82rem; line-height: 1.55; color: #94a3b8;">
      Kami menggunakan cookie untuk memastikan navigasi katalog instrumen dan reagen lab berjalan optimal serta memproses permintaan penawaran Anda sesuai UU No. 27 Tahun 2022 (UU PDP).
    </p>

    <div class="d-flex align-items-center justify-content-between gap-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.08);">
      <a href="{{ route('privacy') }}" style="font-size: 0.8rem; color: #94a3b8; text-decoration: underline; transition: color 0.2s;" onmouseover="this.style.color='var(--color-accent)'" onmouseout="this.style.color='#94a3b8'">
        Pelajari Selengkapnya
      </a>
      <button id="acceptCookieConsentBtn" type="button" class="btn btn-sm px-3 py-1 text-white fw-semibold" style="background: var(--color-accent); border-radius: 0px !important; border: 1px solid var(--color-accent); font-family: var(--font-headline); font-size: 0.78rem; letter-spacing: 0.5px; text-transform: uppercase; cursor: pointer;">
        Saya Mengerti
      </button>
    </div>

  </div>
</div>

<script>
  (function () {
    const consentKey = 'prolabios_cookie_consent_v1';
    const banner = document.getElementById('cookieConsentBanner');
    const acceptBtn = document.getElementById('acceptCookieConsentBtn');

    if (!banner || !acceptBtn) return;

    if (!localStorage.getItem(consentKey)) {
      setTimeout(function () {
        banner.style.display = 'block';
        banner.style.opacity = '0';
        banner.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        banner.style.transform = 'translateY(10px)';
        requestAnimationFrame(function () {
          banner.style.opacity = '1';
          banner.style.transform = 'translateY(0)';
        });
      }, 600);
    }

    acceptBtn.addEventListener('click', function () {
      localStorage.setItem(consentKey, '1');
      banner.style.opacity = '0';
      banner.style.transform = 'translateY(10px)';
      setTimeout(function () {
        banner.style.display = 'none';
      }, 300);
    });
  })();
</script>
