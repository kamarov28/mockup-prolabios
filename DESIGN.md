# Design System: Soft Neo-Brutalism
**Prolabios — B2B Lab Distributor Homepage Language**

> This document is derived from the shipped homepage implementation.
> It is the canonical reference for building additional pages.
> Update it when a new pattern ships; don't invent patterns outside it.

---

## 1. Thesis

**Soft Neo-Brutalism** takes the bold tension of classic neo-brutalism and softens it for a B2B lab distributor: high contrast, modular structure, physical micro-interactions — without aggressive pure-black chrome.

- **High contrast, still comfortable** — thick charcoal outlines + warm Natural canvas
- **Functional & modular** — every surface boundary is visible (cards as physical blocks)
- **Physical press UI** — hard offset shadows collapse on interaction (not float/glow)
- **Warm credibility** — playful palette, not sterile; Indonesian-first copy

---

## 2. Color palette

| Role | Token | Hex | Usage |
|------|-------|-----|-------|
| **Page canvas** | `--nb-bg` | `#D6D0C5` | All section backgrounds (Natural) |
| **Soft canvas** | `--nb-bg-soft` | `#EDE8E0` | Hero copy box, spec stats, alt stripes |
| **Card surface** | `--nb-card` | `#FFFFFF` | Product, bento, news card interiors |
| **Primary / CTA** | `--nb-primary` | `#A6171C` | Main CTA buttons, Ruby accent text |
| **Primary dark** | `--nb-primary-dark` | `#7A1015` | Hover state on Ruby fills |
| **Highlight / Badge** | `--nb-accent` | `#F1C045` | SKU badges, progress bar, back-to-top |
| **Ink / Border** | `--nb-ink` | `#1E1E1E` | All borders, hard shadows, primary type |
| **Muted text** | `--nb-muted` | `#5A5A5A` | Body copy, secondary labels |

**Never use**: Bootstrap green/success, Bootstrap default blue, soft blur shadows (`rgba` box-shadows with spread), dark glass (`rgba(0,0,0,0.9)` + `backdrop-filter`).

---

## 3. Tokens (live in `light-mode.css` `:root`)

```css
--nb-bg: #D6D0C5;
--nb-bg-soft: #EDE8E0;
--nb-primary: #A6171C;
--nb-primary-dark: #7A1015;
--nb-accent: #F1C045;
--nb-card: #FFFFFF;
--nb-ink: #1E1E1E;
--nb-muted: #5A5A5A;
--nb-border-w: 2px;
--nb-border: 2px solid #1E1E1E;
--nb-shadow: 4px 4px 0 #1E1E1E;
--nb-shadow-sm: 3px 3px 0 #1E1E1E;
--nb-shadow-lg: 6px 6px 0 #1E1E1E;
--nb-radius: 6px;
--nb-radius-sm: 4px;
--nb-radius-lg: 8px;
--font-display: 'Space Grotesk', system-ui, sans-serif;
--font-body: 'Plus Jakarta Sans', system-ui, sans-serif;
--font-mono: 'JetBrains Mono', ui-monospace, monospace;
```

---

## 4. Shadow depth scale

Shadows are **zero-blur, offset-only**. Depth communicates importance.

| Level | Value | Use |
|-------|-------|-----|
| `--nb-shadow-sm` | `3px 3px 0 #1E1E1E` | Badges, small cards, icon buttons |
| `--nb-shadow` | `4px 4px 0 #1E1E1E` | Standard cards, product cards |
| `--nb-shadow-lg` | `6px 6px 0 #1E1E1E` | Hero boxes, principals shell |
| Hero / RFQ deepest | `8px 8px 0 #1E1E1E` | Single most-important callout per page |
| On hover (all) | `2px 2px 0 #1E1E1E` | Shadow collapses as card "presses in" |
| On active | `0 0 0 #1E1E1E` | Fully collapsed = fully pressed |

**Lead card modifier** (bento anchor card): `border: 2.5px solid --nb-primary` + `box-shadow: 5px 5px 0 --nb-primary` — Ruby shadow instead of Charcoal. One per section maximum.

---

## 5. Interaction grammar — the press rule

**All interactive surfaces use the same physical physics**: the surface presses INTO the canvas on hover; the shadow collapses (gets smaller) because the card is "closer to the wall."

```css
/* Resting state */
.nb-card {
  box-shadow: 4px 4px 0 #1E1E1E;
  transition: transform 0.12s ease, box-shadow 0.12s ease;
}

/* Hover = press in */
.nb-card:hover {
  transform: translate(2px, 2px);
  box-shadow: 2px 2px 0 #1E1E1E;
}

/* Active = fully pressed */
.nb-card:active {
  transform: translate(4px, 4px);
  box-shadow: 0 0 0 #1E1E1E;
}
```

