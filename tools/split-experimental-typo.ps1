# tools/split-experimental-typo.ps1
# Jalankan dari root repo:
#   .\tools\split-experimental-typo.ps1
# Lalu:
#   npm run build
#   php artisan view:clear

$ErrorActionPreference = "Stop"

$root = (Get-Location).Path
$srcPath = Join-Path $root "resources\css\experimental-typo.css"
$siteDir = Join-Path $root "resources\css\site"

if (-not (Test-Path $srcPath)) {
  Write-Error "Tidak ketemu resources\css\experimental-typo.css — cd ke root repo dulu."
}

$first = (Get-Content $srcPath -TotalCount 12) -join "`n"
if ($first -match "@import\s+'./site/" -and $first -notmatch "Prolabios Ultra-Minimalist") {
  Write-Error @"
experimental-typo.css sudah jadi barrel.
Restore dulu:
  git show main:resources/css/experimental-typo.css | Set-Content resources\css\experimental-typo.css -Encoding utf8
"@
}

$lines = Get-Content $srcPath
Write-Host "Source lines: $($lines.Count)"

if ($lines.Count -lt 3000) {
  Write-Warning "Baris terasa sedikit ($($lines.Count)). Pastikan file full, bukan barrel."
}

$parts = @(
  @{ Name = "01-base.css";          Start = 0;    End = 918 },
  @{ Name = "02-motion.css";        Start = 918;  End = 1196 },
  @{ Name = "03-theme.css";         Start = 1196; End = 1387 },
  @{ Name = "04-pages-core.css";    Start = 1387; End = 1763 },
  @{ Name = "05-pages-catalog.css"; Start = 1763; End = 2071 },
  @{ Name = "06-content-areas.css"; Start = 2071; End = 2392 },
  @{ Name = "07-admin-login.css";   Start = 2392; End = 2544 },
  @{ Name = "08-b2b-hitech.css";    Start = 2544; End = 2998 },
  @{ Name = "09-cart-rfq.css";      Start = 2998; End = $lines.Count }
)

New-Item -ItemType Directory -Force -Path $siteDir | Out-Null

foreach ($p in $parts) {
  if ($p.End -gt $lines.Count) {
    Write-Error "Range $($p.Name) End=$($p.End) > total lines $($lines.Count)"
  }
  $chunk = $lines[$p.Start..($p.End - 1)]
  $header = "/* site/$($p.Name) */"
  $out = Join-Path $siteDir $p.Name
  @($header) + $chunk | Set-Content -Path $out -Encoding utf8
  Write-Host ("  {0,-22} lines {1,4}-{2,-4}" -f $p.Name, ($p.Start + 1), $p.End)
}

$barrel = @"
/* experimental-typo.css — Vite entry barrel. Rules live in resources/css/site/*.css */
@import './site/01-base.css';
@import './site/02-motion.css';
@import './site/03-theme.css';
@import './site/04-pages-core.css';
@import './site/05-pages-catalog.css';
@import './site/06-content-areas.css';
@import './site/07-admin-login.css';
@import './site/08-b2b-hitech.css';
@import './site/09-cart-rfq.css';
"@
Set-Content -Path $srcPath -Value $barrel -Encoding utf8

$readme = @"
# Site CSS modules

``experimental-typo.css`` is the Vite entry (barrel only).
Edit files in this folder, then run ``npm run build``.

| File | Scope |
|------|--------|
| 01-base.css | Tokens, navbar, hero, footer |
| 02-motion.css | Micro-interactions |
| 03-theme.css | Global dark theme + page headers |
| 04-pages-core.css | Profil, layanan/sektor, kontak |
| 05-pages-catalog.css | Produk, blog, product cards, tables |
| 06-content-areas.css | Content areas, marquee, mobile filter |
| 07-admin-login.css | Admin login |
| 08-b2b-hitech.css | B2B / high-tech homepage |
| 09-cart-rfq.css | Cart & RFQ |
"@
Set-Content -Path (Join-Path $siteDir "README.md") -Value $readme -Encoding utf8

Write-Host ""
Write-Host "Done."
Write-Host "Next:"
Write-Host "  npm run build"
Write-Host "  php artisan view:clear"
Write-Host "  # cek beranda + /produk + /sektor di browser"
Write-Host "  git checkout -b refactor/split-experimental-typo-css"
Write-Host "  git add resources/css/experimental-typo.css resources/css/site tools/split-experimental-typo.ps1"
Write-Host "  git commit -m `"refactor(css): split experimental-typo into site modules`""