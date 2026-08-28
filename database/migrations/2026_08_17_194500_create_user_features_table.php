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
        if (!Schema::hasTable('user_features')) {
            Schema::create('user_features', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->integer('language_id')->nullable();
                $table->string('icon', 255)->nullable();
                $table->string('title', 255)->nullable();
                $table->text('text')->nullable();
                $table->integer('serial_number')->default(0);
                $table->timestamps();
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
        Schema::dropIfExists('user_features');
    }
};
