# Design System: Trade Show Booth (Playful Exhibition)

## 1. Overview & Thesis

**Trade Show Booth** is a warm, high-energy B2B catalog experience inspired by a premium exhibition floor — not a dark ops dashboard.

The palette is inherently **playful and tactile**:
- **Natural** `#D6D0C5` — matte booth wall / unbleached card stock
- **Ruby** `#A6171C` — bold brand stripe, active states
- **Sunny** `#F1C045` — the “energy” color: primary actions, badges, progress, cart pulse

Personality targets: approachable lab partner, clear hierarchy, optimistic CTAs. Avoid cold slate, pure black sections, and military-dark hero overlays unless a photo needs legibility.

---

## 2. Color roles (playful mapping)

| Token | Hex | Role |
|-------|-----|------|
| Natural | `#D6D0C5` | Page canvas |
| Natural Light | `#EDE8E0` | Soft section bands, elevated panels |
| Natural Dark | `#C8C1B4` | Subtle dividers / hover wells |
| Ruby | `#A6171C` | Brand, nav active, links, outline CTAs |
| Ruby Dark | `#7A1015` | Hover on Ruby fills |
| Sunny | `#F1C045` | **Primary buttons**, badges, cart, hero progress |
| Ink | `#1A1A1A` | Headlines & body |
| Muted | `#5A5A5A` / `#6B6B6B` | Supporting copy |
| Paper | `#FFFFFF` | Cards, forms |

**Rule:** If the user should click it first, prefer **Sunny** fill + dark ink text. Ruby is for brand structure and secondary outlines.

---

## 3. Layout & UX principles

1. **Warm canvas first** — sections sit on Natural / Natural Light, never pure black.
2. **Sunny = action** — catalog download, RFQ, hero primary, cart badge.
3. **Soft lift, not harsh chrome** — cards use white paper + soft warm shadow; hover lifts + thin Ruby or Sunny edge.
4. **Hero photo** — use a **warm dim** overlay (ink at ~55–70% with slight Ruby tint), not a pure black scrub. Controls bar can stay Ruby; progress track is Sunny.
5. **Tabs / sector finder** — friendly underline or pill; active = Ruby text + Sunny/Ruby marker.
6. **Typography** — Space Grotesk headlines, Instrument Sans body; keep high contrast on Natural.
7. **Radius** — slightly softened on pills/badges (SKU, status); large structural frames may stay crisp for booth geometry.
8. **Footer** — dark ink ground is OK as a “floor plate”; top edge Ruby stripe; social hover Sunny or Ruby.

---

## 4. Component checklist

- Navbar: Natural glass, Ruby hairline bottom, **Sunny** “Unduh Katalog” fill optional, cart badge Sunny
- Product cards: white paper, warm shadow, Ruby cat-code chip, clear dark titles
- Bento / infrastructure: white or soft Natural Light panels, readable ink
- Sector finder: full Natural canvas, dark titles, paper product panel
- Final CTA: Natural Light band, ink headline, Sunny or Ruby text link

---

## 5. Non-goals

- Dark-mode-first editorial (Linear/Anduril black panels) on public pages
- White text on near-black content sections
- Primary CTAs that are only outline-gray with no Sunny energy
