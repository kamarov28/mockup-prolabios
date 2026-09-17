# DESIGN SYSTEM — Modern Flat Precision

> **PT. Prolabios Mitra Analitika**
> High-Precision B2B Scientific E-Procurement & RFQ Platform.
> Visual Style: **Modern Flat Precision** (Clean industrial laboratory, confident typography, solid color planes, zero drop shadows, zero heavy black borders).

---

## 1. Core Philosophy & Principles

1. **Flat Precision & Clarity**: Laboratory instruments, diagnostic media, and chemical reagents demand utmost legibility and modern clarity. Surfaces are defined by pure geometric whitespace and subtle contrast planes, not artificial borders or heavy drop shadows.
2. **Zero Shadows & Zero Borders**: All hard offset shadows (`box-shadow: 4px 4px 0 #1E1E1E`) and thick ink borders (`2px solid #1E1E1E`) are completely retired. Contrast is achieved via background surface hierarchy (`#FFFFFF` on `#F8F9FA`).
3. **Smooth Color & Opacity Transitions**: Eliminating physical "press/translate" physics (`transform: translate(2px, 2px)`). Interactions now rely on refined, rapid color shifts (0.15s – 0.20s ease).
4. **Signature Palette Integrity**: Retaining Prolabios authority with Ruby Red (`#A6171C`) as the core brand driver, Sunny Gold (`#F1C045`) for accents, and clean Slate/Neutral surfaces.

---

## 2. Color Palette & Design Tokens

### Core Color Tokens

| Token | Value | Role |
|---|---|---|
| `--color-canvas` | `#F8F9FA` | Primary page canvas (clean neutral laboratory light gray) |
| `--color-surface` | `#FFFFFF` | Primary card surfaces, modals, elevated panels |
| `--color-surface-subtle` | `#F3F4F6` | Secondary input backings, table headers, inactive pills |
| `--color-text-main` | `#111827` / `#1E1E1E` | Primary high-contrast typography |
| `--color-text-muted` | `#6B7280` / `#5A5A5A` | Secondary metadata, SKU descriptors, timestamps |
| `--color-primary` (Ruby) | `#A6171C` | Prolabios signature Ruby Red (primary CTA, active navigation) |
| `--color-primary-dark` | `#871015` | Hover & active state for primary actions |
| `--color-primary-soft` | `#FEE2E2` | Tinted background for active pill indicators & badges |
| `--color-accent` (Sunny) | `#F1C045` | High-visibility tag highlights, alert pills |
| `--color-success` | `#16A34A` | Verified badges, passed certificates, WhatsApp CTA (`#25D366`) |

---

## 3. Geometry & Corner Radii (Industrial Precision: 4px–6px)

Sharp, technical, and engineered corners matching analytical instrument hardware:

```css
:root {
  /* Corner Radii (Compact Precision) */
  --radius-xs: 2px;   /* Micro-badges, inline code */
  --radius-sm: 4px;   /* SKU tags, category pills, input elements */
  --radius-md: 5px;   /* Buttons, compact cards, thumbnails */
  --radius-lg: 6px;   /* Panels, cards, modals */
  --radius-full: 9999px; /* Status dots, circle controls */

  /* Surface Elevation */
  --shadow-none: none;
  --border-none: none;
  --divider-subtle: 1px solid rgba(17, 24, 39, 0.12);
}
```

---

## 4. Typography & Optical Hierarchy (HIG Adapted)

### Font Families
- **Display / Headings**: `'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif`
  - Technical authority, engineered proportions, authoritative medium/bold weights.
- **Body & Data**: `'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif`
  - Supreme legibility across technical specifications, catalog tables, and data sheets.
- **Monospace / Catalog Codes**: `'IBM Plex Mono', ui-monospace, SFMono-Regular, Menlo, monospace`
  - Designed for CAT numbers, batch identifiers, ISO codes, and tabular figures (`font-variant-numeric: tabular-nums; font-feature-settings: "tnum" 1`).

### Optical Letter-Spacing (HIG Tracking System)
- **Large Titles (`h1`, `h2`, `>= 28px`)**: `--tracking-display: -0.03em` (compact, cohesive display).
- **Section Titles (`h3`, `20px - 26px`)**: `--tracking-title: -0.02em` (clear structural anchors).
- **Headlines & Card Titles (`h4 - h6`, `16px - 18px`)**: `--tracking-headline: -0.012em`.
- **Body Text & Form Inputs**: `--tracking-body: -0.005em` (balanced reading flow, bounded to `45ch - 75ch`).
- **Micro-labels, SKU, Badges (`<= 13px`)**: `--tracking-micro: 0.025em` (open tracking for micro-legibility).

---

## 5. Spatial Rhythm & Ergonomics (HIG Adapted)

### 8pt Grid & 4pt Micro-Rhythm
- `--space-1` (`4px`): Micro-gaps between inline icons and labels.
- `--space-2` (`8px`): Form label to input distance, tight badge padding.
- `--space-3` (`12px`): Compact container gaps.
- `--space-4` (`16px`): Standard card padding and mobile screen safe gutter.
- `--space-6` (`24px`): Standard grid column/row gap, tablet margin.
- `--space-8` (`32px`): Component group separation.
- `--space-12` (`48px`): Section vertical spacing.
- `--space-16` (`64px`): Major page section breaks.

