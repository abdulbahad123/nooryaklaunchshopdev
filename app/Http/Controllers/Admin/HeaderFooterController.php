<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\Language;
use App\Models\Ulink;
use Illuminate\Http\Request;
use Session;

class HeaderFooterController extends Controller
{
    /**
     * Display the unified Header & Footer settings page.
     */
    public function index(Request $request)
    {
        $langCode = $request->input('language', 'en');
        $lang = Language::where('code', $langCode)->first() ?? Language::where('is_default', 1)->first() ?? Language::first();
        $langId = $lang ? $lang->id : 1;

        $data['language'] = $lang;
        $data['lang_id'] = $langId;
        $data['abs'] = BasicSetting::first() ?? new BasicSetting();
        $data['abe'] = BasicExtended::first() ?? new BasicExtended();
        $data['ulinks'] = Ulink::where('language_id', $langId)->orderBy('id', 'asc')->get();

        return view('admin.header_footer.index', $data);
    }

    /**
     * Update Header and Footer settings.
     */
    public function update(Request $request, $langid)
    {
        $dir = public_path('assets/front/img/');
        $bs = BasicSetting::first() ?? new BasicSetting();
        $be = BasicExtended::first() ?? new BasicExtended();

        // 1. Header Logo Upload
        if ($request->hasFile('logo')) {
            if (!empty($bs->logo) && file_exists($dir . $bs->logo)) {
                $bs->logo = Uploader::update_picture($dir, $request->file('logo'), $bs->logo);
            } else {
                $bs->logo = Uploader::upload_picture($dir, $request->file('logo'));
            }
        }

        // 2. Footer Logo Upload
        if ($request->hasFile('footer_logo')) {
            if (!empty($bs->footer_logo) && file_exists($dir . $bs->footer_logo)) {
                $bs->footer_logo = Uploader::update_picture($dir, $request->file('footer_logo'), $bs->footer_logo);
            } else {
                $bs->footer_logo = Uploader::upload_picture($dir, $request->file('footer_logo'));
            }
        }

        // 3. Header Action Buttons
        if ($request->has('header_demo_text')) {
            $bs->header_demo_text = $request->input('header_demo_text');
        }
        if ($request->has('header_demo_url')) {
            $bs->header_demo_url = $request->input('header_demo_url');
        }
        if ($request->has('header_login_text')) {
            $bs->header_login_text = $request->input('header_login_text');
        }
        if ($request->has('header_login_url')) {
            $bs->header_login_url = $request->input('header_login_url');
        }

        // 4. Footer Settings
        if ($request->has('footer_text')) {
            $bs->footer_text = $request->input('footer_text');
        }
        if ($request->has('useful_links_title')) {
            $bs->useful_links_title = $request->input('useful_links_title');
        }
        if ($request->has('contact_info_title')) {
            $bs->contact_info_title = $request->input('contact_info_title');
        }
        if ($request->has('newsletter_title')) {
            $bs->newsletter_title = $request->input('newsletter_title');
        }
        if ($request->has('newsletter_subtitle')) {
            $bs->newsletter_subtitle = $request->input('newsletter_subtitle');
        }
        if ($request->has('copyright_text')) {
            $bs->copyright_text = $request->input('copyright_text');
        }

        // Social Links in BasicSetting or JSON
        if ($request->has('social_instagram')) {
            $bs->social_instagram = $request->input('social_instagram');
        }
        if ($request->has('social_facebook')) {
            $bs->social_facebook = $request->input('social_facebook');
        }
        if ($request->has('social_linkedin')) {
            $bs->social_linkedin = $request->input('social_linkedin');
        }
        if ($request->has('social_youtube')) {
            $bs->social_youtube = $request->input('social_youtube');
        }
        if ($request->has('social_twitter')) {
            $bs->social_twitter = $request->input('social_twitter');
        }

        $bs->save();

        // 5. BasicExtended Contact Info
        if ($request->has('contact_addresses')) {
            $be->contact_addresses = $request->input('contact_addresses');
        }
        if ($request->has('contact_numbers')) {
            $be->contact_numbers = $request->input('contact_numbers');
        }
        if ($request->has('contact_mails')) {
            $be->contact_mails = $request->input('contact_mails');
        }
        $be->save();

        // Sync across all languages if applicable
        try {
            BasicSetting::query()->update([
                'logo' => $bs->logo,
                'footer_logo' => $bs->footer_logo,
            ]);
        } catch (\Throwable $e) {}

        Session::flash('success', __('Header & Footer Settings Updated Successfully'));
        return "success";
    }

    /**
     * Remove custom header or footer logo and revert to template graphic.
     */
    public function removeLogo(Request $request, $type)
    {
        $dir = public_path('assets/front/img/');
        $bs = BasicSetting::first();

        if ($bs) {
            if ($type === 'header' && !empty($bs->logo)) {
                $path = $dir . $bs->logo;
                if (file_exists($path)) {
                    @unlink($path);
                }
                $bs->logo = null;
                $bs->save();
                BasicSetting::query()->update(['logo' => null]);
            } elseif ($type === 'footer' && !empty($bs->footer_logo)) {
                $path = $dir . $bs->footer_logo;
                if (file_exists($path)) {
                    @unlink($path);
                }
                $bs->footer_logo = null;
                $bs->save();
                BasicSetting::query()->update(['footer_logo' => null]);
            }
        }

        Session::flash('success', __('Logo removed successfully!'));
        return back();
    }
}