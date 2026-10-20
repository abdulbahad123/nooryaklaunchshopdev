<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSetting;
use App\Models\Language;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Render the high-converting dynamic WhatsApp SaaS landing page.
     */
    public function index()
    {
        $langCode = session()->get('lang', 'en');
        $lang = Language::where('code', $langCode)->first() ?? Language::where('is_default', 1)->first() ?? Language::first();
        $langId = $lang ? $lang->id : 1;

        $settings = LandingPageSetting::getSettings($langId);

        return view('front.landing', [
            'settings' => $settings,
            'currentLang' => $lang,
        ]);
    }
}