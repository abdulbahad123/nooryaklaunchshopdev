<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Admin\ImageText;
use App\Models\Feature;
use App\Models\Process;
use Validator;
use Session;

class HerosectionController extends Controller
{
    public function imgtext(Request $request)
    {
        ImageText::ensureColumnsExist();
        $lang = Language::where('code', $request->language)->firstOrFail();
        ImageText::seedDefaultContent($lang->id);

        $data['lang_id'] = $lang->id;
        $data['data'] = ImageText::where('language_id', $lang->id)->first();
        $data['features'] = Feature::where('language_id', $lang->id)->orderBy('serial_number', 'ASC')->get();
        $data['processes'] = Process::where('language_id', $lang->id)->orderBy('serial_number', 'ASC')->get();

        return view('admin.home.hero.img-text', $data);
    }

    public function update(Request $request, $langid)
    {
        ImageText::ensureColumnsExist();

        $rules = [
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif|max:20480',
            'work_process_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif|max:20480',
            'dashboard_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,gif|max:20480',
            'hero_section_title' => 'nullable|max:255',
            'hero_section_text' => 'nullable|max:255',
            'hero_section_desc' => 'nullable|max:255',
            'hero_section_button_text' => 'nullable|max:50',
            'hero_section_button_url' => 'nullable',
            'hero_section_video_url' => 'nullable',
            'hero_badge1_title' => 'nullable|max:100',
            'hero_badge1_subtitle' => 'nullable|max:100',
            'hero_badge2_title' => 'nullable|max:100',
            'hero_badge2_subtitle' => 'nullable|max:100',
            'hero_badge3_title' => 'nullable|max:100',
            'hero_badge3_subtitle' => 'nullable|max:100',
            'features_section_title' => 'nullable|max:255',
            'features_section_subtitle' => 'nullable|max:255',
            'features_section_text' => 'nullable|max:255',
            'features_section_btn_text' => 'nullable|max:50',
            'features_section_btn_url' => 'nullable',
            'features_section_video_url' => 'nullable',
            'features_bullet1' => 'nullable|max:255',
            'features_bullet2' => 'nullable|max:255',
            'features_bullet3' => 'nullable|max:255',
            'features_bullet4' => 'nullable|max:255',
            'blog_section_title' => 'nullable|max:255',
            'partner_section_title' => 'nullable|max:255',
            'partner_section_subtitle' => 'nullable|max:255',
            'work_process_section_title' => 'nullable|max:255',
            'work_process_section_subtitle' => 'nullable|max:255',
            'work_process_btn_text' => 'nullable|max:50',
            'work_process_btn_url' => 'nullable|max:255',
            'template_section_title' => 'nullable|max:255',
            'template_section_subtitle' => 'nullable|max:255',
            'pricing_section_title' => 'nullable|max:255',
            'pricing_section_subtitle' => 'nullable|max:255',
            'featured_shop_section_title' => 'nullable|max:255',
            'featured_shop_section_subtitle' => 'nullable|max:255',
            'testimonial_section_title' => 'nullable|max:255',
            'hero_stat1_value' => 'nullable|max:255',
            'hero_stat1_label' => 'nullable|max:255',
            'hero_stat1_icon' => 'nullable|max:255',
            'hero_stat2_value' => 'nullable|max:255',
            'hero_stat2_label' => 'nullable|max:255',
            'hero_stat2_icon' => 'nullable|max:255',
            'hero_stat3_value' => 'nullable|max:255',
            'hero_stat3_label' => 'nullable|max:255',
            'hero_stat3_icon' => 'nullable|max:255',
            'hero_stat4_value' => 'nullable|max:255',
            'hero_stat4_label' => 'nullable|max:255',
            'hero_stat4_icon' => 'nullable|max:255',
            'process_stat1_value' => 'nullable|max:255',
            'process_stat1_label' => 'nullable|max:255',
            'process_stat1_icon' => 'nullable|max:255',
            'process_stat2_value' => 'nullable|max:255',
            'process_stat2_label' => 'nullable|max:255',
            'process_stat2_icon' => 'nullable|max:255',
            'process_stat3_value' => 'nullable|max:255',
            'process_stat3_label' => 'nullable|max:255',
            'process_stat3_icon' => 'nullable|max:255',
            'process_stat4_value' => 'nullable|max:255',
            'process_stat4_label' => 'nullable|max:255',
            'process_stat4_icon' => 'nullable|max:255',
            'bottom_feat1_text' => 'nullable|max:255',
            'bottom_feat1_icon' => 'nullable|max:255',
            'bottom_feat2_text' => 'nullable|max:255',
            'bottom_feat2_icon' => 'nullable|max:255',
            'bottom_feat3_text' => 'nullable|max:255',
            'bottom_feat3_icon' => 'nullable|max:255',
            'bottom_feat4_text' => 'nullable|max:255',
            'bottom_feat4_icon' => 'nullable|max:255',
            'bottom_feat5_text' => 'nullable|max:255',
            'bottom_feat5_icon' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $dir = public_path('assets/front/img');
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }
        $content = ImageText::where('language_id', $langid)->first();

        $data = $request->except(['_token', 'image', 'work_process_image', 'dashboard_image', 'feature_cards', 'process_steps']);

        if ($request->hasFile('image')) {
            $existingImage = $content ? $content->image : null;
            if (!empty($existingImage)) {
                $data['image'] = Uploader::update_picture($dir, $request->file('image'), $existingImage);
            } else {
                $data['image'] = Uploader::upload_picture($dir, $request->file('image'));
            }
        }

        if ($request->hasFile('work_process_image')) {
            $existingWpImage = $content ? $content->work_process_image : null;
            if (!empty($existingWpImage)) {
                $data['work_process_image'] = Uploader::update_picture($dir, $request->file('work_process_image'), $existingWpImage);
            } else {
                $data['work_process_image'] = Uploader::upload_picture($dir, $request->file('work_process_image'));
            }
        }

        if ($request->hasFile('dashboard_image')) {
            $existingDashImage = $content ? $content->dashboard_image : null;
            if (!empty($existingDashImage)) {
                $data['dashboard_image'] = Uploader::update_picture($dir, $request->file('dashboard_image'), $existingDashImage);
            } else {
                $data['dashboard_image'] = Uploader::upload_picture($dir, $request->file('dashboard_image'));
            }
        }

        ImageText::ensureColumnsExist();

        try {
            if ($content) {
                $content->update($data);
            } else {
                $data['unique_id'] = 12345;
                $data['language_id'] = $langid;
                ImageText::create($data);
            }
            ImageText::where('language_id', $langid)->update($data);

            // Sync trust badges and counter stats across ALL image_texts rows so changes are immediately visible site-wide
            $globalHomeFields = [
                'bottom_feat1_text', 'bottom_feat1_icon',
                'bottom_feat2_text', 'bottom_feat2_icon',
                'bottom_feat3_text', 'bottom_feat3_icon',
                'bottom_feat4_text', 'bottom_feat4_icon',
                'bottom_feat5_text', 'bottom_feat5_icon',
                'hero_stat1_value', 'hero_stat1_label', 'hero_stat1_icon',
                'hero_stat2_value', 'hero_stat2_label', 'hero_stat2_icon',
                'hero_stat3_value', 'hero_stat3_label', 'hero_stat3_icon',
                'hero_stat4_value', 'hero_stat4_label', 'hero_stat4_icon',
                'process_stat1_value', 'process_stat1_label', 'process_stat1_icon',
                'process_stat2_value', 'process_stat2_label', 'process_stat2_icon',
                'process_stat3_value', 'process_stat3_label', 'process_stat3_icon',
                'process_stat4_value', 'process_stat4_label', 'process_stat4_icon',
            ];
            if (isset($data['image'])) {
                $globalHomeFields[] = 'image';
            }
            if (isset($data['work_process_image'])) {
                $globalHomeFields[] = 'work_process_image';
            }
            if (isset($data['dashboard_image'])) {
                $globalHomeFields[] = 'dashboard_image';
            }
            $syncData = array_intersect_key($data, array_flip($globalHomeFields));
            if (!empty($syncData)) {
                ImageText::query()->update($syncData);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            ImageText::ensureColumnsExist();
            try {
                if ($content) {
                    $content->update($data);
                } else {
                    $data['unique_id'] = 12345;
                    $data['language_id'] = $langid;
                    ImageText::create($data);
                }
                ImageText::where('language_id', $langid)->update($data);
                if (!empty($syncData)) {
                    ImageText::query()->update($syncData);
                }
            } catch (\Throwable $ex) {}
        }

        // Update embedded Feature Cards (Screenshot 4) if submitted
        if ($request->has('feature_cards') && is_array($request->feature_cards)) {
            foreach ($request->feature_cards as $fId => $fData) {
                $feat = Feature::where('id', $fId)->where('language_id', $langid)->first();
                if ($feat) {
                    if (isset($fData['title'])) $feat->title = $fData['title'];
                    if (isset($fData['text'])) $feat->text = $fData['text'];
                    if (isset($fData['icon']) && !empty($fData['icon'])) $feat->icon = $fData['icon'];
                    $feat->save();
                }
            }
        }

        // Update embedded Process Steps (Screenshot 3) if submitted
        if ($request->has('process_steps') && is_array($request->process_steps)) {
            foreach ($request->process_steps as $pId => $pData) {
                $proc = Process::where('id', $pId)->where('language_id', $langid)->first();
                if ($proc) {
                    if (isset($pData['title'])) $proc->title = $pData['title'];
                    if (isset($pData['text'])) $proc->text = $pData['text'];
                    if (isset($pData['icon']) && !empty($pData['icon'])) $proc->icon = $pData['icon'];
                    $proc->save();
                }
            }
        }

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function removeImg($language_id, Request $request)
    {
        $data = ImageText::where('language_id', $language_id)->first();

        if ($data) {
            $col = $request->name;
            if (in_array($col, ['image', 'work_process_image', 'dashboard_image'])) {
                if (!empty($data->{$col})) {
                    $filePath = public_path('assets/front/img/' . $data->{$col});
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
                $data->{$col} = NULL;
                $data->save();
                ImageText::query()->update([$col => NULL]);
            }
        }
        session()->flash('success', __('Removed successfully'));
        return response()->json(['status' => 'success']);
    }

    /**
     * All Page Hero Banners Manager (About Us, Home, Stores Launched, Themes, Pricing, Contact)
     */
    public function pageHeroBanners(Request $request)
    {
        ImageText::ensureColumnsExist();
        $lang = Language::where('code', $request->language ?? 'en')->first() ?? Language::where('is_default', 1)->first() ?? Language::first();
        if (!$lang) {
            $lang = (object)['id' => 1, 'code' => 'en', 'name' => 'English'];
        }

        ImageText::seedDefaultContent($lang->id);

        $data['lang_id'] = $lang->id;
        $data['language'] = $lang;
        $data['data'] = ImageText::where('language_id', $lang->id)->first();

        return view('admin.home.hero.page-hero-banners', $data);
    }

    public function aboutHeroBanner(Request $request)
    {
        $langCode = $request->language ?? 'en';
        return redirect()->to(route('admin.page_hero_banners', ['language' => $langCode]) . '#tab-about');
    }

    public function updatePageHeroBanners(Request $request, $langid)
    {
        ImageText::ensureColumnsExist();

        $rules = [
            'about_hero_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg',
            'shops_hero_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg',
            'templates_hero_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg',
            'contact_hero_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg',
            'about_hero_badge' => 'nullable|max:255',
            'about_hero_title' => 'nullable|max:255',
            'about_hero_subtitle' => 'nullable',
            'about_hero_btn_text' => 'nullable|max:100',
            'about_hero_btn_url' => 'nullable|max:255',
            'shops_hero_badge' => 'nullable|max:255',
            'shops_hero_title' => 'nullable|max:255',
            'shops_hero_subtitle' => 'nullable',
            'shops_hero_feat1_text' => 'nullable|max:255',
            'shops_hero_feat2_text' => 'nullable|max:255',
            'shops_hero_feat3_text' => 'nullable|max:255',
            'templates_hero_badge' => 'nullable|max:255',
            'templates_hero_title' => 'nullable|max:255',
            'templates_hero_subtitle' => 'nullable',
            'pricing_hero_badge' => 'nullable|max:255',
            'pricing_hero_title' => 'nullable|max:255',
            'pricing_hero_subtitle' => 'nullable',
            'contact_hero_badge' => 'nullable|max:255',
            'contact_hero_title' => 'nullable|max:255',
            'contact_hero_subtitle' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $dir = public_path('assets/front/img/');
        $content = ImageText::where('language_id', $langid)->first();

        $data = $request->except(['_token', 'about_hero_image', 'shops_hero_image', 'templates_hero_image', 'contact_hero_image']);

        $imageFields = ['about_hero_image', 'shops_hero_image', 'templates_hero_image', 'contact_hero_image'];
        foreach ($imageFields as $imgField) {
            if ($request->hasFile($imgField)) {
                $existing = $content ? $content->$imgField : null;
                if (!is_null($existing)) {
                    $data[$imgField] = Uploader::update_picture($dir, $request->file($imgField), $existing);
                } else {
                    $data[$imgField] = Uploader::upload_picture($dir, $request->file($imgField));
                }
            }
        }

        ImageText::ensureColumnsExist();

        try {
            if ($content) {
                $content->update($data);
            } else {
                $data['unique_id'] = 12345;
                $data['language_id'] = $langid;
                ImageText::create($data);
            }
            ImageText::where('language_id', $langid)->update($data);

            // Sync trust badges and counter stats across ALL image_texts rows so changes are immediately visible site-wide
            $globalHomeFields = [
                'bottom_feat1_text', 'bottom_feat1_icon',
                'bottom_feat2_text', 'bottom_feat2_icon',
                'bottom_feat3_text', 'bottom_feat3_icon',
                'bottom_feat4_text', 'bottom_feat4_icon',
                'bottom_feat5_text', 'bottom_feat5_icon',
                'hero_stat1_value', 'hero_stat1_label', 'hero_stat1_icon',
                'hero_stat2_value', 'hero_stat2_label', 'hero_stat2_icon',
                'hero_stat3_value', 'hero_stat3_label', 'hero_stat3_icon',
                'hero_stat4_value', 'hero_stat4_label', 'hero_stat4_icon',
                'process_stat1_value', 'process_stat1_label', 'process_stat1_icon',
                'process_stat2_value', 'process_stat2_label', 'process_stat2_icon',
                'process_stat3_value', 'process_stat3_label', 'process_stat3_icon',
                'process_stat4_value', 'process_stat4_label', 'process_stat4_icon',
            ];
            $syncData = array_intersect_key($data, array_flip($globalHomeFields));
            if (!empty($syncData)) {
                ImageText::query()->update($syncData);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            ImageText::ensureColumnsExist();
            try {
                if ($content) {
                    $content->update($data);
                } else {
                    $data['unique_id'] = 12345;
                    $data['language_id'] = $langid;
                    ImageText::create($data);
                }
                ImageText::where('language_id', $langid)->update($data);
                if (!empty($syncData)) {
                    ImageText::query()->update($syncData);
                }
            } catch (\Throwable $ex) {}
        }

        Session::flash('success', __('Page Hero Banners Updated Successfully'));
        return "success";
    }

    public function removePageHeroImg($language_id, Request $request)
    {
        $data = ImageText::where('language_id', $language_id)->first();

        if ($data && !empty($request->name)) {
            $col = $request->name;
            if (isset($data->$col)) {
                @unlink(public_path('assets/front/img/') . $data->$col);
                $data->$col = NULL;
                $data->save();
            }
        }
        session()->flash('success', __('Removed successfully'));
        return response()->json(['status' => 'success']);
    }
}