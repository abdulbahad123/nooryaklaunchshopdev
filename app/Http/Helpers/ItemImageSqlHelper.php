<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ItemImageSqlHelper
{
    private static ?array $cachedMap = null;
    private static ?array $cachedImageToGroup = null;

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
     * Get array of sibling slider image filenames for a given image filename from SQL dump
     */
    public static function getImageGroupFromSql(?string $imageName): array
    {
        if (empty($imageName)) {
            return [];
        }

        if (self::$cachedImageToGroup === null) {
            self::loadMap();
        }

        $clean = basename(ltrim($imageName, '/'));
        return self::$cachedImageToGroup[$clean] ?? [];
    }

    /**
     * Parse user_item_images.sql (or candidate seeds) and build:
     * 1. [item_id => [image1, image2, ...]]
     * 2. [clean_image_filename => [image1, image2, ...]]
     */
    public static function loadMap(): array
    {
        if (self::$cachedMap !== null && self::$cachedImageToGroup !== null) {
            return self::$cachedMap;
        }

        $map = [];
        $imageToGroup = [];

        $candidates = [
            base_path('user_item_images.sql'),
            database_path('seeds/reset_and_import_user_item_images.sql'),
            database_path('seeds/whitelabel_agency_user_items.sql'),
        ];

        $sqlContent = '';
        foreach ($candidates as $cand) {
            if (file_exists($cand)) {
                $sqlContent = file_get_contents($cand);
                if (!empty($sqlContent)) {
                    break;
                }
            }
        }

        if (!empty($sqlContent)) {
            // Pattern: (id, item_id, 'image_filename', ...)
            preg_match_all('/\(\s*\d+\s*,\s*(\d+)\s*,\s*\'([^\']+)\'/i', $sqlContent, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $itemId = (int)$m[1];
                $img = $m[2];
                $map[$itemId][] = $img;
            }

            foreach ($map as $itemId => $imgs) {
                $uniqueImgs = array_values(array_unique($imgs));
                foreach ($uniqueImgs as $img) {
                    $clean = basename(ltrim($img, '/'));
                    if (!isset($imageToGroup[$clean])) {
                        $imageToGroup[$clean] = $uniqueImgs;
                    }
                }
            }
        }

        self::$cachedMap = $map;
        self::$cachedImageToGroup = $imageToGroup;
        return $map;
    }

    /**
     * Resolves the full array of slider image filenames for ANY given item.
     * Supports Eloquent model, array, stdClass DB row, or null.
     */
    public static function resolveSliderImagesForItem($item): array
    {
        if (empty($item)) {
            return [];
        }

        $itemId = is_object($item) ? ($item->id ?? ($item->item_id ?? null)) : ($item['id'] ?? ($item['item_id'] ?? null));
        $thumb = is_object($item) ? ($item->thumbnail ?? '') : ($item['thumbnail'] ?? '');

        // 1. Check if DB has >= 2 valid slider images for this item_id
        if (!empty($itemId)) {
            try {
                if (is_object($item) && isset($item->sliders) && is_iterable($item->sliders) && count($item->sliders) > 1) {
                    $dbImgs = [];
                    foreach ($item->sliders as $s) {
                        $im = is_object($s) ? ($s->image ?? '') : ($s['image'] ?? '');
                        if (!empty($im) && $im !== 'noimage.jpg') {
                            $dbImgs[] = $im;
                        }
                    }
                    if (count($dbImgs) > 1) {
                        return array_values(array_unique($dbImgs));
                    }
                }

                if (Schema::hasTable('user_item_images')) {
                    $dbImgs = DB::table('user_item_images')
                        ->where('item_id', $itemId)
                        ->whereNotNull('image')
                        ->where('image', '!=', '')
                        ->where('image', '!=', 'noimage.jpg')
                        ->pluck('image')
                        ->toArray();

                    if (count(array_unique($dbImgs)) > 1) {
                        return array_values(array_unique($dbImgs));
                    }
                }
            } catch (\Throwable $e) {
                // Ignore DB error and continue to SQL fallback
            }
        }

        // 2. Lookup in master SQL map by item_id
        if (!empty($itemId)) {
            $sqlImgs = self::getSliderImagesFromSql((int)$itemId);
            if (count($sqlImgs) > 1) {
                return $sqlImgs;
            }
        }

        // 3. Lookup in master SQL map by thumbnail filename
        if (!empty($thumb) && $thumb !== 'noimage.jpg') {
            $group = self::getImageGroupFromSql($thumb);
            if (count($group) > 1) {
                return $group;
            }
        }

        // 4. Lookup in master SQL map by existing single slider image in DB
        if (!empty($itemId)) {
            try {
                if (Schema::hasTable('user_item_images')) {
                    $singleImg = DB::table('user_item_images')
                        ->where('item_id', $itemId)
                        ->whereNotNull('image')
                        ->where('image', '!=', '')
                        ->where('image', '!=', 'noimage.jpg')
                        ->value('image');

                    if (!empty($singleImg)) {
                        $group = self::getImageGroupFromSql($singleImg);
                        if (count($group) > 1) {
                            return $group;
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        // 5. Lookup by product title/slug match in user_item_contents
        if (!empty($itemId)) {
            try {
                if (Schema::hasTable('user_item_contents') && Schema::hasTable('user_item_images')) {
                    $title = DB::table('user_item_contents')->where('item_id', $itemId)->value('title');
                    if (!empty($title)) {
                        $siblingItemId = DB::table('user_item_contents')
                            ->join('user_item_images', 'user_item_contents.item_id', '=', 'user_item_images.item_id')
                            ->where('user_item_contents.title', $title)
                            ->where('user_item_contents.item_id', '!=', $itemId)
                            ->whereNotNull('user_item_images.image')
                            ->where('user_item_images.image', '!=', '')
                            ->where('user_item_images.image', '!=', 'noimage.jpg')
                            ->groupBy('user_item_images.item_id')
                            ->havingRaw('COUNT(DISTINCT user_item_images.image) >= 2')
                            ->value('user_item_images.item_id');

                        if ($siblingItemId) {
                            $siblingImgs = DB::table('user_item_images')
                                ->where('item_id', $siblingItemId)
                                ->pluck('image')
                                ->toArray();
                            if (count(array_unique($siblingImgs)) > 1) {
                                return array_values(array_unique($siblingImgs));
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        // 6. Fallback: single thumbnail
        if (!empty($thumb) && $thumb !== 'noimage.jpg') {
            return [$thumb];
        }

        return [];
    }

    /**
     * Populate/ensure user_item_images from user_item_images.sql for any item that doesn't have multiple images
     */
    public static function ensureItemImagesForUser(int $userId): void
    {
        try {
            if (!Schema::hasTable('user_items') || !Schema::hasTable('user_item_images')) {
                return;
            }

            $userItems = DB::table('user_items')
                ->where('user_id', $userId)
                ->get(['id', 'thumbnail']);

            if ($userItems->isEmpty()) {
                return;
            }

            $now = now();
            foreach ($userItems as $item) {
                $count = DB::table('user_item_images')
                    ->where('item_id', $item->id)
                    ->where('image', '!=', 'noimage.jpg')
                    ->count();

                if ($count <= 1) {
                    $resolved = self::resolveSliderImagesForItem($item);
                    if (count($resolved) > 1) {
                        DB::table('user_item_images')->where('item_id', $item->id)->delete();
                        $inserts = [];
                        foreach ($resolved as $img) {
                            $inserts[] = [
                                'item_id' => $item->id,
                                'image' => $img,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                        DB::table('user_item_images')->insert($inserts);
                    }
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
                $candidates = [
                    base_path('user_item_images.sql'),
                    database_path('seeds/reset_and_import_user_item_images.sql'),
                    database_path('seeds/whitelabel_agency_user_items.sql'),
                ];
                foreach ($candidates as $cand) {
                    if (file_exists($cand)) {
                        $sql = file_get_contents($cand);
                        if (!empty($sql)) {
                            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                            DB::unprepared($sql);
                            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error importing user_item_images.sql: ' . $e->getMessage());
        }
    }
}
