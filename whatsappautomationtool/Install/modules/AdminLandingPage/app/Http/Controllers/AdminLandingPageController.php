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
        }

        try {
            \Illuminate\Support\Facades\Cache::flush();
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