**Never use** on this site:
- `translateY(-4px)` (float up) — it contradicts the physics
- `transform: scale(1.02)` — no scaling hover
- Soft blur shadows on hover (`box-shadow: 0 16px 36px rgba(0,0,0,0.08)`)
- `backdrop-filter` on any element that is not the full-viewport search overlay

Always add `@media (prefers-reduced-motion: reduce) { .element { transform: none !important; transition: none !important; } }`.

---

## 6. Typography

| Role | Font | Weight | Use |
|------|------|--------|-----|
| Display / headings | Space Grotesk | 700 | Hero H1, section H2, card titles |
| Technical / SKU | JetBrains Mono | 600–700 | Catalog codes, specs, ISO tags, counters |
| Body / forms | Plus Jakarta Sans | 400–500 | Descriptions, RFQ copy, inputs, paragraphs |

### Size scale (rem)
- **Hero H1**: `clamp(1.85rem, 4.2vw, 3.25rem)`, `letter-spacing: -0.03em`, `line-height: 1.08`
- **Section H2**: `typo-section-title` — ~1.85rem, weight 700, `letter-spacing: -0.03em`
- **Card H3**: `1.15rem–1.8rem` depending on card size
- **Body copy**: `0.85rem–1.05rem`, `line-height: 1.6–1.7`
- **Badges / mono labels**: `0.65rem–0.75rem`, `letter-spacing: 0.06em`, uppercase

