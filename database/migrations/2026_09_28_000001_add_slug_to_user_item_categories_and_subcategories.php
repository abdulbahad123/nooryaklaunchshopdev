<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Add slug to user_item_categories if missing
        if (!Schema::hasColumn('user_item_categories', 'slug')) {
            Schema::table('user_item_categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });

            // Backfill slugs from name
            $categories = DB::table('user_item_categories')->get();
            foreach ($categories as $cat) {
                $baseSlug = Str::slug($cat->name ?? 'category-' . $cat->id);
                $slug = $baseSlug ?: 'category-' . $cat->id;
                // Ensure uniqueness per user
                $count = DB::table('user_item_categories')
                    ->where('user_id', $cat->user_id)
                    ->where('slug', $slug)
                    ->where('id', '!=', $cat->id)
                    ->count();
                if ($count > 0) {
                    $slug = $slug . '-' . $cat->id;
                }
                DB::table('user_item_categories')
                    ->where('id', $cat->id)
                    ->update(['slug' => $slug]);
            }
        }

        // Add slug to user_item_sub_categories if missing
        if (!Schema::hasColumn('user_item_sub_categories', 'slug')) {
            Schema::table('user_item_sub_categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });

            // Backfill slugs from name
            $subcategories = DB::table('user_item_sub_categories')->get();
            foreach ($subcategories as $subcat) {
                $baseSlug = Str::slug($subcat->name ?? 'subcategory-' . $subcat->id);
                $slug = $baseSlug ?: 'subcategory-' . $subcat->id;
                $count = DB::table('user_item_sub_categories')
                    ->where('user_id', $subcat->user_id)
                    ->where('slug', $slug)
                    ->where('id', '!=', $subcat->id)
                    ->count();
                if ($count > 0) {
                    $slug = $slug . '-' . $subcat->id;
                }
                DB::table('user_item_sub_categories')
                    ->where('id', $subcat->id)
                    ->update(['slug' => $slug]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user_item_categories', 'slug')) {
            Schema::table('user_item_categories', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
        if (Schema::hasColumn('user_item_sub_categories', 'slug')) {
            Schema::table('user_item_sub_categories', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
