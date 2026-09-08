<?php
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

$request = Request::create('/test', 'POST', [
    'team_members_data' => [
        0 => ['name' => 'Michael', 'role' => 'CEO', 'image' => 'assets/old.jpg'],
        1 => ['name' => 'Sarah', 'role' => 'CTO', 'image' => 'assets/old2.jpg'],
    ]
], [], [
    'team_members_data' => [
        0 => ['image_file' => UploadedFile::fake()->create('avatar.jpg', 100)],
    ]
]);

$teamData = array_values($request->input('team_members_data', []));

$files = $request->file('team_members_data');
if (!empty($files) && is_array($files)) {
    foreach ($files as $ti => $fileData) {
        if (isset($fileData['image_file']) && $fileData['image_file'] instanceof UploadedFile) {
            echo "SUCCESS: Found uploaded file for index $ti: " . $fileData['image_file']->getClientOriginalName() . "\n";
            $teamData[$ti]['image'] = 'uploads/website_builder/team_' . $ti . '.jpg';
        }
    }
}

print_r($teamData);
