<?php
for ($port = 3300; $port <= 3320; $port++) {
    $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.1);
    if ($fp) {
        echo "Port {$port} is open!\n";
        fclose($fp);
        
        // Try connecting with root / empty or root / root
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;port={$port}", 'root', '');
            echo "  Successfully connected to 127.0.0.1:{$port} with user 'root' and empty password.\n";
            showDbDetails($pdo);
        } catch (Exception $e) {
            echo "  Failed empty pass on {$port}: " . $e->getMessage() . "\n";
            try {
                $pdo = new PDO("mysql:host=127.0.0.1;port={$port}", 'root', 'root');
                echo "  Successfully connected to 127.0.0.1:{$port} with user 'root' and password 'root'.\n";
                showDbDetails($pdo);
            } catch (Exception $e2) {
                echo "  Failed root pass on {$port}: " . $e2->getMessage() . "\n";
            }
        }
    }
}

function showDbDetails($pdo) {
    try {
        $q = $pdo->query("SHOW DATABASES");
        echo "  Databases:\n";
        while ($row = $q->fetchColumn()) {
            echo "    - {$row}\n";
        }
    } catch(Exception $e) {
        echo "  Failed SHOW DATABASES: " . $e->getMessage() . "\n";
    }
}
