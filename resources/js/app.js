/**
 * PROLABIOS Mockup - Main JavaScript
 * Handles: Search, Sidebar Navigation, Contact Form, Mobile Menu, Slider UX
 */

/** True when user prefers less motion (OS or site toggle). */
function prefersReducedMotion() {
  return document.documentElement.classList.contains('no-motion')
    || window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

let gsapSafetyTimer = null;
let cachedProductCards = null;
function getProductCards() {
  if (!cachedProductCards) {
    cachedProductCards = Array.from(document.querySelectorAll('.product-card'));
  }
  return cachedProductCards;
}

function sanitizeCategorySlug(slug) {
  if (!slug) return '';
  return slug.replace(/[^a-zA-Z0-9\-_]/g, '');
}

function isProductPath(pathname) {
  const path = pathname || window.location.pathname;
  return path === '/produk' || path.endsWith('/produk.php') || path.includes('/produk');
}

/** Debounce high-frequency handlers (search typing, etc.). */
function debounce(fn, wait) {
  let timer = null;
  return function debounced() {
    const ctx = this;
    const args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      fn.apply(ctx, args);
    }, wait);
  };
}

/** rAF-throttled scroll listener (passive). */
function onScrollThrottled(handler) {
  let ticking = false;
  window.addEventListener('scroll', function () {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function () {
      handler();
      ticking = false;
    });
  }, { passive: true });
}

document.addEventListener('DOMContentLoaded', function () {
  function safeInit(name, fn) {
    if (typeof fn === 'function') {
      try {
        fn();
      } catch (e) {
        console.error('Error in ' + name + ':', e);
      }
    }
  }

  safeInit('initSearch', initSearch);
  safeInit('initSidebarNavigation', initSidebarNavigation);
  safeInit('initContactForm', initContactForm);
  safeInit('initMobileMenu', initMobileMenu);
  safeInit('initAnchorSmoothScroll', initAnchorSmoothScroll);
  safeInit('initHeaderScrollEffect', initHeaderScrollEffect);
  safeInit('initPrincipalSlider', initPrincipalSlider);
  safeInit('initBlogCategoryFilter', initBlogCategoryFilter);
  safeInit('initScrollAnimations', initScrollAnimations);
  safeInit('initGSAPAnimations', initGSAPAnimations);
  safeInit('initHeroBgSlideshow', initHeroBgSlideshow);
  safeInit('initSearchOverlay', initSearchOverlay);
  safeInit('initScrollToTop', initScrollToTop);
  safeInit('initMotionToggleSync', initMotionToggleSync);
  safeInit('initMarqueeVisibility', initMarqueeVisibility);
  safeInit('initDraggableMarquee', initDraggableMarquee);
  safeInit('initDraggableLogoMarquee', initDraggableLogoMarquee);

  if (isProductPath()) {
    try {
      applyPagination(1);
    } catch (e) {
      console.error('Error in applyPagination:', e);
    }
  }
});

function initSearch() {
  const searchForm = document.querySelector('.search-form');
  if (!searchForm) return;

  const searchInput = searchForm.querySelector('input');
  const searchBtn = searchForm.querySelector('button');
  if (!searchInput || !searchBtn) return;

  const onProductPage = isProductPath();

  function filterProducts(query) {
    const q = query.toLowerCase();
    const cards = getProductCards();
    let hasResults = false;

    cards.forEach(function (card) {
      const text = card.textContent.toLowerCase();
      if (!q || text.includes(q)) {
        card.classList.remove('hidden-by-filter');
        card.style.display = '';
        hasResults = true;
      } else {
        card.classList.add('hidden-by-filter');
        card.style.display = 'none';
      }
    });

    applyPagination(1);

    const header = document.querySelector('.main-content h2');
    const subheader = document.querySelector('.main-content .text-muted');
    if (header) header.textContent = query ? 'Hasil Pencarian' : 'Semua Produk';
    if (subheader) {
      subheader.textContent = query
        ? (hasResults ? 'Menampilkan hasil untuk "' + query + '"' : 'Tidak ada hasil untuk "' + query + '"')
        : 'Menampilkan semua produk kami';
    }
  }

  function doSearch() {
    const query = searchInput.value.trim();
    if (onProductPage) {
      const newUrl = query ? '/produk?q=' + encodeURIComponent(query) : '/produk';
      window.history.pushState({ path: newUrl }, '', newUrl);
      filterProducts(query);
    } else if (query.length > 0) {
      window.location.href = '/produk?q=' + encodeURIComponent(query);
    }
  }

  if (onProductPage) {
    const initialQuery = new URLSearchParams(window.location.search).get('q');
    if (initialQuery) {
      searchInput.value = initialQuery;
      filterProducts(initialQuery);
    }

    // Debounce live filter so typing does not re-layout the catalog every keystroke
    searchInput.addEventListener('input', debounce(function () {
      filterProducts(this.value.trim());
    }, 180));
  }

  searchBtn.addEventListener('click', function (e) {
    e.preventDefault();
    doSearch();
  });
  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      doSearch();
    }
  });
}

function initSidebarNavigation() {
  const sidebarLinks = document.querySelectorAll('.nav-list .nav-item a, .list-group a.list-group-item');
  if (!sidebarLinks.length) return;

  sidebarLinks.forEach(function (link) {
    link.addEventListener('click', function (event) {
      handleSidebarClick(event, link);
    });
  });

  applyInitialCategoryFromURL();
}

function handleSidebarClick(event, link) {
  const href = link.getAttribute('href') || '';
  const isInternal = href === '#' || href.startsWith('javascript:');

  if (isInternal) {
    event.preventDefault();
  }

  if (isProductPath() && href.indexOf('?') !== -1 && (href.indexOf('kategori=') !== -1 || href.indexOf('s=') !== -1)) {
    event.preventDefault();
    const url = new URL(href, window.location.origin);
    const cat = url.searchParams.get('kategori') || url.searchParams.get('s');

    const newUrl = new URL(window.location.href);
    if (cat) {
      newUrl.searchParams.set('kategori', cat);
      newUrl.searchParams.delete('s');
    }
    window.history.pushState({}, '', newUrl.toString());

    updateSidebarSelection(link, cat);
    filterProductsByCategory(cat);
  }
}

function getQueryParam(name) {
  return new URLSearchParams(window.location.search).get(name);
}

function setActiveSidebarForCategory(category) {
  if (!category) return;
  const safeCategory = sanitizeCategorySlug(category);
  document.querySelectorAll('.nav-list, .list-group').forEach(function (list) {
    list.querySelectorAll('.nav-item, a.list-group-item').forEach(function (item) {
      item.classList.remove('active');
    });

    const match = list.querySelector("a[href*='=" + safeCategory + "']");
    if (match) {
      const parent = match.closest('.nav-item');
      if (parent) {
        parent.classList.add('active');
      } else {
        match.classList.add('active');
      }
    }
  });
}

function updateSidebarSelection(link, category) {
  const parentList = link.closest('.nav-list, .list-group');
  if (parentList) {
    parentList.querySelectorAll('.nav-item, a.list-group-item').forEach(function (item) {
      item.classList.remove('active');
    });
  }

  const selectedItem = link.closest('.nav-item');
  if (selectedItem) {
    selectedItem.classList.add('active');
  } else {
    link.classList.add('active');
  }

  const selectedText = link.textContent.trim();
  const cleanText = selectedText.replace(/\s*\d+\s*$/, '').trim();

  setTextContent('.main-content h2, #category-title', cleanText);
  setTextContent('.main-content .text-muted, #category-subtitle', 'Menampilkan hasil untuk ' + cleanText);
}

function filterProductsByCategory(category) {
  const productCards = getProductCards();
  if (!productCards || !productCards.length) return;

  if (!category) {
    productCards.forEach(function (card) {
      card.classList.remove('hidden-by-filter');
      card.style.display = '';
    });
    applyPagination(1);
    return;
  }

  productCards.forEach(function (card) {
    const cat = card.getAttribute('data-category');
    if (!cat) {
      card.classList.remove('hidden-by-filter');
      card.style.display = '';
      return;
    }

    const cats = cat.split(/[ ,]+/).map(function (c) {
      return c.trim().toLowerCase();
    });
    
    if (cats.includes(category.toLowerCase())) {
      card.classList.remove('hidden-by-filter');
      card.style.display = '';
    } else {
      card.classList.add('hidden-by-filter');
      card.style.display = 'none';
    }
  });
  
  applyPagination(1);
}

function applyInitialCategoryFromURL() {
  const category = getQueryParam('kategori') || getQueryParam('s');
  if (!category) return;

  const safeCategory = sanitizeCategorySlug(category);
  setActiveSidebarForCategory(safeCategory);
  filterProductsByCategory(safeCategory);

  const match = document.querySelector(".nav-list a[href*='=" + safeCategory + "']");
  if (match) {
    const selectedText = match.textContent.trim();
    const cleanText = selectedText.replace(/\s*\d+\s*$/, '').trim();

    setTextContent('.main-content h2', cleanText);
    setTextContent('.main-content .text-muted', 'Menampilkan hasil untuk ' + cleanText);
  }
}

