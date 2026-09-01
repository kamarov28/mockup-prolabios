# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

B2B buyers: lab managers, procurement officers, and QC technicians at pharmaceutical companies, universities, hospitals, and industrial labs in Indonesia. They are task-driven: they need to find a specific product (by catalog code or application), verify it is the right fit, and initiate an RFQ. They navigate with intent, not curiosity.

## Product Purpose

PT. Prolabios Mitra Analitika is a B2B distributor of laboratory instruments, microbiological media, and analytical equipment in Indonesia. The platform lets buyers browse a product catalog, build an RFQ cart, submit a request (with company credentials and PIC), and receive confirmation. Follow-up is handled operationally by the sales team via WhatsApp and email.

## Positioning

Local Indonesian distributor with curated technical products across microbiology, analytical chemistry, and general lab consumables — not a global marketplace. Personal sales follow-up is a core part of the offer. "Professional, Robust, Offering the best."

## Operating Context

- Buyers visit to check availability, compare catalog codes, and start a procurement process.
- RFQ is the primary conversion action; there is no direct online purchase.
- Products are organized by category and sector (industry vertical).
- Admin manages RFQs, products, categories, and site settings via a Laravel admin dashboard.

## Capabilities and Constraints

- Laravel 13 + Blade templating; Bootstrap 5 as CSS framework base; Tailwind CSS v4 + Vite for custom styles.
- Product images are re-encoded to WebP; SVG uploads blocked.
- Email and WhatsApp notifications are async via Laravel queues.
- Docker deployment for production.
- No direct purchase — RFQ only.

## Brand Commitments

- Company name: PT. Prolabios Mitra Analitika / PROLABIOS
- Tagline: "Professional, Robust, Offering the best"
- Brand palette (new, user-pinned): #D6D0C5 Natural (ground/bg), #A6171C Ruby (accent/secondary), #F1C045 Sunny (complementary/buttons)
- Design language: playful but credible; warm, approachable — not sterile or corporate-dark
- Light mode only on this branch (feature/light-mode-redesign)

## Evidence on Hand

- Full Blade template set: welcome, produk, detail-produk, layanan, sektor, profil, kontak, informasi, cart, rfq-checkout, rfq-success
- Admin dashboard: rfqs, products, categories, settings, posts
- Real product data seeded via database
- Logo: images/logo-prolabios.png

## Product Principles

1. **Clarity over beauty** — buyers are task-driven; the catalog must be instantly scannable.
2. **Trust through professionalism** — local company, global product standards; the design must earn credibility.
3. **Warmth without informality** — playful palette and language, but still B2B-appropriate.
4. **Conversion is the RFQ** — every page should have a clear path to cart or RFQ.
5. **Indonesian-first** — copy, UX patterns, and content defaults to Indonesian language and business norms.