### Ergonomic Touch Target (HIG 44x44pt Rule)
- All interactive elements (buttons, quantity steppers, icon buttons, cart controls, pagination links) must enforce a minimum hit target of **`44px × 44px`** (`--touch-target-min: 44px`) on touch/mobile screens (`@media (max-width: 768px)` or `@media (pointer: coarse)`).

---

## 6. Standard Component Guidelines

### 6.1 Navigation Bar (`navbar`)
- **Surface**: Pure `#FFFFFF` with a subtle `1px solid rgba(0, 0, 0, 0.06)` bottom divider (zero drop shadow).
- **Navigation Links**: Pill-shaped with generous padding (`padding: 0.45rem 1rem; border-radius: 20px`).
  - Hover: Background `#F3F4F6`, text color Ruby `#A6171C`.
  - Active: Soft Ruby tint `#FEE2E2`, text color `#A6171C`, bold weight.
- **Utility Buttons**: Circle pill buttons (`width: 40px; height: 40px; border-radius: 50%`) with `#F3F4F6` background.
- **Catalog Download CTA**: Rounded pill (`border-radius: 20px`), solid Ruby `#A6171C` fill with white text.

### 6.2 Buttons & 3-Tier Action Hierarchy (HIG Prominence)
- **Primary / Prominent (`.nb-btn-primary`, `.btn-primary`)**:
  - Exactly one primary action per visual context (e.g., "Ajukan RFQ", "Tambah ke Keranjang").
  - Solid Ruby Red fill (`#A6171C`), white text, 8px radius, zero border/shadow.
  - Hover: `--nb-primary-dark` (`#7A1015`), smooth 0.15s ease.
- **Secondary / Tinted (`.nb-btn-secondary`, `.nb-btn-tinted`)**:
  - Contextual supporting actions (e.g., "Lihat Brosur", "Filter Kategori").
  - Tinted neutral surface (`#F1F4F8` / `#F3F4F6`) with 1px `#E5E7EB` border and dark text (`#111827`).
  - Hover: `#E2E8F0`.
- **Tertiary / Plain (`.nb-btn-ghost`)**:
  - Non-destructive escape actions (e.g., "Kembali", "Batal", "Hapus Keranjang").
  - Transparent or ultra-light grey, text-only with subtle hover background.

### 6.3 Cards & Grids (`.card`, `.product-card`, `.editorial-featured-card`)
- **Background**: `#FFFFFF` against canvas `#F8F9FA`.
- **Border & Shadow**: Completely removed (`border: none !important; box-shadow: none !important;`).
- **Corner Radius**: `14px`.
- **Image Container**: Separated by subtle contrast background (`#F3F4F6`), top corners rounded `14px`, zero bottom border.
- **Hover**: Smooth background shift or image zoom, no translate jump.

### 6.4 Tabs & Interactive Segmented Controls
- **Bar Container**: Compact pill bar with `#E5E7EB` background and `12px` border radius.
- **Tab Buttons**: Clean pill button.
  - Inactive: Transparent background, text `#4B5563`.
  - Hover: `rgba(255, 255, 255, 0.6)`.
  - Active: Solid `#FFFFFF` fill with primary text color `#A6171C` and bold weight.

### 6.5 Forms & Data Input Ergonomics
- **Stacked Labels**: Always positioned directly above the input with `8px` (`var(--space-2)`) gap.
- **Touch Target**: Input height default to `44px` (`min-height: 44px;`) with `10px 14px` padding.
- **Accessible Focus Ring**: 2px solid Ruby outline with 2px offset (`var(--nb-focus-ring)`).
- **Inline Validation**: Immediate high-contrast error message below the field; no top-of-form error banners alone.

### 6.6 Corporate Footer (Apple-style Clean Minimalist)
- **Background**: Apple Light Neutral `#F5F5F7` with subtle top border `1px solid rgba(0, 0, 0, 0.08)` — eliminates heavy visual clutter and gives breathable space to the page ending.
- **Typography & Links**: Clean, icon-free links in muted neutral grey `#6E6E73` (hover to `#1D1D1F`), 0.82rem font size.
- **Section Headers**: Compact uppercase with generous letter-spacing (`0.8rem`, `letter-spacing: 0.06em`, `#1D1D1F`).
- **Logo & Trust**: Logo without boxed background, paired with a clean status pill with a green dot for PKP verification.
- **Social Icons**: Subtle grey circular icons (`34px × 34px`, `#E8E8ED` bg, `#424245` icon) turning charcoal on hover.
- **WhatsApp Action**: Minimal white pill button with subtle green icon and border (`#25D366`), cleanly integrated without overpowering the layout.
- **Legal Bar**: Discrete single-line layout separated by bullet dots (`&bull;`).

---

## 6. Implementation Files

- `resources/css/site/flat-home.css` — Core Modern Flat style rules and overrides.
- `resources/css/site/tokens.css` — Color tokens and font stack variables.
- `resources/css/site.css` — CSS barrel file importing modular stylesheets.
- `resources/views/layouts/partials/navbar.blade.php` — Redesigned modern flat header.
- `resources/views/layouts/partials/footer.blade.php` — Redesigned modern flat corporate footer.