function setTextContent(selector, text) {
  const element = document.querySelector(selector);
  if (element) {
    element.textContent = text;
  }
}

function initContactForm() {
  if (window.handleContactForm) return;
  window.handleContactForm = function (e) {
    e.preventDefault();

    const form = document.getElementById('contactForm');
    const success = document.getElementById('formSuccess');
    if (!form || !success) return false;

    const requiredFields = form.querySelectorAll('[required]');
    let valid = true;

    requiredFields.forEach(function (field) {
      if (!field.value.trim()) {
        field.style.borderColor = 'var(--color-primary)';
        valid = false;
      } else {
        field.style.borderColor = 'var(--color-border)';
      }
    });

    if (valid) {
      form.style.display = 'none';
      success.style.display = 'block';
      success.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return false;
  };
}

function initMobileMenu() {
  const menuToggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('header nav');
  if (!menuToggle || !nav) return;

  menuToggle.addEventListener('click', function () {
    nav.classList.toggle('nav-open');
    this.classList.toggle('menu-active');
  });
}

function initAnchorSmoothScroll() {
  document.querySelectorAll('a[href*="#"]').forEach(function (link) {
    link.addEventListener('click', function (event) {
      const href = link.getAttribute('href');
      if (!href.startsWith('#') || href.length <= 1) return;

      const target = document.querySelector(href);
      if (target) {
        event.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
}

function initHeaderScrollEffect() {
  const elements = document.querySelectorAll('.header, .navbar');
  if (!elements.length) return;

  onScrollThrottled(function () {
    const isScrolled = window.scrollY > 50;
    elements.forEach(function (el) {
      el.classList.toggle('header-scrolled', isScrolled);
    });
  });
}

// Client-side pagination logic
let currentProductPage = 1;
const productsPerPage = 12;

function applyPagination(page = 1) {
  currentProductPage = page;
  const cards = getProductCards();
  const visibleCards = cards.filter(card => !card.classList.contains('hidden-by-filter'));
  
  visibleCards.forEach((card, index) => {
    const startIndex = (currentProductPage - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    if (index >= startIndex && index < endIndex) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });

  renderPaginationControls(visibleCards.length);
}

function renderPaginationControls(totalItems) {
  const container = document.getElementById('dynamic-pagination');
  if (!container) return;
  
  // Clear the container first to prevent duplication on repeated renders
  container.innerHTML = '';
  
  const totalPages = Math.ceil(totalItems / productsPerPage);
  if (totalPages <= 1) return;

  const nav = document.createElement('nav');
  const ul = document.createElement('ul');
  ul.className = 'pagination';

  for (let i = 1; i <= totalPages; i++) {
    const li = document.createElement('li');
    li.className = `page-item ${i === currentProductPage ? 'active' : ''}`;
    
    const link = document.createElement('a');
    link.className = 'page-link';
    link.href = '#';
    link.textContent = i;
    
    link.addEventListener('click', (e) => {
      e.preventDefault();
      applyPagination(i);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    li.appendChild(link);
    ul.appendChild(li);
  }
  
  nav.appendChild(ul);
  container.appendChild(nav);
}

function initPrincipalSlider() {
  const principalSlider = document.querySelector('.principal-slider');
  if (!principalSlider) return;

  const principalTrack = principalSlider.querySelector('.principal-track');
  const originalItems = Array.from(principalTrack.children);
  if (originalItems.length > 0) {
    originalItems.forEach(function (item) {
      principalTrack.appendChild(item.cloneNode(true));
    });
  }

  let isDragging = false;
  let startX = 0;
  let scrollStart = 0;
  let autoScrollId = null;
  let resumeTimeoutId = null;
  const scrollSpeed = 1;
  const resumeDelay = 900;

  function getHalfWidth() {
    return principalTrack.scrollWidth / 2;
  }

  let halfWidth = getHalfWidth();
  let resizeTimeout = null;
  window.addEventListener('resize', function () {
    if (resizeTimeout) clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function () {
      halfWidth = getHalfWidth();
    }, 150);
  });

  function normalizeScroll() {
    if (halfWidth === 0) return;
    if (principalSlider.scrollLeft >= halfWidth) {
      principalSlider.scrollLeft -= halfWidth;
    } else if (principalSlider.scrollLeft < 0) {
      principalSlider.scrollLeft += halfWidth;
    }
  }

  let isInView = true;

  function autoScrollFrame() {
    // Fully stop the rAF loop when motion is off or the slider is off-screen
    if (prefersReducedMotion() || !isInView) {
      autoScrollId = null;
      return;
    }
    if (!isDragging) {
      principalSlider.scrollLeft += scrollSpeed;
      normalizeScroll();
    }
    autoScrollId = requestAnimationFrame(autoScrollFrame);
  }

  function startAutoScroll() {
    if (autoScrollId !== null || prefersReducedMotion() || !isInView) return;
    autoScrollId = requestAnimationFrame(autoScrollFrame);
  }

  function stopAutoScroll() {
    if (autoScrollId !== null) {
      cancelAnimationFrame(autoScrollId);
      autoScrollId = null;
    }
    if (resumeTimeoutId !== null) {
      clearTimeout(resumeTimeoutId);
      resumeTimeoutId = null;
    }
  }

  function resumeAutoScroll() {
    if (autoScrollId !== null || isDragging) return;
    resumeTimeoutId = setTimeout(function () {
      resumeTimeoutId = null;
      if (!isDragging) {
        startAutoScroll();
      }
    }, resumeDelay);
  }

  function startDrag(event) {
    if (event.pointerType === 'mouse' && event.button !== 0) return;
    isDragging = true;
    principalSlider.classList.add('dragging');
    startX = event.clientX;
    scrollStart = principalSlider.scrollLeft;
    stopAutoScroll();
    principalSlider.setPointerCapture(event.pointerId);
  }

  function onDrag(event) {
    if (!isDragging) return;
    event.preventDefault();
    const x = event.clientX;
    const walk = (x - startX) * 1.2;
    principalSlider.scrollLeft = scrollStart - walk;
    normalizeScroll();
  }

  function endDrag(event) {
    if (!isDragging) return;
    isDragging = false;
    principalSlider.classList.remove('dragging');
    if (event && event.pointerId && principalSlider.hasPointerCapture(event.pointerId)) {
      principalSlider.releasePointerCapture(event.pointerId);
    }
    resumeAutoScroll();
  }

  principalSlider.addEventListener('pointerdown', startDrag);
  principalSlider.addEventListener('pointermove', onDrag);
  principalSlider.addEventListener('pointerup', endDrag);
  principalSlider.addEventListener('pointercancel', endDrag);
  principalSlider.addEventListener('lostpointercapture', endDrag);
  principalSlider.addEventListener('dragstart', function (event) {
    event.preventDefault();
  });

  principalSlider.querySelectorAll('img').forEach(function (img) {
    img.setAttribute('draggable', 'false');
    img.setAttribute('loading', 'lazy');
    img.setAttribute('decoding', 'async');
  });

  // Pause infinite scroll when slider is not visible (saves main-thread work)
  if ('IntersectionObserver' in window) {
    const sliderObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        isInView = entry.isIntersecting;
        if (isInView) {
          startAutoScroll();
        } else {
          stopAutoScroll();
        }
      });
    }, { rootMargin: '80px 0px', threshold: 0 });
    sliderObserver.observe(principalSlider);
  }

  // React to motion toggle without restarting the whole page
  document.addEventListener('prolabios:motion-change', function () {
    if (prefersReducedMotion()) {
      stopAutoScroll();
    } else {
      startAutoScroll();
    }
  });

  startAutoScroll();
}

function initBlogCategoryFilter() {
  const blogCards = document.querySelectorAll('.blog-card');
  if (!blogCards.length) return;

  const categoryLinks = document.querySelectorAll('.sidebar .nav-list .nav-item a');
  if (!categoryLinks.length) return;

  categoryLinks.forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();
      const raw = link.textContent.trim();
      const categoryName = raw.replace(/\s*\d+\s*$/, '').trim().toLowerCase();

      const parentList = link.closest('.nav-list');
      if (parentList) {
        parentList.querySelectorAll('.nav-item').forEach(function (item) {
          item.classList.remove('active');
        });
      }

      const selectedItem = link.closest('.nav-item');
      if (selectedItem) selectedItem.classList.add('active');

      blogCards.forEach(function (card) {
        const meta = card.querySelector('.blog-meta');
        if (!meta) return;

        const metaText = meta.textContent.toLowerCase();
        card.style.display = metaText.includes(categoryName) || categoryName === 'semua' ? '' : 'none';
      });
    });
  });
}


