# Replace legacy /produk/detail?id= links with product_url()
# Run from repo root after pulling this branch:
#   .\tools\patch-product-url-links.ps1

$ErrorActionPreference = "Stop"
$files = @(
  "resources\views\produk.blade.php",
  "resources\views\sektor.blade.php",
  "resources\views\welcome.blade.php",
  "resources\views\detail-produk.blade.php",
  "resources\views\beli-produk.blade.php",
  "resources\views\cart.blade.php"
)

$replacements = @(
  @("{{ url('/produk/detail') }}?id={{ `$prod['id'] }}", "{{ product_url(`$prod) }}"),
  @("{{ url('/produk/detail') }}?id={{ `$product['id'] }}", "{{ product_url(`$product) }}"),
  @("{{ url('/produk/detail') }}?id={{ `$item['id'] }}", "{{ product_url(`$item) }}"),
  @("url('/produk/detail') . '?id=' . `$product['id']", "product_url(`$product)"),
  @("{{ !empty(`$prod['slug'] ?? null) ? url('/produk/'.`$prod['slug']) : url('/produk/detail?id='.`$prod['id']) }}", "{{ product_url(`$prod) }}")
)

foreach ($rel in $files) {
  $path = Join-Path (Get-Location) $rel
  if (-not (Test-Path $path)) { Write-Host "skip missing $rel"; continue }
  $body = Get-Content $path -Raw
  $n = 0
  foreach ($pair in $replacements) {
    $old = $pair[0]; $new = $pair[1]
    # Unescape backticks we used for PowerShell safety
    $old = $old.Replace('`$', '$')
    $new = $new.Replace('`$', '$')
    if ($body.Contains($old)) {
      $c = ([regex]::Matches($body, [regex]::Escape($old))).Count
      $body = $body.Replace($old, $new)
      $n += $c
    }
  }
  if ($n -gt 0) {
    Set-Content -Path $path -Value $body -Encoding utf8 -NoNewline
    Write-Host "$rel : $n replacement(s)"
  } else {
    Write-Host "$rel : no legacy patterns (ok if already patched)"
  }
}

Write-Host "Done. Review git diff, then commit."
