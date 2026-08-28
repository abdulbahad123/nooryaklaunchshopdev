<?php
// TEMPORARY DEBUG FILE - DELETE AFTER USE
// Access via: https://launchshop.cockroachjantaparty.top/debug_request.php
// Then access: https://launchshop.cockroachjantaparty.top/manti
// And compare the REQUEST_URI values

header('Content-Type: text/plain');

echo "=== SERVER VARIABLES ===\n\n";
echo "HTTP_HOST:       " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
echo "SERVER_NAME:     " . ($_SERVER['SERVER_NAME'] ?? 'NOT SET') . "\n";
echo "REQUEST_URI:     " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME:     " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";
echo "DOCUMENT_ROOT:   " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
echo "PHP_SELF:        " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n\n";

echo "=== ENVIRONMENT ===\n\n";
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $envContents = file_get_contents($envFile);
    preg_match('/^APP_URL=(.*)$/m', $envContents, $matches);
    echo "APP_URL:         " . ($matches[1] ?? 'NOT FOUND') . "\n";
    preg_match('/^WEBSITE_HOST=(.*)$/m', $envContents, $matches2);
    echo "WEBSITE_HOST:    " . ($matches2[1] ?? 'NOT FOUND') . "\n";
}

echo "\n=== REQUEST HEADERS ===\n\n";
foreach (getallheaders() as $key => $val) {
    echo "$key: $val\n";
}
