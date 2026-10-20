<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\CounterInformation;
use App\Models\CounterSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CounterInformationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('language')) {
            $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        } else {
            $language = Language::where('is_default', 1)->first() ?? Language::first();
        }
        $information['counters'] = CounterInformation::where('language_id', $language->id)->orderBy('id', 'ASC')->orderBy('created_at','DESC')->get();
        $information['counterSection'] = CounterSection::where('language_id', $language->id)->first();
        $information['langs'] = Language::all();
        $information['language'] = $language;
        $information['lang_id'] = $language->id;
        \App\Models\Admin\ImageText::ensureColumnsExist();
        $information['homeSec'] = \App\Models\Admin\ImageText::where('language_id', $language->id)->first();

        return view('admin.about.counter-section.index', $information);
    }

    public function updateShopsCounters(Request $request, $lang_id)
    {
        \App\Models\Admin\ImageText::ensureColumnsExist();

        $fields = [
            'shops_stat1_value', 'shops_stat1_label', 'shops_stat1_icon',
            'shops_stat2_value', 'shops_stat2_label', 'shops_stat2_icon',
            'shops_stat3_value', 'shops_stat3_label', 'shops_stat3_icon',
            'shops_stat4_value', 'shops_stat4_label', 'shops_stat4_icon',
        ];

        $data = [];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        try {
            \App\Models\Admin\ImageText::updateOrInsert(
                [
                    'unique_id' => 12345,
                    'language_id' => $lang_id,
                ],
                $data
            );
        } catch (\Throwable $e) {
            \App\Models\Admin\ImageText::updateOrInsert(
                [
                    'language_id' => $lang_id,
                ],
                $data
            );
        }

        Session::flash('success', 'Stores Launched counter stats updated successfully!');
        return redirect()->back();
    }


    public function updateInfo(Request $request)
    {
        $image = $request->file('image');
        $allowedExts =  array('jpg', 'png', 'jpeg');
        $request->validate([
            'lang_code' => 'required',
            'title' => 'required',
            'text' => 'required',
            'image' => [
                function ($attribute, $value, $fail) use ($image, $allowedExts) {
                    if (!empty($image)) {
                        $ext = $image->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
        ]);

        $language = Language::query()->where('code', '=', $request->lang_code)->first();
        if (!$language) {
            $language = Language::where('is_default', 1)->first() ?? Language::first();
        }
        $info = CounterSection::where('language_id', $language->id)->first();
        if (empty($info)) {
            $info = new CounterSection();
            $info->language_id = $language->id;
        }
        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/counter-section/');
            $info->image =  Uploader::upload_picture($dir, $image);
        }

        $info->title = $request->title;
        $info->text = $request->text;
        $info->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function removeImg($language_id, Request $request)
    {
        $data = CounterSection::where('language_id', $language_id)->first();

        if ($request->name == 'image') {
            @unlink(public_path('assets/front/img/counter-section/') . $data->image);
            $data->image = NULL;
        }
        $data->save();
        session()->flash('success', __('Remove successfully'));
        return response()->json(['status' => 'success']);
    }

    public function storeCounter(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'icon' => 'required',
            'amount' => 'required|numeric',
            'title' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->all();
        CounterInformation::create($in);
        Session::flash('success', __('Created Successfully'));
        return 'success';
    }

    public function updateCounter(Request $request)
    {
        $rules = [
            'icon' => 'required',
            'amount' => 'required|numeric',
            'title' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $counterInfo = CounterInformation::query()->find($request->id);
        $counterInfo->update($request->except('language'));
        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function destroyCounter($id)
    {
        $counterInfo = CounterInformation::query()->findOrFail($id);
        $counterInfo->delete();
        return redirect()->back()->with('success', __('Deleted Successfully'));
    }

    public function bulkDestroyCounter(Request $request)
    {
        $ids = $request['ids'];
        foreach ($ids as $id) {
            $counterInfo = CounterInformation::query()->find($id);
            $counterInfo->delete();
        }
        Session::flash('success', __('Deleted Successfully'));
        return 'success';
    }
}
