<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$html = view('admin.products.form', [
    'sectors' => [],
    'product' => [
        'title' => '',
        'catalog' => '',
        'category' => '',
        'sub_category' => '',
        'sector' => '',
        'image' => '',
        'description' => ''
    ]
])->render();

$lines = explode("\n", $html);
echo "Total lines: " . count($lines) . "\n";
for ($i = 830; $i <= 870; $i++) {
    if (isset($lines[$i - 1])) {
        echo $i . ": " . $lines[$i - 1] . "\n";
    }
}
