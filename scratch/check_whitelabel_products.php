<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dbHost = env('SASS_ADMIN_DB_HOST', '127.0.0.1');
$dbPort = env('SASS_ADMIN_DB_PORT', '3306');
$dbUser = env('SASS_ADMIN_DB_USER', 'bazaarwa_sass_admindb');
$dbPass = env('SASS_ADMIN_DB_PASS', 'Bahad@123');
$candDbs = ['bazaarwa_Sass_admindb', 'bazaarwa_sass_admindb', 'sass_admin'];

foreach ($candDbs as $cdb) {
    try {
        $pdo = new \PDO("mysql:host={$dbHost};port={$dbPort};dbname={$cdb};charset=utf8mb4", $dbUser, $dbPass, [\PDO::ATTR_TIMEOUT => 3]);
        echo "=== Connected to DB: {$cdb} ===\n";
        
        $agencies = $pdo->query("SELECT id, name, custom_domain FROM agencies")->fetchAll(\PDO::FETCH_OBJ);
        echo "Agencies count: " . count($agencies) . "\n";
        foreach ($agencies as $ag) {
            echo "  Agency ID: {$ag->id} | Name: {$ag->name} | Custom Domain: {$ag->custom_domain}\n";
            $prods = $pdo->query("
                SELECT p.id, p.name, p.slug, p.tagline, ap.status as agency_status
                FROM agency_products ap
                JOIN products p ON p.id = ap.product_id
                WHERE ap.agency_id = {$ag->id}
            ")->fetchAll(\PDO::FETCH_OBJ);
            echo "    Assigned Products count: " . count($prods) . "\n";
            foreach ($prods as $pr) {
                echo "      - Product ID: {$pr->id} | Name: {$pr->name} | Slug: {$pr->slug} | Status: {$pr->agency_status}\n";
            }
        }

        $allProds = $pdo->query("SELECT id, name, slug, is_active FROM products")->fetchAll(\PDO::FETCH_OBJ);
        echo "All Products in DB: " . count($allProds) . "\n";
        foreach ($allProds as $ap) {
            echo "  - Product ID: {$ap->id} | Name: {$ap->name} | Slug: {$ap->slug} | Active: {$ap->is_active}\n";
        }
        break;
    } catch (\Throwable $e) {
        echo "Failed to connect to {$cdb}: " . $e->getMessage() . "\n";
    }
}
