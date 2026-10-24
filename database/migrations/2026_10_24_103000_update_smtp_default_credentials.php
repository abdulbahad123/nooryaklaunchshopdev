<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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
        try {
            if (Schema::hasTable('basic_extendeds')) {
                DB::table('basic_extendeds')
                    ->where('smtp_host', 'like', '%metroshop.in%')
                    ->orWhere('smtp_host', 'like', '%nooryak.in%')
                    ->orWhereNull('smtp_host')
                    ->orWhere('smtp_host', '')
                    ->update([
                        'smtp_host'     => 'mail.saasreselling.com',
                        'smtp_port'     => '465',
                        'encryption'    => 'ssl',
                        'smtp_username' => 'admin@saasreselling.com',
                        'smtp_password' => 'Admin@nooryak',
                        'from_mail'     => 'admin@saasreselling.com',
                        'is_smtp'       => 1,
                    ]);
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
