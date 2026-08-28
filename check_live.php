<?php
/**
 * Diagnostic script — upload to live server root and run: php check_live.php
 * DELETE after use!
 */
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ENV CHECK ===\n";
$sassDb   = env('SASS_ADMIN_DB') ?: env('DB_DATABASE_admin');
$sassUser = env('SASS_ADMIN_DB_USER') ?: env('DB_USERNAME_admin');
$sassPass = env('SASS_ADMIN_DB_PASS') ?: env('DB_PASSWORD_admin', '');
$sassHost = env('SASS_ADMIN_DB_HOST', env('DB_HOST', '127.0.0.1'));
$sassPort = env('SASS_ADMIN_DB_PORT', env('DB_PORT', '3306'));

echo "SASS_ADMIN_DB   = " . ($sassDb   ?: 'NOT SET') . "\n";
echo "SASS_ADMIN_USER = " . ($sassUser ?: 'NOT SET') . "\n";
echo "SASS_ADMIN_PASS = " . ($sassPass ? '(set)' : 'NOT SET') . "\n";
echo "SASS_ADMIN_HOST = {$sassHost}\n";
echo "DB_DATABASE     = " . env('DB_DATABASE') . "\n";
echo "CPANEL_USER     = " . env('CPANEL_USER') . "\n\n";

if (!$sassDb || !$sassUser) {
    echo "MISSING: SASS_ADMIN_DB or DB_USERNAME_admin not set in .env!\n";
    exit(1);
}

// Connect to Sass Admin DB using dedicated PDO (same as middleware does)
echo "=== CONNECTING TO SASS ADMIN DB VIA DEDICATED PDO ===\n";
try {
    $dsn = "mysql:host={$sassHost};port={$sassPort};dbname={$sassDb};charset=utf8mb4";
    $pdo = new PDO($dsn, $sassUser, $sassPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_TIMEOUT            => 5,
    ]);
    echo "PDO Connection: SUCCESS to '{$sassDb}' as '{$sassUser}'\n\n";
} catch (\Throwable $e) {
    echo "PDO Connection FAILED: " . $e->getMessage() . "\n";
    echo "\nCheck that DB_USERNAME_admin and DB_PASSWORD_admin in .env are correct.\n";
    exit(1);
}

// Check agencies
echo "=== AGENCIES in {$sassDb} ===\n";
try {
    $stmt = $pdo->query("SELECT id, name, slug, type, custom_domain FROM agencies");
    $agencies = $stmt->fetchAll();
    if (empty($agencies)) {
        echo "No agencies found.\n";
    } else {
        foreach ($agencies as $a) {
            echo "  [{$a->id}] {$a->name} | Type: {$a->type} | Domain: {$a->custom_domain}\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Check products
echo "\n=== PRODUCTS in {$sassDb} ===\n";
try {
    $stmt = $pdo->query("SELECT id, name, slug, is_active FROM products");
    $products = $stmt->fetchAll();
    if (empty($products)) {
        echo "NO PRODUCTS FOUND — must add Launchshop product in Sass Admin first!\n";
    } else {
        foreach ($products as $p) {
            echo "  [{$p->id}] {$p->name} | slug: {$p->slug} | active: {$p->is_active}\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Check db_name column
echo "\n=== AGENCY_PRODUCTS.db_name in {$sassDb} ===\n";
try {
    $cols = $pdo->query("SHOW COLUMNS FROM agency_products LIKE 'db_name'")->fetchAll();
    if (empty($cols)) {
        echo "WARNING: db_name column MISSING — run: php artisan migrate --force (on Sass Admin)\n";
    } else {
        echo "db_name column EXISTS.\n";
        $stmt = $pdo->query("
            SELECT a.name, a.custom_domain, p.slug AS product_slug,
                   ap.db_name, ap.db_status
            FROM agency_products ap
            JOIN agencies a ON a.id = ap.agency_id
            JOIN products  p ON p.id = ap.product_id
        ");
        $rows = $stmt->fetchAll();
        if (empty($rows)) {
            echo "No rows in agency_products — create an agency AND assign the Launchshop product.\n";
        } else {
            foreach ($rows as $r) {
                $ok = $r->db_name ? "OK" : "NULL (not provisioned yet)";
                echo "  Agency: {$r->name} | Domain: {$r->custom_domain}\n";
                echo "  Product: {$r->product_slug} | db_name: " . ($r->db_name ?? 'NULL') . " | status: {$r->db_status} [{$ok}]\n\n";
            }
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Check existing launchshop databases in MySQL
echo "=== LAUNCHSHOP DATABASES IN MYSQL ===\n";
try {
    $stmt = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA
                        WHERE SCHEMA_NAME LIKE '%launchshop%'
                           OR SCHEMA_NAME LIKE '%bazaarwa_ps_%'
                        ORDER BY SCHEMA_NAME");
    if (empty($stmt)) {
        echo "Only main launchshop DB found — no agency DBs provisioned yet.\n";
    } else {
        foreach ($stmt as $db) {
            echo "  DB: {$db->SCHEMA_NAME}\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\nDone. DELETE this file from server after use!\n";
