# Design System: Soft Neo-Brutalism

## 1. Thesis

**Soft Neo-Brutalism** takes the bold tension of classic neo-brutalism and softens it for a B2B lab distributor: high contrast, modular structure, playful micro-interactions — without aggressive pure-black chrome.

- **High contrast, still comfortable** — thick dark outlines + warm Natural canvas
- **Functional & modular** — every surface boundary is visible (cards as physical blocks)
- **Modern playful** — hard offset shadows (no blur) + small radius (4–8px)

---

## 2. Color palette

| Role | Hex | Usage |
|------|-----|--------|
| **Background** | Natural `#D6D0C5` | Page canvas (technical paper / booth wall) |
| **Primary / CTA** | Ruby `#A6171C` | Main actions, hero CTA, critical indicators |
| **Highlight / Badge** | Sunny `#F1C045` | Status badges, SKU chips, accents |
| **Card surface** | White `#FFFFFF` | Product & bento card interiors |
| **Border & text** | Charcoal `#1E1E1E` | Borders, hard shadows, primary type |
| Ruby dark | `#7A1015` | Hover on Ruby fills |
| Muted text | `#5A5A5A` | Secondary copy |

### CSS tokens
```css
--nb-bg: #D6D0C5;
--nb-primary: #A6171C;
--nb-accent: #F1C045;
--nb-card: #FFFFFF;
--nb-ink: #1E1E1E;
--nb-border: 2px solid #1E1E1E;
--nb-shadow: 4px 4px 0 #1E1E1E;
--nb-shadow-sm: 3px 3px 0 #1E1E1E;
--nb-radius: 6px;
```

---

## 3. Structure & layout

### Bento grid
Isolate products, features, and lab specs into clear card blocks of varying size. Navigation of technical data stays modular.

### Hard / offset shadows
```css
box-shadow: 4px 4px 0 #1E1E1E; /* no blur */
```
Depth is 2D and intentional — not soft elevation.

### Borders & radius
- Border: **1.5px–2.5px** solid `#1E1E1E`
- Radius: **4px–8px** (soft neo, not pure brutal square)

### Micro-interactions (physical press)
```css
.btn:hover, .card:hover {
  transform: translate(2px, 2px);
  box-shadow: 2px 2px 0 #1E1E1E;
}
.btn:active {
  transform: translate(4px, 4px);
  box-shadow: 0 0 0 #1E1E1E;
}
```

---

## 4. Typography

| Role | Font | Use |
|------|------|-----|
| **Display / headings** | Space Grotesk | Hero, section titles, product names |
| **Technical / SKU** | JetBrains Mono | Catalog numbers, specs, ISO badges |
| **Body / forms** | Plus Jakarta Sans | Descriptions, RFQ copy, inputs |

### Hierarchy example (product screen)
1. **Category badge** — mono + Sunny BG → `[ SKUS-LAB-2026 ]`
2. **Product title** — Space Grotesk Bold → Digital Centrifuge…
3. **Specs line** — JetBrains Mono → Voltage: 220V | Max RCF: …
4. **Body** — Plus Jakarta Sans → prose
5. **CTA** — Bold sans + Ruby fill + charcoal border → Request Official Quote

---

## 5. Component rules

- **Buttons**: Ruby fill, charcoal border, hard shadow; hover shifts into shadow
- **Cards**: White fill, charcoal border, hard shadow, radius 6–8px
- **Badges / SKU**: Sunny fill, charcoal border, mono type
- **Navbar**: Natural surface, charcoal bottom border, Ruby active link
- **Hero**: Warm photo treatment; CTA = Ruby + hard shadow
- **Forms**: White fields, charcoal border, focus ring Sunny or Ruby
- **Footer**: Charcoal ground OK; top edge Ruby or Sunny stripe

---

## 6. Non-goals

- Soft blurry Material shadows
- Pure black content sections as default
- Zero-radius aggressive brutalism on every control
- Low-contrast gray-on-beige text
