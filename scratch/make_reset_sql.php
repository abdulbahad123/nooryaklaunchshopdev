<?php

$sqlContent = file_get_contents('user_item_images.sql');

$header = "-- Reset and Import user_item_images Master Dataset\n";
$header .= "-- Wipes existing user_item_images table and imports 6,260 master slider image rows\n\n";
$header .= "SET FOREIGN_KEY_CHECKS=0;\n";
$header .= "TRUNCATE TABLE `user_item_images`;\n\n";

$footer = "\nSET FOREIGN_KEY_CHECKS=1;\n";

file_put_contents('database/seeds/reset_and_import_user_item_images.sql', $header . $sqlContent . $footer);
echo "Created database/seeds/reset_and_import_user_item_images.sql successfully." . PHP_EOL;
