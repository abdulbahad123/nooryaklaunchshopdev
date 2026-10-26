<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('basic_settings', 'is_openai')) {
                $table->tinyInteger('is_openai')->default(0)->after('whatsapp_popup');
            }
            if (!Schema::hasColumn('basic_settings', 'is_gemini')) {
                $table->tinyInteger('is_gemini')->default(0)->after('openai_image_model');
            }
        });

        // Clean up any dummy keys in basic_settings
        try {
            DB::table('basic_settings')
                ->where('openai_api_key', 'LIKE', '%XXXX%')
                ->orWhere('openai_api_key', 'LIKE', '%DUMMY%')
                ->update(['openai_api_key' => null, 'is_openai' => 0]);

            DB::table('basic_settings')
                ->where('gemini_api_key', 'LIKE', '%XXXX%')
                ->orWhere('gemini_api_key', 'LIKE', '%DUMMY%')
                ->update(['gemini_api_key' => null, 'is_gemini' => 0]);
        } catch (\Throwable $e) {
            // ignore
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            if (Schema::hasColumn('basic_settings', 'is_openai')) {
                $table->dropColumn('is_openai');
            }
            if (Schema::hasColumn('basic_settings', 'is_gemini')) {
                $table->dropColumn('is_gemini');
            }
        });
    }
};
