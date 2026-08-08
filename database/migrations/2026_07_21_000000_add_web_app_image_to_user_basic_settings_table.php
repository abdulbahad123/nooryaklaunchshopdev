<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('user_basic_settings', 'web_app_image')) {
                $table->string('web_app_image', 255)->nullable()->after('logo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            if (Schema::hasColumn('user_basic_settings', 'web_app_image')) {
                $table->dropColumn('web_app_image');
            }
        });
    }
};
