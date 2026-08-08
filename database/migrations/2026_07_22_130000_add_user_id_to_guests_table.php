<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('guests')) {
            Schema::table('guests', function (Blueprint $table) {
                if (!Schema::hasColumn('guests', 'user_id')) {
                    $table->unsignedInteger('user_id')->nullable()->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('guests')) {
            Schema::table('guests', function (Blueprint $table) {
                if (Schema::hasColumn('guests', 'user_id')) {
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};
