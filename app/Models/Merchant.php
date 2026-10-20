<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Merchant extends Model
{
    protected $table = 'store_merchants';
    protected $guarded = [];

    public static function ensureTableExistsAndSeeded($langId = null)
    {
        if (!Schema::hasTable('store_merchants')) {
            Schema::create('store_merchants', function (Blueprint $table) {
                $table->id();
                $table->integer('language_id')->default(1);
                $table->string('type', 50)->default('featured'); // 'featured', 'review', 'why_choose'
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

        // Check if seeded
        $targetLangId = $langId ?: 1;
        $count = self::where('language_id', $targetLangId)->count();
        if ($count === 0) {
            // Seed Featured Merchants
            $featured = [
                ['name' => 'Ayaan Patel', 'designation' => 'Founder, Electri', 'quote' => 'Expanded to 5 cities with EcomBuilder.in.', 'image' => 'indian_man.png', 'serial_number' => 1],
                ['name' => 'Sneha Reddy', 'designation' => 'Founder, Urban Chic', 'quote' => 'Built a fashion brand customers love.', 'image' => 'indian_woman.png', 'serial_number' => 2],
                ['name' => 'Rahul Verma', 'designation' => 'Founder, TechGadgets', 'quote' => '3x revenue growth in just 6 months.', 'image' => 'indian_man.png', 'serial_number' => 3],
                ['name' => 'Nooryak Khan', 'designation' => 'Founder, FreshMart', 'quote' => 'Scaled from 0 to 10K+ orders in 3 months.', 'image' => 'indian_man.png', 'serial_number' => 4],
                ['name' => 'Priya Mehta', 'designation' => 'Founder, Skinflow', 'quote' => 'Turned passion into a profitable brand.', 'image' => 'indian_woman.png', 'serial_number' => 5],
                ['name' => 'Ananya Sharma', 'designation' => 'Founder, DecorCraft', 'quote' => 'Super fast setup and excellent conversion rates.', 'image' => 'indian_woman.png', 'serial_number' => 6],
            ];
            foreach ($featured as $item) {
                self::create(array_merge($item, [
                    'language_id' => $targetLangId,
                    'type' => 'featured',
                    'status' => 1,
                ]));
            }

            // Seed Loved by Merchants Reviews
            $reviews = [
                ['name' => 'Priya Mehta', 'designation' => 'Founder, Skinflow', 'quote' => 'I love how customizable everything is. My store looks premium and my customers trust my brand.', 'rating' => 5, 'serial_number' => 1],
                ['name' => 'Ayaan Patel', 'designation' => 'Founder, Electri', 'quote' => 'From setup to sales, EcomBuilder.in has been my growth partner. Highly recommended!', 'rating' => 5, 'serial_number' => 2],
                ['name' => 'Sneha Reddy', 'designation' => 'Founder, Urban Chic', 'quote' => 'The analytics dashboard is a game changer. I know exactly where my sales are coming from every single day.', 'rating' => 5, 'serial_number' => 3],
                ['name' => 'Nooryak Khan', 'designation' => 'Founder, FreshMart', 'quote' => 'EcomBuilder.in made it incredibly easy to launch my store. The themes are beautiful and the support is outstanding.', 'rating' => 5, 'serial_number' => 4],
            ];
            foreach ($reviews as $item) {
                self::create(array_merge($item, [
                    'language_id' => $targetLangId,
                    'type' => 'review',
                    'status' => 1,
                ]));
            }

            // Seed Why Choose Cards
            $whyChoose = [
                ['name' => 'Beautiful & Fast Themes', 'quote' => 'Mobile-first, high-converting themes that look stunning.', 'icon' => 'fas fa-mobile-alt', 'badge_color' => 'orange', 'serial_number' => 1],
                ['name' => 'Easy Customization', 'quote' => 'Build your brand your way without any coding.', 'icon' => 'fas fa-sliders-h', 'badge_color' => 'red', 'serial_number' => 2],
                ['name' => 'Powerful Features', 'quote' => 'Everything you need to sell, grow & succeed online.', 'icon' => 'fas fa-rocket', 'badge_color' => 'blue', 'serial_number' => 3],
                ['name' => 'Trusted Support', 'quote' => 'Dedicated support team whenever you need us.', 'icon' => 'fas fa-headset', 'badge_color' => 'green', 'serial_number' => 4],
                ['name' => 'Secure & Reliable', 'quote' => 'Built on a secure platform you can count on.', 'icon' => 'fas fa-shield-alt', 'badge_color' => 'orange', 'serial_number' => 5],
            ];
            foreach ($whyChoose as $item) {
                self::create(array_merge($item, [
                    'language_id' => $targetLangId,
                    'type' => 'why_choose',
                    'status' => 1,
                ]));
            }
        }
    }
}