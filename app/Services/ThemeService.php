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
        if (request()->has('preview_theme')) {
            session()->put('preview_theme', request()->query('preview_theme'));
        }
        if (request()->has('clear_preview_theme')) {
            session()->forget('preview_theme');
        }

        $theme = null;
        $user = app('user');
        if ($user && isset($user->id)) {
            $theme = DB::table('user_basic_settings')
                ->where('user_id', $user->id)
                ->value('theme');
        }

        return session()->get('preview_theme') ?? $theme ?? 'grocery';
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
