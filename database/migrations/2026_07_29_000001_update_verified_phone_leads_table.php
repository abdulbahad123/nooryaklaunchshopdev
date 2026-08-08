<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVerifiedPhoneLeadsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('verified_phone_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('verified_phone_leads', 'email')) {
                $table->string('email')->nullable()->after('country_code');
            }
            if (!Schema::hasColumn('verified_phone_leads', 'status')) {
                $table->string('status')->default('Not Purchased')->after('purchased');
            }
            if (!Schema::hasColumn('verified_phone_leads', 'status_date')) {
                $table->timestamp('status_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('verified_phone_leads', 'is_verified')) {
                $table->boolean('is_verified')->default(true)->after('status_date');
            }
            if (!Schema::hasColumn('verified_phone_leads', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('verified_phone_leads', function (Blueprint $table) {
            $table->dropColumn(['email', 'status', 'status_date', 'is_verified']);
            $table->dropSoftDeletes();
        });
    }
}
