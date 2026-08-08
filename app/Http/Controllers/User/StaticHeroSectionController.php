<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\Language;
use App\Models\User\StaticHeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StaticHeroSectionController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $language = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $information['language'] = $language;
        $information['u_langs'] = Language::where('user_id', $user_id)->get();
        $information['data'] = StaticHeroSection::where('language_id', $language->id)
            ->where('user_id', $user_id)
            ->first();
        return view('user.home.hero_section.static_hero_section', $information);
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'nullable|max:255',
            'subtitle' => 'nullable|max:255',
            'btn_name' => 'nullable|max:255',
            'btn_url' => 'nullable|max:255',
        ]);
        $data = StaticHeroSection::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $request->language_id)->first();
        if (empty($data)) {
            $data = new StaticHeroSection();
            $data->user_id = Auth::guard('web')->user()->id;
            $data->language_id = $request->language_id;
        }

        $destDir = public_path('assets/front/img/hero-section');

        if ($request->hasFile('hero_section_background_image')) {
            $data->background_image = Uploader::update_picture(
                $destDir,
                $request->file('hero_section_background_image'),
                $data->background_image
            );
        } elseif (!empty($request->ai_generated_hero_section_background_image)) {
            if (!empty($data->background_image) && file_exists($destDir . DIRECTORY_SEPARATOR . $data->background_image)) {
                @unlink($destDir . DIRECTORY_SEPARATOR . $data->background_image);
            }
            $data->background_image = moveAiStorageImageToPublicAssets(
                $request->ai_generated_hero_section_background_image,
                $destDir
            );
        }
        if ($request->hasFile('hero_image')) {
            $data->hero_image = Uploader::update_picture(
                $destDir,
                $request->file('hero_image'),
                $data->hero_image
            );
        } elseif (!empty($request->ai_generated_hero_image)) {
            if (!empty($data->hero_image) && file_exists($destDir . DIRECTORY_SEPARATOR . $data->hero_image)) {
                @unlink($destDir . DIRECTORY_SEPARATOR . $data->hero_image);
            }
            $data->hero_image = moveAiStorageImageToPublicAssets(
                $request->ai_generated_hero_image,
                $destDir
            );
        }

        $data->title = $request->title;
        $data->subtitle = $request->subtitle;
        $data->button_text = $request->button_text;
        $data->button_url = $request->button_url;
        $data->save();

        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }


}
