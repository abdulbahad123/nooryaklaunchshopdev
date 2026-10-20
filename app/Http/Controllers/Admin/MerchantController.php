<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Merchant;
use App\Models\Admin\ImageText;
use App\Http\Helpers\Uploader;
use Session;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first() ?: Language::where('is_default', 1)->first();
        if (!$lang) {
            $lang = Language::first();
        }
        $lang_id = $lang->id;

        Merchant::ensureTableExistsAndSeeded($lang_id);
        ImageText::ensureColumnsExist();

        $data['lang_id'] = $lang_id;
        $data['homeSec'] = ImageText::where('language_id', $lang_id)->first();
        $data['featuredMerchants'] = Merchant::where('type', 'featured')->where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['lovedMerchants'] = Merchant::where('type', 'review')->where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['whyChooseCards'] = Merchant::where('type', 'why_choose')->where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();

        return view('admin.merchants.index', $data);
    }

    public function updateHeaders(Request $request, $langid)
    {
        ImageText::ensureColumnsExist();
        $imgText = ImageText::where('language_id', $langid)->first();
        if (!$imgText) {
            $imgText = new ImageText();
            $imgText->language_id = $langid;
        }

        $imgText->shops_featured_merchants_title = $request->shops_featured_merchants_title;
        $imgText->shops_featured_merchants_subtitle = $request->shops_featured_merchants_subtitle;
        $imgText->shops_loved_merchants_title = $request->shops_loved_merchants_title;
        $imgText->shops_loved_merchants_subtitle = $request->shops_loved_merchants_subtitle;
        $imgText->shops_why_choose_title = $request->shops_why_choose_title;
        $imgText->save();

        Session::flash('success', __('Section headings updated successfully!'));
        return redirect()->back();
    }

    public function storeItem(Request $request)
    {
        $data = [
            'language_id' => $request->language_id,
            'type' => $request->type ?? 'featured',
            'name' => $request->name,
            'designation' => $request->designation,
            'quote' => $request->quote,
            'rating' => $request->rating ?? 5,
            'icon' => $request->icon ?? 'fas fa-mobile-alt',
            'badge_color' => $request->badge_color ?? 'orange',
            'serial_number' => $request->serial_number ?? 0,
            'status' => $request->status ?? 1,
        ];

        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/merchants/');
            $data['image'] = Uploader::upload_picture($dir, $request->file('image'));
        }

        Merchant::create($data);
        Session::flash('success', __('Item added successfully!'));
        return redirect()->back();
    }

    public function updateItem(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);
        $merchant->name = $request->name;
        $merchant->designation = $request->designation;
        $merchant->quote = $request->quote;
        $merchant->rating = $request->rating ?? $merchant->rating;
        $merchant->icon = $request->icon ?? $merchant->icon;
        $merchant->badge_color = $request->badge_color ?? $merchant->badge_color;
        $merchant->serial_number = $request->serial_number ?? $merchant->serial_number;
        $merchant->status = $request->status ?? $merchant->status;

        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/merchants/');
            if (!empty($merchant->image) && file_exists($dir . $merchant->image)) {
                $merchant->image = Uploader::update_picture($dir, $request->file('image'), $merchant->image);
            } else {
                $merchant->image = Uploader::upload_picture($dir, $request->file('image'));
            }
        }

        $merchant->save();
        Session::flash('success', __('Item updated successfully!'));
        return redirect()->back();
    }

    public function deleteItem(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);
        if (!empty($merchant->image)) {
            @unlink(public_path('assets/front/img/merchants/' . $merchant->image));
        }
        $merchant->delete();
        Session::flash('success', __('Item deleted successfully!'));
        return redirect()->back();
    }
}