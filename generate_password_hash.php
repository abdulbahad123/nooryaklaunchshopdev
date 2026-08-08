<?php
/**
 * Generate Laravel Password Hash
 * Run: php generate_password_hash.php
 */

$password = 'password123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "========================================\n";
echo "Laravel Password Hash Generator\n";
echo "========================================\n\n";
echo "Plain Password: {$password}\n";
echo "Hashed Password: {$hash}\n\n";
echo "========================================\n";
echo "SQL Query to Update Templates:\n";
echo "========================================\n\n";

$sql = "UPDATE `users` 
SET 
    `password` = '{$hash}',
    `email_verified` = 1,
    `status` = 1,
    `online_status` = 1
WHERE `preview_template` = 1;\n";

echo $sql;
echo "\n========================================\n";
echo "Copy the SQL above and run it in phpMyAdmin\n";
echo "========================================\n";
