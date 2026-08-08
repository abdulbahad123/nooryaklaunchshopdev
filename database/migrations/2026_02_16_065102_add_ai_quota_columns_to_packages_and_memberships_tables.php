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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('ai_engine', 50)
                ->nullable()
                ->after('number_of_custom_page');

            $table->unsignedBigInteger('ai_token_limit')
                ->default(0)
                ->after('ai_engine');

            $table->unsignedInteger('ai_image_limit')
                ->default(0)
                ->after('ai_token_limit');
        });



        // ===================================
        // Add AI snapshot + usage to memberships
        // ===================================
        Schema::table('memberships', function (Blueprint $table) {
            $table->string('ai_engine', 50)
                ->nullable()
                ->after('conversation_id');

            $table->unsignedBigInteger('ai_token_limit')
                ->default(0)
                ->after('ai_engine');

            $table->unsignedInteger('ai_image_limit')
                ->default(0)
                ->after('ai_token_limit');

            $table->unsignedBigInteger('ai_used_tokens')
                ->default(0)
                ->after('ai_image_limit');

            $table->unsignedInteger('ai_used_images')
                ->default(0)
                ->after('ai_used_tokens');

            $table->unsignedBigInteger('ai_token_purchased')
                ->default(0)
                ->after('ai_used_images');

            $table->unsignedInteger('ai_image_purchased')
                ->default(0)
                ->after('ai_token_purchased');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn([
                'ai_engine',
                'ai_token_limit',
                'ai_image_limit',
                'ai_used_tokens',
                'ai_used_images',
                'ai_token_purchased',
                'ai_image_purchased',
            ]);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'ai_engine',
                'ai_token_limit',
                'ai_image_limit',
            ]);
        });
    }
};
