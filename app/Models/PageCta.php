<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PageCta extends Model
{
    protected $table = 'page_ctas';

    protected $fillable = [
        'language_id',
        'page',
        'status',
        'title',
        'subtitle',
        'btn_text',
        'btn_url',
        'feat1',
        'feat2',
        'feat3',
        'image',
    ];

    /**
     * Self-healing: ensure table exists automatically
     */
    public static function ensureTableExists(): void
    {
        try {
            if (!Schema::hasTable('page_ctas')) {
                Schema::create('page_ctas', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->integer('language_id')->default(1);
                    $table->string('page', 50)->default('home');
                    $table->string('status', 10)->default('1');
                    $table->text('title')->nullable();
                    $table->text('subtitle')->nullable();
                    $table->text('btn_text')->nullable();
                    $table->text('btn_url')->nullable();
                    $table->text('feat1')->nullable();
                    $table->text('feat2')->nullable();
                    $table->text('feat3')->nullable();
                    $table->text('image')->nullable();
                    $table->timestamps();
                    $table->unique(['language_id', 'page']);
                });
            }

            // Migrate any existing 'global' row data to 'home' and other pages if they don't have custom text yet
            $globalRow = self::where('page', 'global')->first();
            if ($globalRow) {
                foreach (['home', 'pricing', 'templates', 'shops', 'about', 'contact'] as $p) {
                    $existingP = self::where('page', $p)->first();
                    if (!$existingP && !empty($globalRow->title)) {
                        self::create([
                            'language_id' => $globalRow->language_id ?? 1,
                            'page'        => $p,
                            'status'      => $globalRow->status ?? '1',
                            'title'       => $globalRow->title,
                            'subtitle'    => $globalRow->subtitle,
                            'btn_text'    => $globalRow->btn_text,
                            'btn_url'     => $globalRow->btn_url,
                            'feat1'       => $globalRow->feat1,
                            'feat2'       => $globalRow->feat2,
                            'feat3'       => $globalRow->feat3,
                            'image'       => $globalRow->image,
                        ]);
                    }
                }
                self::where('page', 'global')->delete();
            }
        } catch (\Throwable $e) {}
    }
}