<?php

namespace Modules\AdminLandingPage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLandingPageController extends Controller
{
    public function settings()
    {
        return view('adminlandingpage::index');
    }

    public function save(Request $request)
    {
        $posts = $request->except(['_token']);
        $sisterDbs = [
            'nooryak_whatsapp',
            'nooryak_whatsappbulk',
            'nooryak_ps_youverse_whatsapp',
            'nooryak_ps_youverse_whatsappbulk',
            'bazaarwa_ps_youverse_whatsapp',
            'bazaarwa_ps_youverse_whatsappbulk',
            'nooryak_whatautomation',
            'nooryak_replix_automation',
            'replix_automation',
        ];
        $currentDb = config('database.connections.mysql.database');

        foreach ($posts as $name => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            if ($value === null) {
                $value = "";
            }
            DB::table('options')->updateOrInsert(
                ['name' => $name],
                ['value' => (string)$value]
            );

            // Cross-sync to sister WhatsApp DBs if accessible
            foreach ($sisterDbs as $sDb) {
                if ($sDb === $currentDb) continue;
                try {
                    DB::statement("INSERT INTO `{$sDb}`.options (name, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = VALUES(value)", [$name, (string)$value]);
                } catch (\Throwable $e) {}
            }
        }

        try {
            app()->forgetInstance('options');
        } catch (\Throwable $e) {}

        try {
            \Illuminate\Support\Facades\Cache::flush();
        } catch (\Throwable $e) {}

        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
        } catch (\Throwable $e) {}

        if (function_exists('ms')) {
            ms([
                "status" => 1,
                "message" => __("Landing page settings saved successfully!")
            ]);
        }

        return response()->json([
            "status" => 1,
            "message" => __("Landing page settings saved successfully!")
        ]);
    }
}