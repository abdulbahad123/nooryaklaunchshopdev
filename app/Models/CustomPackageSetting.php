<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CustomPackageSetting extends Model
{
    protected $table = 'custom_package_settings';

    protected $fillable = [
        'enterprise_status',
        'enterprise_title',
        'enterprise_subtitle',
        'enterprise_price',
        'enterprise_billing_note',
        'enterprise_features',
        'enterprise_button_text',
        'enterprise_button_url',
        'enterprise_whatsapp_number',
        'enterprise_whatsapp_message',
        'most_popular_status',
        'most_popular_title',
        'most_popular_desc',
        'most_people_choice_status',
        'most_people_choice_title',
        'most_people_choice_desc',
    ];

    public static function getSettings(): self
    {
        self::ensureTableExists();

        $setting = null;
        try {
            $setting = self::first();
        } catch (\Throwable $e) {}

        if (!$setting) {
            try {
                $setting = self::create(self::defaultData());
            } catch (\Throwable $e) {
                $setting = new self(self::defaultData());
            }
        }

        return $setting;
    }

    public static function defaultData(): array
    {
        return [
            'enterprise_status' => 1,
            'enterprise_title' => 'Enterprise',
            'enterprise_subtitle' => 'Kickstart Your E-commerce Business.',
            'enterprise_price' => 'Custom',
            'enterprise_billing_note' => 'Tailored to your needs',
            'enterprise_features' => "Free .com Domain Every Year\nEverything in Premium\nUnlimited Staff Accounts\nDedicated Account Manager\nCustom Integrations\nSLA & Uptime Guarantee\nPriority 24/7 Support",
            'enterprise_button_text' => 'Talk to Sales',
            'enterprise_button_url' => '/contact',
            'enterprise_whatsapp_number' => '917200770351',
            'enterprise_whatsapp_message' => 'Hi, I want to enquire about the Enterprise Plan for Ecom Builder.',
            'most_popular_status' => 1,
            'most_popular_title' => 'Most Popular',
            'most_popular_desc' => 'Ideal balance for growing businesses with all essential tools.',
            'most_people_choice_status' => 1,
            'most_people_choice_title' => 'Most People Choice',
            'most_people_choice_desc' => 'Best value with all premium features and a free domain.',
        ];
    }

    public static function ensureTableExists(): void
    {
        try {
            if (!Schema::hasTable('custom_package_settings')) {
                Schema::create('custom_package_settings', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->tinyInteger('enterprise_status')->default(1);
                    $table->string('enterprise_title')->nullable()->default('Enterprise');
                    $table->string('enterprise_subtitle')->nullable()->default('Kickstart Your E-commerce Business.');
                    $table->string('enterprise_price')->nullable()->default('Custom');
                    $table->string('enterprise_billing_note')->nullable()->default('Tailored to your needs');
                    $table->text('enterprise_features')->nullable();
                    $table->string('enterprise_button_text')->nullable()->default('Talk to Sales');
                    $table->string('enterprise_button_url')->nullable()->default('/contact');
                    $table->string('enterprise_whatsapp_number')->nullable()->default('917200770351');
                    $table->text('enterprise_whatsapp_message')->nullable();
                    $table->tinyInteger('most_popular_status')->default(1);
                    $table->string('most_popular_title')->nullable()->default('Most Popular');
                    $table->text('most_popular_desc')->nullable();
                    $table->tinyInteger('most_people_choice_status')->default(1);
                    $table->string('most_people_choice_title')->nullable()->default('Most People Choice');
                    $table->text('most_people_choice_desc')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {}
    }

    public function getWhatsAppUrl(): string
    {
        $rawNum = $this->enterprise_whatsapp_number ?: '917200770351';
        $cleanNum = preg_replace('/[^0-9]/', '', $rawNum);
        $rawMsg = $this->enterprise_whatsapp_message ?: 'Hi, I want to enquire about the Enterprise Plan for Ecom Builder.';
        return 'https://wa.me/' . $cleanNum . '?text=' . rawurlencode($rawMsg);
    }
}