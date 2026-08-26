#!/usr/bin/env bash
# One-shot production release. Run from repo root as deploy user.
# Usage: bash deploy/release.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

echo "==> App root: $ROOT"

if [[ "${APP_ENV:-}" == "local" ]]; then
  echo "WARN: APP_ENV=local — continue only if intentional."
fi

echo "==> Composer (no-dev)"
composer install --no-dev --optimize-autoloader --no-interaction

if command -v npm >/dev/null 2>&1; then
  echo "==> Frontend build"
  npm ci
  npm run build
else
  echo "WARN: npm not found — skip asset build"
fi

echo "==> Migrate"
php artisan migrate --force

if php artisan list --raw 2>/dev/null | grep -q '^products:sync-sectors'; then
  echo "==> Sync product sectors from CSV → pivot"
  php artisan products:sync-sectors || true
fi

echo "==> Optimize Laravel caches"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true

if php artisan list --raw 2>/dev/null | grep -q '^queue:restart'; then
  echo "==> Restart queue workers"
  php artisan queue:restart || true
fi

echo "==> Health (local)"
php artisan route:list --name=system.health >/dev/null 2>&1 || true

echo "Done. Verify: curl -sS \"\${APP_URL:-http://127.0.0.1}/health\""
