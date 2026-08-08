u<?php

use Illuminate\Database\Migrations\Migration;
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
        // Update whatsapp_header_title in the basic_settings table for all rows
        DB::table('basic_settings')
            ->where('whatsapp_header_title', 'Hi, There!')
            ->orWhere('whatsapp_header_title', 'Hi, there!')
            ->orWhereNull('whatsapp_header_title')
            ->update(['whatsapp_header_title' => 'LaunchShop Support']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('basic_settings')
            ->where('whatsapp_header_title', 'LaunchShop Support')
            ->update(['whatsapp_header_title' => 'Hi, There!']);
    }
};
