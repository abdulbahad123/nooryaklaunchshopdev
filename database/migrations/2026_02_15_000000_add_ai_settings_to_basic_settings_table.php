<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            if (!Schema::hasColumn('basic_settings', 'openai_api_key')) {
                $table->string('openai_api_key', 255)->nullable()->after('whatsapp_popup');
            }
            if (!Schema::hasColumn('basic_settings', 'openai_text_model')) {
                $table->string('openai_text_model', 255)->nullable()->after('openai_api_key');
            }
            if (!Schema::hasColumn('basic_settings', 'openai_image_model')) {
                $table->string('openai_image_model', 255)->nullable()->after('openai_text_model');
            }

            if (!Schema::hasColumn('basic_settings', 'gemini_api_key')) {
                $table->string('gemini_api_key', 255)->nullable()->after('openai_image_model');
            }
            if (!Schema::hasColumn('basic_settings', 'gemini_text_model')) {
                $table->string('gemini_text_model', 255)->nullable()->after('gemini_api_key');
            }
            if (!Schema::hasColumn('basic_settings', 'gemini_image_model')) {
                $table->string('gemini_image_model', 255)->nullable()->after('gemini_text_model');
            }

            if (!Schema::hasColumn('basic_settings', 'pollinations_secret_key')) {
                $table->string('pollinations_secret_key', 255)->nullable()->after('gemini_image_model');
            }
            if (!Schema::hasColumn('basic_settings', 'pollinations_text_model')) {
                $table->string('pollinations_text_model', 255)->nullable()->after('pollinations_secret_key');
            }
            if (!Schema::hasColumn('basic_settings', 'pollinations_image_model')) {
                $table->string('pollinations_image_model', 255)->nullable()->after('pollinations_text_model');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            if (Schema::hasColumn('basic_settings', 'pollinations_image_model')) {
                $table->dropColumn('pollinations_image_model');
            }
            if (Schema::hasColumn('basic_settings', 'pollinations_text_model')) {
                $table->dropColumn('pollinations_text_model');
            }
            if (Schema::hasColumn('basic_settings', 'pollinations_secret_key')) {
                $table->dropColumn('pollinations_secret_key');
            }

            if (Schema::hasColumn('basic_settings', 'gemini_image_model')) {
                $table->dropColumn('gemini_image_model');
            }
            if (Schema::hasColumn('basic_settings', 'gemini_text_model')) {
                $table->dropColumn('gemini_text_model');
            }
            if (Schema::hasColumn('basic_settings', 'gemini_api_key')) {
                $table->dropColumn('gemini_api_key');
            }

            if (Schema::hasColumn('basic_settings', 'openai_image_model')) {
                $table->dropColumn('openai_image_model');
            }
            if (Schema::hasColumn('basic_settings', 'openai_text_model')) {
                $table->dropColumn('openai_text_model');
            }
            if (Schema::hasColumn('basic_settings', 'openai_api_key')) {
                $table->dropColumn('openai_api_key');
            }
        });
    }
};
