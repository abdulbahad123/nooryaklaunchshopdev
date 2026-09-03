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
        // Who / Audience section
        'who_label',
        'who_brand_name',
        'who_subtitle',
        'who_description',
        'audiences_data',
        // Use Cases / Visionaries section
        'usecases_label',
        'usecases_title',
        'usecases_subtitle',
        'usecases_data',
        // Process section
        'process_label',
        'process_heading',
        'process_subtitle',
        'process_data',
        // Features section
        'features_label',
        'features_heading',
        'features_subtitle',
        'features_data',
        // Templates section
        'templates_label',
        'templates_heading',
        'templates_subtitle',
        // Pricing section
        'pricing_label',
        'pricing_heading',
        'pricing_subtitle',
        // Testimonials section
        'testimonials_label',
        'testimonials_heading',
        'testimonials_data',
        // CTA Banner section
        'cta_banner_title',
        'cta_banner_subtitle',
        'cta_banner_trust',
        'cta_banner_image',
        // Contact section
        'contact_heading',
        'contact_subtitle',
        'contact_email',
        'contact_phone',
        'contact_address',
        // Footer
        'footer_brand_name',
        'footer_text',
        'footer_copyright',
        'footer_social',
        // FAQ
        'faq_data',
        // Custom
        'custom_css',
    ];

    protected $casts = [
        'trust_badges'      => 'array',
        'audiences_data'    => 'array',
        'usecases_data'     => 'array',
        'features_data'     => 'array',
        'process_data'      => 'array',
        'testimonials_data' => 'array',
        'cta_banner_trust'  => 'array',
        'footer_social'     => 'array',
        'faq_data'          => 'array',
    ];

    public static function getSettings(): self
    {
        $setting = self::first();
        if (!$setting) {
            $setting = self::create([
                // Hero
                'hero_badge'          => '⚡ No-coding required',
                'hero_title'          => 'Build Your Website in Just Few Minutes',
                'hero_subtitle'       => 'Create beautiful, professional websites in minutes with our intuitive drag-and-drop builder and AI-powered features.',
                'cta_primary_text'    => 'Get Started Free',
                'cta_primary_url'     => '/user/register',
                'cta_secondary_text'  => 'View Templates',
                'cta_secondary_url'   => '#templates',
                'primary_color'       => '#5B4BF5',
                'secondary_color'     => '#7C6CF8',
                'trust_badges'        => [
                    ['icon' => 'shield-check', 'text' => 'No Technical Skills Required'],
                    ['icon' => 'zap',          'text' => 'Instant Setup'],
                    ['icon' => 'layers',       'text' => '10k+ Business Templates'],
                ],
                // Who section
                'who_label'           => "Who it's for",
                'who_brand_name'      => 'website builder',
                'who_subtitle'        => 'Perfect for Every Business & Creator',
                'who_description'     => "Whether you're launching a personal brand, a portfolio, a local business site, or online store — website builder makes it simple.",
                'audiences_data'      => [
                    ['icon' => 'fa-user-circle',   'title' => 'Freelancers', 'color' => '#5B4BF5'],
                    ['icon' => 'fa-rocket',         'title' => 'Startups',    'color' => '#06B6D4'],
                    ['icon' => 'fa-briefcase',      'title' => 'Agencies',    'color' => '#F59E0B'],
                    ['icon' => 'fa-store',          'title' => 'Shops',       'color' => '#EC4899'],
                    ['icon' => 'fa-pen-nib',        'title' => 'Bloggers',    'color' => '#8B5CF6'],
                    ['icon' => 'fa-calendar-days',  'title' => 'Events',      'color' => '#EF4444'],
                ],
                // Use Cases
                'usecases_label'      => 'Use Cases',
                'usecases_title'      => 'Built for Visionaries',
                'usecases_subtitle'   => "Whether you're a freelancer or a founder, we have the perfect starting point.",
                'usecases_data'       => [
                    ['label' => 'Portfolio',  'icon' => 'fa-image',         'color' => '#8B5CF6', 'image' => 'assets/website_builder/wb_card_portfolio.png'],
                    ['label' => 'Startup',    'icon' => 'fa-rocket',        'color' => '#06B6D4', 'image' => 'assets/website_builder/wb_card_startup.png'],
                    ['label' => 'Agency',     'icon' => 'fa-briefcase',     'color' => '#F59E0B', 'image' => 'assets/website_builder/wb_card_agency.png'],
                    ['label' => 'eCommerce',  'icon' => 'fa-cart-shopping', 'color' => '#EC4899', 'image' => 'assets/website_builder/wb_card_ecommerce.png'],
                    ['label' => 'Restaurant', 'icon' => 'fa-utensils',      'color' => '#EF4444', 'image' => 'assets/website_builder/wb_card_restaurant.png'],
                    ['label' => 'Events',     'icon' => 'fa-calendar',      'color' => '#22C55E', 'image' => 'assets/website_builder/wb_card_events.png'],
                ],
                // Process
                'process_label'       => 'Process',
                'process_heading'     => 'Launch in 3 Simple Steps',
                'process_subtitle'    => 'Stop wrestling with code. Our visual editor makes website building as easy as editing a document.',
                'process_data'        => [
                    ['step' => '01', 'title' => 'Choose a Template', 'desc' => 'Select from our gallery of professionally designed, conversion-optimized templates.'],
                    ['step' => '02', 'title' => 'Customize Content', 'desc' => 'Use our visual editor to update text, images, and colors to match your brand.'],
                    ['step' => '03', 'title' => 'Publish to World',  'desc' => 'Connect your custom domain and go live with a single click. SSL included.'],
                ],
                // Features
                'features_label'      => 'Features',
                'features_heading'    => 'Everything You Need',
                'features_subtitle'   => "We've packed all the technical heavy lifting into a simple interface.",
                'features_data'       => [
                    ['icon' => 'fa-mobile-screen',       'title' => 'Mobile Optimized',         'desc' => 'Looks perfect on every screen size.'],
                    ['icon' => 'fa-chart-line',           'title' => 'SEO Ready',                'desc' => 'Built to rank high on Google search.'],
                    ['icon' => 'fa-globe',               'title' => 'Custom Domain',             'desc' => 'Connect your own .com instantly.'],
                    ['icon' => 'fa-bolt',                'title' => 'Fast Hosting',              'desc' => 'Lightning fast load times globally.'],
                    ['icon' => 'fa-shield-halved',       'title' => 'Secure (SSL)',              'desc' => 'Free security certificate included.'],
                    ['icon' => 'fa-rotate',              'title' => 'Analytics',                 'desc' => 'Track your visitors and traffic easily.'],
                    ['icon' => 'fa-wand-magic-sparkles', 'title' => 'AI Page Rewriter',          'desc' => 'Regenerate content anytime with AI.'],
                    ['icon' => 'fa-users',               'title' => 'Client-Ready White Label',  'desc' => 'Create & manage websites under your own brand.'],
                ],
                // Templates
                'templates_label'     => 'Templates',
                'templates_heading'   => 'Start with a Professional Template',
                'templates_subtitle'  => 'Choose a design you love and make it yours.',
                // Pricing
                'pricing_label'       => 'Pricing',
                'pricing_heading'     => 'Simple, Transparent Pricing',
                'pricing_subtitle'    => 'Choose the perfect plan for your needs',
                // Testimonials
                'testimonials_label'   => 'Testimonials',
                'testimonials_heading' => 'Loved by Thousands of Customers',
                'testimonials_data'    => [
                    ['name' => 'Sarah Johnson', 'role' => 'Small Business Owner', 'rating' => 5, 'comment' => '"website builder made it so easy to create our business website. The templates are beautiful and the support is excellent!"'],
                    ['name' => 'Mike Chen',     'role' => 'Freelance Designer',   'rating' => 5, 'comment' => '"As a freelancer, I needed a professional portfolio fast. website builder delivered exactly what I needed."'],
                    ['name' => 'Emily Davis',   'role' => 'Marketing Manager',    'rating' => 5, 'comment' => '"The AI tools and ease of use are incredible. I built my entire website in just a few hours!"'],
                ],
                // CTA Banner
                'cta_banner_title'    => 'Start Your Professional Website Today',
                'cta_banner_subtitle' => 'Join thousands of successful businesses who trust website builder for their online presence.',
                'cta_banner_trust'    => [
                    'No credit card required',
                    'Free forever plan',
                    'Cancel anytime',
                ],
                // Contact
                'contact_heading'     => "Let's Build Something Amazing Together",
                'contact_subtitle'    => "Have questions? We're here to help!",
                'contact_email'       => 'hello@websitebuilder.com',
                'contact_phone'       => '+1 (800) 123-4567',
                'contact_address'     => '123 Business St, Suite 100, New York, NY 10001',
                // Footer
                'footer_brand_name'   => 'website builder',
                'footer_text'         => 'The easiest way to build professional websites. No coding required.',
                'footer_copyright'    => '© ' . date('Y') . ' website builder. All rights reserved.',
                'footer_social'       => [
                    ['icon' => 'fa-brands fa-facebook-f',  'url' => '#'],
                    ['icon' => 'fa-brands fa-twitter',      'url' => '#'],
                    ['icon' => 'fa-brands fa-linkedin-in',  'url' => '#'],
                    ['icon' => 'fa-brands fa-instagram',    'url' => '#'],
                ],
                // FAQ
                'faq_data'            => [
                    ['q' => 'Is coding required?',       'a' => 'Not at all! Our visual drag-and-drop builder handles all technical details for you.'],
                    ['q' => 'Can I use my own domain?',  'a' => 'Yes, you can easily map your custom domain in 1 click.'],
                    ['q' => 'Is SSL included?',          'a' => 'Yes, free SSL certificates are automatically provisioned for all websites.'],
                ],
                'custom_css'          => '',
            ]);
        }
        return $setting;
    }
}
