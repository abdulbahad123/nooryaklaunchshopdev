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
                    $table->string('page', 50)->default('global');
                    $table->string('status', 10)->default('1');
                    $table->text('title')->nullable();
                    $table->text('subtitle')->nullable();
                    $table->string('btn_text')->nullable();
                    $table->string('btn_url')->nullable();
                    $table->string('feat1')->nullable();
                    $table->string('feat2')->nullable();
                    $table->string('feat3')->nullable();
                    $table->string('image')->nullable();
                    $table->timestamps();
                    $table->unique(['language_id', 'page']);
                });
            }
        } catch (\Throwable $e) {}
    }
}