<?php
$dir = new RecursiveDirectoryIterator('d:/xamp/htdocs/launchshop_dev/resources/views/website_builder');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);
foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    // Replace 85% with 280px for mobile slider widths
    $newContent = str_replace('85%', '280px', $content);
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
    }
}
