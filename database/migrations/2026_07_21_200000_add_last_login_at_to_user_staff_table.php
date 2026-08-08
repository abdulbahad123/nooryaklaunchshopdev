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
        if (Schema::hasTable('user_staff')) {
            Schema::table('user_staff', function (Blueprint $table) {
                if (!Schema::hasColumn('user_staff', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_staff')) {
            Schema::table('user_staff', function (Blueprint $table) {
                if (Schema::hasColumn('user_staff', 'last_login_at')) {
                    $table->dropColumn('last_login_at');
                }
            });
        }
    }
};
