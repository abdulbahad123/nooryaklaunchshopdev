<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$request = Illuminate\Http\Request::create('/templates', 'GET');
$response = Route::dispatch($request);
file_put_contents(__DIR__ . '/rendered.html', $response->getContent());
echo "HTML written to rendered.html\n";
