<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ImageText extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function ensureColumnsExist(): void
    {
        try {
            if (Schema::hasTable('image_texts')) {
                Schema::table('image_texts', function (Blueprint $table) {
                    $cols = [
                        'hero_badge1_title',
                        'hero_badge1_subtitle',
                        'hero_badge2_title',
                        'hero_badge2_subtitle',
                        'hero_badge3_title',
                        'hero_badge3_subtitle',
                        'work_process_section_subtitle',
                        'work_process_image',
                        'work_process_btn_text',
                        'work_process_btn_url',
                        'dashboard_image',
                        'features_bullet1',
                        'features_bullet2',
                        'features_bullet3',
                        'features_bullet4',
                    ];
                    foreach ($cols as $col) {
                        if (!Schema::hasColumn('image_texts', $col)) {
                            $table->string($col)->nullable();
                        }
                    }
                });
            }
        } catch (\Throwable $e) {}
    }
}