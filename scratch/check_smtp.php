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
    
    $dbs = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($dbs as $db) {
        if (str_contains($db, 'launchshop') || str_contains($db, 'nooryak')) {
            echo "=== DB: {$db} ===\n";
            $pdo->query("USE `{$db}`");
            try {
                $be = $pdo->query("SELECT is_smtp, smtp_host, smtp_port, smtp_username, smtp_password, encryption, from_mail, from_name FROM basic_extendeds LIMIT 1")->fetch();
                print_r($be);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
