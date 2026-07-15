/**
 * PROLABIOS Mockup - Main JavaScript
 * Handles: Search, Sidebar Navigation, Contact Form, Mobile Menu, Slider UX
 */
document.addEventListener('DOMContentLoaded', function () {
  initSearch();
  initSidebarNavigation();
  initContactForm();
  initMobileMenu();
  initAnchorSmoothScroll();
  initHeaderScrollEffect();
  initPrincipalSlider();
  initBlogCategoryFilter();
  initScrollAnimations(); // Custom entrance scroll animations
  initGSAPAnimations(); // Custom GSAP ScrollTrigger animations
  
  if (window.location.pathname === '/produk' || window.location.pathname.endsWith('/produk.php') || window.location.pathname.includes('/produk')) {
    applyPagination(1);
  }
});

function initSearch() {
  const searchForm = document.querySelector('.search-form');
  if (!searchForm) return;

  const searchInput = searchForm.querySelector('input');
  const searchBtn = searchForm.querySelector('button');
  const isProductPage = window.location.pathname === '/produk' || window.location.pathname.endsWith('/produk.php') || window.location.pathname.includes('/produk');

  function filterProducts(query) {
    const q = query.toLowerCase();
    const cards = document.querySelectorAll('.product-card');
    let hasResults = false;

    cards.forEach(card => {
      // Check the entire card text (including Cat number, title, desc)
      const text = card.textContent.toLowerCase();
      
      if (text.includes(q)) {
        card.classList.remove('hidden-by-filter');
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
    if (subheader) subheader.textContent = query 
      ? (hasResults ? `Menampilkan hasil untuk "${query}"` : `Tidak ada hasil untuk "${query}"`)
      : 'Menampilkan semua produk kami';
  }

  function doSearch() {
    const query = searchInput.value.trim();
    if (isProductPage) {
      // Update URL without reloading
      const newUrl = query ? `/produk?q=${encodeURIComponent(query)}` : '/produk';
      window.history.pushState({path: newUrl}, '', newUrl);
      filterProducts(query);
    } else {
      if (query.length > 0) {
        window.location.href = '/produk?q=' + encodeURIComponent(query);
      }
    }
  }

  // Live filter if on product page
  if (isProductPage) {
    // Check URL for existing query on load
    const urlParams = new URLSearchParams(window.location.search);
    const initialQuery = urlParams.get('q');
    
    if (initialQuery) {
      searchInput.value = initialQuery;
      // Small timeout to ensure DOM is ready
      setTimeout(() => filterProducts(initialQuery), 50);
    }

    searchInput.addEventListener('input', function() {
      filterProducts(this.value.trim());
    });
  }

  searchBtn.addEventListener('click', doSearch);
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

  const isProductPage = window.location.pathname === '/produk' || window.location.pathname.endsWith('/produk.php') || window.location.pathname.includes('/produk');

  if (isProductPage && href.indexOf('?') !== -1 && (href.indexOf('kategori=') !== -1 || href.indexOf('s=') !== -1)) {
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
  document.querySelectorAll('.nav-list, .list-group').forEach(function (list) {
    list.querySelectorAll('.nav-item, a.list-group-item').forEach(function (item) {
      item.classList.remove('active');
    });

    const match = list.querySelector("a[href*='=" + category + "']");
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
  const productCards = document.querySelectorAll('.product-card');
  if (!productCards.length) return;

  if (!category) {
    productCards.forEach(function (card) {
      card.classList.remove('hidden-by-filter');
    });
    applyPagination(1);
    return;
  }

  productCards.forEach(function (card) {
    const cat = card.getAttribute('data-category');
    if (!cat) {
      card.classList.remove('hidden-by-filter');
      return;
    }

    const cats = cat.split(/[ ,]+/).map(function (c) {
      return c.trim().toLowerCase();
    });
    
    if (cats.includes(category.toLowerCase())) {
      card.classList.remove('hidden-by-filter');
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

  setActiveSidebarForCategory(category);
  filterProductsByCategory(category);

  const match = document.querySelector(".nav-list a[href*='=" + category + "']");
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
  const header = document.querySelector('.header');
  if (!header) return;

  let ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      window.requestAnimationFrame(function () {
        header.classList.toggle('header-scrolled', window.scrollY > 50);
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
}

// Client-side pagination logic
let currentProductPage = 1;
const productsPerPage = 12;

function applyPagination(page = 1) {
  currentProductPage = page;
  const cards = Array.from(document.querySelectorAll('.product-card'));
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
  
  const totalPages = Math.ceil(totalItems / productsPerPage);
  container.innerHTML = '';
  
  if (totalPages <= 1) return;

  container.style.display = 'flex';
  container.style.flexWrap = 'wrap';
  container.style.justifyContent = 'center';
  container.style.gap = '5px';

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement('button');
    btn.className = `page-link ${i === currentProductPage ? 'active' : ''}`;
    btn.style.border = '1px solid var(--color-border)';
    btn.style.background = i === currentProductPage ? 'var(--color-primary)' : 'var(--color-bg-white)';
    btn.style.color = i === currentProductPage ? '#fff' : 'var(--color-text-main)';
    btn.style.padding = '0.5rem 1rem';
    btn.style.cursor = 'pointer';
    btn.textContent = i;
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      applyPagination(i);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    container.appendChild(btn);
  }
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
  window.addEventListener('resize', function () {
    halfWidth = getHalfWidth();
  });

  function normalizeScroll() {
    if (halfWidth === 0) return;
    if (principalSlider.scrollLeft >= halfWidth) {
      principalSlider.scrollLeft -= halfWidth;
    } else if (principalSlider.scrollLeft < 0) {
      principalSlider.scrollLeft += halfWidth;
    }
  }

  function autoScrollFrame() {
    if (document.documentElement.classList.contains('no-motion')) {
      autoScrollId = requestAnimationFrame(autoScrollFrame);
      return;
    }
    if (!isDragging) {
      principalSlider.scrollLeft += scrollSpeed;
      normalizeScroll();
    }
    autoScrollId = requestAnimationFrame(autoScrollFrame);
  }

  function startAutoScroll() {
    if (autoScrollId !== null) return;
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

function initHeroSlideshow() {
  var slideshow = document.getElementById('heroSlideshow');
  var dotsContainer = document.getElementById('heroSlideDots');
  if (!slideshow || !dotsContainer) return;

  var slides = slideshow.querySelectorAll('.hero-slide');
  var dots = dotsContainer.querySelectorAll('.dot');
  if (slides.length < 2) return;

  var current = 0;
  var interval = 4000;
  var timer = null;

  function goTo(index) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  function next() {
    goTo(current + 1);
  }

  function startTimer() {
    if (timer) return;
    timer = setInterval(next, interval);
  }

  function stopTimer() {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
  }

  dots.forEach(function (dot) {
    dot.addEventListener('click', function () {
      var index = parseInt(this.getAttribute('data-index'), 10);
      goTo(index);
      stopTimer();
      startTimer();
    });
  });

  slideshow.addEventListener('mouseenter', stopTimer);
  slideshow.addEventListener('mouseleave', startTimer);

  startTimer();
}

/**
 * Custom Scroll & Entrance Animation Engine
 * Uses IntersectionObserver API for maximum performance
 */
function initScrollAnimations() {
  const animateElements = document.querySelectorAll('.animate-on-scroll');
  if (!animateElements.length) return;

  const observerOptions = {
    root: null,
    rootMargin: '0px -10% -10% 0px', // slightly offset trigger bounds
    threshold: 0.05
  };

  const animationObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target); // Trigger only once
      }
    });
  }, observerOptions);

  animateElements.forEach(function (el) {
    // If element is already in viewport on load, trigger immediately
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight && rect.bottom >= 0) {
      el.classList.add('is-visible');
    } else {
      animationObserver.observe(el);
    }
  });
}

/**
 * GSAP ScrollTrigger Fluid Animation Engine
 */
function initGSAPAnimations() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  gsap.registerPlugin(ScrollTrigger);

  // Pinned Sequential reveal for Sektor Fokus (Scroll Pinning & Reveal)
  const pinSection = document.querySelector('.focus-section-pin');
  if (pinSection) {
    const items = pinSection.querySelectorAll('.typo-index-item');
    const isDesktop = window.matchMedia("(min-width: 992px)").matches;

    if (isDesktop && items.length) {
      // Set initial states
      gsap.set(items, { opacity: 0.15, y: 40 });

      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: pinSection,
          start: "top 12%", // Pin when the section top is 12% from viewport top
          end: "+=1200",   // Pin duration of 1200px scroll
          scrub: 1.2,      // Smooth interpolation
          pin: true,       // Enable pinning
          anticipatePin: 1
        }
      });

      items.forEach((item, index) => {
        tl.to(item, {
          opacity: 1,
          y: 0,
          duration: 1
        }, index * 1.2); // Sequential stagger inside the timeline
      });
    } else {
      // Mobile / Fallback: Simple reveal on scroll
      gsap.from(items, {
        scrollTrigger: {
          trigger: pinSection,
          start: "top 95%"
        },
        y: 40,
        opacity: 0.15,
        duration: 1,
        stagger: 0.2,
        ease: "power3.out"
      });
      gsap.to(items, {
        scrollTrigger: {
          trigger: pinSection,
          start: "top 80%"
        },
        opacity: 1,
        duration: 1,
        stagger: 0.2,
        ease: "power3.out"
      });
    }
  }
}


