<?php
// TEMPORARY DEBUG - DELETE AFTER USE
// Upload to public/ and access: https://launchshop.cockroachjantaparty.top/diagnose.php

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simulate GET /manti request
$request = Illuminate\Http\Request::create('/manti', 'GET');
$request->server->set('HTTP_HOST', $_SERVER['HTTP_HOST'] ?? 'launchshop.cockroachjantaparty.top');
$request->server->set('REQUEST_URI', '/manti');

// Boot the application
$app->boot();

header('Content-Type: text/plain');
echo "=== ROUTE DIAGNOSIS for GET /manti ===\n\n";

// Check if route exists
$router = $app->make('router');

try {
    // List matching routes
    $routes = $router->getRoutes();
    $matched = null;
    foreach ($routes as $route) {
        if ($route->matches($request)) {
            $matched = $route;
            break;
        }
    }

    if ($matched) {
        echo "✓ ROUTE MATCHED: " . $matched->uri() . "\n";
        echo "  Name: " . ($matched->getName() ?? '(unnamed)') . "\n";
        echo "  Action: " . $matched->getActionName() . "\n";
        echo "  Middleware: " . implode(', ', $matched->gatherMiddleware()) . "\n";
    } else {
        echo "✗ NO ROUTE MATCHED for GET /manti\n\n";
        echo "=== REGISTERED ROUTES containing {username} ===\n";
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'username') !== false || strpos($route->uri(), '{') === 0) {
                echo "  " . implode('|', $route->methods()) . " /" . $route->uri() . "\n";
            }
        }
    }

    echo "\n=== ROUTE COUNT ===\n";
    $count = count($routes->getRoutes());
    echo "Total routes registered: $count\n";

    echo "\n=== getUser() RESULT ===\n";
    $_SERVER['HTTP_HOST'] = 'launchshop.cockroachjantaparty.top';
    $_SERVER['REQUEST_URI'] = '/manti';
    
    $db = $app->make('db');
    $user = $db->table('users')
        ->where('username', 'manti')
        ->where(function($q) use ($db) {
            $q->where('preview_template', 1)->orWhere('status', 1);
        })
        ->first();
    
    if ($user) {
        echo "✓ User 'manti' found: id={$user->id}, status={$user->status}, online_status={$user->online_status}, preview_template={$user->preview_template}\n";
        
        // Check language
        $lang = $db->table('user_languages')
            ->where('user_id', $user->id)
            ->first();
        if ($lang) {
            echo "✓ Language found: id={$lang->id}, code={$lang->code}, is_default=" . ($lang->is_default ?? 'N/A') . "\n";
        } else {
            echo "✗ NO LANGUAGE RECORDS found for user_id={$user->id}\n";
        }

        // Check basic_setting
        $bs = $db->table('user_basic_settings')
            ->where('user_id', $user->id)
            ->first();
        if ($bs) {
            echo "✓ basic_setting found: maintenance_status=" . ($bs->maintenance_status ?? 'N/A') . "\n";
        } else {
            echo "✗ NO basic_setting found for user_id={$user->id}\n";
        }
    } else {
        echo "✗ User 'manti' NOT FOUND (status=0 AND preview_template=0?)\n";
        $anyUser = $db->table('users')->where('username', 'manti')->first();
        if ($anyUser) {
            echo "  (User EXISTS but: status={$anyUser->status}, preview_template={$anyUser->preview_template})\n";
        } else {
            echo "  (User does NOT exist in database at all)\n";
        }
    }

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Done ===\n";
