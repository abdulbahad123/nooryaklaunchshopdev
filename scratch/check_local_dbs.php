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
    
    foreach (['sass_admin', 'sass_admindb'] as $db) {
        echo "========================================\n";
        echo "=== DB: {$db} ===\n";
        echo "========================================\n";
        try {
            $pdo->query("USE `{$db}`");
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            echo "Tables: " . implode(', ', $tables) . "\n";
            
            if (in_array('agencies', $tables)) {
                echo "\n--- AGENCIES ---\n";
                print_r($pdo->query("SELECT * FROM agencies")->fetchAll());
            }
            if (in_array('products', $tables)) {
                echo "\n--- PRODUCTS ---\n";
                print_r($pdo->query("SELECT * FROM products")->fetchAll());
            }
            if (in_array('agency_products', $tables)) {
                echo "\n--- AGENCY PRODUCTS ---\n";
                print_r($pdo->query("SELECT * FROM agency_products")->fetchAll());
            }
        } catch (Exception $e) {
            echo "Error accessing {$db}: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
