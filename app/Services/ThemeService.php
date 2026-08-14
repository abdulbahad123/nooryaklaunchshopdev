<?php

namespace App\Services;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ThemeService
{
    /**
     * Resolve the active theme name.
     */
    public function getActiveTheme(): string
    {
        $theme = null;
        $user = app('user');
        if ($user && isset($user->id)) {
            $theme = DB::table('user_basic_settings')
                ->where('user_id', $user->id)
                ->value('theme');
        }

        if (request()->has('preview_theme')) {
            return request()->query('preview_theme');
        }

        return $theme ?? 'grocery';
    }

    /**
     * Resolve and return the appropriate view for the current active theme.
     */
    public function view($view, array $data = [])
    {
        $theme = $this->getActiveTheme();
        
        // Handle "vegetables" alias mapping to "grocery" view path
        if ($theme === 'vegetables') {
            $theme = 'grocery';
        }

        $registry = config('themes.themes');
        $themePath = isset($registry[$theme]) ? $registry[$theme]['view_path'] : "user-front.{$theme}";

        if ($view === 'index') {
            $themeView = "{$themePath}.index";
            $resolvedView = View::exists($themeView) ? $themeView : "user-front.grocery.index";
        } else {
            $themeView = "{$themePath}.{$view}";
            
            // Check if theme-specific view exists (e.g. user-front.reference.shop), 
            // if not fallback to the default shared storefront view (e.g. user-front.shop)
            $resolvedView = View::exists($themeView) ? $themeView : "user-front.{$view}";
        }

        return view($resolvedView, $data);
    }
}
