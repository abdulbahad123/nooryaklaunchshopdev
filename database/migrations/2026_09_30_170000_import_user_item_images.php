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
        try {
            $sqlPath = base_path('user_item_images.sql');
            if (!file_exists($sqlPath)) {
                return;
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('user_item_images')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $sqlContent = file_get_contents($sqlPath);
            preg_match_all('/INSERT INTO `user_item_images` [^;]+;/s', $sqlContent, $matches);
            if (empty($matches[0])) {
                preg_match_all('/INSERT INTO user_item_images [^;]+;/s', $sqlContent, $matches);
            }

            foreach ($matches[0] as $stmt) {
                DB::statement($stmt);
            }

            // Fill missing slider images for items using thumbnail
            $items = UserItem::all();
            foreach ($items as $item) {
                $hasImage = UserItemImage::where('item_id', $item->id)->exists();
                if (!$hasImage && !empty($item->thumbnail)) {
                    UserItemImage::create([
                        'item_id' => $item->id,
                        'image'   => $item->thumbnail,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Log::error('import_user_item_images migration error: ' . $e->getMessage());
        }
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
