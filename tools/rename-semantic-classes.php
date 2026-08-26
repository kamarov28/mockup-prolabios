<?php
/**
 * Semantic class renames (premium-* → site-*).
 * Run from repo root: php tools/rename-semantic-classes.php
 * Then: npm run build && php artisan view:clear
 */
$map = [
    'premium-top-bar' => 'site-utility-bar',
    'premium-footer' => 'site-footer',
    'product-card-premium' => 'product-card',
    'search-input-pill' => 'utility-search-input',
    'search-btn-pill' => 'utility-search-btn',
];

$roots = [
    'resources/views',
    'resources/css',
    'resources/js',
    'public/css',
];

$ext = ['blade.php', 'css', 'js', 'scss'];

function walk(string $dir, array $ext): Generator
{
    if (! is_dir($dir)) {
        return;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $f) {
        if (! $f->isFile()) {
            continue;
        }
        $name = $f->getFilename();
        foreach ($ext as $e) {
            if (str_ends_with($name, $e)) {
                yield $f->getPathname();
                break;
            }
        }
    }
}

$total = 0;
foreach ($roots as $root) {
    foreach (walk($root, $ext) as $path) {
        $body = file_get_contents($path);
        $n = 0;
        foreach ($map as $from => $to) {
            $c = substr_count($body, $from);
            if ($c > 0) {
                $body = str_replace($from, $to, $body);
                $n += $c;
            }
        }
        $body2 = str_replace(
            ['Premium Top Bar', 'Premium Footer', 'Premium Product Card', 'Premium Download', 'Ultra-Minimalist'],
            ['Site utility bar', 'Site footer', 'Product card', 'Download button', 'Site typography'],
            $body
        );
        if ($body2 !== $body) {
            $n += 1;
            $body = $body2;
        }
        if ($n > 0) {
            file_put_contents($path, $body);
            echo str_replace(getcwd().DIRECTORY_SEPARATOR, '', $path)." : $n\n";
            $total += $n;
        }
    }
}
echo "Done. Total replacements: $total\n";
