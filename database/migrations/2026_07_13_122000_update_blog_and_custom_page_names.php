<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateBlogAndCustomPageNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (\Schema::hasTable('package_features')) {
            DB::table('package_features')
                ->where('keyword', 'Blog')
                ->update(['name' => '{limit} Blogs limit']);

            DB::table('package_features')
                ->where('keyword', 'Custom Page')
                ->update(['name' => '{limit} Custom Pages']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
