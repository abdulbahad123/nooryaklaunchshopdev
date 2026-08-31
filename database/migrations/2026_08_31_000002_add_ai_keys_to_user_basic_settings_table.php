<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('user_basic_settings', 'is_ai')) {
                $table->tinyInteger('is_ai')->default(1);
            }
            if (!Schema::hasColumn('user_basic_settings', 'is_gemini')) {
                $table->tinyInteger('is_gemini')->default(1);
            }
            if (!Schema::hasColumn('user_basic_settings', 'is_openai')) {
                $table->tinyInteger('is_openai')->default(1);
            }
            if (!Schema::hasColumn('user_basic_settings', 'gemini_api_key')) {
                $table->string('gemini_api_key', 255)->nullable();
            }
            if (!Schema::hasColumn('user_basic_settings', 'openai_api_key')) {
                $table->string('openai_api_key', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('user_basic_settings', 'is_ai')) {
                $columnsToDrop[] = 'is_ai';
            }
            if (Schema::hasColumn('user_basic_settings', 'gemini_api_key')) {
                $columnsToDrop[] = 'gemini_api_key';
            }
            if (Schema::hasColumn('user_basic_settings', 'openai_api_key')) {
                $columnsToDrop[] = 'openai_api_key';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
