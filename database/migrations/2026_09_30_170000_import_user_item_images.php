<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User\UserItemImage;
use App\Models\User\UserItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sqlPath = base_path('user_item_images.sql');
        if (!file_exists($sqlPath)) {
            \Log::error("user_item_images.sql not found at " . $sqlPath);
            return;
        }

        $sqlContent = file_get_contents($sqlPath);
        if (empty($sqlContent)) {
            return;
        }

        $currentDb = DB::connection()->getDatabaseName();
        $targetDatabases = [$currentDb];

        try {
            $dbs = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%_launchsh%' OR SCHEMA_NAME LIKE '%_launchshop%'");
            foreach ($dbs as $dbObj) {
                $dbName = $dbObj->SCHEMA_NAME;
                if (!in_array($dbName, $targetDatabases)) {
                    $targetDatabases[] = $dbName;
                }
            }
        } catch (\Throwable $e) {
            \Log::warning("Could not query INFORMATION_SCHEMA: " . $e->getMessage());
        }

        foreach ($targetDatabases as $dbName) {
            try {
                \Log::info("Importing user_item_images.sql into database: {$dbName}");
                DB::statement("USE `{$dbName}`");

                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::statement('DROP TABLE IF EXISTS `user_item_images`;');
                
                DB::unprepared($sqlContent);
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                // For any items in user_items missing user_item_images, insert thumbnail fallback
                $itemsWithoutSliders = DB::select("
                    SELECT i.id, i.thumbnail 
                    FROM `user_items` i 
                    LEFT JOIN `user_item_images` img ON i.id = img.item_id 
                    WHERE img.id IS NULL AND i.thumbnail IS NOT NULL AND i.thumbnail != ''
                ");

                foreach ($itemsWithoutSliders as $item) {
                    $thumbName = basename(parse_url($item->thumbnail, PHP_URL_PATH));
                    DB::table('user_item_images')->insert([
                        'item_id'    => $item->id,
                        'image'      => $thumbName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

            } catch (\Throwable $e) {
                \Log::error("Failed importing user_item_images into DB {$dbName}: " . $e->getMessage());
            }
        }

        try {
            DB::statement("USE `{$currentDb}`");
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No-op
    }
};
