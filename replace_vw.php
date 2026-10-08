<?php
$dir = new RecursiveDirectoryIterator('d:/xamp/htdocs/launchshop_dev/resources/views/website_builder');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);
foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $newContent = str_replace('calc(100vw - 48px)', '85%', $content);
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
    }
}
