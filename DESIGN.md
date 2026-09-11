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

## 3. Geometry & Corner Radii

No strokes, no offsets. Pure rounded geometry:

```css
:root {
  /* Corner Radii */
  --radius-xs: 4px;   /* Inline code, micro-badges */
  --radius-sm: 6px;   /* SKU tags, category pills */
  --radius-md: 10px;  /* Buttons, form inputs, thumbnails */
  --radius-lg: 14px;  /* Cards, Bento panels, modals */
  --radius-xl: 20px;  /* Callout banners, pill badges, nav-links */
  --radius-full: 9999px; /* Circle icon buttons, avatar pills */

  /* Surface Elevation */
  --shadow-none: none;
  --border-none: none;
  --divider-subtle: 1px solid rgba(0, 0, 0, 0.06);
}
```

---

## 4. Typography & Hierarchy

### Font Families
- **Display / Headings**: `'Bricolage Grotesque', 'IBM Plex Sans', system-ui, sans-serif`
  - Expressive, authoritative, tight tracking (`letter-spacing: -0.02em` to `-0.03em`).
- **Body & Data**: `'IBM Plex Sans', system-ui, sans-serif`
  - High legibility across technical specifications, catalog grids, and data sheets.
- **Monospace / Catalog Codes**: `SFMono-Regular, Menlo, Monaco, Consolas, monospace`
  - Used for CAT numbers, batch identifiers, ISO codes, and quantity counters with tabular figures (`font-feature-settings: "tnum" 1`).

---

## 5. Standard Component Guidelines

### 5.1 Navigation Bar (`navbar`)
- **Surface**: Pure `#FFFFFF` with a subtle `1px solid rgba(0, 0, 0, 0.06)` bottom divider (zero drop shadow).
- **Navigation Links**: Pill-shaped with generous padding (`padding: 0.45rem 1rem; border-radius: 20px`).
  - Hover: Background `#F3F4F6`, text color Ruby `#A6171C`.
  - Active: Soft Ruby tint `#FEE2E2`, text color `#A6171C`, bold weight.
- **Utility Buttons**: Circle pill buttons (`width: 40px; height: 40px; border-radius: 50%`) with `#F3F4F6` background.
- **Catalog Download CTA**: Rounded pill (`border-radius: 20px`), solid Ruby `#A6171C` fill with white text.

### 5.2 Buttons & CTAs
- **Primary Button (`.btn-primary`, `.nb-btn-primary`)**:
  - Background: `--color-primary` (`#A6171C`).
  - Radius: `8px` or `20px` (pill).
  - Border & Shadow: None.
  - Hover: Background `--color-primary-dark` (`#871015`), `transform: none`.
- **Ghost / Secondary Button (`.nb-btn-ghost`)**:
  - Background: `#E5E7EB`, text: `#1F2937`.
  - Hover: Background `#D1D5DB`.

### 5.3 Cards & Grids (`.card`, `.product-card`, `.editorial-featured-card`)
- **Background**: `#FFFFFF` against canvas `#F8F9FA`.
- **Border & Shadow**: Completely removed (`border: none !important; box-shadow: none !important;`).
- **Corner Radius**: `14px`.
- **Image Container**: Separated by subtle contrast background (`#F3F4F6`), top corners rounded `14px`, zero bottom border.
- **Hover**: Smooth background shift or image zoom, no translate jump.

### 5.4 Tabs & Interactive Segmented Controls
- **Bar Container**: Compact pill bar with `#E5E7EB` background and `12px` border radius.
- **Tab Buttons**: Clean pill button.
  - Inactive: Transparent background, text `#4B5563`.
  - Hover: `rgba(255, 255, 255, 0.6)`.
  - Active: Solid `#FFFFFF` fill with primary text color `#A6171C` and bold weight.

### 5.5 Corporate Footer (Apple-style Clean Minimalist)
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
