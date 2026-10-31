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

        // Handle dynamic testimonials up to 40 items with ratings and avatar images
        if ($request->has('testimonials_data')) {
            $testiData = $request->input('testimonials_data');
            if (is_string($testiData)) {
                $testiData = json_decode($testiData, true);
            }
            if (is_array($testiData)) {
                // Strict limit of 40 testimonials
                $testiData = array_slice($testiData, 0, 40);

                // Handle file uploads for testimonial avatars
                if ($request->hasFile('testi_avatar_files')) {
                    $uploadDir = public_path('uploads/testimonials');
                    if (!file_exists($uploadDir)) {
                        @mkdir($uploadDir, 0755, true);
                    }
                    foreach ($request->file('testi_avatar_files') as $idx => $imgFile) {
                        if ($imgFile && $imgFile->isValid() && isset($testiData[$idx])) {
                            $filename = 'testi_' . time() . '_' . $idx . '.' . $imgFile->getClientOriginalExtension();
                            $imgFile->move($uploadDir, $filename);
                            $testiData[$idx]['img'] = 'uploads/testimonials/' . $filename;
                        }
                    }
                }

                $posts['landing_testimonials_data'] = json_encode($testiData, JSON_UNESCAPED_UNICODE);

                // Cross-populate individual indexed keys for backward compatibility
                foreach ($testiData as $i => $item) {
                    $num = $i + 1;
                    $posts["landing_testi{$num}_name"] = $item['name'] ?? '';
                    $posts["landing_testi{$num}_role"] = $item['role'] ?? '';
                    $posts["landing_testi{$num}_text"] = $item['text'] ?? '';
                    $posts["landing_testi{$num}_rating"] = $item['rating'] ?? 5;
                    $posts["landing_testi{$num}_img"] = $item['img'] ?? '';
                }
            }
        }

        $sisterDbs = [
            'nooryak_whatsapp',
            'nooryak_whatsappbulk',
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