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
        $userIds = DB::table('users')
            ->where('preview_template', 1)
            ->orWhereIn('username', [
                'grocery', 'furial', 'fashclo', 'electi', 'kidsfa', 
                'manti', 'petrashop', 'skinflow', 'jewellery', 'clothing'
            ])
            ->pluck('id')
            ->toArray();

        if (Schema::hasTable('user_contacts')) {
            DB::table('user_contacts')->whereIn('user_id', $userIds)->update([
                'contact_numbers' => '72007 70351',
                'contact_mails' => 'nooryaktechnologies@gmail.com',
                'contact_addresses' => 'Triplicane, Chennai, IND'
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
        // Reverting this data migration is not required/supported as the template values were different.
    }
};
