# DESIGN SYSTEM — Soft Neo-Brutalism

> **PT. Prolabios Mitra Analitika**
> High-precision B2B E-Procurement & Scientific RFQ Platform.
> Visual Style: **Soft Neo-Brutalism** (Clean industrial laboratory, high-contrast typography, tactile physical components, warm paper canvas).

---

## 1. Core Philosophy & Principles

1. **Precision & Trust**: Laboratory instruments require clarity, readability, and authority. Every element communicates technical accuracy.
2. **Tactile Neo-Brutalism**: Solid borders, hard 0-blur offset drop shadows, and physical button "press" mechanics (2px hover, 4px active translation).
3. **Softness & Warmth**: Avoiding cold brutalism or harsh harsh neon; grounded by a warm Natural canvas (`#f9f5f2`), crisp white cards (`#FFFFFF`), bold Ruby accents (`#A6171C`), and Sunny gold highlights (`#F1C045`).
4. **No Visual Gimmicks**: Keep content purposeful. No extraneous decorative stats strips, no artificial backdrop blurs that degrade mobile performance.

---

## 2. Color Palette & Design Tokens

### Core Color Tokens

| Token | Value | Role |
|---|---|---|
| `--nb-bg` / `--color-bg` | `#f9f5f2` | Primary page background (warm paper / natural canvas) |
| `--nb-ink` / `--color-text-main` | `#1E1E1E` | Primary ink for text, borders, and hard shadows |
| `--nb-card` / `--color-bg-white` | `#FFFFFF` | Card surfaces, modals, popovers |
| `--nb-primary` / `--color-ruby` | `#A6171C` | Prolabios signature Ruby Red (primary CTA, active nav, key accents) |
| `--nb-primary-dark` | `#7A1015` | Hover/active state for primary red actions |
| `--nb-accent` / `--color-sunny` | `#F1C045` | Sunny yellow (technical badges, category tags, notification pills) |
| `--nb-muted` / `--color-text-muted` | `#5A5A5A` | Secondary descriptions, timestamps, metadata |

### Semantic Alert & Feedback Tokens

| Token / Usage | Value | Context |
|---|---|---|
| Error Border / Shadow | `#A6171C` | Input `.has-error`, invalid validation alerts |
| Error Background | `#FEE2E2` | Warning banner background, input error icon container |
| Error Text | `#7F1D1D` | Error message text |
| Success / Secure | `#16A34A` / `#22C55E` | Verification badges, SSL status pills |

---

## 3. Typography & Hierarchy

### Font Families
- **Display / Headings**: `'Bricolage Grotesque', 'Plus Jakarta Sans', system-ui, sans-serif`
  - Characteristic: Expressive, technical-editorial punch, negative tracking (`letter-spacing: -0.03em`).
- **Body & Interface**: `'Plus Jakarta Sans', system-ui, sans-serif`
  - Characteristic: Clean geometric legibility for dense technical catalogs and specifications.
- **Monospace / Technical Data**: `ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace`
  - Characteristic: Used with `font-feature-settings: "tnum" 1` for SKU numbers, catalog codes, counters, and dates.

### Typographic Scale
- **Hero Title**: `clamp(2.4rem, 6.5vw, 4.8rem)` — Bold, tight line-height (1.1).
- **Page Titles**: `clamp(2.0rem, 4.5vw, 3.2rem)` — Heavy weight, `-0.03em` letter-spacing.
- **Section Titles**: `clamp(1.75rem, 3.2vw, 2.25rem)` — Bold section headers.
- **Card Titles**: `1.15rem – 1.35rem` — Crisp headline font.
- **Body Regular**: `0.95rem – 1.05rem` (15px – 16px), line-height: `1.65`.
- **Labels & SKU Badges**: `0.70rem – 0.75rem`, uppercase, bold, mono for codes.

---

## 4. Geometry, Shadows, and Elevation

All shadows are **0-blur directional drop shadows** producing crisp, physical "cut-out" edges.

```css
:root {
  --nb-border-w: 2px;
  --nb-border: 2px solid #1E1E1E;
  
  /* Hard offset shadows (no blur) */
  --nb-shadow-sm: 3px 3px 0 #1E1E1E;
  --nb-shadow: 4px 4px 0 #1E1E1E;
  --nb-shadow-lg: 6px 6px 0 #1E1E1E;
  
  /* Border Radii (Soft Neo-Brutalist corners) */
  --nb-radius-sm: 4px;
  --nb-radius: 6px;
  --nb-radius-lg: 8px;
}
```

