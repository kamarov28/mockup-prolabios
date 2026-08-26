<?php
/**
 * Replace legacy /produk/detail?id= with product_url().
 * Run from repo root: php tools/patch-product-url-links.php
 */
$root = getcwd();
$files = [
    'resources/views/produk.blade.php',
    'resources/views/sektor.blade.php',
    'resources/views/welcome.blade.php',
    'resources/views/detail-produk.blade.php',
    'resources/views/beli-produk.blade.php',
    'resources/views/cart.blade.php',
];
$map = [
    "{{ url('/produk/detail') }}?id={{ $prod['id'] }}" => "{{ product_url($prod) }}",
    "{{ url('/produk/detail') }}?id={{ $product['id'] }}" => "{{ product_url($product) }}",
    "{{ url('/produk/detail') }}?id={{ $item['id'] }}" => "{{ product_url($item) }}",
    "url('/produk/detail') . '?id=' . $product['id']" => "product_url($product)",
    "{{ !empty($prod['slug'] ?? null) ? url('/produk/'.$prod['slug']) : url('/produk/detail?id='.$prod['id']) }}" => "{{ product_url($prod) }}",
];
foreach ($files as $rel) {
    $path = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $rel);
    if (! is_file($path)) {
        echo "skip $rel\n";
        continue;
    }
    $body = file_get_contents($path);
    $n = 0;
    foreach ($map as $old => $new) {
        $c = substr_count($body, $old);
        if ($c > 0) {
            $body = str_replace($old, $new, $body);
            $n += $c;
        }
    }
    if ($n > 0) {
        file_put_contents($path, $body);
        echo "$rel : $n replacement(s)\n";
    } else {
        echo "$rel : no legacy patterns\n";
    }
}
echo "Done.\n";
