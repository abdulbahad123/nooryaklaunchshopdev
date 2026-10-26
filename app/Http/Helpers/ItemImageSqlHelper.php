<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ItemImageSqlHelper
{
    private static ?array $cachedMap = null;

    /**
     * Get array of image filenames for a given item_id from user_item_images.sql
     */
    public static function getSliderImagesFromSql(int $itemId): array
    {
        if (self::$cachedMap === null) {
            self::loadMap();
        }

        return self::$cachedMap[$itemId] ?? [];
    }

    /**
     * Parse user_item_images.sql and build a map of [item_id => [image1, image2, ...]]
     */
    public static function loadMap(): array
    {
        $map = [];
        $sqlPath = base_path('user_item_images.sql');
        if (!file_exists($sqlPath)) {
            self::$cachedMap = [];
            return [];
        }

        $sql = file_get_contents($sqlPath);
        // Pattern: (id, item_id, 'image_filename', ...)
        preg_match_all('/\(\s*\d+\s*,\s*(\d+)\s*,\s*\'([^\']+)\'/i', $sql, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $itemId = (int)$m[1];
            $img = $m[2];
            $map[$itemId][] = $img;
        }

        self::$cachedMap = $map;
        return $map;
    }

    /**
     * Populate/ensure user_item_images from user_item_images.sql for any item that doesn't have images
     */
    public static function ensureItemImagesForUser(int $userId): void
    {
        try {
            if (!Schema::hasTable('user_items') || !Schema::hasTable('user_item_images')) {
                return;
            }

            $userItemIds = DB::table('user_items')->where('user_id', $userId)->pluck('id')->toArray();
            if (empty($userItemIds)) {
                return;
            }

            $existingImagesItemIds = DB::table('user_item_images')
                ->whereIn('item_id', $userItemIds)
                ->pluck('item_id')
                ->unique()
                ->toArray();

            $missingItemIds = array_diff($userItemIds, $existingImagesItemIds);
            if (empty($missingItemIds)) {
                return;
            }

            $map = self::loadMap();
            $inserts = [];
            $now = now();

            foreach ($missingItemIds as $itemId) {
                if (isset($map[$itemId])) {
                    foreach ($map[$itemId] as $img) {
                        $inserts[] = [
                            'item_id' => $itemId,
                            'image' => $img,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($inserts)) {
                foreach (array_chunk($inserts, 100) as $chunk) {
                    DB::table('user_item_images')->insert($chunk);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error ensuring item images for user ' . $userId . ': ' . $e->getMessage());
        }
    }

    /**
     * Import entire user_item_images.sql if table is empty
     */
    public static function importSqlIfEmpty(): void
    {
        try {
            if (!Schema::hasTable('user_item_images')) {
                return;
            }

            $count = DB::table('user_item_images')->count();
            if ($count === 0) {
                $sqlPath = base_path('user_item_images.sql');
                if (file_exists($sqlPath)) {
                    $sql = file_get_contents($sqlPath);
                    if (!empty($sql)) {
                        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                        DB::unprepared($sql);
                        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error importing user_item_images.sql: ' . $e->getMessage());
        }
    }
}
