<?php
// Bootstrap Laravel Application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

echo "<pre>\n";
echo "===============================================\n";
echo "          CLEARING SYSTEM CACHE ONLY           \n";
echo "===============================================\n\n";

try {
    // Clear compiled view files
    Artisan::call('view:clear');
    echo "✓ View cache cleared successfully.\n";

    // Clear route cache
    Artisan::call('route:clear');
    echo "✓ Route cache cleared successfully.\n";

    // Clear config cache
    Artisan::call('config:clear');
    echo "✓ Config cache cleared successfully.\n";

    // Clear application cache
    Artisan::call('cache:clear');
    echo "✓ Application cache cleared successfully.\n";

    // Purge compiled Blade template cache files in storage/framework/views
    $storageViewDir = storage_path('framework/views');
    if (File::exists($storageViewDir)) {
        $files = File::files($storageViewDir);
        $deletedCount = 0;
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore') {
                @File::delete($file);
                $deletedCount++;
            }
        }
        echo "✓ Purged {$deletedCount} compiled view files from storage.\n";
    }

    echo "\n-----------------------------------------------\n";
    echo "SUCCESS: System cache cleared successfully!\n";
    echo "Note: All merchant admin database data remains 100% intact.\n";
    echo "-----------------------------------------------\n";
} catch (\Exception $e) {
    echo "Error clearing cache: " . $e->getMessage() . "\n";
}
echo "</pre>";
