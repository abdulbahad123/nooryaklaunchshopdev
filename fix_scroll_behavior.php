<?php
$dir = new RecursiveDirectoryIterator('d:/xamp/htdocs/launchshop_dev/resources/views/website_builder');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    if(strpos($content, 'scroll-behavior: smooth;') !== false) {
        $content = str_replace('scroll-behavior: smooth;', '', $content);
        file_put_contents($path, $content);
        echo "Fixed: $path\n";
    }
}
echo "Done.\n";