### Motion & Physics Rules
- **Hover State**: Elements depress slightly or elevate (`transform: translate(2px, 2px); box-shadow: 2px 2px 0 #1E1E1E;`).
- **Active / Pressed State**: Full press effect (`transform: translate(4px, 4px); box-shadow: 0 0 0 #1E1E1E;`).
- **Transition Duration**: Rapid, responsive feedback (`0.12s – 0.15s ease`).

---

## 5. Key UI Component Standards

### 5.1 Navbar
- **Positioning**: Fixed full-bleed (`position: fixed; top: 0; left: 0; right: 0; z-index: 1030/99999`).
- **Surface**: `--nb-bg-soft` (`#f9f5f2`), with `border-bottom: 3px solid #1E1E1E` and `--nb-shadow-sm`.
- **Navigation Links**: Tactile pills with transparent borders. On hover: white background with `2px 2px 0 #1E1E1E` shadow. On active route: solid Ruby (`#A6171C`) fill with white text.
- **Mobile Drawer**: Zero blur/opacity flicker (`opacity: 1 !important; filter: none !important`), snappy collapse height animation.

### 5.2 Buttons & CTAs
- **Primary Button (`.btn-primary`, `.nb-btn-primary`)**:
  - Background: `--nb-primary` (`#A6171C`), color: `#FFFFFF`.
  - Border: `2px solid #1E1E1E`, radius: `6px`.
  - Shadow: `3px 3px 0 #1E1E1E`.
  - Hover: Background `#7A1015`, `transform: translate(2px, 2px)`, shadow `1px 1px 0 #1E1E1E`.
- **Accent / Utility Buttons (`.nb-icon-btn`)**:
  - 38px × 38px (desktop) / 44px × 44px (mobile touch targets).
  - Background `#FFFFFF`, 2px solid border, 2px offset shadow.

### 5.3 Badges & Category Tags (`.nb-badge`, `.product-cat-code`, `.catalogue-no`)
- Display: Inline-flex with monospace/display bold styling.
- Border: `1.5px solid #1E1E1E`.
- Background: `--nb-accent` (`#F1C045`) or white/soft canvas.
- Shadow: `1.5px – 2px` hard drop shadow.

### 5.4 Cards & Bento Grid (`.card`, `.product-card`, `.hitech-bento-card`)
- Background: `--nb-card` (`#FFFFFF`).
- Border: `2px solid #1E1E1E`.
- Border radius: `6px` to `8px`.
- Image Container: Separated with a bottom border `2px solid #1E1E1E` on a soft background (`#f9f5f2`).
- Hover: Tactile translation + shadow adjustment.

### 5.5 Form Inputs & Validation
- Standard Inputs: 2px solid `#1E1E1E` border, 4px border radius, white background.
- Focus: Border remains solid `#1E1E1E` with `--nb-shadow-sm` accentuation.
- Error State (`.has-error`):
  - Border color: `#A6171C !important`.
  - Shadow: `3px 3px 0 #A6171C !important`.
  - Error icons: Tinted background (`#FEE2E2`) and inline microcopy.

---

## 6. Implementation Files

- `resources/css/style.css` — Global entry point for vendor libraries (Bootstrap 5 & Bootstrap Icons).
- `resources/css/site.css` — Main site barrel importing modular stylesheets from `resources/css/site/`:
  - `tokens.css` — Color tokens, font families, and geometry variables.
  - `base.css` — Base HTML element styles, typography resets, and global layout.
  - `motion.css` — 0-blur tactile button press and card micro-interactions.
  - `components.css` — Buttons, cards, badges, navbar, and common components.
  - `pages-core.css`, `pages-catalog.css`, `content-areas.css`, `b2b-hitech.css`, `cart-rfq.css` — Specialized page layouts.
- `resources/css/admin.css` — Admin panel barrel importing modular stylesheets from `resources/css/admin/`:
  - `tokens.css`, `base.css`, `layout.css`, `components.css`, `forms-tables.css`, `auth.css`.
