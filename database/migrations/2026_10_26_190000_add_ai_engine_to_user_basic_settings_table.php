<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('user_basic_settings', 'ai_engine')) {
                $table->string('ai_engine', 50)->default('gemini')->after('openai_api_key');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            if (Schema::hasColumn('user_basic_settings', 'ai_engine')) {
                $table->dropColumn('ai_engine');
            }
        });
    }
};
