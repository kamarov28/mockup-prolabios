# Public CSS layers (Phase 4 inventory)

## Loaded on public layout (`layouts/app.blade.php`)

| File | Approx. role |
|------|----------------|
| Bootstrap 5 + Icons | Grid, utilities, icons (bundled via Vite) |
| `resources/css/style.css` | Vendor imports |
| `resources/css/site.css` | Soft Neo-Brutalism modular styles (`resources/css/site/`) |

## Admin (`admin/layout.blade.php`)

| File | Role |
|------|------|
| Bootstrap 5 CDN | Layout utilities only |
| `resources/css/admin.css` | Design system (`admin-*`) |

## Overlap / waste

- `:root` tokens (accent, bg, fonts) defined in `site/tokens.css`
- Admin has its own token set (`admin/tokens.css`, intentional isolation)

## Safe next steps (after merge + visual QA)

1. Single `:root` block as source of truth for public
2. Trim duplicate `!important` only where specificity allows
