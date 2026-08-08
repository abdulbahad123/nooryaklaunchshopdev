<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User;
use App\Models\Language;
use App\Models\Admin\UserCategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;

        if (session()->has('admin_lang')) {
            $lang_code = str_replace('admin_', '', session()->get('admin_lang'));
            $language = Language::where('code', $lang_code)->first();
            if (empty($language)) {
                $language = Language::where('is_default', 1)->first();
            }
        } else {
            $language = Language::where('is_default', 1)->first();
        }
        
        $shops = User::with(['category' => function ($query) use ($language) {
            if ($language) {
                $query->where('language_id', $language->id);
            }
        }])->where('preview_template', 0)
            ->when($term, function ($query, $term) {
                $query->where(function($q) use ($term) {
                    $q->where('username', 'like', '%' . $term . '%')
                      ->orWhere('email', 'like', '%' . $term . '%')
                      ->orWhere('shop_name', 'like', '%' . $term . '%');
                });
            })
            ->orderBy('landing_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('admin.shops.index', compact('shops'));
    }

    public function edit($id)
    {
        $shop = User::findOrFail($id);
        
        if (session()->has('admin_lang')) {
            $lang_code = str_replace('admin_', '', session()->get('admin_lang'));
            $language = Language::where('code', $lang_code)->first();
            if (empty($language)) {
                $language = Language::where('is_default', 1)->first();
            }
        } else {
            $language = Language::where('is_default', 1)->first();
        }

        $categories = UserCategory::where('language_id', $language->id)->get();

        return view('admin.shops.edit', compact('shop', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $shop = User::findOrFail($id);

        $rules = [
            'shop_name' => 'required|max:255',
            'landing_rating' => 'required|numeric|min:0|max:5',
            'landing_order' => 'required|integer',
            'category' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'preview_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ];

        $request->validate($rules);

        $shop->shop_name = $request->shop_name;
        $shop->landing_rating = $request->landing_rating;
        $shop->landing_description = $request->landing_description;
        $shop->landing_order = $request->landing_order;
        $shop->landing_status = $request->landing_status;
        $shop->category_id = $request->category;

        if ($request->hasFile('logo')) {
            $dir = public_path('assets/front/img/user/');
            if (!empty($shop->photo) && file_exists($dir . $shop->photo)) {
                @unlink($dir . $shop->photo);
            }
            $shop->photo = Uploader::upload_picture($dir, $request->file('logo'));
        }

        if ($request->hasFile('preview_image')) {
            $dir = public_path('assets/front/img/template-previews/');
            if (!empty($shop->template_img) && file_exists($dir . $shop->template_img)) {
                @unlink($dir . $shop->template_img);
            }
            $shop->template_img = Uploader::upload_picture($dir, $request->file('preview_image'));
        }

        $shop->save();

        Session::flash('success', __('Shop details updated successfully'));
        return redirect()->route('admin.shops.index');
    }

    public function status(Request $request)
    {
        $shop = User::findOrFail($request->user_id);
        $shop->landing_status = $request->landing_status;
        $shop->save();

        Session::flash('success', __('Landing status updated successfully'));
        return back();
    }

    public function reorder(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids)) {
            foreach ($ids as $index => $id) {
                User::where('id', $id)->update(['landing_order' => $index]);
            }
        }
        return response()->json(['status' => 'success', 'message' => __('Shops order updated successfully.')]);
    }
}
