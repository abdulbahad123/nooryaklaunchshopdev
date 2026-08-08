<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('keyword')->nullable();
            $table->string('type')->default('standard'); // standard, limit, custom
            $table->string('limit_key')->nullable();
            $table->integer('serial_number')->default(0);
            $table->timestamps();
        });

        $defaultFeatures = [
            ['name' => '1 Online Store', 'keyword' => 'Online Store', 'type' => 'custom', 'limit_key' => null],
            ['name' => '{limit} Products limit', 'keyword' => 'product_limit', 'type' => 'limit', 'limit_key' => 'product_limit'],
            ['name' => '{limit} Categories limit', 'keyword' => 'categories_limit', 'type' => 'limit', 'limit_key' => 'categories_limit'],
            ['name' => 'Orders Limit : {limit}', 'keyword' => 'order_limit', 'type' => 'limit', 'limit_key' => 'order_limit'],
            ['name' => 'Unlimited Customers', 'keyword' => 'Unlimited Customers', 'type' => 'custom', 'limit_key' => null],
            ['name' => 'Additional Languages', 'keyword' => 'language_limit', 'type' => 'limit', 'limit_key' => 'language_limit'],
            ['name' => 'Posts Limit : {limit}', 'keyword' => 'post_limit', 'type' => 'limit', 'limit_key' => 'post_limit'],
            ['name' => 'Subdomain Store', 'keyword' => 'Subdomain', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'Custom Domain Mapping', 'keyword' => 'Custom Domain', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'QR Builder', 'keyword' => 'QR Builder', 'type' => 'standard', 'limit_key' => null],
            ['name' => '{limit} Blogs limit', 'keyword' => 'Blog', 'type' => 'standard', 'limit_key' => 'post_limit'],
            ['name' => '{limit} Custom Pages', 'keyword' => 'Custom Page', 'type' => 'standard', 'limit_key' => 'number_of_custom_page'],
            ['name' => 'Google Login', 'keyword' => 'Google Login', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'Google Analytics', 'keyword' => 'Google Analytics', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'Facebook Pixel', 'keyword' => 'Facebook Pixel', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'Google ReCAPTCHA', 'keyword' => 'Google Recaptcha', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'WhatsApp Chat Button', 'keyword' => 'WhatsApp Chat Button', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'Tawk.to Chat Bot', 'keyword' => 'Tawk to', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'Disqus', 'keyword' => 'Disqus', 'type' => 'standard', 'limit_key' => null],
            ['name' => 'AI Content & Image Generator', 'keyword' => 'AI Content & Image Generator', 'type' => 'standard', 'limit_key' => null],
        ];

        foreach ($defaultFeatures as $index => $feat) {
            \DB::table('package_features')->insert([
                'name' => $feat['name'],
                'keyword' => $feat['keyword'],
                'type' => $feat['type'],
                'limit_key' => $feat['limit_key'],
                'serial_number' => ($index + 1) * 10,
                'created_at' => now(),
                'updated_at' => now(),
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
        Schema::dropIfExists('package_features');
    }
}
