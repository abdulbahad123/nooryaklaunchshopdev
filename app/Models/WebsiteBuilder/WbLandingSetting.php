<?php

namespace App\Models\WebsiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbLandingSetting extends Model
{
    use HasFactory;

    protected $table = 'wb_landing_settings';

    protected $fillable = [
        'hero_badge',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'cta_primary_text',
        'cta_primary_url',
        'cta_secondary_text',
        'cta_secondary_url',
        'primary_color',
        'secondary_color',
        'trust_badges',
        'features_data',
        'process_data',
        'testimonials_data',
        'faq_data',
        'contact_email',
        'contact_phone',
        'contact_address',
        'footer_text',
        'custom_css',
    ];

    protected $casts = [
        'trust_badges'      => 'array',
        'features_data'     => 'array',
        'process_data'      => 'array',
        'testimonials_data' => 'array',
        'faq_data'          => 'array',
    ];

    public static function getSettings(): self
    {
        $setting = self::first();
        if (!$setting) {
            $setting = self::create([
                'hero_badge'        => '⚡ No-coding required',
                'hero_title'        => 'Build Your Website in Just Few Minutes',
                'hero_subtitle'     => 'Create beautiful, professional websites in minutes with our intuitive drag-and-drop builder and AI-powered features.',
                'cta_primary_text'  => 'Get Started Free',
                'cta_primary_url'   => '/user/register',
                'cta_secondary_text'=> 'View Templates',
                'cta_secondary_url' => '#templates',
                'primary_color'     => '#6366f1',
                'secondary_color'   => '#8b5cf6',
                'trust_badges'      => [
                    ['icon' => 'shield-check', 'text' => 'No Technical Skills Required'],
                    ['icon' => 'zap',          'text' => 'Instant Setup'],
                    ['icon' => 'layers',       'text' => '10k+ Business Templates'],
                ],
                'features_data'     => [
                    ['icon' => 'smartphone',    'title' => 'Mobile Optimized',      'desc' => 'Looks perfect on every screen size.'],
                    ['icon' => 'search',        'title' => 'SEO Ready',            'desc' => 'Built to rank high on Google search.'],
                    ['icon' => 'globe',         'title' => 'Custom Domain',         'desc' => 'Connect your custom .com domain instantly.'],
                    ['icon' => 'zap',           'title' => 'Fast Hosting',          'desc' => 'Lightning-fast load times globally.'],
                    ['icon' => 'lock',          'title' => 'Secure SSL',            'desc' => 'Free security certificate included.'],
                    ['icon' => 'bar-chart-3',   'title' => 'Analytics',             'desc' => 'Track your visitors and traffic easily.'],
                    ['icon' => 'sparkles',      'title' => 'AI Page Rewriter',     'desc' => 'Regenerate content anytime with AI.'],
                    ['icon' => 'award',         'title' => 'Client-Ready White Label','desc' => 'Create & manage websites under your own brand.'],
                ],
                'process_data'      => [
                    ['step' => '01', 'title' => 'Choose a Template', 'desc' => 'Select from our gallery of professionally designed templates.'],
                    ['step' => '02', 'title' => 'Customize Content', 'desc' => 'Use our visual editor to update text, images, and colors.'],
                    ['step' => '03', 'title' => 'Publish to World',  'desc' => 'Connect your custom domain and go live with a single click.'],
                ],
                'testimonials_data' => [
                    ['name' => 'Sarah Johnson', 'role' => 'Small Business Owner', 'rating' => 5, 'comment' => 'Website Builder made it so easy to create our business website. The templates are beautiful and support is excellent!'],
                    ['name' => 'Mike Chen',    'role' => 'Freelance Designer',   'rating' => 5, 'comment' => 'As a freelancer, I needed a professional portfolio fast. Website Builder delivered exactly what I needed.'],
                    ['name' => 'Emily Davis',  'role' => 'Marketing Manager',    'rating' => 5, 'comment' => 'The AI tools and ease of use are incredible. I built our entire site in just a few hours!'],
                ],
                'faq_data'          => [
                    ['q' => 'Is coding required?', 'a' => 'Not at all! Our visual drag-and-drop builder handles all technical details for you.'],
                    ['q' => 'Can I use my own domain?', 'a' => 'Yes, you can easily map your custom domain (.com, .in, etc.) in 1 click.'],
                    ['q' => 'Is SSL included?', 'a' => 'Yes, free SSL certificates are automatically provisioned for all websites.'],
                ],
                'contact_email'     => 'hello@websitebuilder.com',
                'contact_phone'     => '+1 (800) 123-4567',
                'contact_address'   => '123 Business St, Suite 100, New York, NY 10001',
                'footer_text'       => 'The easiest way to build professional websites. No coding required.',
                'custom_css'        => '',
            ]);
        }
        return $setting;
    }
}
