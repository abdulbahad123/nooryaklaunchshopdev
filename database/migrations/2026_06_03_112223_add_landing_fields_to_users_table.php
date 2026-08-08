<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLandingFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('landing_status')->default(1)->after('status');
            $table->decimal('landing_rating', 3, 2)->default(4.80)->after('landing_status');
            $table->text('landing_description')->nullable()->after('landing_rating');
            $table->integer('landing_order')->default(0)->after('landing_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['landing_status', 'landing_rating', 'landing_description', 'landing_order']);
        });
    }
}
