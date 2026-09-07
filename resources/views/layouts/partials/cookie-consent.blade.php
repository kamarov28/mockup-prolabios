<!-- B2B Cookie Consent Notice (UU PDP & GDPR Compliance) -->
<div id="cookieConsentBanner" class="position-fixed" style="display: none; z-index: 9999; bottom: 24px; right: 24px; max-width: 440px; width: calc(100% - 48px); background: #FFFFFF; border: 2px solid #1E1E1E; box-shadow: 5px 5px 0 #1E1E1E; border-radius: 8px;">
  <div style="padding: 18px 20px;">

    <div class="d-flex align-items-center gap-2 mb-2">
      <i class="bi bi-shield-check" style="color: #A6171C; font-size: 1.25rem;"></i>
      <span class="fw-bold" style="font-family: var(--font-headline, 'Bricolage Grotesque', sans-serif); font-size: 0.95rem; color: #1E1E1E; letter-spacing: -0.01em;">Privasi &amp; Penggunaan Cookie</span>
    </div>

    <p class="mb-3" style="font-size: 0.84rem; line-height: 1.55; color: #5A5A5A;">
      Kami menggunakan cookie untuk memastikan navigasi katalog instrumen dan reagen lab berjalan optimal serta memproses permintaan penawaran Anda sesuai UU No. 27 Tahun 2022 (UU PDP).
    </p>

    <div class="d-flex align-items-center justify-content-between gap-3 pt-2" style="border-top: 1.5px solid rgba(30,30,30,0.12);">
      <a href="{{ route('privacy') }}" style="font-size: 0.82rem; color: #A6171C; font-weight: 600; text-decoration: underline;">
        Pelajari Selengkapnya
      </a>
      <button id="acceptCookieConsentBtn" type="button" class="nb-btn nb-btn-primary" style="font-size: 0.78rem; padding: 6px 14px; font-weight: 700;">
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