/**
 * Custom Scroll & Entrance Animation Engine
 * Uses IntersectionObserver API for maximum performance
 */
function initScrollAnimations() {
  const animateElements = document.querySelectorAll('.animate-on-scroll');
  if (!animateElements.length) return;

  // Instant visibility when motion is reduced — no observer work
  if (prefersReducedMotion()) {
    animateElements.forEach(function (el) {
      el.classList.add('is-visible');
    });
    return;
  }

  const animationObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    root: null,
    rootMargin: '0px 0px -8% 0px',
    threshold: 0.08
  });

  animateElements.forEach(function (el) {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight * 0.92 && rect.bottom >= 0) {
      el.classList.add('is-visible');
    } else {
      animationObserver.observe(el);
    }
  });
}

/**
 * Reveal hero / focus list without animation (reduced motion or GSAP failed)
 */
function revealHeroStatic() {
  const heroTextContainer = document.querySelector('.typo-hero-entrance');
  if (heroTextContainer) {
    heroTextContainer.style.opacity = '1';
    heroTextContainer.dataset.gsapDone = '1';
  }
  document.querySelectorAll(
    '.focus-section-pin .typo-index-item, .typo-news-section .typo-blog-card, .typo-section-head'
  ).forEach(function (item) {
    item.style.opacity = '1';
    item.style.transform = 'none';
    item.classList.add('is-visible');
  });
  document.querySelectorAll('.word-inner, .line-inner').forEach(function (el) {
    el.style.transform = 'none';
    el.style.opacity = '1';
  });
}

/**
 * Safeguard: Ensure typography helpers are only run on trusted, static elements.
 * MUST only be used on trusted, static content. Safe elements must either have the 
 * data-allow-split="true" attribute, match a known-safe ID, or match one of the 
 * whitelisted design-safe static classes.
 *
 * @param {HTMLElement} el The element to check
 * @returns {boolean} True if the element is safe to split/wrap
 */
function isSafeTypographyElement(el) {
  if (!el) return false;
  if (el.dataset.allowSplit === 'true') return true;
  
  // Whitelist of known safe static classes
  const safeClasses = [
    'typo-hero-title',
    'typo-lead',
    'typo-eyebrow',
    'typo-section-label',
    'typo-section-title',
    'typo-index-title',
    'typo-blog-card-title',
    'typo-blog-card-meta',
    'editorial-page-label',
    'editorial-page-title',
    'profil-section-title',
    'profil-section-label'
  ];
  
  // Whitelist of known safe IDs
  const safeIds = [
    'category-title'
  ];

  if (safeIds.indexOf(el.id) !== -1) return true;

  for (let i = 0; i < safeClasses.length; i++) {
    if (el.classList.contains(safeClasses[i])) {
      return true;
    }
  }

  // Also check if any parent up to body has data-allow-split="true"
  let parent = el.parentElement;
  while (parent && parent !== document.body) {
    if (parent.dataset.allowSplit === 'true') return true;
    parent = parent.parentElement;
  }

  return false;
}

/**
 * Typography split helpers — word masks for editorial clip-up reveals.
 * Preserves nested markup (e.g. .typo-outline) while wrapping text nodes.
 * Safeguarded: Only runs on whitelisted, trusted static content.
 */
function typoSplitWords(el) {
  if (!el) return [];
  if (!isSafeTypographyElement(el)) {
    console.warn('typoSplitWords skipped: Element is not whitelisted for typography splitting to prevent potential DOM injection on dynamic/user-controlled content.', el);
    return [];
  }
  if (el.dataset.splitWords === '1') {
    return el.querySelectorAll('.word-inner');
  }

  // Get original text for screen readers
  const originalText = (el.textContent || '').replace(/\s+/g, ' ').trim();

  function wrapTextNode(textNode) {
    const text = textNode.textContent;
    if (!text || !text.trim()) return;

    const frag = document.createDocumentFragment();
    // Keep whitespace as real text so wrapping still works
    text.split(/(\s+)/).forEach(function (part) {
      if (!part) return;
      if (/^\s+$/.test(part)) {
        frag.appendChild(document.createTextNode(part));
        return;
      }
      const mask = document.createElement('span');
      mask.className = 'word-mask';
      mask.setAttribute('aria-hidden', 'true');
      const inner = document.createElement('span');
      inner.className = 'word-inner';
      inner.textContent = part;
      mask.appendChild(inner);
      frag.appendChild(mask);
    });
    textNode.parentNode.replaceChild(frag, textNode);
  }

  function walk(node) {
    if (node.nodeType === Node.TEXT_NODE) {
      wrapTextNode(node);
      return;
    }
    if (node.nodeType !== Node.ELEMENT_NODE) return;
    
    // Only traverse into known safe text formatting tags to prevent layout/behavior breakage
    const tagName = node.tagName.toUpperCase();
    if (['SPAN', 'STRONG', 'EM', 'B', 'I', 'U'].indexOf(tagName) === -1) {
      return;
    }
    
    // Snapshot children — DOM mutates as we wrap
    Array.prototype.slice.call(node.childNodes).forEach(walk);
  }

  Array.prototype.slice.call(el.childNodes).forEach(walk);

  // Find target element to prepend visually hidden span.
  // If container is or contains an anchor/button, prepend inside it so the link has a discernible name.
  let targetEl = el;
  if (el.tagName === 'A' || el.tagName === 'BUTTON') {
    targetEl = el;
  } else {
    const link = el.querySelector('a, button');
    if (link) {
      targetEl = link;
    }
  }

  const srSpan = document.createElement('span');
  srSpan.className = 'visually-hidden';
  srSpan.textContent = originalText;
  targetEl.insertBefore(srSpan, targetEl.firstChild);

  // Remove aria-label if present to avoid accessibility warnings on generic elements
  el.removeAttribute('aria-label');

  el.dataset.splitWords = '1';
  el.classList.add('is-split');
  return el.querySelectorAll('.word-inner');
}

/**
 * Wrap block-level text content in a single line mask (good for labels / meta).
 * Safeguarded: Only runs on whitelisted, trusted static content.
 */
function typoWrapLine(el) {
  if (!el) return null;
  if (!isSafeTypographyElement(el)) {
    console.warn('typoWrapLine skipped: Element is not whitelisted for typography wrapping to prevent potential DOM injection on dynamic/user-controlled content.', el);
    return null;
  }
  if (el.dataset.splitLine === '1') {
    return el.querySelector('.line-inner');
  }
  
  // Skip interactive/unsafe elements and elements containing them
  const unsafeTags = ['A', 'BUTTON', 'INPUT', 'TEXTAREA', 'SELECT', 'SVG'];
  if (unsafeTags.indexOf(el.tagName.toUpperCase()) !== -1 || el.querySelector('a, button, input, textarea, select, svg')) {
    return null;
  }

  const originalText = (el.textContent || '').replace(/\s+/g, ' ').trim();
  const inner = document.createElement('span');
  inner.className = 'line-inner';
  while (el.firstChild) {
    inner.appendChild(el.firstChild);
  }
  const mask = document.createElement('span');
  mask.className = 'line-mask';
  mask.setAttribute('aria-hidden', 'true');
  mask.appendChild(inner);

  // Find target element to prepend visually hidden span
  let targetEl = el;
  if (el.tagName === 'A' || el.tagName === 'BUTTON') {
    targetEl = el;
  } else {
    const link = el.querySelector('a, button');
    if (link) {
      targetEl = link;
    }
  }

  const srSpan = document.createElement('span');
  srSpan.className = 'visually-hidden';
  srSpan.textContent = originalText;

  targetEl.appendChild(srSpan);
  el.appendChild(mask);

  // Remove aria-label if present to avoid accessibility warnings on generic elements
  el.removeAttribute('aria-label');

  el.dataset.splitLine = '1';
  el.classList.add('is-split');
  return inner;
}

/**
 * Editorial typography motion engine (homepage).
 * Word clip-ups, letter-spacing settles, scroll-linked reveals — not plain fade/slide.
 */
