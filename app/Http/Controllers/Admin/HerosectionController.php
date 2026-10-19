<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Admin\ImageText;
use Validator;
use Session;

class HerosectionController extends Controller
{
    public function imgtext(Request $request)
    {
        ImageText::ensureColumnsExist();
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['data'] = ImageText::where('language_id', $lang->id)->first();

        return view('admin.home.hero.img-text', $data);
    }

    public function update(Request $request, $langid)
    {
        ImageText::ensureColumnsExist();

        $rules = [
            'image' => 'mimes:jpg,jpeg,png',
            'work_process_image' => 'mimes:jpg,jpeg,png',
            'hero_section_title' => 'nullable|max:255',
            'hero_section_text' => 'nullable|max:255',
            'hero_section_desc' => 'nullable|max:255',
            'hero_section_button_text' => 'nullable|max:30',
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
            'features_section_btn_text' => 'nullable|max:30',
            'features_section_btn_url' => 'nullable',
            'features_section_video_url' => 'nullable',
            'blog_section_title' => 'nullable|max:255',
            'partner_section_title' => 'nullable|max:255',
            'partner_section_subtitle' => 'nullable|max:255',
            'work_process_section_title' => 'nullable|max:255',
            'work_process_section_subtitle' => 'nullable|max:255',
            'work_process_btn_text' => 'nullable|max:50',
            'work_process_btn_url' => 'nullable',
            'template_section_title' => 'nullable|max:255',
            'template_section_subtitle' => 'nullable|max:255',
            'pricing_section_title' => 'nullable|max:255',
            'pricing_section_subtitle' => 'nullable|max:255',
            'featured_shop_section_title' => 'nullable|max:255',
            'featured_shop_section_subtitle' => 'nullable|max:255',
            'testimonial_section_title' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $content = ImageText::where('language_id', $langid)->first();
        $data = $request->except(['_token', 'language', 'image', 'work_process_image', 'unique_id']);

        $dir = public_path('assets/front/img/');
        if ($request->hasFile('image')) {
            $existingImage = $content ? $content->image : null;

            if (!is_null($existingImage)) {
                $data['image'] = Uploader::update_picture($dir, $request->file('image'), $existingImage);
            } else {
                $data['image'] = Uploader::upload_picture($dir, $request->file('image'));
            }
        }

        if ($request->hasFile('work_process_image')) {
            $existingWpImage = $content ? $content->work_process_image : null;

            if (!is_null($existingWpImage)) {
                $data['work_process_image'] = Uploader::update_picture($dir, $request->file('work_process_image'), $existingWpImage);
            } else {
                $data['work_process_image'] = Uploader::upload_picture($dir, $request->file('work_process_image'));
            }
        }

        // Safe column filter to prevent unknown column SQL exceptions
        try {
            $existingCols = \Illuminate\Support\Facades\Schema::getColumnListing('image_texts');
            if (!empty($existingCols)) {
                $data = array_intersect_key($data, array_flip($existingCols));
            }
        } catch (\Throwable $e) {}

        ImageText::updateOrInsert(
            [
                'unique_id' => 12345,
                'language_id' => $langid,
            ],
            $data
        );

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function removeImg($language_id, Request $request)
    {
        $data = ImageText::where('language_id', $language_id)->first();

        if ($data) {
            if ($request->name == 'image') {
                @unlink(public_path('assets/front/img/') . $data->image);
                $data->image = NULL;
                $data->save();
            } elseif ($request->name == 'work_process_image') {
                @unlink(public_path('assets/front/img/') . $data->work_process_image);
                $data->work_process_image = NULL;
                $data->save();
            }
        }
        session()->flash('success', __('Remove successfully'));
        return response()->json(['status' => 'success']);
    }
}