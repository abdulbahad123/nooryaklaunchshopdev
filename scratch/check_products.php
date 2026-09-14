<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);

$dbName = 'bazaarwa_Sass_admindb';
$dbUser = 'bazaarwa_sass_admindb';
$dbPass = 'Bahad@123';
$dbHost = '127.0.0.1';
$dbPort = '3306';

$dbNameCandidates = array_values(array_unique(array_filter([
    $dbName,
    strtolower($dbName),
    'bazaarwa_sass_admindb',
    'bazaarwa_Sass_admindb',
])));

foreach ($dbNameCandidates as $candDb) {
    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$candDb};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        
        echo "SUCCESS CONNECTED TO: {$candDb}\n";
        
        echo "--- AGENCIES ---\n";
        $agencies = $pdo->query("SELECT id, name, slug, custom_domain FROM agencies")->fetchAll();
        print_r($agencies);

        echo "--- PRODUCTS ---\n";
        $products = $pdo->query("SELECT * FROM products")->fetchAll();
        print_r($products);

        echo "--- AGENCY PRODUCTS ---\n";
        $ap = $pdo->query("SELECT ap.*, p.name as product_name, p.slug as product_slug FROM agency_products ap LEFT JOIN products p ON p.id = ap.product_id")->fetchAll();
        print_r($ap);

        break;
    } catch (Exception $e) {
        echo "Failed {$candDb}: " . $e->getMessage() . "\n";
    }
}
