<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WhatsApp Bulk & Automation Product Suite Routes (100% Isolated)
|--------------------------------------------------------------------------
*/

if ($isWaHost ?? false) {
    Route::group(['middleware' => ['web']], function () {
        Route::get('/', function () {
            return redirect()->away(env('WHATSAPP_BULK_URL', 'https://waziper.nooryak.in'));
        });
        Route::any('{any}', function ($any) {
            return redirect()->away(rtrim(env('WHATSAPP_BULK_URL', 'https://waziper.nooryak.in'), '/') . '/' . ltrim($any, '/'));
        })->where('any', '.*');
    });
}