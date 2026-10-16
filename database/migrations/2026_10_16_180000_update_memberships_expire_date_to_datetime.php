<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Modifies start_date and expire_date on memberships table from DATE to DATETIME
     * to support minute-level testing and precise expiration enforcement.
     *
     * @return void
     */
    public function up()
    {
        try {
            if (Schema::hasTable('memberships')) {
                DB::statement("ALTER TABLE `memberships` MODIFY `start_date` DATETIME NULL;");
                DB::statement("ALTER TABLE `memberships` MODIFY `expire_date` DATETIME NULL;");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Migration memberships datetime update notice: " . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            if (Schema::hasTable('memberships')) {
                DB::statement("ALTER TABLE `memberships` MODIFY `start_date` DATE NULL;");
                DB::statement("ALTER TABLE `memberships` MODIFY `expire_date` DATE NULL;");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Migration memberships datetime rollback notice: " . $e->getMessage());
        }
    }
};