<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        // 1. Update user_contacts table for all preview/demo templates and any records containing 6374913298
        if (Schema::hasTable('user_contacts')) {
            DB::table('user_contacts')
                ->where('contact_numbers', '6374913298')
                ->orWhere('contact_numbers', 'like', '%6374913298%')
                ->update(['contact_numbers' => '72007 70351']);

            $userIds = DB::table('users')
                ->where('preview_template', 1)
                ->orWhereIn('username', [
                    'grocery', 'furial', 'fashclo', 'electi', 'kidsfa', 
                    'manti', 'petrashop', 'skinflow', 'jewellery', 'clothing'
                ])
                ->pluck('id')
                ->toArray();

            if (!empty($userIds)) {
                DB::table('user_contacts')->whereIn('user_id', $userIds)->update([
                    'contact_numbers' => '72007 70351'
                ]);
            }
        }

        // 2. Update basic_extendeds table for the LaunchShop landing page
        if (Schema::hasTable('basic_extendeds')) {
            DB::table('basic_extendeds')
                ->where('contact_numbers', '6374913298')
                ->orWhere('contact_numbers', 'like', '%6374913298%')
                ->update(['contact_numbers' => '72007 70351']);

            DB::table('basic_extendeds')->update([
                'contact_numbers' => '72007 70351'
            ]);
        }
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
