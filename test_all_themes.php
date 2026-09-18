<?php
// test_all_themes.php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\View;
use App\Models\WebsiteBuilderAgency;

echo "=========================================================\n";
echo " DYNAMIC SECTION WORKING STATUS AUDIT FOR ALL 5 THEMES   \n";
echo "=========================================================\n\n";

// Unique test marker text
$marker = "TEST_REFRESH_VAL_" . rand(1000, 9999);

// Mock agency data with unique test marker in every single dynamic field
$agencyData = [
    'site_title' => "SiteTitle_$marker",
    'hero_badge' => "HeroBadge_$marker",
    'hero_title' => "HeroTitle_$marker",
    'hero_subtitle' => "HeroSubtitle_$marker",
    'primary_btn_text' => "PriBtn_$marker",
    'secondary_btn_text' => "SecBtn_$marker",
    'hero_bullet_1_title' => "B1Title_$marker",
    'hero_bullet_1_text' => "B1Text_$marker",
    'hero_bullet_2_title' => "B2Title_$marker",
    'hero_bullet_2_text' => "B2Text_$marker",
    'hero_bullet_3_title' => "B3Title_$marker",
    'hero_bullet_3_text' => "B3Text_$marker",
    'about_badge' => "AboutBadge_$marker",
    'about_hero_title' => "AboutTitle_$marker",
    'about_hero_subtitle' => "AboutSubtitle_$marker",
    'about_primary_btn_text' => "AboutPriBtn_$marker",
    'about_secondary_btn_text' => "AboutSecBtn_$marker",
    'story_badge' => "StoryBadge_$marker",
    'story_title' => "StoryTitle_$marker",
    'story_text' => "StoryText_$marker",
    'founder_name' => "FounderName_$marker",
    'founder_role' => "FounderRole_$marker",
    'mission_title' => "MissionTitle_$marker",
    'mission_text' => "MissionText_$marker",
    'vision_title' => "VisionTitle_$marker",
    'vision_text' => "VisionText_$marker",
    'values_title' => "ValuesTitle_$marker",
    'values_text' => "ValuesItem1_$marker, ValuesItem2_$marker",
    'team_badge' => "TeamBadge_$marker",
    'team_title' => "TeamTitle_$marker",
    'team_subtitle' => "TeamSubtitle_$marker",
    'services_badge' => "ServicesBadge_$marker",
    'services_title' => "ServicesTitle_$marker",
    'services_subtitle' => "ServicesSubtitle_$marker",
    'portfolio_badge' => "PortfolioBadge_$marker",
    'portfolio_title' => "PortfolioTitle_$marker",
    'portfolio_subtitle' => "PortfolioSubtitle_$marker",
    'testimonials_badge' => "TestimonialsBadge_$marker",
    'testimonials_title' => "TestimonialsTitle_$marker",
    'testimonials_subtitle' => "TestimonialsSubtitle_$marker",
    'contact_badge' => "ContactBadge_$marker",
    'contact_title' => "ContactTitle_$marker",
    'contact_subtitle' => "ContactSubtitle_$marker",
    'contact_bullet_1_title' => "CB1Title_$marker",
    'contact_bullet_1_text' => "CB1Text_$marker",
    'contact_bullet_2_title' => "CB2Title_$marker",
    'contact_bullet_2_text' => "CB2Text_$marker",
    'contact_bullet_3_title' => "CB3Title_$marker",
    'contact_bullet_3_text' => "CB3Text_$marker",
    'contact_form_title' => "FormTitle_$marker",
    'contact_form_subtitle' => "FormSubtitle_$marker",
    'faqs_badge' => "FaqsBadge_$marker",
    'faqs_title' => "FaqsTitle_$marker",
    'consultant_title' => "ConsultantTitle_$marker",
    'consultant_desc' => "ConsultantDesc_$marker",
    'copyright_text' => "CopyrightText_$marker",
    'portfolio_data' => [
        ['title' => "Project1_$marker", 'category' => "CatAlpha_$marker", 'desc' => "Desc1_$marker"],
        ['title' => "Project2_$marker", 'category' => "CatBeta_$marker", 'desc' => "Desc2_$marker"],
    ],
    'team_members_data' => [
        ['name' => "Member1_$marker", 'role' => "Role1_$marker"],
    ],
    'testimonials_data' => [
        ['name' => "Reviewer1_$marker", 'role' => "Client1_$marker", 'comment' => "Comment1_$marker"],
    ],
    'services_data' => [
        ['title' => "Service1_$marker", 'desc' => "SvcDesc1_$marker", 'icon' => 'fa-building'],
    ],
];