function initGSAPAnimations() {
  if (typeof window !== 'undefined' && 'history' in window && 'scrollRestoration' in window.history) {
    window.history.scrollRestoration = 'manual';
  }
  const heroTextContainer = document.querySelector('.typo-hero-entrance');
  const pinSection = document.querySelector('.focus-section-pin');
  const newsSection = document.querySelector('.typo-news-section');
  const productsSection = document.querySelector('.typo-products-section');
  const activeHeroImg = document.querySelector('.hero-bg-slide.active');
  const pageHeader = document.querySelector('.editorial-page-header');
  const profilHeroImg = document.querySelector('.profil-hero-img img');
  const catalogSection = document.getElementById('catalog-section');
  const sektorSection = document.getElementById('sektor-nav');
  const layananSection = document.getElementById('service-nav');
  const infoContainer = document.querySelector('.blog-card, .profil-body-text');
  const kontakContainer = document.getElementById('contactForm');
  const hasTargets = heroTextContainer || pinSection || newsSection || productsSection || activeHeroImg || pageHeader || profilHeroImg || catalogSection || sektorSection || layananSection || infoContainer || kontakContainer;

  if (!hasTargets) return;
  if (heroTextContainer && heroTextContainer.dataset.gsapDone === '1') return;

  if (prefersReducedMotion()) {
    revealHeroStatic();
    return;
  }

  if (typeof gsap === 'undefined') {
    if (!gsapSafetyTimer) {
      gsapSafetyTimer = setTimeout(function () {
        if (!document.querySelector('.typo-hero-entrance[data-gsap-done="1"]')) {
          revealHeroStatic();
        }
      }, 1800);
    }
    return;
  }

  if (gsapSafetyTimer) {
    clearTimeout(gsapSafetyTimer);
    gsapSafetyTimer = null;
  }

  if (typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    ScrollTrigger.clearScrollMemory();
  }

  // Shared editorial easing — snappier than power2, reads as "designed" motion
  const EASE_EXPO = 'expo.out';
  const EASE_POWER = 'power3.out';

  // ── Hero: typography-first entrance timeline ─────────────────────────────
  if (heroTextContainer) {
    const eyebrow = heroTextContainer.querySelector('.typo-eyebrow');
    const title = heroTextContainer.querySelector('.typo-hero-title');
    const lead = heroTextContainer.querySelector('.typo-lead');
    const ctas = heroTextContainer.querySelectorAll('.typo-btn-link');
    const ctaWrap = heroTextContainer.querySelector('.typo-hero-ctas');

    const titleWords = title ? typoSplitWords(title) : [];
    const leadWords = lead ? typoSplitWords(lead) : [];
    const eyebrowInner = eyebrow ? typoWrapLine(eyebrow) : null;

    gsap.set(heroTextContainer, { opacity: 1 });
    if (titleWords.length) gsap.set(titleWords, { yPercent: 115, rotate: 0.001 });
    if (leadWords.length) gsap.set(leadWords, { yPercent: 90, opacity: 0 });
    if (eyebrowInner) gsap.set(eyebrowInner, { yPercent: 110 });
    if (eyebrow) gsap.set(eyebrow, { letterSpacing: '0.55em', opacity: 0.35 });
    if (ctas.length) gsap.set(ctas, { y: 28, opacity: 0 });
    if (ctaWrap) gsap.set(ctaWrap, { opacity: 1 });

    const heroTl = gsap.timeline({
      defaults: { ease: EASE_EXPO },
      onComplete: function () {
        heroTextContainer.dataset.gsapDone = '1';
        // Drop will-change after entrance to free GPU layers
        gsap.set([titleWords, leadWords, eyebrowInner, ctas], { clearProps: 'willChange' });
      }
    });

    // Background: slow lens settle (secondary to type)
    if (activeHeroImg) {
      gsap.set(activeHeroImg, { scale: 1.12 });
      heroTl.to(activeHeroImg, {
        scale: 1,
        duration: 2.2,
        ease: 'power2.out',
        clearProps: 'scale,transform'
      }, 0);
    }

    if (eyebrowInner) {
      heroTl.to(eyebrowInner, { yPercent: 0, duration: 0.95 }, 0.08);
    }
    if (eyebrow) {
      heroTl.to(eyebrow, {
        letterSpacing: '0.18em',
        opacity: 1,
        duration: 1.15,
        ease: EASE_POWER
      }, 0.08);
    }

    // Title words clip up — signature editorial move
    if (titleWords.length) {
      heroTl.to(titleWords, {
        yPercent: 0,
        duration: 1.2,
        stagger: 0.055,
        ease: EASE_EXPO
      }, 0.18);
    }

    // Outline word gets a subtle delayed opacity pop if present
    const outlineInners = title
      ? title.querySelectorAll('.typo-outline .word-inner, .typo-outline.word-inner')
      : [];
    if (outlineInners.length) {
      // outline already moves with title; add stroke contrast settle
      heroTl.fromTo(
        title.querySelectorAll('.typo-outline'),
        { opacity: 0.85 },
        { opacity: 1, duration: 0.8, ease: EASE_POWER },
        0.55
      );
    }

    if (leadWords.length) {
      heroTl.to(leadWords, {
        yPercent: 0,
        opacity: 1,
        duration: 0.85,
        stagger: 0.018,
        ease: EASE_POWER
      }, 0.48);
    }

    if (ctas.length) {
      heroTl.to(ctas, {
        y: 0,
        opacity: 1,
        duration: 0.75,
        stagger: 0.1,
        ease: EASE_EXPO
      }, 0.68);

      // Underline draw via scaleX on ::after is hard; animate border via custom property
      ctas.forEach(function (btn, i) {
        heroTl.fromTo(btn, {
          borderBottomColor: 'rgba(255,73,80,0)'
        }, {
          borderBottomColor: '',
          duration: 0.5,
          ease: 'power2.out'
        }, 0.78 + i * 0.1);
      });
    }

    // Scroll parallax on hero type stack (subtle, typography-led depth)
  }

  // Marquee stays pure CSS (constant speed). Do NOT mutate animation-duration
  // on scroll — that restarts/seeks the keyframes and looks like maju-mundur.

  // ── Sektor Fokus: per-row typography reveals ─────────────────────────────
  if (pinSection && typeof ScrollTrigger !== 'undefined') {
    const head = pinSection.querySelector('.typo-section-head');
    if (head) {
      const label = head.querySelector('.typo-section-label');
      const heading = head.querySelector('.typo-section-title');
      const sub = head.querySelector('.typo-section-sub');
      const labelInner = label ? typoWrapLine(label) : null;
      const headingWords = heading ? typoSplitWords(heading) : [];

      if (labelInner) gsap.set(labelInner, { yPercent: 110 });
      if (label) gsap.set(label, { letterSpacing: '0.45em', opacity: 0.4 });
      if (headingWords.length) gsap.set(headingWords, { yPercent: 110 });
      if (sub) gsap.set(sub, { y: 18, opacity: 0 });
      gsap.set(head, { opacity: 1 });

      const headTl = gsap.timeline({
        scrollTrigger: {
          trigger: head,
          start: 'top 82%',
          once: true
        }
      });
      if (labelInner) headTl.to(labelInner, { yPercent: 0, duration: 0.85, ease: EASE_EXPO }, 0);
      if (label) {
        headTl.to(label, {
          letterSpacing: '0.18em',
          opacity: 1,
          duration: 1,
          ease: EASE_POWER
        }, 0);
      }
      if (headingWords.length) {
        headTl.to(headingWords, {
          yPercent: 0,
          duration: 1.05,
          stagger: 0.05,
          ease: EASE_EXPO
        }, 0.08);
      }
      if (sub) headTl.to(sub, { y: 0, opacity: 1, duration: 0.7, ease: EASE_POWER }, 0.28);
    }

    pinSection.querySelectorAll('.typo-index-item').forEach(function (item, index) {
      const num = item.querySelector('.typo-index-number');
      const title = item.querySelector('.typo-index-title');
      const desc = item.querySelector('.typo-index-desc');
      const link = item.querySelector('.typo-index-link');
      const titleWords = title ? typoSplitWords(title) : [];

      gsap.set(item, { opacity: 1 });
      if (num) gsap.set(num, { x: -36, opacity: 0, letterSpacing: '0.2em' });
      if (titleWords.length) gsap.set(titleWords, { yPercent: 115 });
      if (desc) gsap.set(desc, { y: 22, opacity: 0 });
      if (link) gsap.set(link, { y: 12, opacity: 0 });

      const rowTl = gsap.timeline({
        scrollTrigger: {
          trigger: item,
          start: 'top 88%',
          once: true
        },
        delay: Math.min(index * 0.04, 0.16)
      });

      if (num) {
        rowTl.to(num, {
          x: 0,
          opacity: 1,
          letterSpacing: '0.02em',
          duration: 0.9,
          ease: EASE_EXPO
        }, 0);
      }
      if (titleWords.length) {
        rowTl.to(titleWords, {
          yPercent: 0,
          duration: 1,
          stagger: 0.045,
          ease: EASE_EXPO
        }, 0.06);
      }
      if (desc) {
        rowTl.to(desc, { y: 0, opacity: 1, duration: 0.75, ease: EASE_POWER }, 0.28);
      }
      if (link) {
        rowTl.to(link, { y: 0, opacity: 1, duration: 0.55, ease: EASE_EXPO }, 0.38);
      }
    });
  }

  // ── Products: premium grid reveals ──────────────────────────────────────
  if (productsSection && typeof ScrollTrigger !== 'undefined') {
    const head = productsSection.querySelector('.typo-section-head');
    if (head) {
      const label = head.querySelector('.typo-section-label');
      const heading = head.querySelector('.typo-section-title');
      const sub = head.querySelector('.typo-section-sub');
      const viewAll = head.querySelector('.typo-btn-link');
      const labelInner = label ? typoWrapLine(label) : null;
      const headingWords = heading ? typoSplitWords(heading) : [];

      if (labelInner) gsap.set(labelInner, { yPercent: 110 });
      if (headingWords.length) gsap.set(headingWords, { yPercent: 110 });
      if (sub) gsap.set(sub, { y: 16, opacity: 0 });
      if (viewAll) gsap.set(viewAll, { y: 16, opacity: 0 });
      gsap.set(head, { opacity: 1 });

      const pTl = gsap.timeline({
        scrollTrigger: { trigger: head, start: 'top 85%', once: true }
      });
      if (labelInner) pTl.to(labelInner, { yPercent: 0, duration: 0.8, ease: EASE_EXPO }, 0);
      if (headingWords.length) {
        pTl.to(headingWords, {
          yPercent: 0,
          duration: 1,
          stagger: 0.05,
          ease: EASE_EXPO
        }, 0.05);
      }
      if (sub) pTl.to(sub, { y: 0, opacity: 1, duration: 0.65, ease: EASE_POWER }, 0.25);
      if (viewAll) pTl.to(viewAll, { y: 0, opacity: 1, duration: 0.65, ease: EASE_POWER }, 0.25);
    }

    productsSection.querySelectorAll('.product-card-premium').forEach(function (card, i) {
      const img = card.querySelector('.img-wrap img');
      
      gsap.set(card, { y: 35, opacity: 0 });
      if (img) gsap.set(img, { scale: 1.08 });
      
      const cTl = gsap.timeline({
        scrollTrigger: {
          trigger: card,
          start: 'top 92%',
          once: true
        },
        delay: (i % 4) * 0.08
      });
      
      cTl.to(card, {
        y: 0,
        opacity: 1,
        duration: 1,
        ease: EASE_EXPO
      }, 0);
      
      if (img) {
        cTl.to(img, {
          scale: 1,
          duration: 1.2,
          ease: EASE_POWER
        }, 0);
      }
    });
  }

  // ── News: editorial column type reveals ──────────────────────────────────
  if (newsSection && typeof ScrollTrigger !== 'undefined') {
    const head = newsSection.querySelector('.typo-section-head');
    if (head) {
      const label = head.querySelector('.typo-section-label');
      const heading = head.querySelector('.typo-section-title');
      const sub = head.querySelector('.typo-section-sub');
      const labelInner = label ? typoWrapLine(label) : null;
      const headingWords = heading ? typoSplitWords(heading) : [];

      if (labelInner) gsap.set(labelInner, { yPercent: 110 });
      if (headingWords.length) gsap.set(headingWords, { yPercent: 110 });
      if (sub) gsap.set(sub, { y: 16, opacity: 0 });
      gsap.set(head, { opacity: 1 });

      const nTl = gsap.timeline({
        scrollTrigger: { trigger: head, start: 'top 85%', once: true }
      });
      if (labelInner) nTl.to(labelInner, { yPercent: 0, duration: 0.8, ease: EASE_EXPO }, 0);
      if (headingWords.length) {
        nTl.to(headingWords, {
          yPercent: 0,
          duration: 1,
          stagger: 0.05,
          ease: EASE_EXPO
        }, 0.05);
      }
      if (sub) nTl.to(sub, { y: 0, opacity: 1, duration: 0.65, ease: EASE_POWER }, 0.25);
    }

    newsSection.querySelectorAll('.typo-blog-card').forEach(function (card, i) {
      const meta = card.querySelector('.typo-blog-card-meta');
      const title = card.querySelector('.typo-blog-card-title');
      const desc = card.querySelector('.typo-blog-card-desc');
      const titleWords = title ? typoSplitWords(title) : [];
      const metaInner = meta ? typoWrapLine(meta) : null;

      gsap.set(card, { opacity: 1 });
      if (metaInner) gsap.set(metaInner, { yPercent: 100 });
      if (titleWords.length) gsap.set(titleWords, { yPercent: 110 });
      if (desc) gsap.set(desc, { y: 16, opacity: 0 });

      const cTl = gsap.timeline({
        scrollTrigger: {
          trigger: card,
          start: 'top 90%',
          once: true
        },
        delay: i * 0.08
      });
      if (metaInner) cTl.to(metaInner, { yPercent: 0, duration: 0.7, ease: EASE_EXPO }, 0);
      if (titleWords.length) {
        cTl.to(titleWords, {
          yPercent: 0,
          duration: 0.95,
          stagger: 0.035,
          ease: EASE_EXPO
        }, 0.05);
      }
      if (desc) cTl.to(desc, { y: 0, opacity: 1, duration: 0.65, ease: EASE_POWER }, 0.22);
    });
  }

  // ── Common Page Header Animations ──────────────────────────────────────────
  if (pageHeader) {
    const label = pageHeader.querySelector('.editorial-page-label');
    const title = pageHeader.querySelector('.editorial-page-title');
    const subtitle = pageHeader.querySelector('.editorial-page-subtitle');
    const labelInner = label ? typoWrapLine(label) : null;
    const titleWords = title ? typoSplitWords(title) : [];

    gsap.set(pageHeader, { opacity: 1 });
    if (labelInner) gsap.set(labelInner, { yPercent: 110 });
    if (titleWords.length) gsap.set(titleWords, { yPercent: 110 });
    if (subtitle) gsap.set(subtitle, { y: 15, opacity: 0 });

    const headerTl = gsap.timeline({
      defaults: { ease: EASE_EXPO }
    });
    if (labelInner) headerTl.to(labelInner, { yPercent: 0, duration: 0.8 }, 0.05);
    if (titleWords.length) {
      headerTl.to(titleWords, {
        yPercent: 0,
        duration: 1,
        stagger: 0.04,
        ease: EASE_EXPO
      }, 0.1);
    }
    if (subtitle) headerTl.to(subtitle, { y: 0, opacity: 1, duration: 0.7, ease: EASE_POWER }, 0.35);
  }

  // ── Profil Page Animations ──────────────────────────────────────────────────
  if (profilHeroImg && typeof ScrollTrigger !== 'undefined') {
    gsap.fromTo(profilHeroImg, 
      { scale: 1.15, transformOrigin: 'center center' },
      { 
        scale: 1, 
        duration: 1.6, 
        ease: 'power2.out',
        scrollTrigger: {
          trigger: '.profil-hero-img',
          start: 'top 85%',
          once: true
        }
      }
    );
  }

  document.querySelectorAll('.profil-section-title').forEach(function (title) {
    const parent = title.parentNode;
    const label = parent.querySelector('.profil-section-label');
    const labelInner = label ? typoWrapLine(label) : null;
    const titleWords = typoSplitWords(title);
    const bodyText = parent.querySelectorAll('.profil-body-text');

    if (labelInner) gsap.set(labelInner, { yPercent: 110 });
    if (titleWords.length) gsap.set(titleWords, { yPercent: 110 });
    if (bodyText.length) gsap.set(bodyText, { y: 15, opacity: 0 });

    const sectionTl = gsap.timeline({
      scrollTrigger: {
        trigger: title,
        start: 'top 85%',
        once: true
      }
    });

    if (labelInner) sectionTl.to(labelInner, { yPercent: 0, duration: 0.8, ease: EASE_EXPO }, 0);
    if (titleWords.length) {
      sectionTl.to(titleWords, {
        yPercent: 0,
        duration: 1,
        stagger: 0.04,
        ease: EASE_EXPO
      }, 0.06);
    }
    if (bodyText.length) {
      sectionTl.to(bodyText, { y: 0, opacity: 1, duration: 0.7, stagger: 0.1, ease: EASE_POWER }, 0.28);
    }
  });

  const vmCards = document.querySelectorAll('.profil-vm-card');
  if (vmCards.length && typeof ScrollTrigger !== 'undefined') {
    gsap.fromTo(vmCards, 
      { y: 30, opacity: 0 },
      {
        y: 0,
        opacity: 1,
        duration: 0.85,
        stagger: 0.15,
        ease: EASE_POWER,
        scrollTrigger: {
          trigger: '#visi-misi',
          start: 'top 80%',
          once: true
        }
      }
    );
  }

  const valCards = document.querySelectorAll('.profil-value-card');
  if (valCards.length && typeof ScrollTrigger !== 'undefined') {
    valCards.forEach(function (card, idx) {
      const letter = card.querySelector('.profil-value-letter');
      const valTitle = card.querySelector('.profil-value-title');
      const valDesc = card.querySelector('.profil-body-text');

      if (letter) gsap.set(letter, { scale: 0.4, opacity: 0 });
      if (valTitle) gsap.set(valTitle, { y: 12, opacity: 0 });
      if (valDesc) gsap.set(valDesc, { y: 12, opacity: 0 });

      const cardTl = gsap.timeline({
        scrollTrigger: {
          trigger: card,
          start: 'top 85%',
          once: true
        },
        delay: idx * 0.1
      });

      if (letter) cardTl.to(letter, { scale: 1, opacity: 1, duration: 0.8, ease: 'back.out(1.7)' }, 0);
      if (valTitle) cardTl.to(valTitle, { y: 0, opacity: 1, duration: 0.6, ease: EASE_POWER }, 0.15);
      if (valDesc) cardTl.to(valDesc, { y: 0, opacity: 1, duration: 0.6, ease: EASE_POWER }, 0.25);
    });
  }

  const profilSidebar = document.querySelector('.col-lg-3.col-md-4.order-md-1');
  if (profilSidebar && document.querySelector('.profil-sidebar-title') && typeof ScrollTrigger !== 'undefined') {
    const sidebarElements = profilSidebar.querySelectorAll('.profil-sidebar-title, p, .profil-social-link, .profil-cta-box');
    gsap.fromTo(sidebarElements,
      { y: 15, opacity: 0 },
      {
        y: 0,
        opacity: 1,
        duration: 0.8,
        stagger: 0.08,
        ease: EASE_POWER,
        scrollTrigger: {
          trigger: profilSidebar,
          start: 'top 85%',
          once: true
        }
      }
    );
  }

  // ── Produk Page Animations ──────────────────────────────────────────────────
  if (catalogSection && typeof ScrollTrigger !== 'undefined') {
    const categoryLinks = catalogSection.querySelectorAll('.layanan-sidebar-nav a, .profil-cta-box');
    if (categoryLinks.length) {
      const isFiltering = window.location.search && (window.location.search.includes('category') || window.location.search.includes('q=') || window.location.search.includes('s=') || window.location.search.includes('page='));
      if (isFiltering) {
        gsap.set(categoryLinks, { x: 0, opacity: 1 });
      } else {
        gsap.fromTo(categoryLinks,
          { x: -15, opacity: 0 },
          {
            x: 0,
            opacity: 1,
            duration: 0.5,
            stagger: 0.03,
            ease: EASE_POWER,
            scrollTrigger: {
              trigger: '#produk-sidebar',
              start: 'top 85%',
              once: true
            }
          }
        );
      }
    }

    const catTitle = document.getElementById('category-title');
    const catSubtitle = document.getElementById('category-subtitle');
    const searchWrap = document.querySelector('.produk-search-wrap');
    
    if (catTitle) {
      const titleWords = typoSplitWords(catTitle);
      gsap.set(titleWords, { yPercent: 110 });
      gsap.to(titleWords, {
        yPercent: 0,
        duration: 0.95,
        stagger: 0.04,
        ease: EASE_EXPO
      });
    }
    if (catSubtitle) {
      gsap.fromTo(catSubtitle, { y: 12, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, ease: EASE_POWER, delay: 0.15 });
    }
    if (searchWrap) {
      gsap.fromTo(searchWrap, { x: 20, opacity: 0 }, { x: 0, opacity: 1, duration: 0.7, ease: EASE_POWER });
    }

    const productCards = document.querySelectorAll('#product-container .product-card');
    if (productCards.length) {
      productCards.forEach(function (card) {
        const inner = card.querySelector('.product-card-premium');
        const img = card.querySelector('.img-wrap img');
        
        gsap.set(inner, { y: 30, opacity: 0 });
        if (img) gsap.set(img, { scale: 1.15 });

        const cardTl = gsap.timeline({
          scrollTrigger: {
            trigger: card,
            start: 'top 88%',
            once: true
          }
        });

        cardTl.to(inner, { y: 0, opacity: 1, duration: 0.8, ease: EASE_POWER }, 0);
        if (img) cardTl.to(img, { scale: 1, duration: 1.2, ease: 'power2.out' }, 0);
      });
    }
  }

  // ── Sektor Page Animations ──────────────────────────────────────────────────
  if (sektorSection && typeof ScrollTrigger !== 'undefined') {
    if (sektorSection.dataset.gsapSidebarDone !== '1') {
      const categoryLinks = sektorSection.querySelectorAll('.layanan-sidebar-nav a, .profil-cta-box');
      if (categoryLinks.length) {
        gsap.fromTo(categoryLinks,
          { x: -15, opacity: 0 },
          {
            x: 0,
            opacity: 1,
            duration: 0.7,
            stagger: 0.04,
            ease: EASE_POWER
          }
        );
        sektorSection.dataset.gsapSidebarDone = '1';
      }
    }

    const mainCol = sektorSection.querySelector('.col-lg-9');
    if (mainCol) {
      const img = mainCol.querySelector('.profil-hero-img img');
      const title = mainCol.querySelector('.profil-section-title');
      const bodyTexts = mainCol.querySelectorAll('.profil-body-text');
      const table = mainCol.querySelector('.table-responsive');

      if (img) gsap.set(img, { scale: 1.15 });
      const titleWords = title ? typoSplitWords(title) : [];
      if (titleWords.length) gsap.set(titleWords, { yPercent: 110 });
      if (bodyTexts.length) gsap.set(bodyTexts, { y: 15, opacity: 0 });
      if (table) gsap.set(table, { y: 20, opacity: 0 });

      const contentTl = gsap.timeline({
        scrollTrigger: {
          trigger: mainCol,
          start: 'top 85%',
          once: true
        }
      });

      if (img) contentTl.to(img, { scale: 1, duration: 1.4, ease: 'power2.out' }, 0);
      if (titleWords.length) {
        contentTl.to(titleWords, {
          yPercent: 0,
          duration: 0.95,
          stagger: 0.04,
          ease: EASE_EXPO
        }, 0.05);
      }
      if (bodyTexts.length) {
        contentTl.to(bodyTexts, { y: 0, opacity: 1, duration: 0.7, stagger: 0.1, ease: EASE_POWER }, 0.25);
      }
      if (table) {
        contentTl.to(table, { y: 0, opacity: 1, duration: 0.8, ease: EASE_POWER }, 0.4);
      }
    }
  }

  // ── Layanan Page Animations ─────────────────────────────────────────────────
  if (layananSection && typeof ScrollTrigger !== 'undefined') {
    if (layananSection.dataset.gsapSidebarDone !== '1') {
      const categoryLinks = layananSection.querySelectorAll('.layanan-sidebar-nav a, .profil-cta-box');
      if (categoryLinks.length) {
        gsap.fromTo(categoryLinks,
          { x: -15, opacity: 0 },
          {
            x: 0,
            opacity: 1,
            duration: 0.7,
            stagger: 0.04,
            ease: EASE_POWER
          }
        );
        layananSection.dataset.gsapSidebarDone = '1';
      }
    }

    const activeBlock = layananSection.querySelector('.service-content-block:not(.d-none)');
    if (activeBlock) {
      const img = activeBlock.querySelector('.profil-hero-img img');
      const title = activeBlock.querySelector('.profil-section-title');
      const label = activeBlock.querySelector('.profil-section-label');
      const labelInner = label ? typoWrapLine(label) : null;
      const bodyTexts = activeBlock.querySelectorAll('.profil-body-text');
      const featureCards = activeBlock.querySelectorAll('.layanan-feature-card');
      const ctaStrip = activeBlock.querySelector('.layanan-cta-strip');

      if (img) gsap.set(img, { scale: 1.15 });
      if (labelInner) gsap.set(labelInner, { yPercent: 110 });
      const titleWords = title ? typoSplitWords(title) : [];
      if (titleWords.length) gsap.set(titleWords, { yPercent: 110 });
      if (bodyTexts.length) gsap.set(bodyTexts, { y: 15, opacity: 0 });
      if (featureCards.length) gsap.set(featureCards, { y: 25, opacity: 0 });
      if (ctaStrip) gsap.set(ctaStrip, { y: 25, opacity: 0 });

      const blockTl = gsap.timeline({
        scrollTrigger: {
          trigger: activeBlock,
          start: 'top 85%',
          once: true
        }
      });

      if (img) blockTl.to(img, { scale: 1, duration: 1.4, ease: 'power2.out' }, 0);
      if (labelInner) blockTl.to(labelInner, { yPercent: 0, duration: 0.8, ease: EASE_EXPO }, 0);
      if (titleWords.length) {
        blockTl.to(titleWords, {
          yPercent: 0,
          duration: 0.95,
          stagger: 0.04,
          ease: EASE_EXPO
        }, 0.05);
      }
      if (bodyTexts.length) {
        blockTl.to(bodyTexts, { y: 0, opacity: 1, duration: 0.7, stagger: 0.1, ease: EASE_POWER }, 0.25);
      }
      if (featureCards.length) {
        blockTl.to(featureCards, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: EASE_POWER }, 0.35);
      }
      if (ctaStrip) {
        blockTl.to(ctaStrip, { y: 0, opacity: 1, duration: 0.8, ease: EASE_POWER }, 0.45);
      }
    }
  }

  // ── Informasi Page Animations ───────────────────────────────────────────────
  if (infoContainer && typeof ScrollTrigger !== 'undefined') {
    const blogCards = document.querySelectorAll('.col-lg-8 .blog-card');
    if (blogCards.length) {
      blogCards.forEach(function (card, idx) {
        const inner = card;
        const img = card.querySelector('.blog-card-img-wrap img');
        
        gsap.set(inner, { y: 30, opacity: 0 });
        if (img) gsap.set(img, { scale: 1.15 });

        const cardTl = gsap.timeline({
          scrollTrigger: {
            trigger: card,
            start: 'top 90%',
            once: true
          },
          delay: idx * 0.05
        });

        cardTl.to(inner, { y: 0, opacity: 1, duration: 0.8, ease: EASE_POWER }, 0);
        if (img) cardTl.to(img, { scale: 1, duration: 1.2, ease: 'power2.out' }, 0);
      });
    }

    const detailHeroImg = document.querySelector('.col-lg-8 .profil-hero-img img');
    if (detailHeroImg) {
      gsap.fromTo(detailHeroImg, 
        { scale: 1.15, transformOrigin: 'center center' },
        { 
          scale: 1, 
          duration: 1.4, 
          ease: 'power2.out',
          scrollTrigger: {
            trigger: '.profil-hero-img',
            start: 'top 85%',
            once: true
          }
        }
      );
    }

    const detailTitle = document.querySelector('.col-lg-8 .profil-section-title');
    if (detailTitle) {
      const titleWords = typoSplitWords(detailTitle);
      gsap.set(titleWords, { yPercent: 110 });
      gsap.to(titleWords, {
        yPercent: 0,
        duration: 0.95,
        stagger: 0.04,
        ease: EASE_EXPO,
        scrollTrigger: {
          trigger: detailTitle,
          start: 'top 85%',
          once: true
        }
      });
    }

    const detailBody = document.querySelector('.col-lg-8 .profil-body-text');
    if (detailBody) {
      gsap.fromTo(detailBody,
        { y: 15, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.7,
          ease: EASE_POWER,
          scrollTrigger: {
            trigger: detailBody,
            start: 'top 85%',
            once: true
          }
        }
      );
    }

    const sidebarWidget = document.querySelector('.col-lg-4.col-md-5');
    if (sidebarWidget) {
      const sidebarLinks = sidebarWidget.querySelectorAll('.layanan-sidebar-nav a, h3, div');
      gsap.fromTo(sidebarLinks,
        { y: 15, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.7,
          stagger: 0.03,
          ease: EASE_POWER,
          scrollTrigger: {
            trigger: sidebarWidget,
            start: 'top 85%',
            once: true
          }
        }
      );
    }
  }

  // ── Kontak Page Animations ──────────────────────────────────────────────────
  if (kontakContainer && typeof ScrollTrigger !== 'undefined') {
    const infoBlocks = document.querySelectorAll('.kontak-info-block');
    if (infoBlocks.length) {
      gsap.fromTo(infoBlocks,
        { y: 25, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.8,
          stagger: 0.1,
          ease: EASE_POWER,
          scrollTrigger: {
            trigger: '.col-lg-4.col-md-5',
            start: 'top 85%',
            once: true
          }
        }
      );
    }

    const formTitle = document.querySelector('.col-lg-8.col-md-7 .profil-section-title');
    if (formTitle) {
      const titleWords = typoSplitWords(formTitle);
      gsap.set(titleWords, { yPercent: 110 });
      gsap.to(titleWords, {
        yPercent: 0,
        duration: 0.95,
        stagger: 0.04,
        ease: EASE_EXPO,
        scrollTrigger: {
          trigger: formTitle,
          start: 'top 85%',
          once: true
        }
      });
    }

    const formElements = kontakContainer.querySelectorAll('.row > div');
    if (formElements.length) {
      gsap.fromTo(formElements,
        { y: 20, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.75,
          stagger: 0.05,
          ease: EASE_POWER,
          scrollTrigger: {
            trigger: kontakContainer,
            start: 'top 80%',
            once: true
          }
        }
      );
    }
  }

  // Refresh ScrollTrigger after splits change layout
  if (typeof ScrollTrigger !== 'undefined') {
    requestAnimationFrame(function () {
      ScrollTrigger.refresh();
    });
  }
}

