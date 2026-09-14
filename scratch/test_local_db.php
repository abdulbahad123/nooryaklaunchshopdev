<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "Connected to MySQL on 127.0.0.1:3306 with root!\n";
    $stmt = $pdo->query("SHOW DATABASES");
    print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
} catch (Exception $e) {
    echo "127.0.0.1 root error: " . $e->getMessage() . "\n";
}

try {
    $pdo2 = new PDO('mysql:host=127.0.0.1;port=3306', 'bazaarwa_launchshopdevuser', 'Bahad@123');
    echo "Connected to MySQL on 127.0.0.1 with bazaarwa_launchshopdevuser!\n";
} catch (Exception $e) {
    echo "127.0.0.1 bazaarwa_launchshopdevuser error: " . $e->getMessage() . "\n";
}