// Create object wrapper so Blade can access properties like $agency->hero_title
$agency = (object) $agencyData;

$themes = [
    'texigo_theme' => [
        'views' => ['index', 'about', 'portfolio', 'contact'],
        'var_name' => 'agency'
    ],
    'agency_template' => [
        'views' => ['index', 'about', 'portfolio', 'contact'],
        'var_name' => 'agency'
    ],
    'interior_template' => [
        'views' => ['index', 'about', 'portfolio', 'contact'],
        'var_name' => 'interior'
    ],
    'construction_theme' => [
        'views' => ['index', 'about', 'portfolio', 'contact', 'services'],
        'var_name' => 'agency'
    ],
    'evently_theme' => [
        'views' => ['index', 'about', 'portfolio', 'contact'],
        'var_name' => 'interior'
    ],
];

$overallPassed = true;

foreach ($themes as $themeFolder => $config) {
    echo "---------------------------------------------------------\n";
    echo " THEME: " . strtoupper($themeFolder) . "\n";
    echo "---------------------------------------------------------\n";

    $varName = $config['var_name'];

    foreach ($config['views'] as $viewName) {
        $fullViewName = "website_builder.{$themeFolder}.{$viewName}";
        
        if (!View::exists($fullViewName)) {
            echo " [FAIL] View '{$fullViewName}' does not exist!\n";
            $overallPassed = false;
            continue;
        }

        try {
            $viewData = [
                $varName => $agency,
                'agency' => $agency,
                'interior' => $agency,
                'subdomain' => 'testsubdomain',
                'portfolio' => $agencyData['portfolio_data'],
                'services' => $agencyData['services_data'],
            ];
            
            $rendered = View::make($fullViewName, $viewData)->render();

            // Test key fields in the rendered HTML
            $checks = [];
            if ($viewName === 'index') {
                $checks['Hero Title'] = "HeroTitle_$marker";
                $checks['Hero Subtitle'] = "HeroSubtitle_$marker";
                $checks['Services Title'] = "ServicesTitle_$marker";
                $checks['Testimonials Title'] = "TestimonialsTitle_$marker";
            } elseif ($viewName === 'about') {
                $checks['About Badge'] = "AboutBadge_$marker";
                $checks['About Hero Title'] = "AboutTitle_$marker";
                $checks['Story Badge'] = "StoryBadge_$marker";
                $checks['Team Badge'] = "TeamBadge_$marker";
            } elseif ($viewName === 'portfolio') {
                $checks['Portfolio Badge'] = "PortfolioBadge_$marker";
                $checks['Portfolio Title'] = "PortfolioTitle_$marker";
                $checks['Dynamic Category CatAlpha'] = "CatAlpha_$marker";
                $checks['Dynamic Category CatBeta'] = "CatBeta_$marker";
            } elseif ($viewName === 'contact') {
                $checks['Contact Badge'] = "ContactBadge_$marker";
                $checks['Contact Subtitle'] = "ContactSubtitle_$marker";
                $checks['Form Title'] = "FormTitle_$marker";
                $checks['FAQs Title'] = "FaqsTitle_$marker";
            } elseif ($viewName === 'services') {
                $checks['Services Badge'] = "ServicesBadge_$marker";
            }

            $pagePassed = true;
            foreach ($checks as $checkLabel => $expectedString) {
                if (str_contains($rendered, $expectedString)) {
                    echo "  ✓ {$viewName}.blade.php -> {$checkLabel}: PASSED\n";
                } else {
                    echo "  ✗ {$viewName}.blade.php -> {$checkLabel}: FAILED (Expected: '{$expectedString}')\n";
                    $pagePassed = false;
                    $overallPassed = false;
                }
            }

            if (empty($checks)) {
                echo "  ✓ {$viewName}.blade.php -> Rendered cleanly (No errors)\n";
            }
        } catch (\Throwable $e) {
            echo "  ✗ {$viewName}.blade.php -> RENDER ERROR: " . $e->getMessage() . "\n";
            $overallPassed = false;
        }
    }
    echo "\n";
}

echo "=========================================================\n";
if ($overallPassed) {
    echo " AUDIT RESULT: ALL TESTS PASSED SUCCESSFULLY! 100% WORKING \n";
} else {
    echo " AUDIT RESULT: SOME TESTS FAILED! FIXING REQUIRED \n";
}
echo "=========================================================\n";
