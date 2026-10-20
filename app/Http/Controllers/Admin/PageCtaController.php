<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Admin\ImageText;
use App\Models\Language;
use Illuminate\Http\Request;
use Validator;
use Session;

class PageCtaController extends Controller
{
    /**
     * Display the CTA Banners management page with tabs for all pages.
     */
    public function index(Request $request)
    {
        ImageText::ensureColumnsExist();

        $langCode = $request->input('language', 'en');
        $lang = Language::where('code', $langCode)->first() ?? Language::where('is_default', 1)->first() ?? Language::first();
        $langId = $lang ? $lang->id : 1;

        ImageText::seedDefaultContent($langId);

        $data['lang_id'] = $langId;
        $data['language'] = $lang;
        $data['homeSec'] = ImageText::where('language_id', $langId)->first() ?? ImageText::first() ?? new ImageText();

        return view('admin.page_cta.index', $data);
    }

    /**
     * Update CTA Banners across all tabs.
     */
    public function update(Request $request, $langid)
    {
        ImageText::ensureColumnsExist();

        $pages = ['global', 'home', 'pricing', 'templates', 'shops', 'about', 'contact'];
        $imageFields = [];
        foreach ($pages as $p) {
            $imageFields[] = "cta_{$p}_image";
        }

        $dir = public_path('assets/front/img/');
        $content = ImageText::where('language_id', $langid)->first() ?? ImageText::first();

        $data = $request->except(array_merge(['_token'], $imageFields));

        foreach ($imageFields as $imgField) {
            if ($request->hasFile($imgField)) {
                $existing = $content ? $content->$imgField : null;
                if (!empty($existing) && file_exists($dir . $existing)) {
                    $data[$imgField] = Uploader::update_picture($dir, $request->file($imgField), $existing);
                } else {
                    $data[$imgField] = Uploader::upload_picture($dir, $request->file($imgField));
                }
            }
        }

        try {
            if ($content) {
                $content->update($data);
            } else {
                $data['unique_id'] = 12345;
                $data['language_id'] = $langid;
                ImageText::create($data);
            }
            ImageText::where('language_id', $langid)->update($data);
            
            // Site-wide sync to ensure default/fallback rows have the updated CTA content
            $firstRow = ImageText::first();
            if ($firstRow && $firstRow->id !== ($content->id ?? null)) {
                $firstRow->update($data);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            ImageText::ensureColumnsExist();
            try {
                if ($content) {
                    $content->update($data);
                }
                ImageText::where('language_id', $langid)->update($data);
            } catch (\Throwable $ex) {}
        }

        Session::flash('success', __('Call To Action (CTA) Banners Updated Successfully'));
        return "success";
    }

    /**
     * Remove custom CTA banner image and revert to template graphic.
     */
    public function removeImg(Request $request, $language_id, $field)
    {
        $allowed = ['cta_global_image', 'cta_home_image', 'cta_pricing_image', 'cta_templates_image', 'cta_shops_image', 'cta_about_image', 'cta_contact_image'];
        if (!in_array($field, $allowed)) {
            return back()->with('error', 'Invalid image field.');
        }

        $content = ImageText::where('language_id', $language_id)->first() ?? ImageText::first();
        if ($content && !empty($content->$field)) {
            $path = public_path('assets/front/img/' . $content->$field);
            if (file_exists($path)) {
                @unlink($path);
            }
            $content->update([$field => null]);
            ImageText::where('language_id', $language_id)->update([$field => null]);
        }

        Session::flash('success', __('Image removed successfully!'));
        return back();
    }
}