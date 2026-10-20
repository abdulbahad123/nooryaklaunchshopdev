<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\PageCta;
use App\Models\Language;
use Illuminate\Http\Request;
use Session;

class PageCtaController extends Controller
{
    /**
     * Display the CTA Banners management page with tabs for all pages.
     */
    public function index(Request $request)
    {
        PageCta::ensureTableExists();

        $langCode = $request->input('language', 'en');
        $lang = Language::where('code', $langCode)->first() ?? Language::where('is_default', 1)->first() ?? Language::first();
        $langId = $lang ? $lang->id : 1;

        $pages = ['home', 'pricing', 'templates', 'shops', 'about', 'contact'];
        $ctas = [];
        foreach ($pages as $p) {
            $ctas[$p] = PageCta::where('language_id', $langId)->where('page', $p)->first() 
                     ?? PageCta::where('page', $p)->first() 
                     ?? new PageCta(['language_id' => $langId, 'page' => $p, 'status' => '1']);
        }

        $data['lang_id'] = $langId;
        $data['language'] = $lang;
        $data['ctas'] = $ctas;

        return view('admin.page_cta.index', $data);
    }

    /**
     * Update CTA Banners across all tabs.
     */
    public function update(Request $request, $langid)
    {
        PageCta::ensureTableExists();

        $pages = ['home', 'pricing', 'templates', 'shops', 'about', 'contact'];
        $dir = public_path('assets/front/img/');

        foreach ($pages as $p) {
            $existing = PageCta::where('language_id', $langid)->where('page', $p)->first();
            $imageName = $existing ? $existing->image : null;

            if ($request->hasFile("cta_{$p}_image")) {
                if (!empty($imageName) && file_exists($dir . $imageName)) {
                    $imageName = Uploader::update_picture($dir, $request->file("cta_{$p}_image"), $imageName);
                } else {
                    $imageName = Uploader::upload_picture($dir, $request->file("cta_{$p}_image"));
                }
            }

            $fields = [
                'status'   => $request->input("cta_{$p}_status", '1'),
                'title'    => $request->input("cta_{$p}_title"),
                'subtitle' => $request->input("cta_{$p}_subtitle"),
                'btn_text' => $request->input("cta_{$p}_btn_text"),
                'btn_url'  => $request->input("cta_{$p}_btn_url"),
                'feat1'    => $request->input("cta_{$p}_feat1"),
                'feat2'    => $request->input("cta_{$p}_feat2"),
                'feat3'    => $request->input("cta_{$p}_feat3"),
            ];
            if (!empty($imageName)) {
                $fields['image'] = $imageName;
            }

            // Sync across all existing rows for this page so changes immediately show site-wide
            PageCta::where('page', $p)->update($fields);
            PageCta::updateOrCreate(
                ['language_id' => $langid, 'page' => $p],
                $fields
            );
        }

        Session::flash('success', __('Call To Action (CTA) Banners Updated Successfully'));
        return "success";
    }

    /**
     * Remove custom CTA banner image and revert to template graphic.
     */
    public function removeImg(Request $request, $language_id, $page)
    {
        PageCta::ensureTableExists();

        $cta = PageCta::where('language_id', $language_id)->where('page', $page)->first()
            ?? PageCta::where('page', $page)->first();

        if ($cta && !empty($cta->image)) {
            $path = public_path('assets/front/img/' . $cta->image);
            if (file_exists($path)) {
                @unlink($path);
            }
            $cta->update(['image' => null]);
            PageCta::where('page', $page)->update(['image' => null]);
        }

        Session::flash('success', __('Image removed successfully!'));
        return back();
    }
}