# Design System: Trade Show Booth (PT. Prolabios Mitra Analitika)

## 1. Overview & Thesis
The **Trade Show Booth** design system establishes a high-clarity, industrial-editorial aesthetic for PT. Prolabios Mitra Analitika's B2B procurement and RFQ platform. It draws direct inspiration from premium trade show exhibition stands: physical tactile surfaces, sharp technical precision, architectural structure, and authoritative high-contrast typography.

The experience defaults to an intentional warm-industrial palette (`#D6D0C5` Natural background, `#A6171C` Ruby structural accents, and `#F1C045` Sunny navigational highlights) that replaces generic dark-tech tropes with a tangible physical world.

---

## 2. Color Palette & Strategy

### Primary & Accent Tokens
- **Natural (`--color-natural` / `--color-bg-body`)**: `#D6D0C5`
  - Core canvas surface, invoking physical matte exhibition booths and high-grade unbleached card stock.
- **Natural Dark (`--color-natural-dark` / `--color-bg-dark`)**: `#C8C1B4`
  - Alternating section backgrounds, subtle framing panels, and card contrast backings.
- **Natural Light (`--color-natural-light` / `--color-bg-gray`)**: `#EDE8E0`
  - Elevated card surfaces, subtle metric containers, and nested structural compartments.
- **Ruby (`--color-primary` / `--color-ruby`)**: `#A6171C`
  - Primary brand anchor, utility bar background, active navigation markers, key CTAs, and border highlights.
- **Ruby Dark (`--color-secondary`)**: `#7A1015`
  - Deep active/hover states for buttons, gradient stops for hero banners and sidebar CTA panels.
- **Sunny (`--color-accent` / `--color-sunny`)**: `#F1C045`
  - High-visibility action badges, cart item counters, utility bar active hovers, and hero primary action button.

### Neutral & Surface Tokens
- **Text Main (`--color-text-main`)**: `#1A1A1A` (90%+ contrast against natural & white surfaces)
- **Text Muted (`--color-text-muted`)**: `#6B6B6B` / `#5A5A5A` (Secondary metadata, supporting descriptions)
- **White (`--color-bg-white`)**: `#FFFFFF` (Product card interiors, bento widgets, form fields)
- **Border Default (`--color-border`)**: `rgba(0, 0, 0, 0.12)` (0px radius crisp line division)
- **Footer Ground (`.site-footer`)**: `#1A1A1A` with a `3px solid #A6171C` top boundary.

---

## 3. Typography Ramp & Hierarchy

### Font Families
- **Headline / Technical Display**: `'Space Grotesk', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
- **Body & Editorial**: `'Instrument Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
- **Monospace & SKU Codes**: `ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace` (`font-feature-settings: "tnum" 1`)

### Scale & Application
- **Hero Title (`.typo-hero-title`, `.hero-headline`)**: `clamp(2.4rem, 6.5vw, 4.8rem)`, `font-weight: 700-800`, `letter-spacing: -0.04em`, line-height `1.08`.
- **Editorial Page Titles (`.editorial-page-title`)**: `3.5rem`, `font-weight: 700`, `letter-spacing: -2px`.
- **Section Titles (`.typo-section-title`)**: `clamp(1.75rem, 3.2vw, 2.25rem)`, `letter-spacing: -0.03em`.
- **Index Numbers (`.typo-index-number`)**: `2.2rem`, `font-weight: 500`, `#A6171C`.
- **Section Labels & Eyebrows (`.typo-section-label`, `.editorial-page-label`)**: `0.7rem - 0.75rem`, uppercase, `letter-spacing: 0.18em - 3px`, `font-weight: 600`.
- **Body Text**: `0.95rem - 1.05rem`, line-height `1.65 - 1.7`.
- **Product Catalog Codes (`.product-cat-code`, `.catalogue-no`)**: `0.72rem`, tabular mono figures, padded pill border.

---

## 4. Named Architectural Rules & Components

1. **Sharp Geometry (Zero-Radius Rule)**
   - All interactive surfaces, buttons, cards, navbar containers, and form controls enforce `border-radius: 0px` (or minimal contextual pill tags for SKU/status tags only).

2. **Exhibition Utility Top Bar + Sticky Navigation**
   - Utility Top Bar is anchored with `#A6171C` Ruby, displaying direct phone, email, and integrated search.
   - Sticky navbar uses `rgba(214, 208, 197, 0.97)` with `backdrop-filter: blur(12px)` and a `2px solid #A6171C` bottom border.

3. **High-Contrast Bento & Spec Cards**
   - Cards (`.card`, `.product-card`, `.hitech-bento-card`) feature clean `#FFFFFF` surfaces with subtle `1px solid rgba(0, 0, 0, 0.08)` borders, lifting smoothly on hover (`translateY(-6px)` to `-8px`).

4. **Principal Continuous Marquee**
   - Marquee containers use `#FFFFFF` logo pods (`180px x 90px`) flanked by left/right gradient masking blending seamlessly into `#D6D0C5`.

5. **Motion Contract & Accessibility Safeguard**
   - Smooth cubic-bezier transitions (`cubic-bezier(0.16, 1, 0.3, 1)`).
   - Strict `prefers-reduced-motion` and `.no-motion` overrides disabling all looping marquees, animations, and transitions.

---

## 5. Deliberate Build Exclusions & Non-Canonized Elements
- Legacy dark-mode only selectors and redundant glow pseudo-elements outside light-mode scope are deprecated and omitted from current surface authoring.
