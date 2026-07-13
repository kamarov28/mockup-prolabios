<?php

$file = 'storage/app/private/data/products.json';
$products = json_decode(file_get_contents($file), true);

$sectors = [
    'brewing', 'biomolecular', 'hospital-clinic', 'cosmetic', 'dairy', 
    'general-purpose', 'food', 'pharmaceutical', 'water'
];

$images = [
    "https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
    "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
    "https://images.unsplash.com/photo-1614935151651-0bea6508abb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
];

$newProducts = [];
foreach ($sectors as $index => $sec) {
    // Add 4 products per sector
    for ($i = 1; $i <= 4; $i++) {
        $newProducts[] = [
            'catalog' => '610' . rand(100, 999),
            'title' => ucfirst($sec) . ' Specific Media ' . $i,
            'description' => 'Media kultur spesifik untuk analisis dan deteksi di industri ' . ucfirst($sec) . '.',
            'category' => 'culture-media',
            'sector' => $sec,
            'image' => $images[($index + $i) % count($images)]
        ];
    }
}

// Ensure the old products don't clash too much, we'll just prepend these new dummy ones.
$allProducts = array_merge($newProducts, $products);

file_put_contents($file, json_encode($allProducts, JSON_PRETTY_PRINT));
echo "Added dummy products!\n";
