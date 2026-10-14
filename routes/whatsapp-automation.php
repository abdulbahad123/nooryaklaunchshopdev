<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WhatsApp Bulk & Automation Product Suite Routes (100% Isolated)
|--------------------------------------------------------------------------
*/

if ($isWaHost ?? false) {
    Route::any('{any?}', function () {
        $whatsappPublic = base_path('whatsappautomationtool/Install/public/index.php');
        if (file_exists($whatsappPublic)) {
            require $whatsappPublic;
            exit;
        }
        return response('WhatsApp Bulk application initializing...', 503);
    })->where('any', '.*');
}