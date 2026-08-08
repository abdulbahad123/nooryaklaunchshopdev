<?php

namespace App\Http\Controllers\User;

use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserCurrency;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\UserItem;
use App\Http\Helpers\Uploader;
use App\Models\User\UserItemImage;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\Common;
use App\Models\User;
use App\Models\User\ProductVariantOption;
use App\Models\User\ProductVariantOptionContent;
use App\Models\User\ProductVariation;
use App\Models\User\ProductVariationContent;
use App\Models\User\UserItemContent;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserItemCategory;
use App\Models\User\UserItemReview;
use Illuminate\Support\Facades\Session;
use App\Models\User\UserItemSubCategory;
use App\Models\User\UserItemVariation;
use App\Models\User\UserOrder;
use App\Models\Variant;
use App\Models\VariantContent;
use App\Models\VariantOption;
use App\Models\VariantOptionContent;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $data['item_limit'] = $current_package->product_limit;
        $data['total_item'] = UserItemContent::where('language_id', $lang->id)->where('user_id', Auth::guard('web')->user()->id)->count();
        $title = null;
        if ($request->filled('title')) {
            $title = $request->title;
        }

        $data['items'] = DB::table('user_items')->where('user_items.user_id', Auth::guard('web')->user()->id)
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category')
            ->orderBy('user_items.id', 'DESC')
            ->where('user_item_contents.language_id', '=', $lang_id)
            ->where('user_item_categories.language_id', '=', $lang_id)
            ->when($title, function ($query) use ($title) {
                return $query->where('user_item_contents.title', 'LIKE', '%' . $title . '%');
            })
            ->paginate(10);
        $data['lang_id'] = $lang_id;
        $data['currency'] = UserCurrency::where('user_id', Auth::guard('web')->user()->id)->where('is_default', 1)->first();
        return view('user.item.index', $data);
    }


    public function type(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['digitalCount'] = UserItem::where([['type', 'digital'], ['user_id', $user_id]])->count();
        $data['physicalCount'] = UserItem::where([['type', 'physical'], ['user_id', $user_id]])->count();
        return view('user.item.type', $data);
    }

    public function create(Request $request)
    {
        $data['lang'] = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $data['languages'] = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $data['currency'] = UserCurrency::where('user_id', Auth::guard('web')->user()->id)->where('is_default', 1)->first();

        $data['categories'] = UserItemCategory::where('language_id', $data['lang']->id)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('user.item.create', $data);
    }

    public function uploadUpdate(Request $request, $id)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__("Only png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'slider']);
        }
        $product = UserItem::findOrFail($id);
        if ($request->hasFile('file')) {
            $dir = public_path('assets/front/img/product/featured/');
            @unlink($dir . $product->feature_image);
            $product->feature_image = Uploader::upload_picture($dir, $request->file('file'));
            $product->save();
        }
        return response()->json(['status' => "success", "image" => "Product image", 'product' => $product]);
    }
    public function getCategory($langid)
    {
        $category = UserItemCategory::where('language_id', $langid)->where('status', 1)->get();
        return $category;
    }

    public function store(Request $request)
    {
    
 
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $item_limit = $current_package->product_limit;

        $total_item = UserItem::where('user_id', Auth::guard('web')->user()->id)->count();
        $total_item = $total_item + 1;

        if ($item_limit < $total_item) {
            Session::flash('warning', __('Item Limit Exceeded'));
            return 'success';
        }

        $languages = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $defaulLang = Language::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();
        $messages = [];
        $rules = [];
        $sliderImgURLs = $request->has('image') ? $request->image : [];
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $sliderImgExts = [];
        $rules['image'] = [
            'required',
            function ($attribute, $value, $fail) use ($allowedExtensions, $sliderImgExts) {
                if (!empty($sliderImgExts)) {
                    foreach ($sliderImgExts as $sliderImgExt) {
                        if (!in_array($sliderImgExt, $allowedExtensions)) {
                            $fail(__('Only jpeg,png,svg,jpg files are allowed'));
                            break;
                        }
                    }
                }
            }
        ];
        // $rules['thumbnail'] = 'required';
        $rules['thumbnail'] = 'required_without:ai_generated_image';
        // if product type is 'physical'
        if ($request->type == 'physical') {
            $rules['stock'] = 'required';
            $rules['sku'] = 'required|unique:user_items';
        }
        $rules['status'] = 'required';
        // pplimorp
        $rules['current_price'] = 'required|numeric|min:0.01';
        $rules['previous_price'] = 'nullable|numeric|min:0.01';
        $rules['category'] = 'required';
        $messages['image.required'] = __('The slider Image is required') . '.';

        $catUIds = [];
        $catUIds = UserItemCategory::where('id', $request->category)
            ->pluck('unique_id')->toArray();

        $categoryLangIds = UserItemCategory::whereIn('unique_id', $catUIds)->pluck('language_id')->toArray();

        foreach ($languages as $language) {
            $code = $language->code;
            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_label_id') ||
                $request->input($code . '_summary') ||
                $request->input($code . '_description') ||
                $request->input($code . '_meta_keywords') ||
                $request->input($code . '_meta_description')
            ) {
                //check category is exist for every input langauge
                if (!in_array($language->id, $categoryLangIds)) {
                    $rules[$code . '_category'] = 'required';
                    $messages[$code . '_category.required'] = __('Please add') . ' ' . $language->name . ' ' . __('content for this category before submitting content in this language.');
                }
                $rules[$code . '_title'] = [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($language, $request, $code) {
                        $slug = make_slug($request[$code . '_title']);
                        $ics = UserItemContent::where('language_id', $language->id)->where('user_id', Auth::guard('web')->user()->id)->get();
                        foreach ($ics as $key => $ic) {
                            if (strtolower($slug) == strtolower($ic->slug)) {
                                $fail(__('The title field must be unique for') . ' ' . $language->name . ' ' . __('language'));
                            }
                        }
                    }
                ];
                $rules[$code . '_summary'] = 'required';
                $rules[$code . '_description'] = 'required';
            }
            $messages[$language->code . '_title.required'] = __('The title field is required for') . ' ' . $language->name . ' ' . __('language');
            $messages[$language->code . '_summary.required'] = __('The summary field is required for') . ' ' . $language->name . ' ' . __('language');
            $messages[$language->code . '_description.required'] = __('The description field is required for') . ' ' . $language->name . ' ' . __('language');
        }

        // if product type is 'digital'
        if ($request->type == 'digital') {
            $rules['file_type'] = 'required';
            // if 'file upload' is chosen
            if ($request->has('file_type') && $request->file_type == 'upload') {
                $rules['download_file'] = 'required|mimes:zip';
            } elseif ($request->has('file_type') && $request->file_type == 'link') {
                $rules['download_link'] = 'required';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!empty($sliderImgURLs)) {
            foreach ($sliderImgURLs as $sliderImgURL) {
                $n = strrpos($sliderImgURL, ".");
                $extension = ($n === false) ? "" : substr($sliderImgURL, $n + 1);
                array_push($sliderImgExts, $extension);
            }
        }

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        // if the type is digital && 'upload file' method is selected, then store the downloadable file
        if ($request->type == 'digital' && $request->file_type == 'upload') {
            if ($request->hasFile('download_file')) {
                $digitalFile = $request->file('download_file');
                $filename = time() . '-' . uniqid() . "." . $digitalFile->extension();
                $directory = storage_path('/digital_products');
                @mkdir($directory, 0775, true);
                $digitalFile->move($directory, $filename);
            }
        }

        $user_currency = UserCurrency::where('is_default', 1)->where('user_id', Auth::guard('web')->user()->id)->first();
        $currency_id = $user_currency->id;

        $thumbnail_name = null;
        $item = new UserItem();
        $thumbnail = $request->file('thumbnail');
        if ($request->hasFile('thumbnail')) {
            $dir = public_path('assets/front/img/user/items/thumbnail/');

            $thumbnail_name = uniqid() . '.webp';
            $image = Image::make($thumbnail->getRealPath());

            @mkdir($dir, 0775, true);
            $image->resize(255, 255);
            $image->save($dir . $thumbnail_name);
        } elseif (!empty($request->ai_generated_image)) {
            $thumbnail_name = moveAiStorageImageToPublicAssets(
                $request->ai_generated_image,
                public_path('assets/front/img/user/items/thumbnail/')
            );
        }

        $sliderDir = public_path('assets/front/img/user/items/slider-images/');
        @mkdir($sliderDir, 0775, true);
        $item->user_id = Auth::guard('web')->user()->id;
        $item->stock = $request->stock;
        $item->sku = $request->sku;
        $item->thumbnail = $thumbnail_name;
        $item->status = $request->status;
        $item->current_price = $request->current_price;
        $item->previous_price = $request->previous_price;
        $item->currency_id = $currency_id;
        $item->type = $request->type;
        $item->download_file = $filename ?? null;
        $item->download_link = $request->download_link;
        $item->background_color = $request->background_color;
        $item->save();
        foreach ($request->image as $value) {
            UserItemImage::create([
                'item_id' => $item->id,
                'image' => $value,
            ]);
        }
        // store varations as json
        $catUnique_id = UserItemCategory::where('id', $request->category)
            ->pluck('unique_id')->first();
        $subcatUnique_id = UserItemSubCategory::where('id', $request->subcategory)
            ->pluck('unique_id')->first();

        foreach ($languages as $language) {
            $code = $language->code;
            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_label_id') ||
                $request->input($code . '_summary') ||
                $request->input($code . '_description') ||
                $request->input($code . '_meta_keywords') ||
                $request->input($code . '_meta_description')
            ) {
                $categoryId = UserItemCategory::where([['language_id', $language->id], ['unique_id', $catUnique_id]])->pluck('id')->first();
                $subcategoryId = UserItemSubCategory::where([['language_id', $language->id], ['unique_id', $subcatUnique_id]])->pluck('id')->first();

                $adContent = new UserItemContent();
                $adContent->item_id = $item->id;
                $adContent->user_id = Auth::guard('web')->user()->id;
                $adContent->language_id = $language->id;
                $adContent->category_id = $categoryId;
                $adContent->subcategory_id = $subcategoryId;
                $adContent->label_id = $request[$code . '_label_id'];
                $adContent->title = $request[$code . '_title'];
                $adContent->slug = make_slug($request[$code . '_title']);
                $adContent->summary = Purifier::clean($request[$code . '_summary'], 'youtube');
                $adContent->description = Purifier::clean($request[$code . '_description'], 'youtube');
                $adContent->meta_keywords = $request[$code . '_meta_keywords'];
                $adContent->meta_description = $request[$code . '_meta_description'];
                $adContent->save();
            }
        }
        Session::flash('success', __('Created successfully'));
        return 'success';
    }
    public function edit(Request $request, $id)
    {
        $currentLang = Language::where('code', $request->language)->pluck('id')->firstOrFail();
        $user_id = Auth::guard('web')->user()->id;
        $data['languages'] = Language::where('user_id', $user_id)->get();
        $data['item'] = UserItem::where([['id', $id], ['user_id', $user_id]])->firstOrFail();

        $data['title'] = UserItemContent::where([['item_id', $data['item']->id], ['language_id', $currentLang]])->pluck('title')->first();

        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $item_limit = $current_package->product_limit;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $data['lang'] = $lang;
        $total_item = UserItem::where('user_id', $user_id)->count();
        if ($total_item > $item_limit) {
            Session::flash('warning', __('Delete item to enable editing'));
            return redirect()->back()->with('success');
        }

        $data['currency'] = UserCurrency::where('user_id', $user_id)->where('is_default', 1)->first();

        return view('user.item.edit', $data);
    }

    public function update(Request $request)
    {
        $item = UserItem::findOrFail($request->item_id);
        // if product type is 'physical'
        if ($item->type == 'physical') {
            $rules['stock'] = 'required';
            $rules['sku'] = 'required|unique:user_items,sku,' . $item->id;
        }
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
        $sliderImgURLs = array_key_exists("image", $request->all()) && count($request->image) > 0 ? $request->image : [];
        $sliderImgExts = [];
        // get all the slider images extension
        if (!empty($sliderImgURLs)) {
            foreach ($sliderImgURLs as $sliderImgURL) {
                $n = strrpos($sliderImgURL, ".");
                $extension = ($n === false) ? "" : substr($sliderImgURL, $n + 1);
                array_push($sliderImgExts, $extension);
            }
        }
        if (array_key_exists("image", $request->all()) && count($request->image) > 0) {
            $rules['image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $sliderImgExts) {
                foreach ($sliderImgExts as $sliderImgExt) {
                    if (!in_array($sliderImgExt, $allowedExtensions)) {
                        $fail(__('Only jpeg,png,svg,jpg files are allowed'));
                        break;
                    }
                }
            };
        }
        $languages = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $messages = [];
        $rules['current_price'] = 'required|numeric|min:0.01';
        $rules['previous_price'] = 'nullable|numeric|min:0.01';
        $rules['category'] = 'required';

        $catUIds = [];
        $catUIds = UserItemCategory::where('id', $request->category)
            ->pluck('unique_id')->toArray();

        $categoryLangIds = UserItemCategory::whereIn('unique_id', $catUIds)->pluck('language_id')->toArray();

        foreach ($languages as $language) {
            $code = $language->code;
            $langName = ' ' . $language->name . ' ' . __('language');
            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_label_id') ||
                $request->input($code . '_summary') ||
                $request->input($code . '_description') ||
                $request->input($code . '_meta_keywords') ||
                $request->input($code . '_meta_description')
            ) {
                //check category is exist for every input langauge
                if (!in_array($language->id, $categoryLangIds)) {
                    $rules[$code . '_category'] = 'required';
                    $messages[$code . '_category.required'] = __('Please add') . ' ' . $language->name . ' ' . __('content for this category before submitting content in this language.');
                }
                $rules[$code . '_title'] = [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($language, $request, $code) {
                        $slug = make_slug($request[$code . '_title']);
                        $ics = UserItemContent::where('language_id', $language->id)->where('user_id', Auth::guard('web')->user()->id)->where('item_id', '<>', $request->item_id)->get();
                        foreach ($ics as $key => $ic) {
                            if (strtolower($slug) == strtolower($ic->slug)) {
                                $fail(__('The title field must be unique for') . ' ' . $language->name . ' ' . __('language'));
                            }
                        }
                    }
                ];

                $rules[$code . '_summary'] = 'required';
                $rules[$code . '_description'] = 'required';
            }
            $messages[$code . '_title.required'] = __('The title field is required for') . $langName;
            $messages[$code . '_summary.required'] = __('The summary field is required for') . $langName;
            $messages[$code . '_description.required'] = __('The description field is required for') . $langName;
        }


        // if product type is 'digital'
        if ($item->type == 'digital') {
            // if 'file upload' is chosen
            if ($request->has('file_type') && $request->file_type == 'upload') {
                if (empty($item->download_file)) {
                    $rules['download_file'] = 'required|mimes:zip';
                }
            }
            // if 'file donwload link' is chosen
            elseif ($request->has('file_type') && $request->file_type == 'link') {
                $rules['download_link'] = 'required';
            }
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        if (!empty($sliderImgURLs)) {
            foreach ($sliderImgURLs as $sliderImgURL) {
                $n = strrpos($sliderImgURL, ".");
                $extension = ($n === false) ? "" : substr($sliderImgURL, $n + 1);
                array_push($sliderImgExts, $extension);
            }
        }

        // if the type is digital && 'upload file' method is selected, then store the downloadable file
        if ($request->type == 'digital' && $request->file_type == 'upload') {
            if ($request->hasFile('download_file')) {
                $digitalFile = $request->file('download_file');
                $filename = time() . '-' . uniqid() . "." . $digitalFile->extension();
                $directory = storage_path('digital_products/');
                @mkdir($directory, 0775, true);
                @unlink($directory . $item->download_file);
                $digitalFile->move($directory, $filename);
            }
        }

        //also if user update file to link delete prevous file
        if ($request->type == 'digital' && $request->file_type == 'link') {
            @unlink(storage_path('digital_products/') . $item->download_file);
        }

        $thumbnail = $request->file('thumbnail');
        if ($request->hasFile('thumbnail')) {

            $dir = public_path('assets/front/img/user/items/thumbnail/');
            @unlink($dir . $item->thumbnail);

            $thumbnail_name = uniqid() . '.webp';
            $image = Image::make($thumbnail->getRealPath());

            @mkdir($dir, 0775, true);
            $image->resize(255, 255);
            $image->save($dir . $thumbnail_name);
        }

        $item->stock = $request->stock;
        $item->sku = $request->sku;
        $item->status = $request->status;
        $item->thumbnail = $request->hasFile('thumbnail') ? $thumbnail_name : $item->thumbnail;
        $item->current_price = $request->current_price;
        $item->previous_price = $request->previous_price;
        $item->type = $request->type;
        $item->download_file = $filename ?? null;
        $item->download_link = $request->download_link;
        $item->background_color = $request->background_color;
        $item->save();
        if ($request->image) {
            foreach ($request->image as $value) {
                UserItemImage::create([
                    'item_id' => $item->id,
                    'image' => $value,
                ]);
            }
        }

        $catUnique_id = UserItemCategory::where('id', $request->category)
            ->pluck('unique_id')->first();
        $subcatUnique_id = UserItemSubCategory::where('id', $request->subcategory)
            ->pluck('unique_id')->first();

        foreach ($languages as $language) {
            $categoryId = UserItemCategory::where([['language_id', $language->id], ['unique_id', $catUnique_id]])->pluck('id')->first();
            $subcategoryId = UserItemSubCategory::where([['unique_id', $subcatUnique_id], ['language_id', $language->id]])->pluck('id')->first();


            $adContent = UserItemContent::where('item_id', $request->item_id)
                ->where('language_id', $language->id)
                ->first();
            if ($adContent == NULL) {
                $adContent = new UserItemContent();
                $adContent->item_id = $item->id;
                $adContent->user_id = Auth::guard('web')->user()->id;
                $adContent->language_id = $language->id;
            }
            if (
                $request->input($code . '_title') ||
                $request->input($code . '_label_id') ||
                $request->input($code . '_summary') ||
                $request->input($code . '_description') ||
                $request->input($code . '_meta_keywords') ||
                $request->input($code . '_meta_description')
            ) {
                $adContent->category_id = $categoryId;
                $adContent->subcategory_id = $subcategoryId;
                $adContent->label_id = $request[$language->code . '_label_id'];
                $adContent->title = $request[$language->code . '_title'];
                $adContent->slug = make_slug($request[$language->code . '_title']);
                $adContent->summary = Purifier::clean($request[$language->code . '_summary'], 'youtube');
                $adContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
                $adContent->meta_keywords = $request[$language->code . '_meta_keywords'];
                $adContent->meta_description = $request[$language->code . '_meta_description'];
                $adContent->save();
            }
        }
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }
    public function feature(Request $request)
    {

        $item = UserItem::findOrFail($request->item_id);
        $item->is_feature = $request->is_feature;
        $item->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }
    public function specialOffer(Request $request)
    {
        $item = UserItem::findOrFail($request->item_id);
        $item->secial_offer = $request->secial_offer;
        $item->save();
        if ($request->secial_offer == 1) {
            Session::flash('success', 'Item added to Special offer successfully!');
        } else {
            Session::flash('success', 'Item remove from Special offer successfully!');
        }
        return back();
    }


    public function delete(Request $request)
    {
        $item = UserItem::findOrFail($request->item_id);
        if (!empty($item->thumbnail)) {
            $otherCount = UserItem::where('thumbnail', $item->thumbnail)->where('id', '!=', $item->id)->count();
            if ($otherCount == 0 && file_exists(public_path('assets/front/img/user/items/thumbnail/') . $item->thumbnail)) {
                // Keep image on disk so CSV re-import or cloned products can reuse it
            }
        }
        foreach ($item->sliders as $key => $image) {
            $image->delete();
        }
        $item->itemContents()->delete();

        //delete reviews
        $reviews = UserItemReview::where('item_id', $item->id)->get();
        foreach ($reviews as $review) {
            $review->delete();
        }

        //delete product variation
        $product_variations = ProductVariation::where('item_id', $request->item_id)->get();
        foreach ($product_variations as $product_variation) {

            //delete product variation contents
            $product_variation_contents = ProductVariationContent::where('product_variation_id', $product_variation->id)->get();
            foreach ($product_variation_contents as $product_variation_content) {
                $product_variation_content->delete();
            }

            //delete product_variant_options
            $product_variant_options = ProductVariantOption::where('product_variation_id', $product_variation->id)->get();
            foreach ($product_variant_options as $product_variant_option) {
                //delete product_variant_option_contents
                $product_variant_option_contents = ProductVariantOptionContent::where('product_variant_option_id', $product_variant_option->id)->get();
                foreach ($product_variant_option_contents as $product_variant_option_content) {
                    $product_variant_option_content->delete();
                }

                $product_variant_option->delete();
            }

            $product_variation->delete();
        }

        $item->delete();
        @unlink(storage_path('digital_products/') . $item->download_file);
        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $item = UserItem::where('id', $id)->first();
            if ($item) {
                if (!empty($item->thumbnail)) {
                    $otherCount = UserItem::where('thumbnail', $item->thumbnail)->where('id', '!=', $item->id)->count();
                    if ($otherCount == 0 && file_exists(public_path('assets/front/img/user/items/thumbnail/') . $item->thumbnail)) {
                        // Keep image on disk so CSV re-import or cloned products can reuse it
                    }
                }
                foreach ($item->sliders as $key => $image) {
                    $image->delete();
                }
                $item->itemContents()->delete();


                //delete product variation
                $product_variations = ProductVariation::where('item_id', $id)->get();
                foreach ($product_variations as $product_variation) {

                    //delete product variation contents
                    $product_variation_contents = ProductVariationContent::where('product_variation_id', $product_variation->id)->get();
                    foreach ($product_variation_contents as $product_variation_content) {
                        $product_variation_content->delete();
                    }

                    //delete product_variant_options
                    $product_variant_options = ProductVariantOption::where('product_variation_id', $product_variation->id)->get();
                    foreach ($product_variant_options as $product_variant_option) {
                        //delete product_variant_option_contents
                        $product_variant_option_contents = ProductVariantOptionContent::where('product_variant_option_id', $product_variant_option->id)->get();
                        foreach ($product_variant_option_contents as $product_variant_option_content) {
                            $product_variant_option_content->delete();
                        }

                        $product_variant_option->delete();
                    }

                    $product_variation->delete();
                }

                @unlink(storage_path('digital_products/') . $item->download_file);
                $item->delete();
            }
        }
        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
    public function variants($pid, $lang)
    {
        $variations = UserItemVariation::where('item_content_id', $pid)->where('language_id', $lang)->get();
        $variants = [];
        $i = 0;
        foreach ($variations as $key => $value) {
            $variants[$i] = [
                'name' => str_replace("_", " ", $value->variant_name),
                'uniqid' => uniqid(),
            ];
            $option_names = json_decode($value->option_name);
            $option_prices = json_decode($value->option_price);
            $option_stocks = json_decode($value->option_stock);
            $j = 0;
            foreach ($option_names as $okey => $val) {
                $variants[$i]['options'][$j]['name'] = $val;
                $variants[$i]['options'][$j]['price'] = $option_prices[$okey];
                $variants[$i]['options'][$j]['stock'] = $option_stocks[$okey];
                $j++;
            }
            $i++;
        }
        return response()->json($variants);
    }

    public function variations($id, Request $request)
    {
        $currentLang = Language::where('code', $request->language)->pluck('id')->firstOrFail();
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $item_limit = $current_package->product_limit;
        $total_item = UserItem::where('user_id', Auth::guard('web')->user()->id)->count();
        if ($item_limit < $total_item) {
            Session::flash('warning', __('Item limit exceeded'));
            return back();
        }

        $id = (int)$id;
        $data['item_id'] = $id;
        $data['variations'] = ProductVariation::where('item_id', $id)->get();

        $data['title'] = UserItemContent::where([['item_id', $id], ['language_id', $currentLang]])->pluck('title')->first();
        $data['currency'] = UserCurrency::where('user_id', Auth::guard('web')->user()->id)->where('is_default', 1)->first();

        return view('user.item.variation', $data);
    }

    public function getVariation(Request $request)
    {
        $variation_content = VariantContent::where('id', $request->variation_content_id)->select('variant_id')->first();
        $variant_option_contents = VariantOptionContent::where([
            ['variant_id', $variation_content->variant_id],
            ['language_id', $request->language_id],
        ])->get();
        return $variant_option_contents;
    }

    public function variationStore(Request $request)
    {
        // Get the user ID and user languages
        $user_id = Auth::guard('web')->user()->id;

        // Initialize validation rules and messages arrays
        $rules = [];
        $messages = [];

        // Default rules for variations and options
        $rules['unique_id.*'] = 'required';
        $messages['unique_id.*.required'] = __('The variation ID is required');
        $rules["*variation_name.*"] = 'required';
        $rules["*option_name.*"] = 'required';
        $messages["*variation_name.*.required"] = __('The variation name is required.');
        $messages["*option_name.*.required"] = __('The variation name is required.');

        // Rules for price and stock
        $rules['*_price.*'] = 'required|numeric';
        $messages['*_price.*.required'] = __('The price is required');
        $messages['*_price.*.numeric'] = __('The price must be a number');

        $rules['*_stock.*'] = 'required|numeric';
        $messages['*_stock.*.required'] = __('The stock is required');
        $messages['*_stock.*.numeric'] = __('The stock must be a number');

        // Create validator instance
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check if validation fails
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $user_id = Auth::guard('web')->user()->id;
        $languages = Language::where('user_id', $user_id)->get();
        $item_id = $request->item_id;
        $unique_ids = $request->unique_id;
        if (!is_null($unique_ids)) {
            foreach ($unique_ids as $index => $unique_id) {
                // Create the main variation
                $variation = ProductVariation::firstOrNew(['unique_id' => $unique_id]);
                if (!$variation->exists) {
                    $variation->user_id = $user_id;
                    $variation->item_id = $item_id;
                    $variation->unique_id = $unique_id;
                    $variation->save();
                }

                // Save the variation content for each language
                $variation_name_key = $unique_id . '_variation_name';
                $selected_variation_ids = $request->input($variation_name_key) ?? [];

                // Fetch existing variation content IDs to identify outdated ones
                $existingContentIds = ProductVariationContent::where('product_variation_id', $variation->id)
                    ->where('user_id', $user_id)
                    ->pluck('variation_name')
                    ->toArray();

                // Determine which IDs to delete (those not in the current selection)
                $idsToDelete = array_diff($existingContentIds, $selected_variation_ids);

                // Delete outdated ProductVariationContent records
                ProductVariationContent::where('product_variation_id', $variation->id)
                    ->whereIn('variation_name', $idsToDelete)
                    ->delete();

                $_variant = VariantContent::whereIn('id', $selected_variation_ids)->select('variant_id')->get();

                foreach ($languages as $language) {
                    foreach ($_variant as $variant) {
                        // Check for an existing variation name in the language
                        $variation_name = VariantContent::where([
                            ['variant_id', $variant->variant_id],
                            ['language_id', $language->id],
                        ])->select('id')->first();

                        if ($variation_name) {
                            // Find or create the variation content for this language
                            $variationContent = ProductVariationContent::firstOrNew([
                                'user_id' => $user_id,
                                'product_variation_id' => $variation->id,
                                'language_id' => $language->id,
                                'variation_name' => $variation_name->id,
                            ]);

                            // Update necessary fields
                            $variationContent->item_id = $item_id;
                            $variationContent->save();
                        }
                    }
                }

                // Save the variant options
                $prices = $request->input("{$unique_id}_price") ?? [];
                $stocks = $request->input("{$unique_id}_stock") ?? [];
                $option_ids = $request->input("{$unique_id}_optionid") ?? [];

                foreach ($prices as $option_index => $price) {

                    $stock = $stocks[$option_index] ?? null;
                    $option_id = $option_ids[$option_index] ?? null;


                    if ($price !== null && $stock !== null) {
                        if ($option_id == 'new') {
                            $variantOption = new ProductVariantOption();
                        } else {
                            $variantOption = ProductVariantOption::where('id', $option_id)->first();
                            // If the $variantOption is still null, create a new instance
                            if (is_null($variantOption)) {
                                $variantOption = new ProductVariantOption();
                            }
                        }
                        $variantOption->product_variation_id = $variation->id;
                        $variantOption->unique_id = $variation->unique_id;
                        $variantOption->user_id = $user_id;
                        $variantOption->item_id = $item_id;
                        $variantOption->price = $price ?? 0;
                        $variantOption->stock = $stock ?? 0;
                        $variantOption->save();

                        $option_name_key = "{$unique_id}_option_name";
                        $option_name = $request->$option_name_key[$option_index] ?? null;

                        $_variant_options = VariantOptionContent::where('id', $option_name)->select('variant_id', 'index_key')->first();


                        foreach ($languages as $language) {
                            $variation_name = VariantOptionContent::where([['variant_id', $_variant_options->variant_id], ['language_id', $language->id], ['index_key', $_variant_options->index_key]])->select('id', 'language_id', 'option_name')->first();

                            if ($variation_name !== null) {
                                $variantOptionContent = ProductVariantOptionContent::where([['product_variant_option_id', intval($variantOption->id)], ['language_id', $language->id]])->first();

                                if (is_null($variantOptionContent)) {
                                    $variantOptionContent = new ProductVariantOptionContent();
                                }
                                $variantOptionContent->product_variant_option_id = $variantOption->id;
                                $variantOptionContent->user_id = $user_id;
                                $variantOptionContent->item_id = $item_id;
                                $variantOptionContent->language_id = $language->id;
                                $variantOptionContent->option_name = $variation_name->id;
                                $variantOptionContent->save();
                            }
                        }
                    }
                }
            }
            Session::flash('success', __('Created successfully'));
        } else {
            Session::flash('success', __('Updated successfully'));
        }
        return 'success';
    }

    public function variationDelete($id)
    {
        //get_product_variation first
        $product_variation = ProductVariation::where('unique_id', $id)->first();
        if ($product_variation) {
            //get product varition contents
            $product_variation_contents = ProductVariationContent::where('product_variation_id', $id)->get();
            foreach ($product_variation_contents as $item) {
                $item->delete();
            }
            $product_variation->delete();

            //get product variation options
            $product_variation_options = ProductVariantOption::where('unique_id', $id)->get();
            foreach ($product_variation_options as $product_variation_option) {
                //get product_variant_option_contents
                $product_variant_option_contents = ProductVariantOptionContent::where('product_variant_option_id', $product_variation_option->id)->get();
                foreach ($product_variant_option_contents as $product_variant_option_content) {
                    $product_variant_option_content->delete();
                }
                $product_variation_option->delete();
            }

            $count = ProductVariation::where('item_id', $product_variation->item_id)->count();
            if ($count > 0) {
                return 'success';
            } else {
                return 'reload';
            }
        } else {
            return 'error';
        }
    }

    public function variationOptionDelete(Request $request)
    {
        $variant_option = ProductVariantOption::where('id', $request->id)->first();
        if ($variant_option) {
            $option_contents = ProductVariantOptionContent::where('product_variant_option_id', $variant_option->id)->get();
            foreach ($option_contents as $option_content) {
                $option_content->delete();
            }
            $variant_option->delete();
        }
        return 'success';
    }

    public function paymentStatus(Request $request)
    {
        $order = UserOrder::findOrFail($request->order_id);
        $user = User::where('id', $order->user_id)->firstOrFail();

        $dir = public_path('assets/front/invoices/');
        if ($request->payment_status == 'Completed') {
            @unlink($dir . $order->invoice_number);
            $invoice = Common::generateInvoice($order, $user);
        }
        $order->payment_status = $request->payment_status;
        $order->save();

        $be = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();;
        $sub = 'Payment Status Updated';

        $to = $order->billing_email;
        $fname = $order->billing_fname;

        if ($be->is_smtp == 1) {
            $mail_body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status is changed to ' . $request->payment_status . '.<br/>Thank you.';

            /******** Send mail to user ********/
            $data = [];
            $data['smtp_status'] = $be->is_smtp;
            $data['smtp_host'] = $be->smtp_host;
            $data['smtp_port'] = $be->smtp_port;
            $data['encryption'] = $be->encryption;
            $data['smtp_username'] = $be->smtp_username;
            $data['smtp_password'] = $be->smtp_password;

            //mail info in array
            $data['from_mail'] = $be->from_mail;
            $data['recipient'] = $to;
            $data['subject'] = $sub;
            $data['body'] = $mail_body;
            if ($request->payment_status == 'Completed') {
                $data['invoice'] = $dir . $invoice;
            }
            BasicMailer::sendMail($data);
        }

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function settings()
    {
        $data['shopsettings'] = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        return view('user.item.settings', $data);
    }

    public function updateSettings(Request $request)
    {

        $shopsettings = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        if (!$shopsettings) {
            $shopsettings  = new UserShopSetting();
        }
        $shopsettings->user_id = Auth::guard('web')->user()->id;
        $shopsettings->item_rating_system = $request->item_rating_system;
        $shopsettings->disqus_comment_system = $request->disqus_comment_system;
        $shopsettings->catalog_mode = $request->catalog_mode;
        $shopsettings->time_format = $request->time_format;
        $user_id = Auth::guard('web')->user()->id;
        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user_id);
        $permissions = json_decode($permissions, true);
        if (is_array($permissions) && in_array('GST Billing', $permissions)) {
            $shopsettings->tax = $request->tax ? $request->tax : 0.00;
        } else {
            $shopsettings->tax = 0.00;
        }
        $shopsettings->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    // public function slider(Request $request)
    // {
    //     $filename = null;
    //     $request->validate([
    //         'file' => 'mimes:jpg,jpeg,png|required',
    //     ]);
    //     if ($request->hasFile('file')) {
    //         $filename = Uploader::upload_picture(public_path('assets/front/img/user/items/slider-images'), $request->file('file'));
    //     }
    //     return response()->json(['status' => 'success', 'file_id' => $filename]);
    // }
    public function slider(Request $request)
    {
        $filename = null;

        // file OR image_url
        $validator = Validator::make($request->all(), [
            'file'      => 'required_without:image_url|mimes:jpg,jpeg,png',
            'image_url' => [
                'required_without:file',
                'max:2000',
                function ($attribute, $value, $fail) {
                    $val = trim((string) $value);
                    if ($val === '') {
                        return;
                    }

                    $isUrl = filter_var($val, FILTER_VALIDATE_URL) !== false;
                    $isStoragePath = str_starts_with($val, '/storage/');

                    if (!$isUrl && !$isStoragePath) {
                        $fail(__('The image url format is invalid.'));
                    }
                }
            ],
        ], [
            'file.required_without' => __('The file field is required.'),
            'image_url.required_without' => __('The image url field is required.'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $dir = public_path('assets/front/img/user/items/slider-images');
        @mkdir($dir, 0775, true);

        //  Normal Dropzone upload
        if ($request->hasFile('file')) {
            $filename = Uploader::upload_picture($dir, $request->file('file'));

            return response()->json([
                'status' => 'success',
                'file_id' => $filename,
                'url' => asset('assets/front/img/user/items/slider-images/' . $filename),
            ]);
        }

        //  AI URL upload 
        $url = trim((string) $request->input('image_url'));

        if (str_starts_with($url, '/storage/')) {
            $relative = substr($url, strlen('/storage/'));
            $source = storage_path('app/public/' . $relative);

            if (!file_exists($source)) {
                $source = public_path(ltrim($url, '/'));
            }

            $imgData = @file_get_contents($source);
        } else {
            $imgData = @file_get_contents($url);
        }
        if ($imgData === false) {
            return response()->json([
                'status' => 'error',
                'errors' => ['image_url' => [__('Failed to download image from URL.')]]
            ], 422);
        }

        // detect extension (png/jpg)
        $ext = 'jpg';
        try {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($imgData);
            if ($mime === 'image/png') $ext = 'png';
            elseif ($mime === 'image/jpeg') $ext = 'jpg';
            else {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['image_url' => [__('Only jpg/jpeg/png images are allowed.')]]
                ], 422);
            }
        } catch (\Throwable $e) {
            // fallback ext
            $ext = 'jpg';
        }

        $filename = uniqid() . '.' . $ext;
        file_put_contents($dir . '/' . $filename, $imgData);

        return response()->json([
            'status' => 'success',
            'file_id' => $filename,
            'url' => asset('assets/front/img/user/items/slider-images/' . $filename),
        ]);
    }

    public function sliderRemove(Request $request)
    {
        if (file_exists(public_path('assets/front/img/user/items/slider-images/') . $request->value)) {
            @unlink(public_path('assets/front/img/user/items/slider-images/') . $request->value);
            return response()->json(['status' => 200, 'message' => 'success']);
        } else {
            return response()->json(['status' => 404, 'message' => 'error']);
        }
    }

    public function dbSliderRemove(Request $request)
    {
        $img = UserItemImage::findOrFail($request->id);
        $imageCount = UserItemImage::where('item_id', $img->item_id)->count();

        if ($imageCount > 1) {
            @unlink(public_path('assets/front/img/user/items/slider-images/') . $img->image);
            $img->delete();
            return "success";
        } else {
            return response()->json(['status' => 200, 'message' => 'success']);
        }
    }

    public function subcatGetter(Request $request)
    {
        $data['subcategories'] = UserItemSubCategory::where('category_id', $request->category_id)->get();
        return $data;
    }

    public function setFlashSale($id, Request $request)
    {
        $rules = [
            'flash_amount' => $request->status == 1 ? 'required' : '',
            'start_date' => $request->status == 1 ? 'required' : '',
            'start_time' => $request->status == 1 ? 'required' : '',
            'end_date' => $request->status == 1 ? 'required' : '',
            'end_time' => $request->status == 1 ? 'required' : '',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $item = UserItem::findOrFail($id);
        $item->flash_amount = $request->flash_amount;
        $item->start_date = $request->start_date;
        $item->start_time = $request->start_time;
        $item->end_date = $request->end_date;
        $item->end_time = $request->end_time;
        $item->flash = $request->status;
        $item->save();
        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function exportCsv(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $userId)->first();
        if (!$lang) {
            $lang = Language::where('user_id', $userId)->where('is_default', 1)->first();
        }
        $langId = $lang ? $lang->id : null;

        $items = UserItem::where('user_items.user_id', $userId)
            ->leftJoin('user_item_contents', function ($join) use ($langId) {
                $join->on('user_items.id', '=', 'user_item_contents.item_id');
                if ($langId) {
                    $join->where('user_item_contents.language_id', '=', $langId);
                }
            })
            ->leftJoin('user_item_categories', function ($join) use ($langId) {
                $join->on('user_item_contents.category_id', '=', 'user_item_categories.id');
            })
            ->leftJoin('user_item_sub_categories', function ($join) use ($langId) {
                $join->on('user_item_contents.subcategory_id', '=', 'user_item_sub_categories.id');
            })
            ->select(
                'user_items.*',
                'user_item_contents.title',
                'user_item_contents.summary',
                'user_item_contents.description',
                'user_item_contents.meta_keywords',
                'user_item_contents.meta_description',
                'user_item_categories.name AS category_name',
                'user_item_sub_categories.name AS subcategory_name'
            )
            ->orderBy('user_items.id', 'DESC')
            ->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=products_export_" . date('Y_m_d_H_i_s') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'type',
                'title',
                'category_name',
                'subcategory_name',
                'current_price',
                'previous_price',
                'stock',
                'sku',
                'thumbnail',
                'slider_images',
                'variants',
                'file_type',
                'download_link',
                'status',
                'summary',
                'description',
                'meta_keywords',
                'meta_description'
            ]);

            foreach ($items as $item) {
                $thumbnailUrl = '';
                if (!empty($item->thumbnail)) {
                    if (filter_var($item->thumbnail, FILTER_VALIDATE_URL)) {
                        $thumbnailUrl = $item->thumbnail;
                    } else {
                        $cleanImg = basename(parse_url($item->thumbnail, PHP_URL_PATH));
                        $thumbnailUrl = asset('assets/front/img/user/items/thumbnail/' . $cleanImg);
                    }
                }

                $sliderImagesList = UserItemImage::where('item_id', $item->id)->pluck('image')->map(function ($img) {
                    if (filter_var($img, FILTER_VALIDATE_URL)) {
                        return $img;
                    }
                    $cleanSlider = basename(parse_url($img, PHP_URL_PATH));
                    return asset('assets/front/img/user/items/slider-images/' . $cleanSlider);
                })->toArray();
                $sliderImagesStr = implode(',', $sliderImagesList);

                $variantList = [];
                $pVariations = ProductVariation::where('item_id', $item->id)->get();
                foreach ($pVariations as $pVar) {
                    $varContent = ProductVariationContent::where('product_variation_id', $pVar->id)->first();
                    $varName = 'Variant';
                    if ($varContent) {
                        $vC = VariantContent::where('id', $varContent->variation_name)->pluck('name')->first();
                        if ($vC) $varName = $vC;
                    }
                    $pOptions = ProductVariantOption::where('product_variation_id', $pVar->id)->get();
                    foreach ($pOptions as $pOpt) {
                        $optContent = ProductVariantOptionContent::where('product_variant_option_id', $pOpt->id)->first();
                        $optName = 'Option';
                        if ($optContent) {
                            $vOC = VariantOptionContent::where('id', $optContent->option_name)->pluck('option_name')->first();
                            if ($vOC) $optName = $vOC;
                        }
                        $variantList[] = "{$varName}:{$optName}={$pOpt->price}:{$pOpt->stock}";
                    }
                }
                $variantsStr = implode(' | ', $variantList);

                fputcsv($file, [
                    $item->type ?? 'physical',
                    $item->title ?? '',
                    $item->category_name ?? '',
                    $item->subcategory_name ?? '',
                    $item->current_price ?? 0,
                    $item->previous_price ?? '',
                    $item->stock ?? 0,
                    $item->sku ?? '',
                    $thumbnailUrl,
                    $sliderImagesStr,
                    $variantsStr,
                    !empty($item->download_link) ? 'link' : (!empty($item->download_file) ? 'upload' : ''),
                    $item->download_link ?? '',
                    $item->status ?? 1,
                    strip_tags($item->summary ?? ''),
                    strip_tags($item->description ?? ''),
                    $item->meta_keywords ?? '',
                    $item->meta_description ?? ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function sampleCsv()
    {
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=sample_products.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'type',
                'title',
                'category_name',
                'subcategory_name',
                'current_price',
                'previous_price',
                'stock',
                'sku',
                'thumbnail',
                'slider_images',
                'variants',
                'file_type',
                'download_link',
                'status',
                'summary',
                'description',
                'meta_keywords',
                'meta_description'
            ]);

            fputcsv($file, [
                'physical',
                'iPhone 16 Pro 256GB',
                'Mobile Phones',
                'Smartphones',
                '999.00',
                '1099.00',
                '50',
                'IPHONE16-256',
                'iphone16.jpg',
                'iphone16.jpg',
                'Color:Red=999:20 | Color:Green=1049:15 | Color:Blue=1099:15',
                '',
                '',
                '1',
                'Latest iPhone 16 Pro with Titanium finish and A18 Pro Chip.',
                'Super Retina XDR display with ProMotion technology, advanced camera control button, and all-day battery life.',
                'iphone 16, apple, smartphone, mobile',
                'Buy iPhone 16 Pro online with best prices and fast shipping.'
            ]);

            fputcsv($file, [
                'physical',
                'iPhone 17 Pro Max 512GB',
                'Mobile Phones',
                'Smartphones',
                '1199.00',
                '1299.00',
                '35',
                'IPHONE17-512',
                'iphone17.jpg',
                'iphone17slide1.jpg,iphone17slide2.jpg',
                'Color:Natural Titanium=1199:15 | Color:Desert Titanium=1249:20 | Weight:16g=250:50',
                '',
                '',
                '1',
                'Next-generation iPhone 17 Pro Max with ultra zoom camera.',
                'Revolutionary mobile performance featuring titanium body, advanced optical zoom, and ceramic shield protection.',
                'iphone 17, apple, smartphone, pro max',
                'Order iPhone 17 Pro Max online with full warranty.'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120'
        ], [
            'csv_file.required' => __('Please select a CSV file to upload.'),
            'csv_file.mimes' => __('Only CSV files are allowed.')
        ]);

        $userId = Auth::guard('web')->user()->id;
        $csvBatchLimit = UserPermissionHelper::getCsvBatchLimit($userId);
        $currentPackage = UserPermissionHelper::currentPackagePermission($userId);
        $packageName = $currentPackage ? $currentPackage->title : 'Basic';

        if ($csvBatchLimit == 0) {
            Session::flash('warning', __('Bulk CSV product upload is not included in your current plan (:plan). Please upgrade to Standard (50 products/CSV) or Premium (100 products/CSV) plan.', ['plan' => $packageName]));
            return redirect()->back();
        }

        $itemLimit = intval($currentPackage->product_limit);
        $currentProductCount = UserItem::where('user_id', $userId)->count();

        if ($currentProductCount >= $itemLimit) {
            Session::flash('warning', __('Product limit exceeded! Your package allows maximum :limit products.', ['limit' => $itemLimit]));
            return redirect()->back();
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            Session::flash('warning', __('Failed to open the uploaded CSV file.'));
            return redirect()->back();
        }

        // Read BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 0, ',');
        if (!$header) {
            fclose($handle);
            Session::flash('warning', __('CSV file is empty or invalid.'));
            return redirect()->back();
        }

        $header = array_map(function ($h) {
            return strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h)));
        }, $header);

        $languages = Language::where('user_id', $userId)->get();
        $userCurrency = UserCurrency::where('is_default', 1)->where('user_id', $userId)->first();
        $currencyId = $userCurrency ? $userCurrency->id : 1;

        $importedCount = 0;
        $skippedLimitCount = 0;
        $skippedBatchLimitCount = 0;
        $invalidRowsCount = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            if ($importedCount >= $csvBatchLimit) {
                $skippedBatchLimitCount++;
                continue;
            }

            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }

            $data = array_combine($header, array_slice($row, 0, count($header)));

            $title = trim($data['title'] ?? '');
            $type = strtolower(trim($data['type'] ?? 'physical'));
            if (!in_array($type, ['physical', 'digital'])) {
                $type = 'physical';
            }

            $currentPrice = floatval($data['current_price'] ?? 0);
            if (empty($title) || $currentPrice <= 0) {
                $invalidRowsCount++;
                continue;
            }

            // Check product limit BEFORE inserting each item
            if (($currentProductCount + $importedCount) >= $itemLimit) {
                $skippedLimitCount++;
                continue;
            }

            $previousPrice = !empty($data['previous_price']) ? floatval($data['previous_price']) : null;
            $stock = ($type == 'physical') ? intval($data['stock'] ?? 0) : 0;
            $sku = trim($data['sku'] ?? '');
            if ($type == 'physical' && empty($sku)) {
                $sku = 'SKU-' . rand(100000, 999999);
            }

            // Ensure SKU is unique for this user if physical
            if ($type == 'physical') {
                $skuExists = UserItem::where('user_id', $userId)->where('sku', $sku)->exists();
                if ($skuExists) {
                    $sku = 'SKU-' . rand(100000, 999999);
                }
            }

            $downloadLink = trim($data['download_link'] ?? '');
            $status = isset($data['status']) && $data['status'] !== '' ? intval($data['status']) : 1;

            $categoryName = trim($data['category_name'] ?? '');
            $subcategoryName = trim($data['subcategory_name'] ?? '');

            // Resolve Category
            $categoryUniqueId = null;
            if (!empty($categoryName)) {
                $cat = UserItemCategory::where('user_id', $userId)->where('name', $categoryName)->first();
                if ($cat) {
                    $categoryUniqueId = $cat->unique_id;
                } else {
                    $categoryUniqueId = uniqid();
                    foreach ($languages as $lang) {
                        UserItemCategory::create([
                            'unique_id' => $categoryUniqueId,
                            'name' => $categoryName,
                            'slug' => make_slug($categoryName),
                            'user_id' => $userId,
                            'language_id' => $lang->id,
                            'status' => 1
                        ]);
                    }
                }
            }

            // Resolve Subcategory
            $subcategoryUniqueId = null;
            if (!empty($subcategoryName)) {
                $subcat = UserItemSubCategory::where('user_id', $userId)->where('name', $subcategoryName)->first();
                if ($subcat) {
                    $subcategoryUniqueId = $subcat->unique_id;
                } else if (!empty($categoryUniqueId)) {
                    $subcategoryUniqueId = uniqid();
                    foreach ($languages as $lang) {
                        $catForLang = UserItemCategory::where('user_id', $userId)->where('unique_id', $categoryUniqueId)->where('language_id', $lang->id)->first();
                        UserItemSubCategory::create([
                            'unique_id' => $subcategoryUniqueId,
                            'category_id' => $catForLang ? $catForLang->id : null,
                            'name' => $subcategoryName,
                            'slug' => make_slug($subcategoryName),
                            'user_id' => $userId,
                            'language_id' => $lang->id,
                            'status' => 1
                        ]);
                    }
                }
            }

            // Thumbnail image processing
            $thumbnailInput = trim($data['thumbnail'] ?? '');
            $sliderImagesInput = trim($data['slider_images'] ?? '');
            $thumbnailName = null;
            $thumbDir = public_path('assets/front/img/user/items/thumbnail/');
            @mkdir($thumbDir, 0775, true);

            if (!empty($thumbnailInput)) {
                $foundLocal = $this->resolveLocalImage($thumbnailInput, $thumbDir);
                if ($foundLocal) {
                    $thumbnailName = $foundLocal;
                } else if (filter_var($thumbnailInput, FILTER_VALIDATE_URL)) {
                    $downloadedName = $this->downloadImageFast($thumbnailInput, $thumbDir, 'webp');
                    $thumbnailName = $downloadedName ? $downloadedName : basename(parse_url($thumbnailInput, PHP_URL_PATH));
                } else {
                    $thumbnailName = basename(parse_url($thumbnailInput, PHP_URL_PATH));
                }
            }

            // Fallback: If thumbnail image file does not exist on disk, fallback to first valid slider image
            if (empty($thumbnailName) || !file_exists($thumbDir . $thumbnailName)) {
                if (!empty($sliderImagesInput)) {
                    $firstSlider = trim(explode(',', $sliderImagesInput)[0]);
                    $foundSliderAsThumb = $this->resolveLocalImage($firstSlider, $thumbDir);
                    if (!$foundSliderAsThumb) {
                        $foundSliderAsThumb = $this->resolveLocalImage($firstSlider, public_path('assets/front/img/user/items/slider-images/'));
                    }
                    if ($foundSliderAsThumb) {
                        $thumbnailName = $foundSliderAsThumb;
                    }
                }
            }

            // Create UserItem
            $item = new UserItem();
            $item->user_id = $userId;
            $item->stock = $stock;
            $item->sku = ($type == 'physical') ? $sku : null;
            $item->thumbnail = $thumbnailName;
            $item->status = $status;
            $item->current_price = $currentPrice;
            $item->previous_price = $previousPrice;
            $item->currency_id = $currencyId;
            $item->type = $type;
            $item->download_link = ($type == 'digital') ? $downloadLink : null;
            $item->save();

            // Slider images processing
            if (!empty($sliderImagesInput)) {
                $sliderList = array_map('trim', explode(',', $sliderImagesInput));
                $sliderDir = public_path('assets/front/img/user/items/slider-images/');
                $thumbDir = public_path('assets/front/img/user/items/thumbnail/');
                @mkdir($sliderDir, 0775, true);

                foreach ($sliderList as $sliderImg) {
                    if (empty($sliderImg)) continue;
                    $sliderName = null;

                    $foundInSlider = $this->resolveLocalImage($sliderImg, $sliderDir);
                    if ($foundInSlider) {
                        $sliderName = $foundInSlider;
                    } else {
                        $foundInThumb = $this->resolveLocalImage($sliderImg, $thumbDir);
                        if ($foundInThumb) {
                            @copy($thumbDir . $foundInThumb, $sliderDir . $foundInThumb);
                            $sliderName = $foundInThumb;
                        } else if (filter_var($sliderImg, FILTER_VALIDATE_URL)) {
                            $downloadedName = $this->downloadImageFast($sliderImg, $sliderDir, 'jpg');
                            $sliderName = $downloadedName ? $downloadedName : basename(parse_url($sliderImg, PHP_URL_PATH));
                        } else {
                            $sliderName = basename(parse_url($sliderImg, PHP_URL_PATH));
                        }
                    }

                    if ($sliderName) {
                        UserItemImage::create([
                            'item_id' => $item->id,
                            'image' => $sliderName
                        ]);
                    }
                }
            }

            // Create UserItemContent for all languages
            foreach ($languages as $lang) {
                $catId = null;
                if (!empty($categoryUniqueId)) {
                    $catId = UserItemCategory::where('user_id', $userId)
                        ->where('language_id', $lang->id)
                        ->where('unique_id', $categoryUniqueId)
                        ->pluck('id')->first();
                }
                if (!$catId) {
                    $catId = UserItemCategory::where('user_id', $userId)
                        ->where('language_id', $lang->id)
                        ->pluck('id')->first();
                }

                $subcatId = null;
                if (!empty($subcategoryUniqueId)) {
                    $subcatId = UserItemSubCategory::where('user_id', $userId)
                        ->where('language_id', $lang->id)
                        ->where('unique_id', $subcategoryUniqueId)
                        ->pluck('id')->first();
                }

                $summary = $data['summary'] ?? '';
                $description = $data['description'] ?? '';

                $adContent = new UserItemContent();
                $adContent->item_id = $item->id;
                $adContent->user_id = $userId;
                $adContent->language_id = $lang->id;
                $adContent->category_id = $catId;
                $adContent->subcategory_id = $subcatId;
                $adContent->title = $title;
                $adContent->slug = make_slug($title);
                $adContent->summary = Purifier::clean($summary, 'youtube');
                $adContent->description = Purifier::clean($description, 'youtube');
                $adContent->meta_keywords = $data['meta_keywords'] ?? null;
                $adContent->meta_description = $data['meta_description'] ?? null;
                $adContent->save();
            }

            // Process product variations from CSV variants column
            $variantsInput = trim($data['variants'] ?? '');
            if (!empty($variantsInput)) {
                $firstCatId = UserItemCategory::where('user_id', $userId)->where('unique_id', $categoryUniqueId)->pluck('id')->first();
                $firstSubcatId = UserItemSubCategory::where('user_id', $userId)->where('unique_id', $subcategoryUniqueId)->pluck('id')->first();
                $this->processProductVariantsCsv($item, $userId, $firstCatId, $firstSubcatId, $languages, $variantsInput);
            }

            $importedCount++;
        }

        fclose($handle);

        if ($skippedBatchLimitCount > 0) {
            Session::flash('warning', __('Imported :imported products. :skipped products skipped because your plan (:plan) limit is :limit products per CSV file upload. Upgrade to Premium for higher limit.', [
                'imported' => $importedCount,
                'skipped' => $skippedBatchLimitCount,
                'plan' => $packageName,
                'limit' => $csvBatchLimit
            ]));
        } else if ($skippedLimitCount > 0) {
            Session::flash('warning', __('Imported :imported products. :skipped products skipped due to package product limit (:limit max).', [
                'imported' => $importedCount,
                'skipped' => $skippedLimitCount,
                'limit' => $itemLimit
            ]));
        } else if ($importedCount > 0) {
            Session::flash('success', __('Successfully imported :count products from CSV.', ['count' => $importedCount]));
        } else {
            Session::flash('warning', __('No valid products were imported from the CSV file.'));
        }

        return redirect()->back();
    }

    private function processProductVariantsCsv($item, $userId, $catId, $subcatId, $languages, $variantsInput)
    {
        $variantItems = array_map('trim', explode('|', $variantsInput));

        $grouped = [];
        foreach ($variantItems as $vStr) {
            if (empty($vStr)) continue;
            if (strpos($vStr, ':') !== false && strpos($vStr, '=') !== false) {
                list($varPart, $valPart) = explode('=', $vStr, 2);
                list($variantName, $optionName) = explode(':', $varPart, 2);

                $price = 0;
                $stock = 0;
                if (strpos($valPart, ':') !== false) {
                    list($price, $stock) = explode(':', $valPart, 2);
                } else {
                    $price = $valPart;
                }

                $variantName = trim($variantName);
                $optionName = trim($optionName);
                $price = floatval($price);
                $stock = intval($stock);

                if (!empty($variantName) && !empty($optionName)) {
                    $grouped[$variantName][] = [
                        'option_name' => $optionName,
                        'price' => $price,
                        'stock' => $stock
                    ];
                }
            }
        }

        foreach ($grouped as $variantName => $options) {
            $uniqueId = uniqid();

            $variant = Variant::where('user_id', $userId)->whereHas('variantContents', function ($q) use ($variantName) {
                $q->where('name', $variantName);
            })->first();

            if (!$variant) {
                $variant = Variant::create([
                    'user_id' => $userId,
                    'category_id' => $catId,
                    'subcategory_id' => $subcatId
                ]);
                foreach ($languages as $lang) {
                    VariantContent::create([
                        'user_id' => $userId,
                        'variant_id' => $variant->id,
                        'language_id' => $lang->id,
                        'name' => $variantName
                    ]);
                }
            }

            $productVariation = ProductVariation::create([
                'user_id' => $userId,
                'item_id' => $item->id,
                'unique_id' => $uniqueId
            ]);

            foreach ($languages as $lang) {
                $varContent = VariantContent::where('variant_id', $variant->id)->where('language_id', $lang->id)->first();
                if ($varContent) {
                    ProductVariationContent::create([
                        'user_id' => $userId,
                        'item_id' => $item->id,
                        'product_variation_id' => $productVariation->id,
                        'language_id' => $lang->id,
                        'variation_name' => $varContent->id
                    ]);
                }
            }

            foreach ($options as $opt) {
                $optName = $opt['option_name'];
                $optPrice = $opt['price'];
                $optStock = $opt['stock'];

                $variantOption = VariantOption::where('variant_id', $variant->id)->whereHas('variantOptionContents', function ($q) use ($optName) {
                    $q->where('option_name', $optName);
                })->first();

                if (!$variantOption) {
                    $variantOption = VariantOption::create([
                        'user_id' => $userId,
                        'variant_id' => $variant->id
                    ]);
                    foreach ($languages as $lang) {
                        VariantOptionContent::create([
                            'user_id' => $userId,
                            'variant_id' => $variant->id,
                            'variant_option_id' => $variantOption->id,
                            'language_id' => $lang->id,
                            'option_name' => $optName
                        ]);
                    }
                }

                $pOption = ProductVariantOption::create([
                    'user_id' => $userId,
                    'item_id' => $item->id,
                    'product_variation_id' => $productVariation->id,
                    'unique_id' => $uniqueId,
                    'price' => $optPrice,
                    'stock' => $optStock
                ]);

                foreach ($languages as $lang) {
                    $optContent = VariantOptionContent::where('variant_option_id', $variantOption->id)->where('language_id', $lang->id)->first();
                    if ($optContent) {
                        ProductVariantOptionContent::create([
                            'user_id' => $userId,
                            'item_id' => $item->id,
                            'product_variation_id' => $productVariation->id,
                            'product_variant_option_id' => $pOption->id,
                            'language_id' => $lang->id,
                            'option_name' => $optContent->id
                        ]);
                    }
                }
            }
        }
    }

    private function downloadImageFast($url, $destinationDir, $defaultExt = 'jpg')
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
            $imgData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200 && !empty($imgData)) {
                $parsed = parse_url($url, PHP_URL_PATH);
                $ext = pathinfo($parsed, PATHINFO_EXTENSION);
                if (empty($ext) || strlen($ext) > 4) {
                    $ext = $defaultExt;
                }
                $filename = uniqid() . '.' . $ext;
                file_put_contents($destinationDir . $filename, $imgData);
                return $filename;
            }
        } catch (\Exception $e) {
            // ignore exception
        }
        return null;
    }

    private function resolveLocalImage($input, $dir)
    {
        if (empty($input)) return null;

        $parsedPath = parse_url($input, PHP_URL_PATH);
        $baseName = basename($parsedPath ? $parsedPath : $input);
        if (empty($baseName)) return null;

        if (file_exists($dir . $baseName)) {
            return $baseName;
        }

        $sanitized = strtolower(preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $baseName));
        if (file_exists($dir . $sanitized)) {
            return $sanitized;
        }

        $info = pathinfo($baseName);
        $ext = strtolower($info['extension'] ?? '');
        $nameOnly = $info['filename'] ?? '';
        $slugified = make_slug($nameOnly) . ($ext ? '.' . $ext : '');
        if (file_exists($dir . $slugified)) {
            return $slugified;
        }

        if (!empty($nameOnly)) {
            $files = glob($dir . '*');
            if ($files) {
                foreach ($files as $file) {
                    $fileNameOnDisk = basename($file);
                    if (strcasecmp($fileNameOnDisk, $baseName) === 0 || strcasecmp(pathinfo($fileNameOnDisk, PATHINFO_FILENAME), $nameOnly) === 0) {
                        return $fileNameOnDisk;
                    }
                }
            }
        }

        return null;
    }

    public function uploadBulkImages(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $csvBatchLimit = UserPermissionHelper::getCsvBatchLimit($userId);
        if ($csvBatchLimit == 0) {
            return response()->json([
                'status' => 'error',
                'message' => __('Bulk Image Upload is not available on your current plan. Please upgrade to Standard or Premium plan.')
            ], 403);
        }

        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|file|mimes:jpeg,jpg,png,webp,gif,svg|max:10240'
        ], [
            'images.required' => __('Please select at least one image file to upload.'),
            'images.*.mimes' => __('Only JPG, JPEG, PNG, WEBP, GIF, and SVG images are allowed.')
        ]);

        $thumbDir = public_path('assets/front/img/user/items/thumbnail/');
        @mkdir($thumbDir, 0775, true);

        $uploaded = [];

        foreach ($request->file('images') as $file) {
            $originalClientName = $file->getClientOriginalName();
            $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $originalClientName));

            $file->move($thumbDir, $cleanName);

            $url = asset('assets/front/img/user/items/thumbnail/' . $cleanName);

            $uploaded[] = [
                'filename' => $cleanName,
                'url' => $url
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => __('Successfully uploaded :count images.', ['count' => count($uploaded)]),
            'images' => $uploaded
        ]);
    }

    public function getBulkImages(Request $request)
    {
        $thumbDir = public_path('assets/front/img/user/items/thumbnail/');
        $files = glob($thumbDir . '*');

        if ($files) {
            usort($files, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });
        } else {
            $files = [];
        }

        $images = [];
        $count = 0;
        foreach ($files as $filePath) {
            if ($count >= 40) break;
            if (is_file($filePath)) {
                $filename = basename($filePath);
                $images[] = [
                    'filename' => $filename,
                    'url' => asset('assets/front/img/user/items/thumbnail/' . $filename),
                    'time' => date('Y-m-d H:i', filemtime($filePath))
                ];
                $count++;
            }
        }

        return response()->json([
            'status' => 'success',
            'images' => $images
        ]);
    }

    public function deleteBulkImage(Request $request)
    {
        $filename = basename($request->filename);
        if (!empty($filename)) {
            $filePath = public_path('assets/front/img/user/items/thumbnail/' . $filename);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $sliderPath = public_path('assets/front/img/user/items/slider-images/' . $filename);
            if (file_exists($sliderPath)) {
                @unlink($sliderPath);
            }
            return response()->json([
                'status' => 'success',
                'message' => __('Image deleted successfully.')
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('Invalid image filename.')
        ], 400);
    }
}
