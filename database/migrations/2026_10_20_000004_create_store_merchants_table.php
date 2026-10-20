<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('store_merchants')) {
            Schema::create('store_merchants', function (Blueprint $table) {
                $table->id();
                $table->integer('language_id')->default(1);
                $table->string('type', 50)->default('featured');
                $table->string('name')->nullable();
                $table->string('designation')->nullable();
                $table->text('quote')->nullable();
                $table->integer('rating')->default(5);
                $table->string('image')->nullable();
                $table->string('icon')->nullable();
                $table->string('badge_color')->nullable();
                $table->integer('serial_number')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('store_merchants');
    }
};