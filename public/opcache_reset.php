<?php
// TEMPORARY - DELETE AFTER USE
// Access once at: https://launchshop.cockroachjantaparty.top/opcache_reset.php
// This clears PHP OPcache so the server loads the updated PHP files

$secret = 'launchshop_clear_2024';
if (!isset($_GET['key']) || $_GET['key'] !== $secret) {
    http_response_code(403);
    die('Forbidden. Add ?key=launchshop_clear_2024 to the URL.');
}

header('Content-Type: text/plain');
echo "=== PHP OPcache Reset ===\n\n";

if (function_exists('opcache_reset')) {
    $result = opcache_reset();
    echo "opcache_reset(): " . ($result ? "SUCCESS ✓" : "FAILED ✗") . "\n";
    
    $status = opcache_get_status(false);
    echo "OPcache enabled: " . ($status['opcache_enabled'] ? 'YES' : 'NO') . "\n";
    echo "Cached scripts cleared: " . ($status ? 'YES' : 'N/A') . "\n";
} else {
    echo "OPcache is NOT enabled on this server.\n";
}

echo "\n=== PHP Info ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "opcache.enable: " . ini_get('opcache.enable') . "\n";
echo "opcache.validate_timestamps: " . ini_get('opcache.validate_timestamps') . "\n";

echo "\n=== Done ===\n";
echo "You can now delete this file.\n";