/**
 * Background Crossfade Slideshow — Manual Control (prev/next)
 */
function initHeroBgSlideshow() {
  const slides = document.querySelectorAll('.hero-bg-slide');
  if (slides.length <= 1) return;

  const prevBtn     = document.getElementById('hero-prev');
  const nextBtn     = document.getElementById('hero-next');
  const heroSection = document.querySelector('.typo-hero');

  const SLIDE_DURATION = 5;    // seconds each slide stays
  const FADE_DURATION  = 1.2;  // crossfade length in seconds
  const SCALE_FROM     = 1.0;
  const SCALE_TO       = 1.06;

  // ── Reduced-motion fallback: instant CSS class swap ───────────────────────
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const motionOff = document.documentElement.classList.contains('no-motion');
  if (prefersReduced || motionOff || typeof gsap === 'undefined') {
    var cur = 0;
    function swap(i) {
      slides[cur].classList.remove('active');
      cur = (i + slides.length) % slides.length;
      slides[cur].classList.add('active');
    }
    if (prevBtn) prevBtn.addEventListener('click', function () { swap(cur - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { swap(cur + 1); });
    setInterval(function () { swap(cur + 1); }, SLIDE_DURATION * 1000);
    return;
  }

  // ── GSAP mode ─────────────────────────────────────────────────────────────
  var current  = 0;
  var autoTimer = null;
  var isPaused  = false;
  var kbTween   = null;

  // Initial state: all invisible, first slide at 0.20 opacity
  gsap.set(slides, { opacity: 0, scale: SCALE_FROM });
  gsap.set(slides[0], { opacity: 0.20 });

  // Ken Burns — slow zoom on the active slide
  function startKenBurns(slide) {
    if (kbTween) kbTween.kill();
    gsap.set(slide, { scale: SCALE_FROM, transformOrigin: '60% 50%' });
    kbTween = gsap.to(slide, {
      scale: SCALE_TO,
      duration: SLIDE_DURATION + FADE_DURATION,
      ease: 'none',
    });
  }
  startKenBurns(slides[0]);

  // ── Dot indicators ────────────────────────────────────────────────────────
  var dots = [];
  var controlsEl = document.querySelector('.typo-hero-controls');
  if (controlsEl) {
    var dotsWrap = document.createElement('div');
    dotsWrap.setAttribute('aria-hidden', 'true');
    dotsWrap.style.cssText = 'display:flex;gap:8px;align-items:center;';
    slides.forEach(function (_, i) {
      var d = document.createElement('button');
      d.setAttribute('aria-label', 'Slide ' + (i + 1));
      d.style.cssText = [
        'width:' + (i === 0 ? '20px' : '8px'),
        'height:8px',
        'border-radius:100px',
        'background:' + (i === 0 ? '#ffffff' : 'rgba(255,255,255,0.3)'),
        'border:none',
        'cursor:pointer',
        'padding:0',
        'transition:width 0.35s ease,background 0.35s ease',
        'flex-shrink:0',
      ].join(';');
      d.addEventListener('click', function () { goTo(i); });
      dotsWrap.appendChild(d);
      dots.push(d);
    });
    // Insert between prev and next buttons
    controlsEl.insertBefore(dotsWrap, nextBtn || null);
  }

  function updateDots(idx) {
    dots.forEach(function (d, i) {
      d.style.width      = i === idx ? '20px' : '8px';
      d.style.background = i === idx ? '#ffffff' : 'rgba(255,255,255,0.3)';
    });
  }

  // ── Core crossfade ────────────────────────────────────────────────────────
  function goTo(next) {
    if (next === current) return;
    var outSlide = slides[current];
    var inSlide  = slides[next];

    // Reset incoming slide scale before fade-in
    gsap.set(inSlide, { scale: SCALE_FROM, transformOrigin: '60% 50%' });

    gsap.to(outSlide, { opacity: 0,    duration: FADE_DURATION, ease: 'power2.inOut' });
    gsap.to(inSlide,  { opacity: 0.20, duration: FADE_DURATION, ease: 'power2.inOut' });

    slides[current].classList.remove('active');
    slides[next].classList.add('active');

    current = next;
    updateDots(current);
    startKenBurns(inSlide);
    resetAutoTimer();
  }

  function advance() {
    if (!isPaused) goTo((current + 1) % slides.length);
  }

  function resetAutoTimer() {
    if (autoTimer) clearInterval(autoTimer);
    autoTimer = setInterval(advance, SLIDE_DURATION * 1000);
  }

  // ── Manual controls ───────────────────────────────────────────────────────
  if (prevBtn) prevBtn.addEventListener('click', function () {
    goTo((current - 1 + slides.length) % slides.length);
  });
  if (nextBtn) nextBtn.addEventListener('click', function () {
    goTo((current + 1) % slides.length);
  });

  // ── Pause on hover / focus ────────────────────────────────────────────────
  if (heroSection) {
    heroSection.addEventListener('mouseenter', function () { isPaused = true; });
    heroSection.addEventListener('mouseleave', function () { isPaused = false; });
    heroSection.addEventListener('focusin',    function () { isPaused = true; });
    heroSection.addEventListener('focusout',   function () { isPaused = false; });
  }

  // ── Pause when browser tab hidden (saves GPU cycles) ─────────────────────
  document.addEventListener('visibilitychange', function () {
    isPaused = document.hidden;
    if (kbTween) {
      if (isPaused) kbTween.pause();
      else kbTween.resume();
    }
  });

  // ── Start ─────────────────────────────────────────────────────────────────
  resetAutoTimer();
}

/**
 * Premium Search Overlay Popup Controller
 */
function initSearchOverlay() {
  const searchButtons = document.querySelectorAll('#nav-search-open, #mobile-search-open');
  const overlay = document.getElementById('search-overlay');
  const closeBtn = document.getElementById('search-close');
  const input = document.getElementById('search-overlay-input');

  if (!searchButtons.length || !overlay || !closeBtn || !input) return;

  function openOverlay(e) {
    if (e) e.preventDefault();
    overlay.classList.add('active');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(function () {
      input.focus();
    }, 180);
  }

  function closeOverlay() {
    overlay.classList.remove('active');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    input.value = '';
  }

  searchButtons.forEach(function (btn) {
    btn.addEventListener('click', openOverlay);
  });
  closeBtn.addEventListener('click', closeOverlay);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('active')) {
      closeOverlay();
    }
  });

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeOverlay();
  });
}

