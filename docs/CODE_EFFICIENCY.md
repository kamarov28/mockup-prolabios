# Code efficiency — phased cleanup

Working branch: **`chore/code-efficiency`**
Tracking issue: [#13](https://github.com/kamarov28/mockup-prolabios/issues/13)

## Principles

1. **If it works, don't break it**
2. Smoke after switch: RFQ, product CRUD, homepage settings, cart → RFQ

## Status on this branch

### Phase 0 — Admin CSS isolation
- [x] On `main` already

### Phase 1 — Split `homeUpdate`
- [x] `HomepageSettingsUpdater`
- [x] Thin `AdminDashboardController::homeUpdate`

### Phase 2 — Product identity
- [x] Canonical detail: `/produk/{slug}`
- [x] Legacy routes: slug before title
- [x] `ResolvesProducts`: id → slug → title
- [x] Title mutators / stock marked `@deprecated`

### Phase 3 — Thin DataService (admin)
- [x] Product / Post / Sector admin → domain services
- [ ] Page/Cart/Rfq still use DataService (OK)

### Phase 4 — Public CSS tokens
- [x] Inventory in `docs/CSS_TOKENS.md`
- [ ] Visual merge deferred

### Phase 5 — Dead weight
- [x] `database:migrate-json` DEPRECATED warning
- [x] Deprecated APIs kept (not deleted)

## Local test

```powershell
git fetch origin
git checkout chore/code-efficiency
git pull origin chore/code-efficiency
php artisan serve
```