### Hierarchy on any section
1. **Section eyebrow** (skip if possible — the heading carries its own weight)
2. **H2 section title** — Space Grotesk 700
3. **Subhead** — Plus Jakarta Sans, muted (#5A5A5A), max 2 lines
4. **Card title** — Space Grotesk 600
5. **Body / spec** — Plus Jakarta Sans 400 or JetBrains Mono for data
6. **CTA** — Space Grotesk 700, Ruby fill or Sunny ghost

---

## 7. Components

### Buttons (`nb-btn`)

```html
<!-- Primary: Ruby fill, white text, hard shadow -->
<a class="nb-btn nb-btn-primary">Label <i class="bi bi-arrow-right"></i></a>

<!-- Ghost: White fill, charcoal text, turns Sunny on hover -->
<a class="nb-btn nb-btn-ghost">Label</a>

<!-- Icon button (hero slider arrows, small actions) -->
<button class="nb-icon-btn"><i class="bi bi-arrow-left"></i></button>
```

Hover: `translate(2px, 2px)` + shadow collapses. Active: `translate(4px, 4px)` + shadow = 0.

Never use Bootstrap `.btn-danger` as a stand-alone CTA on public pages — it maps to Ruby in this system but lacks the neo-brutalist border and shadow.

### Badges

```html
<!-- Full badge (hero, section callout) -->
<span class="nb-badge">B2B PROCUREMENT</span>
<!-- Sunny fill, charcoal border 2px, hard shadow 3px, JetBrains Mono -->

<!-- Compact badge (product code, category chip) -->
<span class="nb-badge-sm">Endotoxin LAL</span>
<!-- EDE8E0 fill, charcoal border 1.5px, no shadow, JetBrains Mono -->

<!-- Catalog code (auto-styled by light-mode.css) -->
<div class="product-cat-code">CAT. BIO-TAL01</div>
<!-- Sunny fill, charcoal border, 2px shadow, mono font -->
```

### Cards

All cards: white fill, `var(--nb-border)`, `var(--nb-shadow)`, `border-radius: var(--nb-radius-lg)`.

```css
/* Apply via class or via light-mode.css automatic rules */
.my-card {
  background: var(--nb-card);
  border: var(--nb-border);
  border-radius: var(--nb-radius-lg);
  box-shadow: var(--nb-shadow);
  transition: transform 0.12s ease, box-shadow 0.12s ease;
}
.my-card:hover {
  transform: translate(2px, 2px);
  box-shadow: 2px 2px 0 var(--nb-ink);
}
```

**Lead anchor card** (one per bento section): `border: 2.5px solid var(--nb-primary)` + `box-shadow: 5px 5px 0 var(--nb-primary)`.

### Sections

Every major section gets a **2px solid `#1E1E1E` bottom border** via `.nb-section` or the section-specific class — this is how the page segments read as distinct "booth panels."

```css
/* Add to any new section */
.my-section {
  background: var(--nb-bg) !important;
  border-bottom: 2px solid #1E1E1E !important;
}
```

Section padding: use `.section-spacious` (large vertical rhythm) consistently. Don't invent one-off padding.

### Navbar

- Background: `--nb-bg-soft` (`#EDE8E0`)
- Border-bottom: `3px solid #1E1E1E`
- Shadow: `var(--nb-shadow-sm)`
- Active link: `--nb-primary` color
- Hover underline: Ruby 1px, slides from left (`::after` pseudo-element)

### Footer

Charcoal (`#1E1E1E`) ground. Heading white, body `rgba(255,255,255,0.78)`, links `rgba(255,255,255,0.88)`. Hover → Sunny `#F1C045`. No blur, no gradient footer.

---

## 8. Section rhythm (homepage order)

| Order | Section | Class | Background |
|-------|---------|-------|------------|
| 1 | Hero | `.nb-hero` | `--nb-bg` |
| 2 | Principals ticker | `.nb-principals` | `--nb-bg` (shell: white) |
| 3 | Value pillars (bento) | `section-spacious` | `--nb-bg` |
| 4 | Sector finder (tabs) | `.focus-section-pin` | `--nb-bg` |
| 5 | Featured products | `.typo-products-section` | `--nb-bg` |
| 6 | News / insights | `.typo-news-section` | `--nb-bg-soft` |
| 7 | RFQ callout | `.nb-rfq-section` | `--nb-bg` |

The news section uses `--nb-bg-soft` as a tonal break — the only "off-Natural" background on the page. Use this pattern for any secondary editorial strip.

---

## 9. Page-specific patterns

### Hero split (`.nb-hero-grid`)
50/50 CSS grid. Left: copy box (`.nb-hero-copy`, `--nb-bg-soft` fill, `6px 6px 0` shadow). Right: image slideshow (`.nb-hero-frame`, `6px 6px 0` shadow). Collapses to single column on `<992px`.

### Principals ticker
Infinite CSS marquee. Shell: white card, `4px 4px 0` charcoal shadow. Label strip: charcoal border-bottom. Logo cells: white card, `2px` charcoal border, `3px 3px 0` shadow. Logos: `opacity: 0.65` at rest, `1` on hover — this is intentional (muted trust signal, not a readability choice).

### Bento grid
`col-lg-7` anchor card + `col-lg-5` supporting cards in alternating layout. Anchor card gets Ruby-colored shadow and border. Supporting cards get standard charcoal shadow.

### Sector tabs
Pill-style tabs (`.focus-section-pin .hitech-tab-btn`): Sunny fill + charcoal border + `2px 2px 0` shadow on `.active`. Tab panels: left side = spec copy on `--nb-bg-soft` with charcoal border; right side = spec card (white, charcoal border, `5px 5px 0` shadow).

### RFQ callout (`.nb-rfq-box`)
Centered, `max-width: 820px`. Deepest shadow on the page: `8px 8px 0 #1E1E1E`. White fill. Sunny badge. Two CTAs: Ruby primary + Ghost secondary.

### Inner Page Hero Banner (`.profil-hero-banner`)
Standard header for inner content pages (Profil, Katalog Produk, dll):
- Container background: `--nb-bg` (`#D6D0C5`), with border-bottom `2px solid #1E1E1E`
- Eyebrow badge: `.nb-badge` (Sunny `#F1C045` with black text & hard shadow)
- Title: `.profil-main-title` (Space Grotesk, bold, -0.03em letter spacing)
- Subtitle: `.profil-main-subtitle` (Plus Jakarta Sans, muted ink, max-width 720px)
- Fast stats strip: `.profil-stats-strip` (CSS grid with `.profil-stat-box` cards, white surface, 2px border, 3px hard shadow)

### Catalog & Filter Grid Layout
- Category Title & Search Bar: Full-width container header row (`d-flex align-items-end justify-content-between`), clean separator border.
- 2-Column Grid (`align-items-start`):
  - Left / Main (`col-lg-8 col-md-7 order-1`): Product cards grid (2 columns) + pagination.
  - Right / Sidebar (`col-lg-4 col-md-5 order-2`): `.card` containing `.layanan-sidebar-nav` and `.profil-cta-box`.
  - The tops of both columns align flush at the top of the grid row.

---

## 10. ARIA & accessibility baseline

- All `<button>` elements: `aria-label` when icon-only
- Images: `alt` describing content, never empty on product images
- Section headings: H1 → H2 → H3 in order, never skip levels
- Focus rings: preserved (don't `outline: none` without replacement)
- Reduced-motion: all CSS transforms guarded with `@media (prefers-reduced-motion: reduce)`
- Contrast: Natural canvas `#D6D0C5` + charcoal text `#1E1E1E` = 10.4:1 ✓

---

## 11. What NOT to do

- `translateY(-Npx)` hover (float up) on any card or button
- `backdrop-filter: blur()` anywhere except the search overlay
- `border-radius: 50%` (circles) — only the back-to-top square uses `6px`
- Bootstrap success green or info blue for any status indicator
- `section-numbers (01/02/03)` — they don't appear in this system
- Eyebrow labels above H2 — the heading carries itself
- Gradient text — use weight or color change instead
- Soft box-shadows (`rgba` with spread) — zero-blur only
- English fallback copy for Indonesian-first CTAs

---

## 12. Non-goals

- Dark mode (light mode only on this branch)
- Soft blurry Material shadows
- Pure black content sections as default
- Zero-radius aggressive brutalism on every control
- Low-contrast gray-on-beige text (`#5A5A5A` on `#D6D0C5` = 4.6:1 — minimum acceptable)
