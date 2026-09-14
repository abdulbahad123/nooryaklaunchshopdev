<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);

$dbUser = 'root';
$dbPass = 'root';
$dbHost = '127.0.0.1';

try {
    $pdo = new PDO("mysql:host={$dbHost};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    foreach (['sass_admin', 'bazaarwa_Sass_admindb', 'bazaarwa_sass_admindb'] as $db) {
        try {
            $pdo->query("USE `{$db}`");
            echo "========================================\n";
            echo "=== DB: {$db} ===\n";
            echo "========================================\n";
            $agencies = $pdo->query("SELECT id, name, slug, custom_domain FROM agencies")->fetchAll();
            echo "--- AGENCIES ---\n";
            print_r($agencies);
            
            $ap = $pdo->query("SELECT ap.*, p.name as product_name, p.slug as product_slug FROM agency_products ap LEFT JOIN products p ON p.id = ap.product_id")->fetchAll();
            echo "--- AGENCY PRODUCTS ---\n";
            print_r($ap);
        } catch (Exception $e) {
            echo "Failed {$db}: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
