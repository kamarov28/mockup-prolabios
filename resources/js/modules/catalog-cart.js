/**
 * Form Controls, Clipboard Helper, & AJAX Add-to-Cart
 */
export function initCatalogCart() {
  initContactForm();
  initCopyCatalogCode();
  initAjaxAddToCart();
}

function setCartBadgeCount(count) {
  document.querySelectorAll('#cart-badge-count, .nav-cart-badge').forEach(function (b) {
    b.textContent = count;
    b.classList.toggle('is-hidden', !(count > 0));
    if (count > 0) {
      b.classList.add('is-bump');
      setTimeout(function () { b.classList.remove('is-bump'); }, 250);
    }
  });
}

export function initContactForm() {
  const form = document.getElementById('contactForm') || document.querySelector('form.contact-form');
  if (!form) return;

  const urlParams = new URLSearchParams(window.location.search);
  const subjekParam = urlParams.get('subjek');
  if (subjekParam) {
    const subjekSelect = document.getElementById('subjek');
    if (subjekSelect) subjekSelect.value = subjekParam;
  }

  form.addEventListener('submit', async function (event) {
    event.preventDefault();
    const success = document.getElementById('formSuccess');
    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn) return;

    const requiredFields = form.querySelectorAll('[required]');
    let valid = true;
    requiredFields.forEach(function (field) {
      if (!field.value.trim()) {
        field.classList.add('has-error');
        valid = false;
      } else {
        field.classList.remove('has-error');
      }
    });
    if (!valid) return;

    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

    const recaptchaKey = form.dataset.recaptchaKey || '';
    if (recaptchaKey && typeof window.grecaptcha !== 'undefined') {
      try {
        const recaptchaToken = await window.grecaptcha.execute(recaptchaKey, { action: 'contact_submit' });
        const tokenInput = document.getElementById('g-recaptcha-response-contact') || form.querySelector('input[name="g-recaptcha-response"]');
        if (tokenInput) tokenInput.value = recaptchaToken;
      } catch (err) {
        console.error('reCAPTCHA error:', err);
        alert('Verifikasi keamanan gagal. Silakan muat ulang halaman dan coba lagi.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        return;
      }
    }

    const csrfInput = form.querySelector('input[name="_token"]');
    const csrfToken = csrfInput ? csrfInput.value : '';
    const submitUrl = form.action || '/kontak/submit';

    const formData = new FormData(form);
    fetch(submitUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          form.classList.add('is-hidden');
          if (success) {
            success.classList.remove('is-hidden');
            success.classList.add('is-visible');
            const msgEl = success.querySelector('p.profil-body-text');
            if (msgEl && data.message) msgEl.textContent = data.message;
            success.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        } else {
          alert(data.message || 'Gagal mengirim pesan.');
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      })
      .catch(error => {
        console.error('Error submitting form:', error);
        alert('Terjadi gangguan koneksi atau server. Silakan hubungi admin.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      });
  });
}

export function initCopyCatalogCode() {
  const codes = document.querySelectorAll('.product-cat-code');
  if (!codes.length) return;

  codes.forEach(function (el) {
    el.classList.add('is-copyable');
    el.setAttribute('title', 'Click to copy catalog code');
    el.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      const fullText = el.textContent.trim().replace(/^CAT\.\s*/i, '');
      if (!fullText) return;

      navigator.clipboard.writeText(fullText).then(function () {
        const originalText = el.textContent;
        el.innerHTML = '<i data-lucide="check" class="text-success me-1"></i> Copied!';
        el.classList.add('is-copied');
        if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();

        setTimeout(function () {
          el.textContent = originalText;
          el.classList.remove('is-copied');
        }, 1800);
      }).catch(function () {
        // Fallback
      });
    });
  });
}

export function initAjaxAddToCart() {
  document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!form || !form.action || !form.action.includes('/cart/add')) return;

    event.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn || submitBtn.disabled) return;

    const originalBtnHtml = submitBtn.innerHTML;
    const csrfInput = form.querySelector('input[name="_token"]');
    const csrfToken = csrfInput ? csrfInput.value : '';

    const formData = new FormData(form);

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menambahkan...';

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.success) {
          setCartBadgeCount(data.cartCount);

          // Success state: green surface + white icon (no green-on-ruby clash)
          submitBtn.classList.add('is-success');
          submitBtn.innerHTML = '<i data-lucide="check-circle-2" class="me-1"></i> Ditambahkan ke RFQ';
          if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();

          showToast(data.message || 'Ditambahkan ke keranjang penawaran!');

          setTimeout(function () {
            submitBtn.disabled = false;
            submitBtn.classList.remove('is-success');
            submitBtn.innerHTML = originalBtnHtml;
            if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
          }, 2000);
        } else {
          showToast(data.message || 'Gagal menambahkan produk.', 'warning');
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnHtml;
          if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
        }
      })
      .catch(function () {
        showToast('Terjadi kesalahan koneksi. Silakan coba lagi.', 'warning');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
        if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
      });
  });
}

export function showToast(message, type = 'success') {
  if (typeof window.Swal !== 'undefined') {
    const Toast = window.Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', window.Swal.stopTimer);
        toast.addEventListener('mouseleave', window.Swal.resumeTimer);
      }
    });
    Toast.fire({
      icon: type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'info'),
      title: message
    });
    return;
  }

  let container = document.getElementById('prolabios-toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'prolabios-toast-container';
    container.className = 'nb-toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = 'nb-toast-item' + (type === 'warning' ? ' nb-toast-item--warning' : '');

  const iconHtml = type === 'success'
    ? '<i data-lucide="check-circle-2" class="nb-toast-icon nb-toast-icon--success"></i>'
    : '<i data-lucide="alert-triangle" class="nb-toast-icon nb-toast-icon--warning"></i>';

  toast.innerHTML = iconHtml + '<span class="nb-toast-text">' + message + '</span>';
  container.appendChild(toast);
  if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();

  requestAnimationFrame(function () {
    toast.classList.add('is-visible');
  });

  setTimeout(function () {
    toast.classList.remove('is-visible');
    toast.classList.add('is-leaving');
    setTimeout(function () {
      if (toast.parentElement) toast.parentElement.removeChild(toast);
    }, 200);
  }, 3200);
}
