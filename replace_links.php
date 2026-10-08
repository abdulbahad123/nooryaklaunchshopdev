<?php
$dir = new RecursiveDirectoryIterator('d:/xamp/htdocs/launchshop_dev/resources/views/website_builder');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);
foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $newContent = preg_replace_callback(
        '/@if\(!empty\(\$t\[\'review_url\'\] \?\? \$t\[\'link\'\] \?\? \'\'\)\)\s*<a href="\{\{ \$t\[\'review_url\'\] \?\? \$t\[\'link\'\] \}\}"(.*?)<\/a>\s*@endif/s',
        function ($matches) {
            return '<a href="{{ !empty($t[\'review_url\']) ? $t[\'review_url\'] : (!empty($t[\'link\']) ? $t[\'link\'] : \'#\') }}"' . $matches[1] . '</a>';
        },
        $content
    );
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
    }
}
