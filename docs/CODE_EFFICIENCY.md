# Code efficiency — phased cleanup

Working branch: **`chore/code-efficiency`**
Tracking issue: open issue *chore: phased code efficiency cleanup*

## Principles

1. **If it works, don't break it** — no big-bang rewrite.
2. One phase ≈ one PR when possible.
3. Smoke after each phase: admin RFQ, product CRUD, homepage settings save, cart → RFQ submit.

## Current snapshot (why this plan)

| Area | Note |
|------|------|
| `DataService` | Thin pass-through to Product/Post/Sector/Homepage — double surface area |
| Product APIs | Dual identity: title **and** id/slug |
| `AdminDashboardController::homeUpdate` | Large single method; already stable partial-save |
| CSS | Admin design system + Bootstrap; public `style` + heavy `experimental-typo` |
| JSON migrate command | One-shot legacy |

## Phases

### Phase 0 — Admin CSS isolation
**Status:** mostly done on `main`

- [x] `body.admin-panel`
- [x] Bootstrap badge → editorial tokens in `admin.css`
- [ ] Optional: remaining Blade `badge bg-*` → `admin-badge-*`

### Phase 1 — Split `homeUpdate` (start here)
**Risk:** low · **Value:** maintainability

- Extract per-section validation (homepage / banners / contacts / general)
- Extract patch builders; controller only orchestrates
- Keep: `getHomepageDataFresh`, partial `saveHomepageData`, HtmlSanitizer, maps URL check

### Phase 2 — Product identity
**Risk:** medium

- Audit `getProductByTitle`, title-based update/delete
- Canonical public URL: **slug** (id for admin)
- Title remains unique + display only

### Phase 3 — Thin DataService usage
**Risk:** low–medium

- Prefer injecting domain services where the controller is clearly one domain
- Keep facade where multi-domain is convenient (e.g. dashboard counts)

### Phase 4 — Public CSS tokens
**Risk:** medium (visual)

- Inventory overlap `style.css` vs `experimental-typo.css`
- Unify tokens; reduce duplicate `!important`

### Phase 5 — Dead weight

- `MigrateJsonToDb`: archive or remove if unused
- Confirm `decrementStock` still on a live path

## Out of scope (for now)

- Gutenberg / block CMS
- Email provider switch (deferred)
- Full Bootstrap removal from admin

## How we cook

```text
git fetch origin
git checkout chore/code-efficiency
git pull origin chore/code-efficiency
# work → commit → push
# open PR into main when phase is smoke-tested
```
