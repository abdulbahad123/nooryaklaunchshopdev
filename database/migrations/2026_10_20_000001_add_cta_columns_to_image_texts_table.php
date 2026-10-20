<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('page_ctas')) {
            Schema::create('page_ctas', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('language_id')->default(1);
                $table->string('page', 50)->default('global');
                $table->string('status', 10)->default('1');
                $table->text('title')->nullable();
                $table->text('subtitle')->nullable();
                $table->string('btn_text')->nullable();
                $table->string('btn_url')->nullable();
                $table->string('feat1')->nullable();
                $table->string('feat2')->nullable();
                $table->string('feat3')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
                $table->unique(['language_id', 'page']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_ctas');
    }
};