/**
 * Scroll-to-top button (throttled, no layout thrash)
 */
function initScrollToTop() {
  const btn = document.getElementById('scroll-to-top');
  if (!btn) return;

  onScrollThrottled(function () {
    const show = window.scrollY > 320;
    btn.classList.toggle('is-visible', show);
    btn.style.opacity = show ? '1' : '0';
    btn.style.visibility = show ? 'visible' : 'hidden';
  });

  btn.addEventListener('click', function () {
    window.scrollTo({
      top: 0,
      behavior: prefersReducedMotion() ? 'auto' : 'smooth'
    });
  });
}

/**
 * Keep motion toggle in sync with animation engines (custom event)
 */
function initMotionToggleSync() {
  const motionToggle = document.getElementById('motion-toggle');
  if (!motionToggle) return;

  // Observe class mutations on documentElement to ensure we dispatch on actual class change
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.attributeName === 'class') {
        document.dispatchEvent(new CustomEvent('prolabios:motion-change', {
          detail: { reduced: prefersReducedMotion() }
        }));
      }
    });
  });

  observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
  });
}

/**
 * Pause CSS marquees when the tab is hidden (saves compositor work)
 */
function initMarqueeVisibility() {
  const marquees = document.querySelectorAll('.typo-marquee, .marquee');
  if (!marquees.length) return;

  function setPaused(paused) {
    marquees.forEach(function (el) {
      el.classList.toggle('is-paused', paused);
    });
  }

  document.addEventListener('visibilitychange', function () {
    setPaused(document.hidden || prefersReducedMotion());
  });

  document.addEventListener('prolabios:motion-change', function () {
    setPaused(document.hidden || prefersReducedMotion());
  });

  setPaused(document.hidden || prefersReducedMotion());
}

