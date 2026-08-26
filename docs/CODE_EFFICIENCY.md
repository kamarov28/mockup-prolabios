# Code efficiency — phased cleanup

Working branch: **`chore/code-efficiency`**
Tracking issue: [#13](https://github.com/kamarov28/mockup-prolabios/issues/13)

## Principles

1. **If it works, don't break it** — no big-bang rewrite.
2. One phase ≈ one PR when possible.
3. Smoke after each phase: admin RFQ, product CRUD, homepage settings save, cart → RFQ submit.

## Phases

### Phase 0 — Admin CSS isolation
**Status:** mostly done on `main`

- [x] `body.admin-panel`
- [x] Bootstrap badge → editorial tokens in `admin.css`
- [ ] Optional: remaining Blade `badge bg-*` → `admin-badge-*`

### Phase 1 — Split `homeUpdate`
**Status:** implemented on this branch — **test before merge**

- [x] `App\Services\HomepageSettingsUpdater` — validate + buildPatch per section
- [x] `AdminDashboardController::homeUpdate` thinned to orchestrate only
- [x] Keep: `getHomepageDataFresh`, partial `saveHomepageData`, HtmlSanitizer, maps URL check

**Smoke test checklist**
1. Admin → Pengaturan Web → Hero: ubah teks → Simpan → refresh OK
2. Tab Bento / Sector / CTA: simpan masing-masing
3. Banner page images: URL atau upload
4. Kontak: email/telepon; maps URL invalid harus error flash
5. Umum: logo/favicon optional
6. Section lain tidak ikut terhapus saat simpan satu section

### Phase 2 — Product identity
**Risk:** medium

- [ ] Audit `getProductByTitle` / update-delete by title
- [ ] Canonical public URL: **slug** (id for admin)

### Phase 3 — Thin DataService usage
- [ ] Prefer domain services where controller is one domain

### Phase 4 — Public CSS tokens
- [ ] Inventory `style.css` vs `experimental-typo.css`

### Phase 5 — Dead weight
- [ ] `MigrateJsonToDb`, `decrementStock` path

## How to test this branch locally

```powershell
git fetch origin
git checkout chore/code-efficiency
git pull origin chore/code-efficiency
# no npm needed for this PHP-only phase
php artisan serve
```
