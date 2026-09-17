<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$team = [
    ['name' => 'Michael Carter', 'role' => 'Founder & CEO', 'image' => 'assets/website_builder/Templates/Construction_agency/team_1.png'],
    ['name' => 'Sarah Mitchell', 'role' => 'Chief Operating Officer', 'image' => 'assets/website_builder/Templates/Construction_agency/team_2.png'],
    ['name' => 'David Thompson', 'role' => 'Head of Engineering', 'image' => 'assets/website_builder/Templates/Construction_agency/team_3.png'],
    ['name' => 'Emily Davis', 'role' => 'Chief Architect', 'image' => 'assets/website_builder/Templates/Construction_agency/team_4.png'],
];

$testimonials = [
    ['name' => 'James Anderson', 'role' => 'Commercial Client', 'comment' => 'BuildCraft made our commercial tower project so easy and stress-free. Highly recommended!', 'avatar' => 'assets/website_builder/Templates/Construction_agency/team_1.png'],
    ['name' => 'Sophia Martinez', 'role' => 'Project Director', 'comment' => 'Reliable, affordable, and always on time. The best construction partner in the country!', 'avatar' => 'assets/website_builder/Templates/Construction_agency/team_2.png'],
    ['name' => 'Robert Wilson', 'role' => 'Real Estate Developer', 'comment' => 'Professional engineers and excellent project delivery. Truly a great experience!', 'avatar' => 'assets/website_builder/Templates/Construction_agency/team_3.png'],
];

DB::table('wb_agency_settings')->where('template_type', 'construction')->update([
    'team_members_data' => json_encode($team),
    'testimonials_data' => json_encode($testimonials),
]);

echo "Updated construction database settings successfully!\n";