/**
 * Interactive Draggable Marquee with Momentum / Inertia (Reusable Factory)
 */
function setupDraggableMarquee(containerSelector, contentSelector, baseSpeed, pauseOnHover) {
  const container = document.querySelector(containerSelector);
  if (!container) return;

  const contents = container.querySelectorAll(contentSelector);
  if (contents.length < 2) return;

  contents.forEach(function (el) {
    el.style.animation = 'none';
  });

  let x = 0;
  let speed = prefersReducedMotion() ? 0 : baseSpeed;
  let isDragging = false;
  let startX = 0;
  let dragX = 0;
  let velocity = 0;
  let lastTime = Date.now();
  let lastX = 0;
  let rAFId = null;
  let isInView = true;
  let isHovered = false;

  if (pauseOnHover) {
    container.addEventListener('mouseenter', function () {
      isHovered = true;
    });
    container.addEventListener('mouseleave', function () {
      isHovered = false;
    });
  }

  let contentWidth = contents[0].getBoundingClientRect().width;
  
  // Debounced resize handler to prevent layout thrashing
  let resizeTimeout = null;
  window.addEventListener('resize', function () {
    if (resizeTimeout) clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function () {
      contentWidth = contents[0].getBoundingClientRect().width;
    }, 150);
  });

  function update() {
    if (!isDragging && (document.hidden || !isInView || prefersReducedMotion())) {
      rAFId = null;
      return;
    }

    if (!isDragging) {
      const currentSpeed = (isHovered && !prefersReducedMotion()) ? 0 : speed;
      x += currentSpeed + velocity;
      velocity *= 0.95; // Inertia friction decay
      if (Math.abs(velocity) < 0.01) velocity = 0;
    } else {
      const now = Date.now();
      const dt = now - lastTime;
      if (dt > 0) {
        velocity = (x - lastX) / (dt / 16.666);
        lastTime = now;
        lastX = x;
      }
    }

    if (x <= -contentWidth) {
      x += contentWidth;
      lastX += contentWidth;
    } else if (x > 0) {
      x -= contentWidth;
      lastX -= contentWidth;
    }

    contents.forEach(function (el) {
      el.style.transform = `translate3d(${x}px, 0, 0)`;
    });

    rAFId = requestAnimationFrame(update);
  }

  function startLoop() {
    if (!rAFId && (isDragging || (!document.hidden && isInView && !prefersReducedMotion()))) {
      rAFId = requestAnimationFrame(update);
    }
  }

  function stopLoop() {
    if (rAFId) {
      cancelAnimationFrame(rAFId);
      rAFId = null;
    }
  }

  function onStart(e) {
    if (e.type === 'mousedown') {
      e.preventDefault();
    }
    isDragging = true;
    velocity = 0;
    startX = e.pageX || (e.touches && e.touches[0] && e.touches[0].pageX);
    dragX = x;
    lastTime = Date.now();
    lastX = x;
    container.style.cursor = 'grabbing';
    startLoop();
  }

  function onMove(e) {
    if (!isDragging) return;
    if (e.type === 'touchmove' && e.cancelable) {
      e.preventDefault(); // Prevent vertical page scrolling when dragging
    }
    const currentX = e.pageX || (e.touches && e.touches[0] && e.touches[0].pageX);
    if (currentX === undefined) return;
    const dx = currentX - startX;
    x = dragX + dx;
  }

  function onEnd() {
    if (!isDragging) return;
    isDragging = false;
    container.style.cursor = 'grab';

    const maxVelocity = 40;
    if (velocity > maxVelocity) velocity = maxVelocity;
    if (velocity < -maxVelocity) velocity = -maxVelocity;
  }

  document.addEventListener('prolabios:motion-change', function () {
    speed = prefersReducedMotion() ? 0 : baseSpeed;
    if (prefersReducedMotion()) {
      stopLoop();
    } else {
      startLoop();
    }
  });

  document.addEventListener('visibilitychange', function () {
    if (document.hidden) {
      stopLoop();
    } else {
      startLoop();
    }
  });

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        isInView = entry.isIntersecting;
        if (isInView) {
          startLoop();
        } else {
          stopLoop();
        }
      });
    }, { threshold: 0 });
    observer.observe(container);
  }

  container.style.cursor = 'grab';
  container.style.userSelect = 'none';

  container.addEventListener('mousedown', onStart);
  window.addEventListener('mousemove', onMove);
  window.addEventListener('mouseup', onEnd);

  container.addEventListener('touchstart', onStart, { passive: true });
  window.addEventListener('touchmove', onMove, { passive: false });
  window.addEventListener('touchend', onEnd);

  startLoop();
}

function initDraggableMarquee() {
  // Rely on high-performance pure CSS keyframe animations running on the compositor thread
}

function initDraggableLogoMarquee() {
  // Rely on high-performance pure CSS keyframe animations running on the compositor thread
}

// Expose key functions globally to window object for dynamic blade script loaders
window.initGSAPAnimations = initGSAPAnimations;
window.revealHeroStatic = revealHeroStatic;
