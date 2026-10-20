<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Language;
use App\Models\Feature;
use App\Models\Admin\ImageText;
use Validator;
use Session;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;
        return view('admin.home.feature.index', $data);
    }

    public function edit($id)
    {
        $data['feature'] = Feature::findOrFail($id);
        return view('admin.home.feature.edit', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'title' => 'required|max:50',
            'serial_number' => 'required|integer',
            'text' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/feature/');
            $main_image =  Uploader::upload_picture($dir, $request->file('image'));
            $image = $main_image;
        } else {
            $image = null;
        }
        $feature = new Feature;
        $feature->icon = $image;
        $feature->language_id = $request->language_id;
        $feature->title = $request->title;
        $feature->text = $request->text;
        $feature->serial_number = $request->serial_number;
        $feature->save();
        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function update(Request $request)
    {
        $rules = [
            'image' => 'required',
            'title' => 'required|max:50',
            'serial_number' => 'required|integer',
            'text' => 'required',
            'image' => 'mimes:jpg,jpeg,png'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $feature = Feature::findOrFail($request->feature_id);
        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/feature/');
            @unlink($dir . $feature->icon);
            $feature->icon = Uploader::upload_picture($dir, $request->file('image'));
        }
        $feature->title = $request->title;
        $feature->text = $request->text;
        $feature->serial_number = $request->serial_number;
        $feature->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {

        $feature = Feature::findOrFail($request->feature_id);
        @unlink(public_path('assets/front/img/feature/') . $feature->icon);
        $feature->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }

    public function updateSection(Request $request, $langid)
    {
        ImageText::ensureColumnsExist();
        $imgText = ImageText::where('language_id', $langid)->first();
        if (!$imgText) {
            $imgText = new ImageText();
            $imgText->language_id = $langid;
        }

        $imgText->features_section_title = $request->features_section_title;
        $imgText->features_section_subtitle = $request->features_section_subtitle;
        $imgText->features_section_text = $request->features_section_text;
        $imgText->features_section_btn_text = $request->features_section_btn_text;
        $imgText->features_section_btn_url = $request->features_section_btn_url;
        $imgText->save();

        Session::flash('success', __('Features Section Content Updated Successfully'));
        return redirect()->back();
    }
}