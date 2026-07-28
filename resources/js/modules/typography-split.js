/**
 * Typography Split Engine & Accessibility Helpers
 */
import { prefersReducedMotion } from './utils.js';

export function revealHeroStatic() {
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

export function isSafeTypographyElement(el) {
  if (!el) return false;
  if (el.dataset.allowSplit === 'true') return true;

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

  const safeIds = ['category-title'];
  if (safeIds.indexOf(el.id) !== -1) return true;

  for (let i = 0; i < safeClasses.length; i++) {
    if (el.classList.contains(safeClasses[i])) {
      return true;
    }
  }

  let parent = el.parentElement;
  while (parent && parent !== document.body) {
    if (parent.dataset.allowSplit === 'true') return true;
    parent = parent.parentElement;
  }

  return false;
}

export function typoSplitWords(el) {
  if (!el || !isSafeTypographyElement(el)) return [];
  if (el.dataset.splitWords === '1') {
    return el.querySelectorAll('.word-inner');
  }

  const originalText = (el.textContent || '').replace(/\s+/g, ' ').trim();

  function wrapTextNode(textNode) {
    const text = textNode.textContent;
    if (!text || !text.trim()) return;

    const parentEl = textNode.parentElement;
    const extraClasses = (parentEl && parentEl !== el && parentEl.className) ? ' ' + parentEl.className : '';
    const frag = document.createDocumentFragment();

    text.split(/(\s+)/).forEach(function (part) {
      if (!part) return;
      if (/^\s+$/.test(part)) {
        frag.appendChild(document.createTextNode(part));
        return;
      }
      const mask = document.createElement('span');
      mask.className = 'word-mask' + extraClasses;
      mask.setAttribute('aria-hidden', 'true');
      const inner = document.createElement('span');
      inner.className = 'word-inner' + extraClasses;
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
    const tagName = node.tagName.toUpperCase();
    if (['SPAN', 'STRONG', 'EM', 'B', 'I', 'U'].indexOf(tagName) === -1) return;
    Array.prototype.slice.call(node.childNodes).forEach(walk);
  }

  Array.prototype.slice.call(el.childNodes).forEach(walk);

  let targetEl = el;
  if (el.tagName === 'A' || el.tagName === 'BUTTON') {
    targetEl = el;
  } else {
    const link = el.querySelector('a, button');
    if (link) targetEl = link;
  }

  const srSpan = document.createElement('span');
  srSpan.className = 'visually-hidden';
  srSpan.textContent = originalText;
  targetEl.insertBefore(srSpan, targetEl.firstChild);
  el.removeAttribute('aria-label');

  el.dataset.splitWords = '1';
  el.classList.add('is-split');
  return el.querySelectorAll('.word-inner');
}

export function typoWrapLine(el) {
  if (!el || !isSafeTypographyElement(el)) return null;
  if (el.dataset.splitLine === '1') {
    return el.querySelector('.line-inner');
  }

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

  let targetEl = el;
  if (el.tagName === 'A' || el.tagName === 'BUTTON') {
    targetEl = el;
  } else {
    const link = el.querySelector('a, button');
    if (link) targetEl = link;
  }

  const srSpan = document.createElement('span');
  srSpan.className = 'visually-hidden';
  srSpan.textContent = originalText;

  targetEl.appendChild(srSpan);
  el.appendChild(mask);
  el.removeAttribute('aria-label');

  el.dataset.splitLine = '1';
  el.classList.add('is-split');
  return inner;
}
