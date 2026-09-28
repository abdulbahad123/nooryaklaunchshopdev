<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixSliderImages extends Command
{
    protected $signature   = 'fix:sliders';
    protected $description = 'One-time fix: backfill slider images from thumbnails for products with noimage.jpg or no slider';

    public function handle(): int
    {
        $this->info('Fixing slider images...');

        // ── Step 1: Items where ALL sliders are noimage.jpg ──────────────────
        $noImageItems = DB::table('user_item_images')
            ->select('item_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(image = "noimage.jpg") as noimage_count'))
            ->groupBy('item_id')
            ->havingRaw('total = noimage_count')
            ->pluck('item_id')
            ->toArray();

        $this->info('Items with only noimage.jpg: ' . count($noImageItems));

        $fixed   = 0;
        $skipped = 0;

        foreach ($noImageItems as $itemId) {
            $item = DB::table('user_items')->where('id', $itemId)->first();
            if (!$item || empty($item->thumbnail) || $item->thumbnail === 'noimage.jpg') {
                $skipped++;
                continue;
            }

            DB::table('user_item_images')->where('item_id', $itemId)->delete();
            DB::table('user_item_images')->insert([
                'item_id'    => $itemId,
                'image'      => 'thumbnail/' . $item->thumbnail,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $fixed++;
            $this->line("  Fixed item #{$itemId}: thumbnail/{$item->thumbnail}");
        }

        // ── Step 2: Items with NO slider record at all ────────────────────────
        $noSliderItems = DB::table('user_items')
            ->leftJoin('user_item_images', 'user_items.id', '=', 'user_item_images.item_id')
            ->whereNull('user_item_images.item_id')
            ->whereNotNull('user_items.thumbnail')
            ->where('user_items.thumbnail', '!=', '')
            ->where('user_items.thumbnail', '!=', 'noimage.jpg')
            ->select('user_items.id', 'user_items.thumbnail')
            ->get();

        $this->info('Items with no slider record: ' . $noSliderItems->count());

        foreach ($noSliderItems as $item) {
            DB::table('user_item_images')->insert([
                'item_id'    => $item->id,
                'image'      => 'thumbnail/' . $item->thumbnail,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $fixed++;
            $this->line("  Added slider for item #{$item->id}: thumbnail/{$item->thumbnail}");
        }

        $this->newLine();
        $this->info("Done! Fixed: {$fixed}  |  Skipped (no thumbnail): {$skipped}");

        return Command::SUCCESS;
    }
}
